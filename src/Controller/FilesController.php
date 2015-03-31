<?php
namespace GintonicCMS\Controller;

use GintonicCMS\Controller\AppController;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class FilesController extends AppController
{   
    public $helpers = array('Number', 'Time');
    
    public function initialize() 
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
    
    public function beforeFilter(Event $event) 
    {
        parent::beforeFilter($event);
        if ($this->RequestHandler->responseType() == 'json') {
            $this->RequestHandler->setContent('json', 'application/json');
        }
    }

    function add()
    {
        $file = $this->Files->newEntity($this->request->data);
        $this->layout = 'ajax';
        if ($this->request->is(['post','put'])) {
            if(isset($this->request->data['callBack'])){
                $this->set('callbackModule',$this->request->data['callBack']);
                unset($this->request->data['callBack']);
            }
            $totalFiles = count($this->request->data['tmpFile']);
            $count = count($this->request->data['tmpFile'])>1?0:'';
            $flag = true;
            $title = $this->request->data['title'];
            $dirName = isset($this->request->data['dir'])?$this->request->data['dir']:"";
            $fileIds = $fileNames = array();
            foreach ($this->request->data['tmpFile'] as $key => $name) {
                $tmpFileArray['title'] = !empty($count)?($title . '_' . $count):$title;
                $tmpFileArray['tmpFile'] = $name;
                if (is_uploaded_file($tmpFileArray['tmpFile']['tmp_name'])) {
                    $this->request->data = $this->Files->moveUploaded(
                            $tmpFileArray, $this->Auth->user('id'),
                            $dirName,
                            $count
                    );
                    $this->request->data['dir'] = $dirName;
                    //$file = $this->Files->patchEntity($file, $this->request->data);
                    $file = $this->Files->newEntity($this->request->data);
                    unset($file->tmpFile);
                    if ($result = $this->Files->save($file)) {
                        $fileIds[] = $result->id;
                        $fileNames[] = $this->request->data['filename'];
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                $count++;
            }
            if($totalFiles == 1){
                $fileId = $fileIds[0];
                $fileName = $fileNames[0];
                $this->set(compact('fileId','fileName','totalFiles'));
            } else {
                $commaSepratedFileId = implode(', ', $fileIds);
                $commaSepratedFileName = implode(', ', $fileNames);
                $this->set(compact('commaSepratedFileId','commaSepratedFileName','totalFiles'));
            }
            $this->render('completed');
        }
        $this->set(compact('file'));
    }

    function get_row($id)
    {
        $this->layout = 'ajax';
        $fileIds = explode(', ', $id);
        foreach ($fileIds as $key => $id) {
                $files[]= $this->Files->get($id);
        }
        $this->set('files',$files);
    }

    public function delete($id)
    {
        $file = $this->Files->get($id);
        if ($this->Files->delete($file)) {
            //Delete File
            $this->Files->deleteFile($file->filename);
            $this->Flash->success(__('File has been deleted'));
        }
        $this->redirect(array('action' => 'index'));
    }

    public function index($userId = 0)
    {
        $arrConditions = array();
        if (!empty($userId)) {
            $this->set(compact('userId'));
            $arrConditions = array('user_id' => $userId);
        }
        $files = TableRegistry::get('Files');
        $arrConditions = array('Files.id NOT IN' => $this->request->session()->read('Auth.User.file.id'));
        if ($this->request->session()->read('Auth.User.role') != 'admin') {
            $arrConditions = array('user_id' => $this->request->session()->read('Auth.User.id'));
        }
        $this->paginate = array(
            'conditions' => $arrConditions,
            'order' => array('Files.created' => 'desc'),
            'limit' => 5
        );
        $this->set('files', $this->paginate('Files'));
    }
    
    public function admin_index($userId = 0)
    {
        $arrConditions = array();
        if (!empty($userId)) {
            $this->set(compact('userId'));
            $arrConditions = array('user_id' => $userId);
        }
        $files = TableRegistry::get('Files');
        $arrConditions = array('Files.id NOT IN' => $this->request->session()->read('Auth.User.file.id'));
        if ($this->request->session()->read('Auth.User.role') != 'admin') {
            $arrConditions = array('user_id' => $this->request->session()->read('Auth.User.id'));
        }
        $this->paginate = array(
            'conditions' => $arrConditions,
            'order' => array('Files.created' => 'desc'),
            'limit' => 5
        );
        $this->set('files', $this->paginate('Files'));
        $this->render('/Files/index');
    }

    public function download($filename)
    {
        $filename = WWW_ROOT . 'files' . DS . 'uploads' . DS . $filename;
        if (file_exists($filename) && !is_dir($filename)) {
            $this->autoRender = false;
            return $this->response->file($filename, array('download' => true));
            exit;
        }
        $this->Flash->warning(__('File Not Found'));
        $this->redirect($this->referer());
    }
    
    public function update()
    {
        $this->layout = false;
        $arrResponse = array('status' => 'fail');
        $files = $this->Files->newEntity($this->request->data);
        if (!empty($this->request->data)) {
            $arrResponse = [
                'status' => 'success',
                'id' => $this->request->data['id'],
                'value' => $this->request->data['title']
            ];
            $files = $this->Files->patchEntity($files, $this->request->data);
            $this->Files->save($files);
        }
        echo json_encode($arrResponse);
        exit;
    }
}
