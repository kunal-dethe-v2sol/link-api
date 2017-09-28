<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GhostWritingRequestType Entity
 *
 * @property string $uuid
 * @property string $ghost_writing_id
 * @property string $request_type_id
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 *
 * @property \App\Model\Entity\GhostWriting $ghost_writing
 * @property \App\Model\Entity\Master $master
 */
class GhostWritingRequestType extends Entity
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
