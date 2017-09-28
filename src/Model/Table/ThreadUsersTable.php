<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ThreadUsers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ThreadsTable|\Cake\ORM\Association\BelongsTo $Threads
 * @property \App\Model\Table\MessagesTable|\Cake\ORM\Association\BelongsTo $Messages
 * @property \App\Model\Table\MessagesTable|\Cake\ORM\Association\BelongsTo $Messages
 *
 * @method \App\Model\Entity\ThreadUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThreadUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThreadUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThreadUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThreadUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThreadUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThreadUser findOrCreate($search, callable $callback = null, $options = [])
 */
class ThreadUsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('thread_users');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Threads', [
            'foreignKey' => 'thread_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('StartMessages', [
            'className' => 'Messages',
            'foreignKey' => 'start_message_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('LastMessages', [
            'className' => 'Messages',
            'foreignKey' => 'last_message_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('uuid')
            ->allowEmpty('uuid', 'create');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['thread_id'], 'Threads'));
        $rules->add($rules->existsIn(['start_message_id'], 'StartMessages'));
        $rules->add($rules->existsIn(['last_message_id'], 'LastMessages'));

        return $rules;
    }
}
