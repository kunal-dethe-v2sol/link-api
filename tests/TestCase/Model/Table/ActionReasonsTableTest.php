<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ActionReasonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ActionReasonsTable Test Case
 */
class ActionReasonsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ActionReasonsTable
     */
    public $ActionReasons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.action_reasons',
        'app.users',
        'app.countries',
        'app.states',
        'app.cities',
        'app.roles',
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
        'app.user_accounts',
        'app.masters',
        'app.user_connections',
        'app.user_exp',
        'app.targets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ActionReasons') ? [] : ['className' => ActionReasonsTable::class];
        $this->ActionReasons = TableRegistry::get('ActionReasons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ActionReasons);

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
