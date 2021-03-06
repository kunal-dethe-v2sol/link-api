<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ArticleCommentsTable|\Cake\ORM\Association\HasMany $ArticleComments
 * @property \App\Model\Table\ArticleLikesTable|\Cake\ORM\Association\HasMany $ArticleLikes
 * @property \App\Model\Table\ArticleSharewithEmailTable|\Cake\ORM\Association\HasMany $ArticleSharewithEmail
 * @property \App\Model\Table\ArticleSharewithGroupsTable|\Cake\ORM\Association\HasMany $ArticleSharewithGroups
 * @property \App\Model\Table\ArticleSharewithPeopleTable|\Cake\ORM\Association\HasMany $ArticleSharewithPeople
 *
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
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

        $this->setTable('articles');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ArticleComments', [
            'foreignKey' => 'article_id'
        ]);
        $this->hasMany('ArticleLikes', [
            'foreignKey' => 'article_id'
        ]);
        $this->hasMany('ArticleSharewithEmail', [
            'foreignKey' => 'article_id'
        ]);
        $this->hasMany('ArticleSharewithGroups', [
            'foreignKey' => 'article_id'
        ]);
        $this->hasMany('ArticleSharewithPeople', [
            'foreignKey' => 'article_id'
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
//        $validator
//            ->uuid('uuid')
//            ->allowEmpty('uuid', 'create');
//
//        $validator
//            ->allowEmpty('image_serialize');
//
//        $validator
//            ->requirePresence('headline', 'create')
//            ->notEmpty('headline');
//
//        $validator
//            ->requirePresence('article_text', 'create')
//            ->notEmpty('article_text');
//
//        $validator
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');
//
//        $validator
//            ->uuid('created_by')
//            ->requirePresence('created_by', 'create')
//            ->notEmpty('created_by');
//
//        $validator
//            ->uuid('modified_by')
//            ->allowEmpty('modified_by');

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

        return $rules;
    }
}
