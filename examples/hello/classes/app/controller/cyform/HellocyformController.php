<?php
namespace app\controller\cyform;

use cyclone\request;
use cyclone\Form;

class HellocyformController extends request\BaseController {

    /**
     * The action which will be executed for the HTTP requests with the following URL-s:
     * <ul>
     *  <li>/cyform/</li>
     *  <li>/cyform/hellocyform/</li>
     *  <li>/cyform/hellocyform/index/</li>
     * </ul>
     *
     * This method loads the form definition found in app/forms/hello.php
     * and passes the form object to the template found at
     * app/views/app/controller/cyform/hellocyform/index.php
     */
    public function action_index() {
        // creating the form instance based on the form model found in app/forms/hello.php
        $form = new Form('hello');
        if ($this->is_post() // if the request is a POST request
                && $form->set_input($this->_request->post)) { // and the POSTDATA can be loaded as valid form input

            $data = $form->get_data(); // then we fetch the resulting data from the form
            var_dump($data); // and we simply show it with var_dump()
        }
        // passing the form object to the view
        // which will be simply echo-ed by the app/views/controller/cyform/hellocyform/index.php
        $this->_content->form = $form;
    }

    /**
     * The action which will be executed for the HTTP requests with the following URL-s:
     * <ul>
     *  <li>/cyform/hellocyform/dynamic</li>
     * </ul>
     *
     * <p>This action method demonstrates how to build form models programmatically instead
     * of loading a more or less static model from a file under the forms/ directory.</p>
     *
     * <p>In this example a form model is is created which contains 2 or 3 form inputs. The
     * second input is hidden based on if the "hide" query string parameter is 0 or 1.</p>
     *
     * <p>Run this example using the following URL-s:
     * <ul>
     *  <li>http://localhost/cyclonephp/cyform/hellocyform/dynamic/?hide=0</li>
     *  <li>http://localhost/cyclonephp/cyform/hellocyform/dynamic/?hide=1</li>
     * </ul>
     * </p>
     */
    public function action_dynamic() {
        // determining if the 2nd input should be rendered
        $hide = isset($this->_request->query['hide'])
            ? $this->_request->query['hide']
            : 0;

        // creating the empty form model
        $model = Form::model();

        // adding the name field to the form model
        $model->field(Form::field('name', 'text')
            ->label('Username: '));

        if ( ! $hide) { // adding the password field if needed
            $model->field(Form::field('password', 'password')
                ->label('Password: '));
        }

        // adding the submit button to the form model
        $model->field(Form::field(NULL, 'submit')->label('Submit!'));

        // creating the form based on the form model
        $form = new Form($model);
        $this->_content->form = $form;
    }

}