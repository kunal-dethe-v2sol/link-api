<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TrainingOptionsFixture
 *
 */
class TrainingOptionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'training_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'type' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'industry_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'institute_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'training_id' => ['type' => 'index', 'columns' => ['training_id'], 'length' => []],
            'industry_id' => ['type' => 'index', 'columns' => ['industry_id'], 'length' => []],
            'institute_id' => ['type' => 'index', 'columns' => ['institute_id'], 'length' => []],
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
            'uuid' => '42ab4861-ab5d-44ec-b8df-0162deb23aa8',
            'training_id' => '589fdcbf-9ca9-4526-b084-d6cc7d0b2298',
            'type' => 'Lorem ipsum dolor sit amet',
            'industry_id' => '3a5dd9b6-9eb9-476c-b08b-84ca652a09fd',
            'institute_id' => '47451945-6d9b-44fa-af4d-5d5b78192820',
            'created' => '2017-08-31 13:03:16',
            'created_by' => '8d4dd7f6-7331-4549-bc17-61edbd1b5c51'
        ],
    ];
}
