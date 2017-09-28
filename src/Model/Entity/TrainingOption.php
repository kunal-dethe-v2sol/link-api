<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TrainingOption Entity
 *
 * @property string $uuid
 * @property string $training_id
 * @property string $type
 * @property string $industry_id
 * @property string $institute_id
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 *
 * @property \App\Model\Entity\Training $training
 * @property \App\Model\Entity\Industry $industry
 * @property \App\Model\Entity\Institute $institute
 */
class TrainingOption extends Entity
{

}
