<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use \ArrayObject;

/**
 * OtpRequests Model
 *
 * @method \App\Model\Entity\OtpRequest get($primaryKey, $options = [])
 * @method \App\Model\Entity\OtpRequest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OtpRequest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OtpRequest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OtpRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OtpRequest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OtpRequest findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OtpRequestsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('otp_requests');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {
     
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->requirePresence('mobile_no', 'create')
                ->notEmpty('mobile_no')
                ->add('mobile_no', 'valid_mobile_no', [
                    'rule' => ['custom', '/\d{10}/i'],
                    'message' => 'Please enter valid 10 digit mobile number.'
                ]);

        $validator
                ->integer('otp')
                ->requirePresence('otp', 'create')
                ->notEmpty('otp');

        return $validator;
    }

}
