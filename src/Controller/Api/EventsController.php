<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[] paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
        $this->loadComponent('Common');
        $this->loadModel('EventInvites');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            //'contain' => ['Users']
        ];
        $events = $this->paginate($this->Events);

        $this->set(compact('events'));       
    }

    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => ['Users', 'EventInvites']
        ]);
        $this->set('event', $event);
        $this->set('_serialize', ['event']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $event = $this->Events->patchEntity($event, $this->request->getData());
            if ($this->Events->save($event)) {
                /*
                 * Save Data for event contacts and connections invites
                 */
                if (isset($requestData['connections']) and ! empty($requestData['connections'][0])) {
                    $invite_connections = explode(',', $requestData['connections'][0]);
                    foreach ($invite_connections as $key => $value) {
                        $saveArray = array(
                            'event_id' => $event->uuid,
                            'type' => 'connection',
                            'connection_id' => $value,
                        );
                        $inviteConnection = $this->EventInvites->newEntity();
                        $inviteConnection = $this->EventInvites->patchEntity($inviteConnection, $saveArray);
                        if ($this->EventInvites->save($inviteConnection)) {
                            
                            /*
                             * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                             */
                        }
                    }
                }

                if (isset($requestData['contacts']) and ! empty($requestData['contacts'][0])) {
                    $invite_connections = explode(',', $requestData['contacts'][0]);
                    foreach ($invite_connections as $key => $value) {
                        $saveArray = array(
                            'event_id' => $event->uuid,
                            'type' => 'contact',
                            'contact_email' => $value,
                        );
                        $inviteConnection = $this->EventInvites->newEntity();
                        $inviteConnection = $this->EventInvites->patchEntity($inviteConnection, $saveArray);
                        if ($this->EventInvites->save($inviteConnection)) {
                            
                            /*
                             * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                             */
                        }
                    }
                }
                
                $this->set($this->Common->apiResponse("200", "Saved successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($event->errors())));
            }
            
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->getData());
            if ($this->Events->save($event)) {
                $this->set($this->Common->apiResponse("200", "Updated successfully"));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($event->errors())));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        if ($this->Events->delete($event)) {
            $this->set($this->Common->apiResponse("200", "Deleted successfully"));
        } else {
           $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($event->errors())));
        }
    }
}
