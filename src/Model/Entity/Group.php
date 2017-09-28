<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $name
 * @property string $slug
 * @property string $image_serialize
 * @property string $about
 * @property string $guidelines
 * @property string $visibility
 * @property string $post_approval_required
 * @property int $pending_member_count
 * @property int $total_member_count
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ArticleSharewithGroup[] $article_sharewith_groups
 * @property \App\Model\Entity\GroupMember[] $group_members
 * @property \App\Model\Entity\PostSharewithGroup[] $post_sharewith_groups
 * @property \App\Model\Entity\Post[] $posts
 */
class Group extends Entity {

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
