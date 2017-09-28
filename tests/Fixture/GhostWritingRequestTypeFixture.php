<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GhostWritingRequestTypeFixture
 *
 */
class GhostWritingRequestTypeFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ghost_writing_request_type';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ghost_writing_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'request_type_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'ngo_id' => ['type' => 'index', 'columns' => ['ghost_writing_id'], 'length' => []],
            'request_type_id' => ['type' => 'index', 'columns' => ['request_type_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'ghost_writing_request_type_ibfk_3' => ['type' => 'foreign', 'columns' => ['request_type_id'], 'references' => ['masters', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'ghost_writing_request_type_ibfk_2' => ['type' => 'foreign', 'columns' => ['ghost_writing_id'], 'references' => ['ghost_writing', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => 'a24e7fe9-ab0e-49ed-a4ba-79202edb4d85',
            'ghost_writing_id' => 'decc2ef4-a78b-47cf-a80e-ab23c907b150',
            'request_type_id' => '9c3309fe-4247-4fcb-88ef-900ac3ee1b07',
            'created' => '2017-09-01 09:33:22',
            'created_by' => 'c301024a-413d-4a34-9bfd-3e9424d98457'
        ],
    ];
}
