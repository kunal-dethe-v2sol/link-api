<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Posts Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\PostCommentsTable|\Cake\ORM\Association\HasMany $PostComments
 * @property \App\Model\Table\PostLikesTable|\Cake\ORM\Association\HasMany $PostLikes
 * @property \App\Model\Table\PostSharewithEmailTable|\Cake\ORM\Association\HasMany $PostSharewithEmail
 * @property \App\Model\Table\PostSharewithGroupsTable|\Cake\ORM\Association\HasMany $PostSharewithGroups
 * @property \App\Model\Table\PostSharewithPeopleTable|\Cake\ORM\Association\HasMany $PostSharewithPeople
 * @property \App\Model\Table\PostSocialLinksTable|\Cake\ORM\Association\HasMany $PostSocialLinks
 *
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PostsTable extends Table
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

        $this->setTable('posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('uuid');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('PostComments', [
            'foreignKey' => 'post_id'
        ]);
        $this->hasMany('PostLikes', [
            'foreignKey' => 'post_id'
        ]);
        $this->hasMany('PostSharewithEmail', [
            'foreignKey' => 'post_id'
        ]);
        $this->hasMany('PostSharewithGroups', [
            'foreignKey' => 'post_id'
        ]);
        $this->hasMany('PostSharewithPeople', [
            'foreignKey' => 'post_id'
        ]);
        $this->hasMany('PostSocialLinks', [
            'foreignKey' => 'post_id'
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
//            ->requirePresence('image_serialize', 'create')
//            ->notEmpty('image_serialize');
//
//        $validator
//            ->allowEmpty('title');
//
//        $validator
//            ->requirePresence('content', 'create')
//            ->notEmpty('content');
//
//        $validator
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');
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
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }
}
