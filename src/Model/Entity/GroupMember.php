<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GroupMember Entity
 *
 * @property string $uuid
 * @property string $group_id
 * @property string $user_id
 * @property string $request_type
 * @property \Cake\I18n\FrozenTime $approved_datetime
 * @property string $approved_by
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\User $user
 */
class GroupMember extends Entity {

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
