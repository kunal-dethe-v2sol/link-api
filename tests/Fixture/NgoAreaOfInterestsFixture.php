<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NgoAreaOfInterestsFixture
 *
 */
class NgoAreaOfInterestsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ngo_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'area_of_interest_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'ngo_id' => ['type' => 'index', 'columns' => ['ngo_id'], 'length' => []],
            'area_of_interest_id' => ['type' => 'index', 'columns' => ['area_of_interest_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'ngo_area_of_interests_ibfk_2' => ['type' => 'foreign', 'columns' => ['area_of_interest_id'], 'references' => ['masters', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'ngo_area_of_interests_ibfk_1' => ['type' => 'foreign', 'columns' => ['ngo_id'], 'references' => ['ngos', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '2d4ea0ff-fc5a-4972-b663-fbb5f6ad065f',
            'ngo_id' => '5fe1a2f8-4db3-4496-9fa8-4847ee3b5b67',
            'area_of_interest_id' => 'ff4b1c47-88f6-4b3f-a963-0b014d085b55',
            'created' => '2017-09-01 09:30:33',
            'created_by' => 'ad3d6f3f-b919-4638-8155-2c16e705df97'
        ],
    ];
}
