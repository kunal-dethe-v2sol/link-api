<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $image_serialize
 * @property string $title
 * @property string $content
 * @property string $group_id
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\PostComment[] $post_comments
 * @property \App\Model\Entity\PostLike[] $post_likes
 * @property \App\Model\Entity\PostSharewithEmail[] $post_sharewith_email
 * @property \App\Model\Entity\PostSharewithGroup[] $post_sharewith_groups
 * @property \App\Model\Entity\PostSharewithPerson[] $post_sharewith_people
 * @property \App\Model\Entity\PostSocialLink[] $post_social_links
 */
class Post extends Entity
{

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
}
