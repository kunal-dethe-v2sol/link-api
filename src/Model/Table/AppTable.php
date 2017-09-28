<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Base class for all models
 */
class AppTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
    }

    /**
     * Formats the array for single user data.
     * 
     * @param Users object $user
     * @return array $user
     */
    public function formatUserData($user) {
        $data = array();

        try {
            if (!empty($user)) {
                $data = array(
                    'uuid' => $user->uuid,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'full_name' => $user->firstname . ' ' . $user->lastname,
                );
            }
        } catch (Exception $ex) {
            $data = array();
        }

        return $data;
    }

    /**
     * Formats the array for single group data.
     * 
     * @param Groups object $group
     * @param string $logging_in_user_id
     * @param array $params
     * @return array $data
     */
    public function formatGroupData($group, $logging_in_user_id = '', $params = array()) {
        $data = array();

        $display_members = true;

        extract($params);

        try {
            if (!empty($group)) {
                //Image checks
                $image = $group->image_serialize ? unserialize($group->image_serialize) : '';
                if ($image) {
                    $image = $image['archiveName'];
                }

                //Group Members
                $members = array();
                if ($display_members) {
                    $groupMembers = $group->_matchingData;
                    if (empty($groupMembers)) {
                        //Not available from the query resultSet
                        //Fetch it directly from the table
                        $groupMembers = $this->GroupMembers
                                ->find()
                                ->where(['group_id' => $group->uuid, 'status IN' => ['admin', 'moderator', 'member']])
                                ->all();
                    }
                    if (!empty($groupMembers)) {
                        foreach ($groupMembers as $groupMember) {
                            $members[] = $this->formatGroupMemberData($group, $groupMember, $logging_in_user_id);
                        }
                    }
                }

                //Permission checks
                $permissions = array(
                    'manage' => false,
                    'edit' => false,
                    'delete' => false,
                    'join' => false,
                    'leave' => false,
                );
                //Edit and Delete
                if ($group->created_by == $logging_in_user_id) {
                    //Owner of the group
                    $permissions['edit'] = true;
                    $permissions['delete'] = true;
                    $permissions['manage'] = true;
                } else {
                    //Admin or Moderator of the group
                    $groupAdminModerator = $this->GroupMembers->checkGroupMemberRoles($group->uuid, $logging_in_user_id, array('admin', 'moderator'));
                    if ($groupAdminModerator) {
                        $permissions['edit'] = true;
                        $permissions['manage'] = true;
                    }
                }
                //Join and Leave
                if ($group->created_by != $logging_in_user_id) {
                    $groupAdminModeratorMember = $this->GroupMembers->checkGroupMemberRoles($group->uuid, $logging_in_user_id, array('admin', 'moderator', 'member'));
                    if ($groupAdminModeratorMember) {
                        //Not owner of the group
                        //Admin, Moderator or Member of the group
                        $permissions['leave'] = true;
                    } else {
                        //Not owner of the group
                        //Not Admin, Moderator or Member of the group
                        $permissions['join'] = true;
                    }
                }

                //LoggedIn Member
                $logged_in_member = array();
                $groupMember = $this->GroupMembers
                        ->find()
                        ->where(['group_id' => $group->uuid, 'user_id' => $logging_in_user_id, 'status IN' => ['admin', 'moderator', 'member']])
                        ->first();
                if ($groupMember) {
                    $logged_in_member = $this->formatGroupMemberData($group, $groupMember, $logging_in_user_id);
                }

                $data = array(
                    'uuid' => $group->uuid,
                    'name' => $group->name,
                    'slug' => $group->slug,
                    'media' => array(
                        'image' => $image,
                    ),
                    'visibility' => ucfirst($group->visibility),
                    'about' => $group->about,
                    'guidelines' => $group->guidelines,
                    'post_approval_required' => $group->post_approval_required,
                    'counts' => array(
                        'pending_member_count' => $group->pending_member_count,
                        'total_member_count' => $group->total_member_count,
                    ),
                    'owner' => $this->formatUserData($this->Users->get($group->created_by)),
                    'logged_in_member' => $logged_in_member,
                    'members' => $members,
                    'permissions' => $permissions,
                );
            }
        } catch (Exception $ex) {
            $data = array();
        }

        return $data;
    }

    /**
     * Formats the array for single group member data.
     * 
     * @param Groups object $group
     * @param GroupMembers object $groupMember
     * @param string $logging_in_user_id
     * @return array $data
     */
    public function formatGroupMemberData($group, $groupMember, $logging_in_user_id = '') {
        $data = array();

        try {
            if (!empty($groupMember)) {
                //Permission checks
                $permissions = array(
                    //'make_admin' => false,
                    'make_moderator' => false,
                    'leave_group' => false,
                    'remove_from_group' => false,
                    //'remove_as_admin' => false, //Remove self as admin
                    'remove_as_moderator' => false, //Remove self as moderator
                );
                if ($group->created_by == $logging_in_user_id) {
                    //Owner of the group
                    //Group Member's status is 'member only
                    if ($groupMember->status == 'member') {
                        //$permissions['make_admin'] = true;
                        $permissions['make_moderator'] = true;
                    } elseif ($groupMember->status == 'moderator') {
                        //$permissions['make_admin'] = true;
                        $permissions['remove_as_moderator'] = true;
                    }

                    //Its not my own group member record
                    //and group member's status is either admin, moderator or member
                    if ($groupMember->user_id != $logging_in_user_id) {
                        if(in_array($groupMember->status, array('admin', 'moderator', 'member'))) {
                            $permissions['remove_from_group'] = true;
                        }
                    }
                } else {
                    //Admin or Moderator of the group
                    $checkGroupMemberRolesEntity = NULL;
                    if ($this->getEntityClass() == 'App\Model\Entity\GroupMember') {
                        $checkGroupMemberRolesEntity = $this;
                    } elseif ($this->getEntityClass() == 'App\Model\Entity\Group') {
                        $checkGroupMemberRolesEntity = $this->GroupMembers;
                    }
                    $groupAdminModerator = $checkGroupMemberRolesEntity->checkGroupMemberRoles($group->uuid, $logging_in_user_id, array('admin', 'moderator'));
                    if ($groupAdminModerator) {
                        //Group member is not the owner of the group
                        if($groupMember->user_id != $group->created_by) {
                            $permissions['remove_from_group'] = true;
                        }
                    }
                }
                //Its my own group member record
                if ($groupMember->user_id == $logging_in_user_id) {
                    //only if I am not the owner of the group
                    if ($group->created_by != $logging_in_user_id) {
                        $permissions['leave_group'] = true;
                    }

                    if ($groupMember->status == 'admin') {
                        //Not the owner of the group
                        if ($group->created_by != $logging_in_user_id) {
                            //$permissions['remove_as_admin'] = true;
                        }
                    } elseif ($groupMember->status == 'moderator') {
                        //Not the owner of the group
                        if ($group->created_by != $logging_in_user_id) {
                            $permissions['remove_as_moderator'] = true;
                        }
                    }
                }

                $data = array(
                    'uuid' => $groupMember->uuid,
                    'user' => $this->formatUserData($this->Users->get($groupMember->user_id)),
                    'status' => $groupMember->status,
                    'permissions' => $permissions,
                );
            }
        } catch (Exception $ex) {
            $data = array();
        }

        return $data;
    }

    /**
     * Formats the array for single thread message data.
     * 
     * @param Groups object $threadMessage
     * @return array $data
     */
    public function formatThreadMessageData($threadMessage) {
        $data = array();

        try {
            if (!empty($threadMessage)) {
                $data = array(
                    'uuid' => $threadMessage->uuid,
                    'message_type' => $threadMessage->message_type,
                    'message' => $threadMessage->message,
                    'created' => array(
                        'date' => $threadMessage->created->format('Y-m-d'),
                        'time' => $threadMessage->created->format('H:i'),
                        'datetime' => $threadMessage->created->format('Y-m-d H:i'),
                    ),
                    'sender' => $this->formatUserData($this->Users->get($threadMessage->user_id)),
                );
            }
        } catch (Exception $ex) {
            $data = array();
        }

        return $data;
    }

    /**
     * Formats the array for single thread data.
     * 
     * @param Groups object $thread
     * @return array $data
     */
    public function formatThreadData($thread, $logging_in_user_id) {
        $data = array();

        try {
            if (!empty($thread)) {
                //Last Message
                $last_message = array();
                $threadLastMessage = $this->Messages
                        ->find()
                        ->contain('Users')
                        ->where(['Messages.thread_id' => $thread->uuid])
                        ->order(['Messages.created' => 'DESC'])
                        ->first();
                $last_message_user = array();
                if ($threadLastMessage) {
                    $last_message = $this->formatThreadMessageData($threadLastMessage);
                }

                //Thread Users
                $users = array();
                $user_names = array();
                $threadUsers = $this->ThreadUsers
                        ->find()
                        ->contain('Users')
                        ->where(['ThreadUsers.thread_id' => $thread->uuid, 'ThreadUsers.status' => 'active'])
                        ->all();
                if (!$threadUsers->isEmpty()) {
                    $count = count($threadUsers);
                    foreach ($threadUsers as $threadUser) {
                        if ($threadUser->user->uuid == $logging_in_user_id) {
                            continue;
                        } else {
                            $user_names[] = $threadUser->user->firstname . ' ' . $threadUser->user->lastname;
                            $users[] = $this->formatUserData($threadUser->user);
                        }
                    }
                }
                $data = array(
                    'uuid' => $thread->uuid,
                    'header' => $thread->header,
                    'creator' => $this->formatUserData($this->Users->get($thread->user_id)),
                    'last_message' => $last_message,
                    'users' => $users,
                    'user_names' => implode(',', $user_names),
                );
            }
        } catch (Exception $ex) {
            $data = array();
        }

        return $data;
    }

    /**
     * Formats the array for single user connection data.
     * 
     * @param Groups object $user_connection
     * @param string $logging_in_user_id
     * @return array $data
     */
    public function formatUserConnectionData($user_connection, $logging_in_user_id = '') {
        $data = array();

        try {
            if (!empty($user_connection)) {
                $connection_user = ($user_connection->user_id == $logging_in_user_id) ? $user_connection->connection : $user_connection->user;

                $data = array(
                    'uuid' => $user_connection->uuid,
                    'user' => $this->formatUserData($connection_user),
                );
            }
        } catch (Exception $ex) {
            $data = array();
        }

        return $data;
    }

}
