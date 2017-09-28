<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Ngos Controller
 *
 * @property \App\Model\Table\NgosTable $Ngos
 *
 * @method \App\Model\Entity\Ngo[] paginate($object = null, array $settings = [])
 */
class NgosController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
        $this->loadComponent('Common');
        $this->loadModel('NgoAreaOfInterests');
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
        $ngos = $this->paginate($this->Ngos);

        $this->set(compact('ngos'));
        $this->set('_serialize', ['ngos']);
    }

    /**
     * View method
     *
     * @param string|null $id Ngo id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ngo = $this->Ngos->get($id, [
            'contain' => ['Users', 'NgoAreaOfInterests']
        ]);

        $this->set('ngo', $ngo);
        $this->set('_serialize', ['ngo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ngo = $this->Ngos->newEntity();
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $ngo = $this->Ngos->patchEntity($ngo, $this->request->getData());
            if ($this->Ngos->save($ngo)) {
                /*
                 * Save Data for area of interests
                 */
                if (isset($requestData['area_of_interests']) and ! empty($requestData['area_of_interests'][0])) {
                    $area_of_interests = explode(',', $requestData['area_of_interests'][0]);
                    foreach ($area_of_interests as $key => $value) {
                        $saveArray = array(
                            'ngo_id' => $ngo->uuid,
                            'area_of_interest_id' => $value,
                        );
                        $area_of_interests = $this->NgoAreaOfInterests->newEntity();
                        $area_of_interests = $this->NgoAreaOfInterests->patchEntity($area_of_interests, $saveArray);
                        if ($this->NgoAreaOfInterests->save($area_of_interests)) {
                            /*
                             * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                             */
                        }
                    }
                }
                $this->set($this->Common->apiResponse("200", "Saved successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($ngo->errors())));
            }
        }
        
    }

    /**
     * Edit method
     *
     * @param string|null $id Ngo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ngo = $this->Ngos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ngo = $this->Ngos->patchEntity($ngo, $this->request->getData());
            if ($this->Ngos->save($ngo)) {
                $this->set($this->Common->apiResponse("200", "Updated successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($ngo->errors())));
            }
        }        
    }

    /**
     * Delete method
     *
     * @param string|null $id Ngo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ngo = $this->Ngos->get($id);
        if ($this->Ngos->delete($ngo)) {
             $this->set($this->Common->apiResponse("200", "Deleted successfully"));
        } else {
           $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($ngo->errors())));
        }
    }
}
