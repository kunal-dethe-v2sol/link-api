<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;

/**
 * GroupMembers Controller
 *
 * @property \App\Model\Table\GroupMembersTable $GroupMembers
 *
 * @method \App\Model\Entity\GroupMember[] paginate($object = null, array $settings = [])
 */
class GroupMembersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Groups');
        $this->loadModel('GroupMembers');
        $this->loadModel('Users');
        $this->loadModel('ActionReasons');
    }

    
    /**
     * Index method
     * 
     * Group Member List.
     * Search Group Member List
     * Member Request List
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
                
                $group_id = $this->request->getParam('group_id');
                
                //Just to check if the Group record exists.
                $group = $this->Groups->get($group_id, [
                    'contain' => []
                ]);
                
                //Posible values - all, admin, moderator, member, reported
                $status = $this->request->getQuery('status', '');
                $search = $this->Common->sanitizeString($this->request->getQuery('search', ''));
                $page = $this->request->getQuery('page', 1);
                $limit = $this->request->getQuery('limit', Configure::read('SITE_PAGINATION_LIMIT'));
                
//                echo '<pre>';
//                print_r($status);
//                print_r($search);
//                print_r($page);
//                print_r($limit);
//                exit();
                
                $params = array(
                    'logging_in_user_id' => $logging_in_user_id,
                    'status' => $status,
                    'search' => $search,
                    'page' => $page,
                    'limit' => $limit,
                );
                
                $group_members = $this->GroupMembers->getGroupMembers($group, $params);
//                pr($group_members);
//                exit();
                
                $response['code'] = 200;
                $response['data'] = $group_members;
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
     * There are 2 scenarios when this action will be called.
     * Scenario 1: User wants to join a Group, so sents a request.
     * Scenario 2: Admin selects user(s) to add to a group.
     *  Only Admin and Moderator
     * 
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $response = array(
            'code' => 500,
            'msg' => "GROUP_MEMBERS_ADD_DEFAULT_ERROR",
        );

        if ($this->request->is('post')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();
                
                $success = false;

                $group_id = $this->request->getParam('group_id');

                //$requestData = $this->request->getData();
                $requestData = $this->Common->getRequestParams();

//                echo '<pre>';
//                print_r($group_id);
//                print_r($requestData);
//                exit();
                //Just to check if the Group record exists.
                $group = $this->Groups->get($group_id, [
                    'contain' => []
                ]);

                $request_type = array_key_exists('request_type', $requestData) ? $requestData['request_type'] : '';
                $user_ids = array_key_exists('user_ids', $requestData) ? $requestData['user_ids'] : array();
                if ($request_type) {
                    //If request_type is 'admin' then logged_in_user should either be an admin or moderator of the group.
                    //If request_type is 'self' then let it continue further.
                    if (($request_type == 'admin' && $this->GroupMembers->checkGroupMemberRoles($group_id, $logging_in_user_id, array('admin', 'moderator'))) || ($request_type == 'self')) {
                        if (count($user_ids)) {
                            if(!is_array($user_ids)) {
                                //Hack for Swagger API
                                $user_ids = explode(',', $user_ids);
                            }
                            foreach ($user_ids as $user_id) {
                                $user_id = str_replace('"', '', $user_id);
                                $user = $this->Users->get($user_id, [
                                    'contain' => []
                                ]);

                                //Check if the User status is active
                                if ($user->status == 'active') {
                                    //Check if this user_id is not already a part of the group.
                                    $groupMember = $this->GroupMembers
                                            ->find()
                                            ->where(['group_id' => $group_id, 'user_id' => $user_id])
                                            ->first();
                                    if ($groupMember) {
                                        //User already part of this group.
                                        //If the request_type is 'self' and user's status is 'rejected, left or removed'
                                        //Then make the status 'requested'
                                        if (in_array($groupMember->status, array('rejected', 'left', 'removed'))) {
                                            //If group admin is adding this member
                                            if($request_type == 'admin') {
                                                $groupMemberData = array(
                                                    'status' => 'member',
                                                    'approved_datetime' => date('Y-m-d H:i:s'),
                                                    'approved_by' => $logging_in_user_id,
                                                    'modified_by' => $logging_in_user_id
                                                );
                                            } else {
                                                //If user is requesting to join the group
                                                if($group->visibility == 'public') {
                                                    //Directly join the user to the group
                                                    $groupMemberData = array(
                                                        'status' => 'member',
                                                        'approved_datetime' => date('Y-m-d H:i:s'),
                                                        'approved_by' => $logging_in_user_id,
                                                        'modified_by' => $logging_in_user_id
                                                    );
                                                } else {
                                                    //Being a private group, approval is required.
                                                    $groupMemberData = array(
                                                        'status' => 'requested',
                                                        'modified_by' => $logging_in_user_id
                                                    );
                                                }
                                            }
                                            
                                            $groupMember = $this->GroupMembers->patchEntity($groupMember, $groupMemberData);
                                            $this->GroupMembers->save($groupMember);
                                        }
                                    } else {
                                        $status = 'requested';
                                        if($request_type == 'admin') {
                                            $status = 'member';
                                        } else {
                                            if($group->visibility == 'public') {
                                                $status = 'member';
                                            } else {
                                                $status = 'requested';
                                            }
                                        }
                                        
                                        //First time user
                                        $groupMemberData = array(
                                            'group_id' => $group_id,
                                            'user_id' => $user_id,
                                            'request_type' => $request_type,
                                            'approved_datetime' => $status == 'member' ? date('Y-m-d H:i:s') : NULL,
                                            'approved_by' => $status == 'member' ? $logging_in_user_id : NULL,
                                            'status' => $status,
                                            'created_by' => $logging_in_user_id,
                                        );
                                        $groupMember = $this->GroupMembers->newEntity($groupMemberData);
                                        $this->GroupMembers->save($groupMember);
                                    }
                                    $success = true;
                                } else {
                                    $response['code'] = 403;
                                    $response['msg'] = "User is not active.";
                                    break;
                                }
                            }
                        } else {
                            $response['code'] = 400;
                            $response['msg'] = "GROUP_MEMBERS_ADD_NO_USERS_FOUND_ERROR";
                        }
                    } else {
                        $response['code'] = 403;
                        $response['msg'] = "GROUP_MEMBERS_ADD_PERMISSION_DENIED";
                    }
                }

                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "GROUP_MEMBERS_ADD_SUCCESS";
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
     * Make Moderator or Admin
     * Remove as Moderator
     * 
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit() {
        $response = array(
            'code' => 500,
            'msg' => "GROUP_MEMBERS_EDIT_DEFAULT_ERROR",
        );

        if ($this->request->is(['put'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $group_id = $this->request->getParam('group_id');
                $group_member_id = $this->request->getParam('group_member_id');

                //$requestData = $this->request->getData();
                $requestData = $this->Common->getRequestParams();

//                echo '<pre>';
//                print_r($group_id);
//                echo ' - ';
//                print_r($group_member_id);
//                echo ' - ';
//                print_r($requestData);
//                exit();
                //Just to check if the Group record exists.
                $group = $this->Groups->get($group_id, [
                    'contain' => []
                ]);

                $groupMember = $this->GroupMembers->get($group_member_id, [
                    'contain' => []
                ]);

                $loggedInGroupMember = $this->GroupMembers
                        ->find()
                        ->where(['group_id' => $group_id, 'user_id' => $logging_in_user_id])
                        ->first();

                $status = array_key_exists('status', $requestData) ? $requestData['status'] : '';
                
                //Check if the group member status is active('admin', 'moderator', 'member')
                //If status is 'admin' then $group_member_id should either be a moderator or member of the group.
                //If status is 'moderator' then $group_member_id should be a member of the group.
                //If status is 'member' then $group_member_id should be a moderator of the group.
                if (($status == 'admin' && in_array($groupMember->status, array('moderator', 'member'))) 
                        || ($status == 'moderator' && $groupMember->status = 'member')
                        || ($status == 'member' && $groupMember->status = 'moderator')
                        ) {
                    //Update the status of the group member
                    $groupMemberData = array(
                        'status' => $status,
                        'modified_by' => $logging_in_user_id
                    );
                    $groupMember = $this->GroupMembers->patchEntity($groupMember, $groupMemberData);
                    if ($this->GroupMembers->save($groupMember)) {
                        $success = true;
                    }
                } else {
                    $response['code'] = 403;
                    $response['msg'] = "GROUP_MEMBERS_EDIT_INVLAID_ROLE";
                }

                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "GROUP_MEMBERS_EDIT_SUCCESS";
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
     * Scenario 1: Leave Group.
     * Scenario 2: Remove from Group.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $response = array(
            'code' => 500,
            'msg' => "GROUP_MEMBERS_DELETE_DEFAULT_ERROR",
        );

        if ($this->request->is(['delete'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $group_id = $this->request->getParam('group_id');
                $group_member_id = $this->request->getParam('group_member_id');

                //$requestData = $this->request->getData();
                $requestData = $this->Common->getRequestParams();

//                echo '<pre>';
//                print_r($group_id);
//                echo ' - ';
//                print_r($group_member_id);
//                echo ' - ';
//                print_r($requestData);
//                exit();
                //Just to check if the Group record exists.
                $group = $this->Groups->get($group_id, [
                    'contain' => []
                ]);

                $groupMember = $this->GroupMembers->get($group_member_id, [
                    'contain' => []
                ]);

                $loggedInGroupMember = $this->GroupMembers
                        ->find()
                        ->where(['group_id' => $group_id, 'user_id' => $logging_in_user_id])
                        ->first();

                $status = array_key_exists('status', $requestData) ? $requestData['status'] : '';
                $reason = array_key_exists('reason', $requestData) ? $requestData['reason'] : '';

                //Check if the group member status is active('admin', 'moderator', 'member')
                //If status is 'left' then $group_member_id should either be an admin, moderator or member of the group.
                //If status is 'removed' then $logging_in_user_id should be an admin of the group.
                if (($status == 'left' && in_array($groupMember->status, array('admin', 'moderator', 'member'))) || ($status == 'removed' && $loggedInGroupMember->status = 'admin')) {
                    //Update the status of the group member
                    $groupMemberData = array(
                        'status' => $status,
                        'modified_by' => $logging_in_user_id
                    );
                    $groupMember = $this->GroupMembers->patchEntity($groupMember, $groupMemberData);
                    if ($this->GroupMembers->save($groupMember)) {
                        $success = true;

                        //If status is 'left', save the reason for it.
                        //Add an entry in the action_reasons table.
                        if ($status == 'left') {
                            $actionReasonData = array(
                                'user_id' => $logging_in_user_id,
                                'reason_type' => 'leave-group',
                                'target_id' => $group_member_id,
                                'reason' => $reason,
                                'created_by' => $logging_in_user_id,
                            );
                            $actionReason = $this->ActionReasons->newEntity($actionReasonData);
                            $this->ActionReasons->save($actionReason);
                        }
                    }
                } else {
                    $response['code'] = 403;
                    $response['msg'] = "GROUP_MEMBERS_DELETE_INVALID_ROLE";
                }

                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "GROUP_MEMBERS_DELETE_SUCCESS";
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
