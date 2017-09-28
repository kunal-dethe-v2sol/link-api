<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EventInvite Entity
 *
 * @property string $uuid
 * @property string $event_id
 * @property string $type
 * @property string $connection_id
 * @property string $contact_email
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 *
 * @property \App\Model\Entity\Event $event
 * @property \App\Model\Entity\User $user
 */
class EventInvite extends Entity
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
