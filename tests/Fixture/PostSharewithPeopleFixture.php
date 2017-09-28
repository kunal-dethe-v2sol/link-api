<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PostSharewithPeopleFixture
 *
 */
class PostSharewithPeopleFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'post_sharewith_people';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'post_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'person_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'post_id' => ['type' => 'index', 'columns' => ['post_id'], 'length' => []],
            'person_id' => ['type' => 'index', 'columns' => ['person_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'post_sharewith_people_ibfk_3' => ['type' => 'foreign', 'columns' => ['person_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'post_sharewith_people_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'post_sharewith_people_ibfk_2' => ['type' => 'foreign', 'columns' => ['post_id'], 'references' => ['posts', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => 'd92e7a23-d1cb-48c2-ab15-e668733e8e95',
            'user_id' => 'b865d8cc-b8e0-4140-a751-e0ec1f2471c0',
            'post_id' => '66090965-aeaa-4c7b-a871-bbd0dfd85f28',
            'person_id' => '1e68bd90-2a55-4a53-bd21-b07b77094bb3',
            'created' => '2017-08-22 12:40:02',
            'created_by' => 'bc934d5b-1992-42dd-ba5f-d5a8b59acea8'
        ],
    ];
}
