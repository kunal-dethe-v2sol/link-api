<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserAccount Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $social_id
 * @property string $email
 * @property string $social_serialize
 * @property string $token
 * @property string $verification_flag
 * @property int $is_primary
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 */
class UserAccount extends Entity
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

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token'
    ];
}
