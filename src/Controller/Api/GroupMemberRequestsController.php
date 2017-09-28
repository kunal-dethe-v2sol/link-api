<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * GroupMembers Controller
 *
 * @property \App\Model\Table\GroupMembersTable $GroupMembers
 *
 * @method \App\Model\Entity\GroupMember[] paginate($object = null, array $settings = [])
 */
class GroupMemberRequestsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Groups');
        $this->loadModel('GroupMembers');
        $this->loadModel('Users');
    }

    /**
     * Edit method
     * 
     * Accept Group Member Request
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit() {
        $response = array(
            'code' => 500,
            'msg' => "The group member request cannot be accepted.",
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

                //Only admin users of the group can accept the group member request.
                if($loggedInGroupMember->status == 'admin') {
                    if($groupMember->status != 'member') {
                        if($groupMember->status == 'requested') {
                            if ($status == 'member') {
                                //Update the status of the group member
                                $groupMemberData = array(
                                    'status' => $status,
                                    'approved_datetime' => date('Y-m-d H:i:s'),
                                    'approved_by' => $logging_in_user_id,
                                    'modified_by' => $logging_in_user_id
                                );
                                $groupMember = $this->GroupMembers->patchEntity($groupMember, $groupMemberData);
                                if ($this->GroupMembers->save($groupMember)) {
                                    $success = true;
                                }
                            } else {
                                $response['code'] = 403;
                                $response['msg'] = "Invalid Group member request.";
                            }

                            if ($success) {
                                $response['code'] = 200;
                                $response['msg'] = "The group member request is accepted.";
                            }
                        } else {
                            $response['code'] = 404;
                            $response['msg'] = "Group member request is not found.";
                        }
                    } else {
                        $response['code'] = 403;
                        $response['msg'] = "User is already a group member.";
                    }
                } else {
                    $response['code'] = 403;
                    $response['msg'] = "Only group admin users can accept the group member request.";
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
     * Reject Member Request
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $response = array(
            'code' => 500,
            'msg' => "The group member request cannot be rejected.",
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

                //Only admin users of the group can reject the group member request.
                if($loggedInGroupMember->status == 'admin') {
                    if($groupMember->status != 'member') {
                        if($groupMember->status == 'requested') {
                            if ($status == 'rejected') {
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
                                $response['msg'] = "Invalid Group member request.";
                            }

                            if ($success) {
                                $response['code'] = 200;
                                $response['msg'] = "The group member request is rejected.";
                            }
                        } else {
                            $response['code'] = 404;
                            $response['msg'] = "Group member request is not found.";
                        }
                    } else {
                        $response['code'] = 403;
                        $response['msg'] = "User is already a group member.";
                    }
                } else {
                    $response['code'] = 403;
                    $response['msg'] = "Only group admin users can reject the group member request.";
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
