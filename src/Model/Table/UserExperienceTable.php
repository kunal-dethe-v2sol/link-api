<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserExperience Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CountriesTable|\Cake\ORM\Association\BelongsTo $Countries
 * @property \App\Model\Table\StatesTable|\Cake\ORM\Association\BelongsTo $States
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\IndustriesTable|\Cake\ORM\Association\BelongsTo $Industries
 * @property \App\Model\Table\FunctionalAreasTable|\Cake\ORM\Association\BelongsTo $FunctionalAreas
 * @property \App\Model\Table\DesignationsTable|\Cake\ORM\Association\BelongsTo $Designations
 *
 * @method \App\Model\Entity\UserExperience get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserExperience newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserExperience[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserExperience|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserExperience patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserExperience[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserExperience findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserExperienceTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('user_experience');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('States', [
            'foreignKey' => 'state_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Masters', [
            'foreignKey' => 'industry_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Masters', [
            'foreignKey' => 'functional_area_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Masters', [
            'foreignKey' => 'designation_id',
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
                    'emp_name',
                    'from_date',
                    'to_date',
                    'role',
                    'industry_id',
                    'functional_area_id',
                    'designation_id'
                ])
                ->notEmpty([
                    'emp_name',
                    'from_date',
                    'to_date',
                    'role',
                    'industry_id',
                    'functional_area_id',
                    'designation_id'
                ])
                ->add('from_date', 'v_from_date', [
                    'rule' => 'date'
                ])
                ->add('to_date', 'v_to_date', [
                    'rule' => 'date'
                ])
                ->add('industry_id', 'v_industry_id', [
                    'rule' => ['custom', '/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i'],
                ])
                ->add('functional_area_id', 'v_functional_area_id', [
                    'rule' => ['custom', '/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i'],
                ])
                ->add('designation_id', 'v_designation_id', [
                    'rule' => ['custom', '/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i'],
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
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['state_id'], 'States'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['industry_id'], 'Masters'));
        $rules->add($rules->existsIn(['functional_area_id'], 'Masters'));
        $rules->add($rules->existsIn(['designation_id'], 'Masters'));

        return $rules;
    }

}
