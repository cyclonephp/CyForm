<?php

namespace cyclone\form\model\field;

/**
 * @author Bence Eros
 * @package CyForm
 */
class BasicField {

    public $name;

    public $type;

    public $label;

    public $description;
    
    public $attributes = array();

    public $view;

    public $validators = array();

    public $on_empty = '';

    public $on_create;

    public $on_edit;

    public $errors = array();

    /**
     * @param string $type
     * @param string $name
     */
    public function  __construct($type, $name = NULL) {
        $this->type = $type;
        $this->name = $name;
    }

    /**
     *
     * @param string $type
     * @return BasicField
     */
    public function type($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $label
     * @return BasicField
     */
    public function label($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $description
     * @return BasicField
     */
    public function description($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $description
     * @return BasicField
     */
    public function descr($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $view
     * @return BasicField
     */
    public function view($view) {
        $this->view = $view;
        return $this;
    }

    /**
     *
     * @param mixed $validator
     * @param mixed $params
     * @param string $error_msg
     * @return BasicField
     */
    public function validator($validator, $params = TRUE, $error_msg = NULL) {
        if (is_string($validator)) {
            $this->validators[$validator] = array(
                'params' => $params,
                'error' => $error_msg
            );
        } else {
            $this->validators []= array(
                'callback' => $validator,
                'params' => $params,
                'error' => $error_msg
            );
        }
        return $this;
    }

    /**
     * @param mixed $on_empty
     * @return BasicField
     */
    public function on_empty($on_empty) {
        $this->on_empty = $on_empty;
        return $this;
    }

    /**
     * @param string $on_create
     * @return BasicField
     */
    public function on_create($on_create) {
        $this->on_create = $on_create;
        return $this;
    }

    /**
     *
     * @param mixed $on_edit
     * @return BasicField
     */
    public function on_edit($on_edit) {
        $this->on_edit = $on_edit;
        return $this;
    }
    
    public function attributes($attributes) {
        $this->attributes = $attributes;
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $value
     * @return BasicField
     */
    public function attribute($key, $value) {
        $this->attributes[$key] = $value;
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $value
     * @return BasicField
     */
    public function attr($key, $value) {
        $this->attributes[$key] = $value;
        return $this;
    }
}
