<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * GhostWriting Controller
 *
 * @property \App\Model\Table\GhostWritingTable $GhostWriting
 *
 * @method \App\Model\Entity\GhostWriting[] paginate($object = null, array $settings = [])
 */
class GhostWritingController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
        $this->loadComponent('Common');
        $this->loadModel('GhostWritingRequestType');
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
        $ghostWriting = $this->paginate($this->GhostWriting);

        $this->set(compact('ghostWriting'));
        $this->set('_serialize', ['ghostWriting']);
    }

    /**
     * View method
     *
     * @param string|null $id Ghost Writing id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ghostWriting = $this->GhostWriting->get($id, [
            'contain' => ['Users', 'GhostWritingRequestType']
        ]);

        $this->set('ghostWriting', $ghostWriting);
        $this->set('_serialize', ['ghostWriting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ghostWriting = $this->GhostWriting->newEntity();
        if ($this->request->is('post')) {
            $ghostWriting = $this->GhostWriting->patchEntity($ghostWriting, $this->request->getData());
            if ($this->GhostWriting->save($ghostWriting)) {
               $this->set($this->Common->apiResponse("200", "Saved successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($ghostWriting->errors())));
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Ghost Writing id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ghostWriting = $this->GhostWriting->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ghostWriting = $this->GhostWriting->patchEntity($ghostWriting, $this->request->getData());
            if ($this->GhostWriting->save($ghostWriting)) {
                 $this->set($this->Common->apiResponse("200", "Updated successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($ghostWriting->errors())));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Ghost Writing id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ghostWriting = $this->GhostWriting->get($id);
        if ($this->GhostWriting->delete($ghostWriting)) {
          $this->set($this->Common->apiResponse("200", "Deleted successfully"));
        } else {
           $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($ghostWriting->errors())));
        }
    }
}
