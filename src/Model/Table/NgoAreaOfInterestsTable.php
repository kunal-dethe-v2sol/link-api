<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NgoAreaOfInterests Model
 *
 * @property \App\Model\Table\NgosTable|\Cake\ORM\Association\BelongsTo $Ngos
 * @property \App\Model\Table\MastersTable|\Cake\ORM\Association\BelongsTo $Masters
 *
 * @method \App\Model\Entity\NgoAreaOfInterest get($primaryKey, $options = [])
 * @method \App\Model\Entity\NgoAreaOfInterest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NgoAreaOfInterest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NgoAreaOfInterest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NgoAreaOfInterest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NgoAreaOfInterest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NgoAreaOfInterest findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NgoAreaOfInterestsTable extends Table
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

        $this->setTable('ngo_area_of_interests');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Ngos', [
            'foreignKey' => 'ngo_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Masters', [
            'foreignKey' => 'area_of_interest_id',
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
        $rules->add($rules->existsIn(['ngo_id'], 'Ngos'));
        $rules->add($rules->existsIn(['area_of_interest_id'], 'Masters'));

        return $rules;
    }
}
