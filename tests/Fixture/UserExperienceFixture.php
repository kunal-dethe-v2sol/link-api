<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserExperienceFixture
 *
 */
class UserExperienceFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'user_experience';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'emp_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'from_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'to_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'till_now' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'role' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'country_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'state_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'city_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'industry_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'functional_area_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'designation_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'achievement' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'short_description' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '0000-00-00 00:00:00', 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'uuid' => 'be2e2006-6bac-4962-a3cb-355b0104078e',
            'user_id' => '0df642b0-55c3-4d38-9752-510312567ce5',
            'emp_name' => 'Lorem ipsum dolor sit amet',
            'from_date' => '2017-09-01',
            'to_date' => '2017-09-01',
            'till_now' => 'Lorem ipsum dolor sit amet',
            'role' => 'Lorem ipsum dolor sit amet',
            'country_id' => 'b49dd481-ae5c-4748-bc6a-ba92a3a04d26',
            'state_id' => 'bc398d5e-b207-4513-a783-b10eb0fd0fcd',
            'city_id' => 'b0321db9-c2a3-46a0-a8af-7c9fbceae82d',
            'industry_id' => '0b080c7e-3187-439e-a11c-a62fb1ae2559',
            'functional_area_id' => '7674549a-6ffc-4237-be90-72b4b6e82c22',
            'designation_id' => 'f540052d-f8a7-4505-9d94-55f89eedeef0',
            'achievement' => 'Lorem ipsum dolor sit amet',
            'short_description' => 'Lorem ipsum dolor sit amet',
            'created' => 1504274602,
            'created_by' => '5c041d6c-d182-4033-8032-4637bbeca5bc',
            'modified' => 1504274602,
            'modified_by' => 'f6e76fc9-5e1e-4b44-b864-fac6388f9d52'
        ],
    ];
}
