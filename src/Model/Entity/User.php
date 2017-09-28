<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property string $uuid
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string $mobile
 * @property string $alt_mobile
 * @property int $marital_status
 * @property int $gender
 * @property \Cake\I18n\FrozenDate $dob
 * @property string $country_id
 * @property string $state_id
 * @property string $city_id
 * @property string $zipcode
 * @property string $profile_image
 * @property string $headline
 * @property string $tokens
 * @property int $v_mobile
 * @property int $v_alt_mobile
 * @property string $role_id
 * @property int $total_exp
 * @property string $keyskills_serialize
 * @property string $summary
 * @property string $resume1
 * @property string $resume2
 * @property string $resume_type1
 * @property string $resume_type2
 * @property string $profile_snapshot
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\ActionReason[] $action_reasons
 * @property \App\Model\Entity\ArticleComment[] $article_comments
 * @property \App\Model\Entity\ArticleLike[] $article_likes
 * @property \App\Model\Entity\ArticleSharewithEmail[] $article_sharewith_email
 * @property \App\Model\Entity\ArticleSharewithGroup[] $article_sharewith_groups
 * @property \App\Model\Entity\ArticleSharewithPerson[] $article_sharewith_people
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\GroupMember[] $group_members
 * @property \App\Model\Entity\Group[] $groups
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\PostComment[] $post_comments
 * @property \App\Model\Entity\PostLike[] $post_likes
 * @property \App\Model\Entity\PostSharewithEmail[] $post_sharewith_email
 * @property \App\Model\Entity\PostSharewithGroup[] $post_sharewith_groups
 * @property \App\Model\Entity\PostSharewithPerson[] $post_sharewith_people
 * @property \App\Model\Entity\Post[] $posts
 * @property \App\Model\Entity\ThreadUser[] $thread_users
 * @property \App\Model\Entity\Thread[] $threads
 * @property \App\Model\Entity\UserAccount[] $user_accounts
 * @property \App\Model\Entity\UserConnection[] $user_connections
 * @property \App\Model\Entity\UserExperience[] $user_exp
 */
class User extends Entity {

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'uuid' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password', 'password_token', 'created', 'created_by', 'modified', 'modified_by'
    ];
    protected $_virtual = [
        'full_name'
    ];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

    // https://book.cakephp.org/3.0/en/orm/entities.html
    protected function _getFullName() {
        return $this->_properties['firstname'] . ' ' . $this->_properties['lastname'];
    }

}
