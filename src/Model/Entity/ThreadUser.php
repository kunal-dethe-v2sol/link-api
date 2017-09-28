<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ThreadUser Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $thread_id
 * @property string $start_message_id
 * @property string $last_message_id
 * @property string $status
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Thread $thread
 * @property \App\Model\Entity\Message $message
 */
class ThreadUser extends Entity {

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
