<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use \ArrayObject;

/**
 * Users Model
 *
 * @property \App\Model\Table\CountriesTable|\Cake\ORM\Association\BelongsTo $Countries
 * @property \App\Model\Table\StatesTable|\Cake\ORM\Association\BelongsTo $States
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\ActionReasonsTable|\Cake\ORM\Association\HasMany $ActionReasons
 * @property \App\Model\Table\ArticleCommentsTable|\Cake\ORM\Association\HasMany $ArticleComments
 * @property \App\Model\Table\ArticleLikesTable|\Cake\ORM\Association\HasMany $ArticleLikes
 * @property \App\Model\Table\ArticleSharewithEmailTable|\Cake\ORM\Association\HasMany $ArticleSharewithEmail
 * @property \App\Model\Table\ArticleSharewithGroupsTable|\Cake\ORM\Association\HasMany $ArticleSharewithGroups
 * @property \App\Model\Table\ArticleSharewithPeopleTable|\Cake\ORM\Association\HasMany $ArticleSharewithPeople
 * @property \App\Model\Table\ArticlesTable|\Cake\ORM\Association\HasMany $Articles
 * @property \App\Model\Table\GroupMembersTable|\Cake\ORM\Association\HasMany $GroupMembers
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\HasMany $Groups
 * @property \App\Model\Table\MessagesTable|\Cake\ORM\Association\HasMany $Messages
 * @property \App\Model\Table\PostCommentsTable|\Cake\ORM\Association\HasMany $PostComments
 * @property \App\Model\Table\PostLikesTable|\Cake\ORM\Association\HasMany $PostLikes
 * @property \App\Model\Table\PostSharewithEmailTable|\Cake\ORM\Association\HasMany $PostSharewithEmail
 * @property \App\Model\Table\PostSharewithGroupsTable|\Cake\ORM\Association\HasMany $PostSharewithGroups
 * @property \App\Model\Table\PostSharewithPeopleTable|\Cake\ORM\Association\HasMany $PostSharewithPeople
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\HasMany $Posts
 * @property \App\Model\Table\ThreadUsersTable|\Cake\ORM\Association\HasMany $ThreadUsers
 * @property \App\Model\Table\ThreadsTable|\Cake\ORM\Association\HasMany $Threads
 * @property \App\Model\Table\UserAccountsTable|\Cake\ORM\Association\HasMany $UserAccounts
 * @property \App\Model\Table\UserConnectionsTable|\Cake\ORM\Association\HasMany $UserConnections
 * @property \App\Model\Table\UserExpTable|\Cake\ORM\Association\HasMany $UserExperience
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends AppTable {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id'
        ]);
        $this->belongsTo('States', [
            'foreignKey' => 'state_id'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id'
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ActionReasons', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ArticleComments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ArticleLikes', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ArticleSharewithEmail', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ArticleSharewithGroups', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ArticleSharewithPeople', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Articles', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('GroupMembers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Groups', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Messages', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('PostComments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('PostLikes', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('PostSharewithEmail', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('PostSharewithGroups', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('PostSharewithPeople', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Posts', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ThreadUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Threads', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserAccounts', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserConnections', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserExperience', [
            'foreignKey' => 'user_id'
        ]);
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        if ($entity->get('uuid') == null) {
            $entity->set('status', $this->_setStatus($entity->get('registration_type')));
        } else {
            
        }
    }

    private function _setStatus($registration_type) {
        return $registration_type == 'direct-request' ? 'on-hold' : 'active';
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
                    'firstname' => [
                        'mode' => 'create'
                    ],
                    'lastname' => [
                        'mode' => 'create'
                    ],
                    'mobile_no' => [
                        'mode' => 'create'
                    ]
                ])
                ->notEmpty([
                    'firstname',
                    'lastname',
                    'mobile_no',
                    'password' => [
                        'mode' => 'update'
                    ]
                ])
                ->add('key_skills', 'valid_key_skills', [
                    'mode' => 'update',
                    'rule' => 'notBlank',
                    'on' => function ($context) {
                        if (isset($context['data']['detail_type']) === true)
                            return $context['data']['detail_type'] == 'primary-info';
                        else
                            return false;
                    }
                ])
                ->add('total_exp', 'valid_total_exp', [
                    'mode' => 'update',
                    'rule' => 'notBlank',
                    'on' => function ($context) {
                        if (isset($context['data']['detail_type']) === true)
                            return $context['data']['detail_type'] == 'primary-info';
                        else
                            return false;
                    }
                ])
                ->add('summary', 'valid_summary_1', [
                    'mode' => 'update',
                    'rule' => 'notBlank',
                    'on' => function ($context) {
                        if (isset($context['data']['detail_type']) === true)
                            return $context['data']['detail_type'] == 'primary-info';
                        else
                            return false;
                    }
                ])
                ->add('summary', 'valid_summary_2', [
                    'mode' => 'update',
                    'rule' => ['maxLength', 500],
                    'on' => function ($context) {
                if (isset($context['data']['detail_type']) === true)
                    return $context['data']['detail_type'] == 'primary-info';
                else
                    return false;
            }
                ])
                ->add('mobile_no', 'valid_mobile_no', [
                    'rule' => ['custom', '/\d{10}/i'],
                    'message' => 'Please enter valid 10 digit mobile number.'
                ])
                ->add('alt_mobile', 'valid_alt_mobile', [
                    'rule' => ['custom', '/\d{10}/i'],
                    'message' => 'Please enter valid 10 digit mobile number.'
                ])
                ->add('firstname', 'valid_firstname', [
                    'rule' => ['custom', '/^[a-z ]+$/i'],
                    'message' => 'Name must be alphabetical only.'
                ])
                ->add('lastname', 'valid_lastname', [
                    'rule' => ['custom', '/^[a-z ]+$/i'],
                    'message' => 'Name must be alphabetical only.'
                ])
                ->add('confirm_password', 'valid_confirm_password', [
                    'rule' => ['compareWith', 'password'],
                    'message' => 'New password and confirm password does not match.',
                ])
                ->add('new_password_token', 'validate_password_token', [
                    'rule' => ['compareWith', 'old_password_token'],
                    'message' => 'Password token does not match.',
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
        $rules->add($rules->isUnique(['mobile_no', 'flag_mobile']));
        $rules->add($rules->isUnique(['alt_mobile_no', 'flag_alt_mobile']));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['state_id'], 'States'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }

}
