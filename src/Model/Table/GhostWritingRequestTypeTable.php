<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GhostWritingRequestType Model
 *
 * @property \App\Model\Table\GhostWritingTable|\Cake\ORM\Association\BelongsTo $GhostWriting
 * @property \App\Model\Table\MastersTable|\Cake\ORM\Association\BelongsTo $Masters
 *
 * @method \App\Model\Entity\GhostWritingRequestType get($primaryKey, $options = [])
 * @method \App\Model\Entity\GhostWritingRequestType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GhostWritingRequestType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GhostWritingRequestType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GhostWritingRequestType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GhostWritingRequestType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GhostWritingRequestType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GhostWritingRequestTypeTable extends Table
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

        $this->setTable('ghost_writing_request_type');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('GhostWriting', [
            'foreignKey' => 'ghost_writing_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Masters', [
            'foreignKey' => 'request_type_id',
            'joinType' => 'INNER'
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
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

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
        $rules->add($rules->existsIn(['ghost_writing_id'], 'GhostWriting'));
        $rules->add($rules->existsIn(['request_type_id'], 'Masters'));

        return $rules;
    }
}
