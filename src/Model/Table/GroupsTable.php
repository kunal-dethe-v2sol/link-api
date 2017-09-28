<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * Groups Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ArticleSharewithGroupsTable|\Cake\ORM\Association\HasMany $ArticleSharewithGroups
 * @property \App\Model\Table\GroupMembersTable|\Cake\ORM\Association\HasMany $GroupMembers
 * @property \App\Model\Table\PostSharewithGroupsTable|\Cake\ORM\Association\HasMany $PostSharewithGroups
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\HasMany $Posts
 *
 * @method \App\Model\Entity\Group get($primaryKey, $options = [])
 * @method \App\Model\Entity\Group newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Group[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Group|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Group[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Group findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GroupsTable extends AppTable {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ArticleSharewithGroups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('GroupMembers', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PostSharewithGroups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('Posts', [
            'foreignKey' => 'group_id'
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
                    'name',
                    'about',
                        ], 'create')
                ->requirePresence([
                    'name',
                    'about',
                    'post_approval_required',
                        ], 'update')
                ->notEmpty([
                    'name',
                    'slug',
                    'about',
                        ], 'create')
                ->notEmpty([
                    'name',
                    'slug',
                    'about',
                    'post_approval_required',
                        ], 'update')
                ->allowEmpty([
                    'uuid',
                    'image_serialize',
                    'guidelines',
                    'visibility',
                    'post_approval_required',
                    'pending_member_count',
                    'total_member_count',
                    'status',
                    'created_by',
                    'modified_by',
                ])
                ->add('name', [
                    'maxLength' => [
                        'rule' => ['maxLength', 100],
                        'message' => 'Max. 100 chars are allowed.',
                    ]
                ])
                ->add('about', [
                    'maxLength' => [
                        'rule' => ['maxLength', 2000],
                        'message' => 'Max. 2000 chars are allowed.',
                    ]
                ])
                ->add('guidelines', [
                    'maxLength' => [
                        'rule' => ['maxLength', 4000],
                        'message' => 'Max. 4000 chars are allowed.',
                    ]
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Returns the list of groups for the logged_in_user according to the type (manage, joined, my or trending).
     * 
     * @param array $params
     * @return boolean $resultSet
     */
    public function getGroups($params = array()) {
        $resultSet = array(
            'groups' => array(),
            'total_count' => 0
        );

        $logging_in_user_id = '';
        $type = '';
        $page = 1;
        $limit = Configure::read('SITE_PAGINATION_LIMIT');

        extract($params);

        try {
            if ($logging_in_user_id && $type) {
                $query = $this
                        ->find();

                if ($type == 'manage') {
                    //Owned Groups
                    $groups = $query
                            ->where(['Groups.user_id' => $logging_in_user_id, 'Groups.status' => 'active'])
                            ->order(['Groups.created' => 'DESC']);
                } elseif ($type == 'joined') {
                    //Owned Groups
                    $subQuery = $this
                            ->find()
                            ->where(['Groups.status' => 'active', 'Groups.created_by' => $logging_in_user_id]);
                    $my_group_ids = array();
                    $myGroups = $subQuery->all();
                    if (!$myGroups->isEmpty()) {
                        foreach ($myGroups as $myGroup) {
                            $my_group_ids[] = $myGroup->uuid;
                        }
                    }
                    
                    //Joined Groups
                    $where = array();
                    if(!empty($my_group_ids)) {
                        $where['Groups.uuid NOT IN'] = $my_group_ids;
                    }
                    $where['Groups.status'] = 'active';
                    $groups = $query
                            ->innerJoinWith('GroupMembers', function ($q) use($logging_in_user_id) {
                                return $q->where([
                                    'GroupMembers.user_id' => $logging_in_user_id, 'GroupMembers.status IN' => ['admin', 'moderator', 'member']
                                ]);
                            })
                            ->where($where)
                            ->order(['GroupMembers.created' => 'DESC']);
                } elseif ($type == 'my') {
                    //My Groups
                    $groups = $query
                            ->innerJoinWith('GroupMembers', function ($q) use($logging_in_user_id) {
                                return $q->where(['GroupMembers.user_id' => $logging_in_user_id, 'GroupMembers.status IN' => ['admin', 'moderator', 'member']]);
                            })
                            ->where(['Groups.status' => 'active'])
                            ->order(['GroupMembers.created' => 'DESC']);
                } elseif ($type == 'trending') {
                    //Trending Groups
                    
                    //My Groups also the once I have requested for.
                    $subQuery = $this
                            ->find()
                            ->innerJoinWith('GroupMembers', function ($q) use($logging_in_user_id) {
                                return $q->where(['GroupMembers.user_id' => $logging_in_user_id, 'GroupMembers.status IN' => ['admin', 'moderator', 'member', 'requested']]);
                            })
                            ->where(['Groups.status' => 'active']);
                    $my_group_ids = array();
                    $myGroups = $subQuery->all();
                    if (!$myGroups->isEmpty()) {
                        foreach ($myGroups as $myGroup) {
                            $my_group_ids[] = $myGroup->uuid;
                        }
                    }
                    
                    //Get all groups except my groups
                    $where = array();
                    if(!empty($my_group_ids)) {
                        $where['Groups.uuid NOT IN'] = $my_group_ids;
                    }
                    $where['Groups.status'] = 'active';
                    $groups = $query
                            ->where($where)
                            ->order(['Groups.created' => 'DESC']);
                }

                $resultSet['total_count'] = $total_count = $query->count();
                $offset = ($page - 1) * $limit;
                
                $query
                        ->offset($offset)
                        ->limit($limit);

//                echo $query->sql(); exit();
                
                $groups = $query->all();
                if (!$groups->isEmpty()) {
                    foreach ($groups as $group) {
                        //pr($group); exit();
                        $resultSet['groups'][] = $this->formatGroupData($group, $logging_in_user_id);
                    }
                    //exit();
                }
            }
        } catch (Exception $ex) {
            
        }

        return $resultSet;
    }

}
