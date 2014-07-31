<?php
/**
 * AppController.php Controller
 *
 * @author Eric McBride <emcbride@csdurant.com> 
 */
App::uses('Controller', 'Controller');
class AppController extends Controller {

	public $components = array(
		'Auth' => array(
			'autoRedirect' => false,			
			'loginAction' => array(
				'controller' => 'users',
				'action' => 'login',
				'admin' => false,
				'plugin' => false,
			),	
		),
		'Session',
	);

	public function beforeFilter() {
		$this->Auth->allow('index', 'view', 'display');
	}

	public function beforeRender() {
		if ($this->Auth->user()) {
			$user_info = $this->Auth->user();
			$this->set(compact('user_info'));
		}		
	}

	public function afterFilter() {
		if ($this->Auth->user()) {
			$this->loadModel('User');
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('last_access', date('Y-m-d H:i:s'));
		}
	}
	/**
	 * Admin Index Method
	 *
	 * @params void
	 * @return void
	 */	
	public function admin_index() {
		$this->{$this->modelClass}->recursive = -1;
		$this->request->data = $this->paginate();
		$this->set(Inflector::pluralize(Inflector::tableize($this->modelClass)), $this->request->data);
	}
	
	/**
	 * Index Method
	 *
	 * @params void
	 * @return void
	 */	
	public function index(){
		$this->{$this->modelClass}->recursive = 0;
		$this->request->data = $this->paginate();
		$this->set(Inflector::pluralize(Inflector::tableize($this->modelClass)), $this->request->data);

	}
	
	/**
	 * Admin Add Method
	 *
	 * @params void
	 * @return void
	 */	
	public function admin_add() {
		$setFlash = $this->modelClass;
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->{$this->modelClass}->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The '. $setFlash .' Added Successfully.'), 'flash_good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The '. $setFlash .' could not be saved.'), 'flash_bad');
			}
		}		
	}
	
	/**
	 * Admin Delete Method
	 *
	 * @params integer $id
	 * @return void
	 */
	public function admin_delete($id = null) {
		$setFlash = $this->modelClass;
		$this->{$this->modelClass}->id = $id;
		if (!$this->{$this->modelClass}->exists()) {
			throw new NotFoundException(__('Invalid '.$setFlash.' id' ));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->{$this->modelClass}->delete()) {
			$this->Session->setFlash(__($setFlash.' has been deleted'), 'flash_good');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__($setFlash.' was not deleted'), 'flash_bad');
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * View Method
	 *
	 * @params int $slug
	 * @return void
	 */
   public function view($slug = null, $id = null) {
        $modelName = $this->modelClass;
        debug($modelName);
        if (!$slug) {
                throw new NotFoundException('Invalid '. $modelName .' Slug');
        }
        
        $property = $this->Property->find('first', array('conditions' => array('Property.slug' => $property_slug)));
        if (empty($property)) {
        	$this->Session->setFlash('That property is no longer available', 'flash_notice');
			$this->redirect(array('action' => 'index'));
        }
		
		$this->set(compact('property'));
    }       	


	
	
}
