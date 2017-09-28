<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[] paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{

     public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index','add','view','edit','delete']);
        $this->loadComponent('Common');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
           // 'contain' => ['Users']
        ];
        $articles = $this->paginate($this->Articles);
        $this->set(compact('articles'));
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id);
        $this->set('article', $article);
        $this->set('_serialize', ['article']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            
            /**
             * TO BE UPDATED LATER - $this->Auth->user('id');
             */
            $requestData['user_id'] = "58ffb89a-219a-4f6d-a81e-e786927a9dae"; 
            
            $article = $this->Articles->patchEntity($article, $requestData);
            if ($this->Articles->save($article)) {
                $this->set($this->Common->apiResponse("200", "The article has been saved."));
            } else {
                $this->set($this->Common->apiResponse("400",$this->Common->toastErrorMessages($article->errors())));
            }
        }
       
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();
            $post = $this->Articles->patchEntity($article, $requestData);
            if ($this->Articles->save($article)) {
                $this->set($this->Common->apiResponse("200",["msg" => "The article has been updated."]));
            } else {
                $this->set($this->Common->apiResponse("400",$this->Common->toastErrorMessages($article->errors())));
            }
        }         
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->set($this->Common->apiResponse("200","The article has been deleted."));
        } else {
            $this->set($this->Common->apiResponse("404","The article could not be deleted. Please, try again."));
        }
    }
}
