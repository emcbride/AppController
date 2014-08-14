## Dynamic App Controller
The AppController Dynamically creates the methods of the basic CRUD operations in the CakePHP framework. All the programmer has to do is create the Controllers appropriately.  If the programmer must add information (i.e. a variable that generates a dropdown menu) all you have to do is use method overloading.  

#EXAMPLE

	class HomesController extends AppController{

 		public function admin_index(){
    			parent::admin_index();
    			$variable = $this->Home->find('list');
    			$this->set(compact('variable'));
		}
	}

###All of the methods from the App Controller Would Automatically be called due to Inheritance.  Polymorphism also takes precedence.

Very Simple way to save you tons of work if you are using a lot of controllers using the basic Create, Read, Update, Delete functions.  Dynamically finds the model name and such.

