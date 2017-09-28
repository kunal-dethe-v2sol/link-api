<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * EnterpreneurClub Controller
 *
 * @property \App\Model\Table\EnterpreneurClubTable $EnterpreneurClub
 *
 * @method \App\Model\Entity\EnterpreneurClub[] paginate($object = null, array $settings = [])
 */
class EnterpreneurClubController extends AppController
{
    
    public function initialize(){
        parent::initialize();
        $this->Auth->allow();
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
            'contain' => ['Users']
        ];
        $enterpreneurClub = $this->paginate($this->EnterpreneurClub);

        $this->set(compact('enterpreneurClub'));
        $this->set('_serialize', ['enterpreneurClub']);
    }

    /**
     * View method
     *
     * @param string|null $id Enterpreneur Club id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $enterpreneurClub = $this->EnterpreneurClub->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('enterpreneurClub', $enterpreneurClub);
        $this->set('_serialize', ['enterpreneurClub']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $enterpreneurClub = $this->EnterpreneurClub->newEntity();
        if ($this->request->is('post')) {
            $enterpreneurClub = $this->EnterpreneurClub->patchEntity($enterpreneurClub, $this->request->getData());
            if ($this->EnterpreneurClub->save($enterpreneurClub)) {
                $this->set($this->Common->apiResponse("200", "Saved successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($enterpreneurClub->errors())));
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Enterpreneur Club id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $enterpreneurClub = $this->EnterpreneurClub->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $enterpreneurClub = $this->EnterpreneurClub->patchEntity($enterpreneurClub, $this->request->getData());
            if ($this->EnterpreneurClub->save($enterpreneurClub)) {
                $this->set($this->Common->apiResponse("200", "Updated successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($enterpreneurClub->errors())));
            }
        }
        
    }

    /**
     * Delete method
     *
     * @param string|null $id Enterpreneur Club id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $enterpreneurClub = $this->EnterpreneurClub->get($id);
        if ($this->EnterpreneurClub->delete($enterpreneurClub)) {
            $this->set($this->Common->apiResponse("200", "Deleted successfully"));
        } else {
           $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($enterpreneurClub->errors())));
        }
    }
}
