<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * PostComments Controller
 *
 * @property \App\Model\Table\PostCommentsTable $PostComments
 *
 * @method \App\Model\Entity\PostComment[] paginate($object = null, array $settings = [])
 */
class PostCommentsController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $post_id = $this->request->getParam('post_id');
        $this->paginate = [
            //'contain' => ['Users', 'Posts']
        ];
        $postComments = $this->PostComments->find('all')
                        ->where(['post_id'=>$post_id])
                        ->toArray();
        
        $this->set(compact('postComments'));       
    }

    /**
     * View method
     *
     * @param string|null $id Post Comment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $postComment = $this->PostComments->get($id);

        $this->set('postComment', $postComment);
        $this->set('_serialize', ['postComment']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post_id = $this->request->getParam('post_id');
        $postComment = $this->PostComments->newEntity();
        if ($this->request->is('post')) {
            $this->request = $this->request->withData('post_id', $post_id);
            $postComment = $this->PostComments->patchEntity($postComment, $this->request->getData());
            if ($this->PostComments->save($postComment)) {
                 $this->set($this->Common->apiResponse('200', "The comment saved successfully"));
            } else {
                $this->set($this->Common->apiResponse('400', $this->Common->toastErrorMessages($postComment->errors())));
            }
        }
       
    }

    /**
     * Edit method
     *
     * @param string|null $id Post Comment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $postComment = $this->PostComments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postComment = $this->PostComments->patchEntity($postComment, $this->request->getData());
            if ($this->PostComments->save($postComment)) {
                $this->Flash->success(__('The post comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post comment could not be saved. Please, try again.'));
        }
        $users = $this->PostComments->Users->find('list', ['limit' => 200]);
        $posts = $this->PostComments->Posts->find('list', ['limit' => 200]);
        $this->set(compact('postComment', 'users', 'posts'));
        $this->set('_serialize', ['postComment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Post Comment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $postComment = $this->PostComments->get($id);
        if ($this->PostComments->delete($postComment)) {
            $this->set($this->Common->apiResponse("200","The post comment has been deleted."));
        } else {
            $this->set($this->Common->apiResponse("404","The post comment could not be deleted. Please, try again."));
        }
        
    }
}
