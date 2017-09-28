<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Trainings Controller
 *
 * @property \App\Model\Table\TrainingsTable $Trainings
 *
 * @method \App\Model\Entity\Training[] paginate($object = null, array $settings = [])
 */
class TrainingsController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
        $this->loadComponent('Common');
        $this->loadModel('TrainingOptions');
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
        $trainings = $this->paginate($this->Trainings);

        $this->set(compact('trainings'));
    }

    /**
     * View method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $training = $this->Trainings->get($id, [
            'contain' => ['Users', 'TrainingOptions']
        ]);

        $this->set('training', $training);
        $this->set('_serialize', ['training']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $training = $this->Trainings->newEntity();
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $training = $this->Trainings->patchEntity($training, $this->request->getData());
            if ($this->Trainings->save($training)) {
                
                /*
                 * Save Data for training industries/institutes
                 */
                if (isset($requestData['industries']) and ! empty($requestData['industries'][0])) {
                    $industries = explode(',', $requestData['industries'][0]);
                    foreach ($industries as $key => $value) {
                        $saveArray = array(
                            'training_id' => $training->uuid,
                            'type' => 'industry',
                            'industry_id' => $value,
                        );
                        $addIndustries = $this->TrainingOptions->newEntity();
                        $addIndustries = $this->TrainingOptions->patchEntity($addIndustries, $saveArray);
                        if ($this->TrainingOptions->save($addIndustries)) {
                            
                            /*
                             * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                             */
                        }
                    }
                }
                
                if (isset($requestData['institutes']) and ! empty($requestData['institutes'][0])) {
                    $institutes = explode(',', $requestData['institutes'][0]);
                    foreach ($institutes as $key => $value) {
                        $saveArray = array(
                            'training_id' => $training->uuid,
                            'type' => 'institute',
                            'industry_id' => $value,
                        );
                        $addInstitutes = $this->TrainingOptions->newEntity();
                        $addInstitutes = $this->TrainingOptions->patchEntity($addInstitutes, $saveArray);
                        if ($this->TrainingOptions->save($addInstitutes)) {
                            
                            /*
                             * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                             */
                        }
                    }
                }
                $this->set($this->Common->apiResponse("200", "Saved successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($training->errors())));
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $training = $this->Trainings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $training = $this->Trainings->patchEntity($training, $this->request->getData());
            if ($this->Trainings->save($training)) {
                 $this->set($this->Common->apiResponse("200", "Updated successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($training->errors())));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $training = $this->Trainings->get($id);
        if ($this->Trainings->delete($training)) {
            $this->set($this->Common->apiResponse("200", "Deleted successfully"));
        } else {
           $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($training->errors())));
        }
    }
}
