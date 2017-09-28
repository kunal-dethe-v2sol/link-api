<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * Threads Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\MessagesTable|\Cake\ORM\Association\HasMany $Messages
 * @property \App\Model\Table\ThreadUsersTable|\Cake\ORM\Association\HasMany $ThreadUsers
 *
 * @method \App\Model\Entity\Thread get($primaryKey, $options = [])
 * @method \App\Model\Entity\Thread newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Thread[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Thread|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Thread patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Thread[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Thread findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ThreadsTable extends AppTable {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('threads');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Messages', [
            'foreignKey' => 'thread_id'
        ]);
        $this->hasMany('ThreadUsers', [
            'foreignKey' => 'thread_id'
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
                ->uuid('uuid')
                ->allowEmpty('uuid', 'create');

        $validator
                ->allowEmpty('header', 'create');

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
     * 1. Returns the list of logged_in_user's threads.
     * 2. Returns the list of logged_in_user's threads with matching messages.
     * 
     * @param array $params
     * @return boolean $resultSet
     */
    public function getThreads($params = array()) {
        $resultSet = array(
            'threads' => array(),
            'is_last' => false,
        );

        $logging_in_user_id = '';
        $search = '';
        $page = 1;
        $limit = Configure::read('SITE_PAGINATION_LIMIT');

        extract($params);

//        pr($params);
//        exit();

        try {
            if ($logging_in_user_id) {
                $threads = NULL;

                $query = $this
                        ->find();

                if ($search) {
                    $query
                            ->matching('ThreadUsers', function($q) use($logging_in_user_id) {
                                return $q->where(['ThreadUsers.user_id' => $logging_in_user_id, 'ThreadUsers.status' => 'active']);
                            })
                            ->matching('Messages', function($q) use($search) {
                                return $q->where(['Messages.message LIKE' => '%' . $search . '%']);
                            })
                            ->where(['Threads.status IN' => array('active', 'blocked')])
                            ->group(['Threads.uuid']);
                } else {
                    $query
                            ->innerJoinWith('ThreadUsers', function($q) use($logging_in_user_id) {
                                return $q->where(['ThreadUsers.user_id' => $logging_in_user_id, 'ThreadUsers.status' => 'active']);
                            })
                            ->where(['Threads.status IN' => array('active', 'blocked')])
                            ->order(['Threads.modified' => 'DESC']);
                }



                $total_count = $query->count();
                $offset = ($page - 1) * $limit;
                if ($offset + $limit >= $total_count) {
                    $resultSet['is_last'] = true;
                }

                $query
                        ->offset($offset)
                        ->limit($limit);

//                echo $query->sql(); exit();

                $threads = $query->all();
                if (!$threads->isEmpty()) {
                    foreach ($threads as $thread) {
                        //pr($thread);
                        $resultSet['threads'][] = $this->formatThreadData($thread, $logging_in_user_id);
                    }
                    //exit();
                }
            }
        } catch (Exception $ex) {
            
        }

        return $resultSet;
    }

}
