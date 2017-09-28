<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * PostLikes Controller
 *
 * @property \App\Model\Table\PostLikesTable $PostLikes
 *
 * @method \App\Model\Entity\PostLike[] paginate($object = null, array $settings = [])
 */
class PostLikesController extends AppController
{

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
        $postComments = $this->PostLikes->find('all')
                        ->where(['post_id'=>$post_id])
                        ->toArray();        
        $this->set(compact('postLikes')); 
        
    }

    /**
     * View method
     *
     * @param string|null $id Post Like id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $postLike = $this->PostLikes->get($id);
        $this->set('postLike', $postLike);        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post_id = $this->request->getParam('post_id');
        $postLike = $this->PostLikes->newEntity();
        if ($this->request->is('post')) {
            $this->request = $this->request->withData('post_id', $post_id);
            $postLike = $this->PostLikes->patchEntity($postLike, $this->request->getData());
            if ($this->PostLikes->save($postLike)) {
                  $this->set($this->Common->apiResponse('200', "The post like has been saved."));
            } else {
                $this->set($this->Common->apiResponse('400', $this->Common->toastErrorMessages($postLike->errors())));
            }           
        }
       
    }    

    /**
     * Delete method
     *
     * @param string|null $id Post Like id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $postLike = $this->PostLikes->get($id);
        if ($this->PostLikes->delete($postLike)) {
           $this->set($this->Common->apiResponse("200","The post like has been deleted."));
        } else {
            $this->set($this->Common->apiResponse("404","Please, try again."));
        }

    }
}
