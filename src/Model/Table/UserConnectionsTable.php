<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * UserConnections Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserConnection get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserConnection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserConnection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserConnection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserConnection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserConnection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserConnection findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserConnectionsTable extends AppTable {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('user_connections');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Connections', [
            'className' => 'Users',
            'foreignKey' => 'connection_id',
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
                ->uuid('uuid')
                ->allowEmpty('uuid', 'create');

        $validator
                ->requirePresence('status', 'create')
                ->notEmpty('status');

        $validator
                ->integer('created_by')
                ->requirePresence('created_by', 'create')
                ->notEmpty('created_by');

        $validator
                ->uuid('modified_by')
                ->allowEmpty('modified_by');

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
        $rules->add($rules->existsIn(['connection_id'], 'Connections'));

        return $rules;
    }

    /**
     * Returns the list of user connections.
     * 
     * @param array $params
     * @return boolean $resultSet
     */
    public function getUserConnections($params = array()) {
        $resultSet = array(
            'user_connections' => array(),
            'total_count' => 0,
            'is_last' => false,
        );

        $logging_in_user_id = '';
        $search = '';
        $page = 1;
        $limit = Configure::read('SITE_PAGINATION_LIMIT');

        extract($params);

        try {
            if ($logging_in_user_id) {
                if ($search) {
                    $query = $this
                        ->find()
                        ->contain(['Users', 'Connections'])
                        ->innerJoinWith('Users', function ($q) {
                            return $q->where(['Users.status' => 'active']);
                        })
                        ->innerJoinWith('Connections', function ($q) {
                            return $q->where(['Connections.status' => 'active']);
                        })
                        ->where([
                            'UserConnections.status' => 'accepted',
                            'OR' => [
                                [
                                    'UserConnections.user_id' => $logging_in_user_id,
                                    'OR' => [
                                        'Connections.firstname LIKE' => '%'.$search.'%',
                                        'Connections.lastname LIKE' => '%'.$search.'%',
                                    ]
                                ],
                                [
                                    'UserConnections.connection_id' => $logging_in_user_id,
                                    'OR' => [
                                        'Users.firstname LIKE' => '%'.$search.'%',
                                        'Users.lastname LIKE' => '%'.$search.'%',
                                    ]
                                ]
                            ]
                        ])
                        ->order(['UserConnections.created' => 'DESC']);
                } else {
                    $query = $this
                            ->find()
                            ->contain(['Users', 'Connections'])
                            ->innerJoinWith('Users', function ($q) {
                                return $q->where(['Users.status' => 'active']);
                            })
                            ->innerJoinWith('Connections', function ($q) {
                                return $q->where(['Connections.status' => 'active']);
                            })
                            ->where([
                                'UserConnections.status' => 'accepted',
                                'OR' => [['UserConnections.user_id' => $logging_in_user_id], ['UserConnections.connection_id' => $logging_in_user_id]]
                            ])
                            ->order(['UserConnections.created' => 'DESC']);
                }

                $total_count = $query->count();
                $resultSet['total_count'] = $total_count;

                $offset = ($page - 1) * $limit;
                if ($limit + $offset >= $total_count) {
                    $resultSet['is_last'] = true;
                }

                $query
                        ->offset($offset)
                        ->limit($limit);

//                echo $query->sql(); exit();

                $user_connections = $query->all();
                if (!$user_connections->isEmpty()) {
                    foreach ($user_connections as $user_connection) {
//                        pr($user_connection);
//                        exit();
                        $resultSet['user_connections'][] = $this->formatUserConnectionData($user_connection, $logging_in_user_id);
                    }
                    //exit();
                }
            }
        } catch (Exception $ex) {
            
        }

        return $resultSet;
    }

}
