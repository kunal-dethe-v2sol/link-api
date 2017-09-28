<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PostSharewithPerson Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $post_id
 * @property string $person_id
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Post $post
 */
class PostSharewithPerson extends Entity
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
