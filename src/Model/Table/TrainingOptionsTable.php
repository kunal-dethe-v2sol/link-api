<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TrainingOptions Model
 *
 * @property \App\Model\Table\TrainingsTable|\Cake\ORM\Association\BelongsTo $Trainings
 * @property \App\Model\Table\IndustriesTable|\Cake\ORM\Association\BelongsTo $Industries
 * @property \App\Model\Table\InstitutesTable|\Cake\ORM\Association\BelongsTo $Institutes
 *
 * @method \App\Model\Entity\TrainingOption get($primaryKey, $options = [])
 * @method \App\Model\Entity\TrainingOption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TrainingOption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TrainingOption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TrainingOption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TrainingOption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TrainingOption findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TrainingOptionsTable extends Table
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

        $this->setTable('training_options');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Trainings', [
            'foreignKey' => 'training_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Industries', [
            'foreignKey' => 'industry_id'
        ]);
        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id'
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
            ->requirePresence('uuid', 'create')
            ->notEmpty('uuid');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

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
        $rules->add($rules->existsIn(['training_id'], 'Trainings'));
        $rules->add($rules->existsIn(['industry_id'], 'Industries'));
        $rules->add($rules->existsIn(['institute_id'], 'Institutes'));

        return $rules;
    }
}
