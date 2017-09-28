<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PremiumClubFixture
 *
 */
class PremiumClubFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'premium_club';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'premium_club_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '43fa62e5-8e16-49f2-a9b5-ae0891bde33c',
            'user_id' => 'efdf5237-2694-4170-884c-9dc4dba21bba',
            'status' => 'Lorem ipsum dolor sit amet',
            'created' => '2017-09-01 09:32:45',
            'created_by' => 'b2a6f13e-04d2-4270-ba5d-b0599cd6f3c1'
        ],
    ];
}
