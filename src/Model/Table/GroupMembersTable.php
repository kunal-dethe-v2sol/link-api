<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * GroupMembers Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\GroupMember get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupMember newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupMember[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupMember|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupMember[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupMember findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GroupMembersTable extends AppTable {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('group_members');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->requirePresence([
                    'group_id',
                    'user_id',
                    'request_type',
                    'status'
                        ], 'create')
                ->notEmpty([
                    'group_id',
                    'user_id',
                    'request_type',
                    'status'
                        ], 'create')
                ->allowEmpty([
                    'uuid',
                    'created_by',
                    'modified_by'
        ]);
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Returns whether the passed $member_id is a group member 
     * and has one of the passed statuses or not.
     * 
     * @param uuid $group_id
     * @param uuid $member_id
     * @param array $statuses
     * @return boolean $success
     */
    public function checkGroupMemberRoles($group_id = '', $member_id = '', $statuses = array()) {
        $success = false;

        if ($group_id && $member_id && count($statuses)) {
            $member = $this
                    ->find()
                    ->where(['group_id' => $group_id, 'user_id' => $member_id, 'status IN' => $statuses])
                    ->first();
            if ($member) {
                $success = true;
            }
        }
        return $success;
    }

    /**
     * Returns the list of group members active in the group.
     * 
     * @param Groups object $group
     * @param array $params
     * @return boolean $resultSet
     */
    public function getGroupMembers($group, $params = array()) {
        $resultSet = array(
            'group_members' => array(),
            'total_count' => 0
        );

        $logging_in_user_id = '';
        $status = '';
        $search = '';
        $page = 1;
        $limit = Configure::read('SITE_PAGINATION_LIMIT');

        extract($params);

        try {
            if ($logging_in_user_id && $group && in_array($status, array('all', 'admin', 'moderator', 'member', 'requested'))) {
                $group_id = $group->uuid;

                $query = $this
                        ->find()
                        ->innerJoinWith('Groups', function ($q) use($status) {
                            return $q->where(['Groups.status' => 'active']);
                        });

                $where = array(
                    'GroupMembers.group_id' => $group_id,
                );
                if ($status == 'all') {
                    $where['GroupMembers.status IN'] = array('admin', 'moderator', 'member');
                } elseif (in_array($status, array('admin', 'moderator', 'member'))) {
                    $where['GroupMembers.status'] = $status;
                } elseif ($status = 'requested') {
                    $where['GroupMembers.status'] = 'requested';
                }
                //Search
                if ($search) {
                    $query->innerJoinWith('Users', function ($q) use($search) {
                        return $q->where(function ($exp, $query) use ($search) {
                            $conc = $query->func()->concat([
                                'firstname' => 'identifier', ' ', 'lastname' => 'identifier'
                            ]);
                            return $exp->like($conc, '%'.$search.'%');
                        });
                    });
                }

                $query->where($where)
                        ->order(['GroupMembers.created' => 'DESC']);

                $resultSet['total_count'] = $total_count = $query->count();
                $offset = ($page - 1) * $limit;

                $query
                        ->offset($offset)
                        ->limit($limit);

//                echo $query->sql(); exit();

                $group_members = $query->all();
                if (!$group_members->isEmpty()) {
                    foreach ($group_members as $group_member) {
                        //pr($group_member);
                        $resultSet['group_members'][] = $this->formatGroupMemberData($group, $group_member, $logging_in_user_id);
                    }
                    //exit();
                }
            }
        } catch (Exception $ex) {
            
        }

        return $resultSet;
    }

}
