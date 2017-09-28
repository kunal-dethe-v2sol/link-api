<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;

/**
 * Threads Controller
 *
 * @property \App\Model\Table\ThreadsTable $Threads
 *
 * @method \App\Model\Entity\Thread[] paginate($object = null, array $settings = [])
 */
class ThreadsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Threads');
        $this->loadModel('ThreadUsers');
        $this->loadComponent('Common');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $response = array(
            'code' => 500,
            'data' => array(),
        );
        if ($this->request->is(['get'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

//                echo $logging_in_user_id;
//                exit();

                $search = $this->Common->sanitizeString($this->request->getQuery('search', ''));
                $page = $this->request->getQuery('page', 1);
                $limit = $this->request->getQuery('limit', Configure::read('SITE_PAGINATION_LIMIT'));

//                echo '<pre>';
//                var_dump($search);
//                print_r($page);
//                print_r($limit);
//                exit();

                $params = array(
                    'logging_in_user_id' => $logging_in_user_id,
                    'search' => $search,
                    'page' => $page,
                    'limit' => $limit,
                );

//                pr($params);
//                exit();

                $threads = $this->Threads->getThreads($params);
                $success = true;

//                pr($threads);
//                exit();

                if ($success) {
                    $response['code'] = 200;
                    $response['data'] = $threads;
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], array('data' => $response['data'])));
    }

    /**
     * View method
     *
     * @param string|null $id Thread id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($thread_id) {
        $response = array(
            'code' => 500,
            'data' => "GROUPS_VIEW_DEFAULT_ERROR",
        );

        if ($this->request->is('get')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                //$thread_id = $this->request->getParam('thread_id');
//                pr($thread);
//                exit();
                $thread = $this->Threads->get($thread_id);
                $thread = $this->Threads->formatThreadData($thread, $logging_in_user_id);
                
                $success = true;

                if ($success) {
                    $response['code'] = 200;
                    $response['data'] = $thread;
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], array('data' => $response['data'])));
    }

    /**
     * Delete method
     *
     * @param string|null $id Thread id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $response = array(
            'code' => 500,
            'msg' => "The Threads could not be deleted. Please, try again.",
        );
        if ($this->request->is(['delete'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $thread_ids = $this->request->getData()['thread_id'];
                $status = $this->request->getData()['status'];
                if (!is_array($thread_ids)) {
                    $thread_ids = explode(',', $thread_ids[0]);
                }
                foreach ($thread_ids as $thread_id) {
                    $thread = $this->Threads->get($thread_id, [
                        'contain' => []
                    ]);
                    $threadData = array(
                        'status' => $status,
                        'modified_by' => $logging_in_user_id,
                    );
                    $thread = $this->Threads->patchEntity($thread, $threadData, array('validate' => false));
                    if ($this->Threads->save($thread)) {
                        $success = true;
                    }
                }
                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "The Threads has been deleted.";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }
        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }

}
