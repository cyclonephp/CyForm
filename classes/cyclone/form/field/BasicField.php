<?php

namespace cyclone\form\field;

use cyclone as cy;
use cyclone\Kohana;
use cyclone\Arr;
use cyclone\Form;
use cyclone\view\ViewException;
use cyclone\view\PHPView;
use cyclone\form\FormException;
use cyclone\form\model\field\BasicField as Model;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package CyForm
 */
class BasicField implements FormField {

    /**
     *
     * @var \cyclone\form\model\field\BasicField the field model defined in the form definition
     */
    public $_model;

    /**
     * @var mixed the current field value
     */
    public $value;

    /**
     * @var array validator - error message pairs. The validator is the order num.
     * of the validator for callback validators
     */
    public $validation_errors = array();

    /**
     * set at the constructor
     *
     * @var cyclone\Form
     */
    protected $_form;

    /**
     *
     * @var array cyform configuration
     */
    protected $_cfg;

    /**
     *
     * @param string $name the name of the input field
     * @param array $model the field definition
     * @param array $cfg same as <code>cyclone\Config::inst()->get('cyform')</code>
     */
    public function  __construct(Form $form, $name, Model $model, $cfg) {
        $this->_form = $form;
        $this->_model = $model;
        $this->_cfg = $cfg;
        $this->value = $model->value;
    }

    /**
     * Empty method. Can be overriden by subclasses if the input type represented
     * by the subclass has got data source to be loaded. A @c cyclone\Form object loads
     * the data sources of its fields on creation in most cases.
     *
     * @usedby cyclone\Form::init()
     */
    public function load_data_source() {
        
    }

    /**
     * Default implementation that works for most inputs. It can be overriden by
     * subclasses.
     *
     * @param mixed $val
     */
    public function set_data($val) {
        $this->value = $val;
    }

    /**
     * Default implementation that works for most inputs. It can be overriden by
     * subclasses.
     * 
     * @return mixed
     */
    public function get_data() {
        return $this->value;
    }

    /**
     *
     * @param array $src the main array the form is populated from, e.g. it can
     * be the $_POST array in a lot of cases. All the form data is visible for
     * this method, it can extract any kind of data from it.
     * @param array $saved_data the business data saved before form rendering, or
     * an empty array. The field must take it's value from this array if it can't
     * find the required input values in $src. It can happen if the input(s) were
     * disabled on the client side therefore weren't submitted.
     */
    public function set_input($src, $saved_data = array()) {
        $this->value = $src;
        if (null === $this->value) {
            $this->set_data(Arr::get($saved_data, $this->_model->name));
        }
        if ('' === $this->value) {
            $this->value = $this->_model->on_empty;
        }
    }

    public function validate() {
        $policy = $this->_cfg['validation_policy'];
        $this->_model->validation
                ->fail_on_first($policy == 'fail_on_first')
                ->data($this->value);
        return $this->_model->validation->validate();
    }

    protected function value_as_string() {
        if (is_array($this->value))
            throw new FormException("cannot convert value of field '{$this->_model->name}' to string");

        return (string) $this->value;
    }

    public function get_field_name($field_name) {
        $form_name = $this->_form->get_name();
        if ($form_name === NULL)
            return $field_name;
        return $form_name . '[' . $field_name . ']';
    }


    public function get_view_data() {
        $model = $this->_model;
        $rval = array(
            'attributes' => $this->_model->attributes,
            'errors' => $model->validation->errors,
            'label' => $model->label,
            'description' => $model->description,
            'name' => $model->name,
            'value' => $this->value
        );

        if (( ! $this->_form->edit_mode()
            && 'disable' == $model->on_create)
            || ($this->_form->edit_mode()
                && 'disable' == $model->on_edit)) {

            $rval['attributes']['disabled'] = 'disabled';
        }
        $rval['attributes']['value'] = $this->value;
        $rval['attributes']['name'] = $this->get_field_name($this->_model->name);
        $rval['attributes']['type'] = $model->type;

        if (NULL === $model->view) {
            $model->view = $model->type;
        }
        return $rval;
    }

    /**
     * Renders the field.
     *
     * @return string
     * @uses BasicField::get_view_data()
     */
    public function render() {
        $view_data = $this->get_view_data();
        try {
            $view = new PHPView($this->_form->_model->theme
                .DIRECTORY_SEPARATOR.$this->_model->view,
                $view_data);
        } catch (ViewException $ex) {
            $view = new PHPView(Form::DEFAULT_THEME . DIRECTORY_SEPARATOR
                    . $this->_model->view, $view_data);
        }
        return $view->render();
    }

    /**
     * Calls @c render() .
     *
     * @return string
     */
    public function __toString() {
        try {
            return $this->render();
        } catch (\Exception $ex) {
            Kohana::exception_handler($ex);
            return '';
        }
    }

}
