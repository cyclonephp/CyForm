<?php
namespace cyclone\form\field;

use cyclone\Form;
use cyclone\Kohana;

class SubformField implements FormField {

    /**
     * @var \cyclone\form\FormModel
     */
    protected $_model;

    /**
     * @var \cyclone\Form
     */
    protected $_form;

    /**
     * @var array
     */
    protected $_cfg;

    /**
     * @var \cyclone\Form
     */
    protected $_subform;

    public function __construct(Form $form, $name, $model, $cfg) {
        $this->_form = $form;
        $this->_model = $model;
        if ($this->_model->theme === NULL) {
            $this->_model->theme = $form->get_theme();
        }
        $this->_cfg = $cfg;
        $this->_subform = new Form($model, TRUE);
    }

    public function get_data() {
        return $this->_subform->get_data();
    }

    public function set_data($val) {
        $this->_subform->set_data($val, FALSE);
    }

    public function set_input($src, $saved_data = array()) {
        // TODO improve
        $this->_subform->set_input($src, TRUE);
    }

    public function load_data_source() {
        $this->_subform->load_data_source();
    }

    public function validate() {
        return $this->_subform->validate();
    }

    public function render() {
        return $this->_subform->render();
    }

    public function get_field_name() {
        return $this->_form->get_name() . '[' . $this->_model->name . ']';
    }

    public function get_saved_data() {
        return $this->_form->get_saved_data();
    }

    public function __toString() {
        try {
            return $this->render();
        } catch (\Exception $ex) {
            Kohana::exception_handler($ex);
            return '';
        }
    }

}