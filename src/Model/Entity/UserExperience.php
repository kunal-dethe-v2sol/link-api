<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserExperience Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $emp_name
 * @property \Cake\I18n\FrozenDate $from_date
 * @property \Cake\I18n\FrozenDate $to_date
 * @property string $till_now
 * @property string $role
 * @property string $country_id
 * @property string $state_id
 * @property string $city_id
 * @property string $industry_id
 * @property string $functional_area_id
 * @property string $designation_id
 * @property string $achievement
 * @property string $short_description
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\Industry $industry
 * @property \App\Model\Entity\FunctionalArea $functional_area
 * @property \App\Model\Entity\Designation $designation
 */
class UserExperience extends Entity
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
