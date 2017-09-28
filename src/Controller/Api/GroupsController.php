<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 *
 * @method \App\Model\Entity\Group[] paginate($object = null, array $settings = [])
 */
class GroupsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('S3');
        $this->loadModel('Groups');
        $this->loadModel('GroupMembers');
    }

    /**
     * Index method
     * 
     * Owned, Joined, My and Trending Group List.
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $response = array(
            'code' => 500,
            'data' => array(),
        );

        if ($this->request->is('get')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                //Posible values - manage, joined, my or trending
                $type = $this->request->getQuery('type', '');
                $page = $this->request->getQuery('page', 1);
                $limit = $this->request->getQuery('limit', Configure::read('SITE_PAGINATION_LIMIT'));

//                echo '<pre>';
//                var_dump($type);
//                print_r($page);
//                print_r($limit);
//                exit();

                $params = array(
                    'logging_in_user_id' => $logging_in_user_id,
                    'type' => $type,
                    'page' => $page,
                    'limit' => $limit,
                );

                $groups = $this->Groups->getGroups($params);
                //pr($groups);
                //exit();

                $response['code'] = 200;
                $response['data'] = $groups;
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], array('data' => $response['data'])));
    }

    /**
     * View method
     * 
     * Single Group Details.
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($group_id) {
        $response = array(
            'code' => 500,
            'data' => "GROUPS_VIEW_DEFAULT_ERROR",
        );

        if ($this->request->is('get')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

//                echo '<pre>';
//                var_dump($group_id);
//                exit();

                $group = $this->Groups->get($group_id);
//                pr($group);
//                exit();

                $params = array(
                    'display_members' => false,
                );
                $group = $this->Groups->formatGroupData($group, $logging_in_user_id, $params);

                $response['code'] = 200;
                $response['data'] = array(
                    'group' => $group
                );
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], array('data' => $response['data'])));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $response = array(
            'code' => 500,
            'msg' => "GROUPS_ADD_DEFAULT_ERROR",
        );

        if ($this->request->is('post')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $requestData = $this->request->getData();
                //$requestData = $this->Common->getRequestParams();
                //$requestFile = $_FILES;
                $requestFile = array_key_exists('image', $requestData) ? $requestData['image'] : '';

//                echo '<pre>';
//                print_r($requestData);
//                print_r($requestFile);
//                exit();
                
                $groupData = array(
                    'slug' => array_key_exists('name', $requestData) ? $this->Common->slugify($requestData['name']) : '',
                    'image_serialize' => '',
                    'user_id' => $logging_in_user_id,
                    'created_by' => $logging_in_user_id,
                );
                $groupData = array_merge($groupData, $requestData);
                $group = $this->Groups->newEntity($groupData);
                if ($this->Groups->save($group)) {
                    $success = true;
                    $group_id = $group->uuid;

                    //Validate uploaded file
                    if (!empty($requestFile)) {
                        if ($requestFile['error'] == 0) {
                            $image = array();
                            $image['originalName'] = $requestFile['name'];
                            
                            $uploadPath = UPLOAD_PATH . 'groups/';
                            
                            $uploadFile = $uploadPath . $image['originalName'];
                            $image['originalExt'] = pathinfo($image['originalName'])["extension"];
                            $image['archiveName'] = (pathinfo($image['originalName'])["filename"]) . '-' . time() . "." . $image['originalExt'];

                            if (move_uploaded_file($requestFile['tmp_name'], $uploadFile)) {
                                $image['filesize'] = filesize($uploadFile);

                                $groupData['image_serialize'] = serialize($image);
                                $groupData['post_approval_required'] = 'no';    //Its presence is set as required in the validations for update mode.
                                $groupData['modified_by'] = $logging_in_user_id;
                                $group = $this->Groups->patchEntity($group, $groupData);
                                if ($this->Groups->save($group)) {
                                    try {
                                        $uploadDone = $this->S3->upload($uploadFile, 'groups/' . $group_id . '/' . $image['archiveName']);
                                        if ($uploadDone) {
                                            //chown(WWW_ROOT . 'uploads/' . 'posts/' . $_FILES['image_serialize']['name'],666);
                                            //unlink(UPLOAD_PATH . 'posts/' . $_FILES['image_serialize']['name']);
                                        }
                                    } catch (Exception $ex) {
                                        $this->Common->logEx(__CLASS__, __FUNCTION__, $ex);
                                    }
                                } else {
                                    pr($group->errors()); exit();
                                }
                            }
                        }
                    }

                    //Add the logged_in_user_id as one of the group member with status 'admin'
                    $groupMemberData = array(
                        'group_id' => $group_id,
                        'user_id' => $logging_in_user_id,
                        'request_type' => 'self',
                        'approved_datetime' => date('Y-m-d H:i:s'),
                        'approved_by' => $logging_in_user_id,
                        'status' => 'admin',
                        'created_by' => $logging_in_user_id,
                    );
                    $group_member = $this->GroupMembers->newEntity($groupMemberData);
                    if ($this->GroupMembers->save($group_member)) {
                        //nothing to be done on success, yet.
                    }

                    //Now check if the group members are also sent in the request.
                    $member_ids = array_key_exists('member_ids', $requestData) ? $requestData['member_ids'] : '';
                    if (count($member_ids) == 1 && empty($member_ids[0])) {
                        $member_ids = array();
                    } elseif (count($member_ids) == 1) {
                        $member_ids = explode(',', $member_ids[0]);
                    }
                    if (count($member_ids)) {
                        foreach ($member_ids as $member_id) {
                            $member_id = str_replace('"', '', $member_id);

                            $groupMemberData = array(
                                'group_id' => $group_id,
                                'user_id' => $member_id,
                                'request_type' => 'admin',
                                'approved_datetime' => date('Y-m-d H:i:s'),
                                'approved_by' => $logging_in_user_id,
                                'status' => 'member',
                                'created_by' => $logging_in_user_id,
                            );
                            $group_member = $this->GroupMembers->newEntity($groupMemberData);
                            if ($this->GroupMembers->save($group_member)) {
                                //nothing to be done on success, yet.
                            }
                        }
                    }
                } else {
                    $response['code'] = 400;
                    $response['msg'] = $this->Common->toastErrorMessages($group->errors());
                }

                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "GROUPS_ADD_SUCCESS";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        $response = array(
            'code' => 500,
            'msg' => "GROUPS_EDIT_DEFAULT_ERROR",
        );

        if ($this->request->is(['put'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                //Only admin users of the group can edit the group settings.
                if ($this->GroupMembers->checkGroupMemberRoles($id, $logging_in_user_id, array('admin'))) {
                    $requestData = $this->request->getData();
                    //                $requestData = $this->Common->getRequestParams();
                    //                $requestFile = $_FILES;
                    $requestFile = array_key_exists('image', $requestData) ? $requestData['image'] : '';

                    //                echo '<pre>';
                    //                print_r($requestData);
                    //                print_r($requestFile);
                    //                exit();
                    //Validate uploaded file
                    $image = '';
                    if (!empty($requestFile)) {
                        if ($requestFile['error'] == 0) {
                            $image = serialize(array('name' => $requestFile['name']));
                        }
                    }

                    $groupData = array(
                        'slug' => array_key_exists('name', $requestData) ? $this->Common->slugify($requestData['name']) : '',
                        'modified_by' => $logging_in_user_id,
                    );

                    //Replace image only if uploaded
                    if ($image) {
                        $groupData['image_serialize'] = $image;
                    }

                    $groupData = array_merge($groupData, $requestData);
                    $group = $this->Groups->patchEntity($group, $groupData);
                    if ($this->Groups->save($group)) {
                        $success = true;
                    } else {
                        $response['code'] = 400;
                        $response['msg'] = $this->Common->toastErrorMessages($group->errors());
                    }

                    if ($success) {
                        $response['code'] = 200;
                        $response['msg'] = "GROUPS_EDIT_SUCCESS";
                    }
                } else {
                    $response['code'] = 403;
                    $response['msg'] = "GROUPS_EDIT_PERMISSION_DENIED";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);

        $response = array(
            'code' => 500,
            'msg' => "GROUPS_DELETE_DEFAULT_ERROR",
        );

        if ($this->request->is(['delete'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                //Only creator of the group can delete it.
                if ($group->created_by == $logging_in_user_id) {
                    $groupData = array(
                        'status' => 'inactive',
                        'modified_by' => $logging_in_user_id,
                    );
                    $group = $this->Groups->patchEntity($group, $groupData, array('validate' => false));
                    if ($this->Groups->save($group)) {
                        $success = true;
                    } else {
                        $response['code'] = 400;
                        $response['msg'] = $this->Common->toastErrorMessages($group->errors());
                    }

                    if ($success) {
                        $response['code'] = 200;
                        $response['msg'] = "GROUPS_DELETE_SUCCESS";
                    }
                } else {
                    $response['code'] = 403;
                    $response['msg'] = "User is not allowed to delete the group.";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }

}
