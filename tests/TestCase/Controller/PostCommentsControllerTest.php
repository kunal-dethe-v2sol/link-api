<?php
namespace App\Test\TestCase\Controller;

use App\Controller\PostCommentsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\PostCommentsController Test Case
 */
class PostCommentsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.post_comments',
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
        'app.messages',
        'app.threads',
        'app.thread_users',
        'app.post_likes',
        'app.post_sharewith_email',
        'app.post_sharewith_groups',
        'app.post_sharewith_people',
        'app.posts',
        'app.post_social_links',
        'app.user_accounts',
        'app.masters',
        'app.user_connections',
        'app.user_exp'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
