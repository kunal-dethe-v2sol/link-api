<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * PremiumClub Controller
 *
 * @property \App\Model\Table\PremiumClubTable $PremiumClub
 *
 * @method \App\Model\Entity\PremiumClub[] paginate($object = null, array $settings = [])
 */
class PremiumClubController extends AppController
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
        $premiumClub = $this->paginate($this->PremiumClub);

        $this->set(compact('premiumClub'));
        $this->set('_serialize', ['premiumClub']);
    }

    /**
     * View method
     *
     * @param string|null $id Premium Club id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $premiumClub = $this->PremiumClub->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('premiumClub', $premiumClub);
        $this->set('_serialize', ['premiumClub']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $premiumClub = $this->PremiumClub->newEntity();
        if ($this->request->is('post')) {
            $premiumClub = $this->PremiumClub->patchEntity($premiumClub, $this->request->getData());
            if ($this->PremiumClub->save($premiumClub)) {
                $this->set($this->Common->apiResponse("200", "Saved successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($premiumClub->errors())));
            }
        }       
    }

    /**
     * Edit method
     *
     * @param string|null $id Premium Club id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $premiumClub = $this->PremiumClub->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $premiumClub = $this->PremiumClub->patchEntity($premiumClub, $this->request->getData());
            if ($this->PremiumClub->save($premiumClub)) {
               $this->set($this->Common->apiResponse("200", "Updated successfully"));
            } else {
               $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($premiumClub->errors())));
            }
        }        
    }

    /**
     * Delete method
     *
     * @param string|null $id Premium Club id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $premiumClub = $this->PremiumClub->get($id);
        if ($this->PremiumClub->delete($premiumClub)) {
            $this->set($this->Common->apiResponse("200", "Deleted successfully"));
        } else {
           $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($premiumClub->errors())));
        }
    }
}
