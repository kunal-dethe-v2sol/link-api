<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NgoAreaOfInterestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NgoAreaOfInterestsTable Test Case
 */
class NgoAreaOfInterestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NgoAreaOfInterestsTable
     */
    public $NgoAreaOfInterests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ngo_area_of_interests',
        'app.ngos',
        'app.users',
        'app.countries',
        'app.states',
        'app.cities',
        'app.roles',
        'app.action_reasons',
        'app.article_comments',
        'app.article_likes',
        'app.article_sharewith_email',
        'app.article_sharewith_groups',
        'app.article_sharewith_people',
        'app.articles',
        'app.group_members',
        'app.groups',
        'app.post_sharewith_groups',
        'app.posts',
        'app.post_comments',
        'app.post_likes',
        'app.post_sharewith_email',
        'app.post_sharewith_people',
        'app.post_social_links',
        'app.messages',
        'app.threads',
        'app.thread_users',
        'app.start_messages',
        'app.last_messages',
        'app.user_accounts',
        'app.masters',
        'app.user_connections',
        'app.connections',
        'app.user_exp'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NgoAreaOfInterests') ? [] : ['className' => NgoAreaOfInterestsTable::class];
        $this->NgoAreaOfInterests = TableRegistry::get('NgoAreaOfInterests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NgoAreaOfInterests);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
