<?php

namespace cyclone\form;

/**
 * @author Bence Eros
 * @package CyForm
 */
class Model {

    public $result_type = 'array';

    public $theme;

    public $title;

    public $attributes = array(
        'method' => 'post',
        'action' => ''
    );

    public $fields = array();

    public $view = 'form';

    /**
     * @param $result_type string
     * @return Model
     */
    public function result_type($result_type) {
        $this->result_type = $result_type;
        return $this;
    }

    public function theme($theme) {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @param $title string
     * @return Model <code>$this</code>
     */
    public function title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param $attributes array
     * @return Model <code>$this</code>
     */
    public function attributes($attributes) {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param $method string
     * @return Model <code>$this</code>
     */
    public function method($method) {
        $this->attributes['method'] = $method;
        return $this;
    }

    /**
     * @param $action string
     * @return Model <code>$this</code>
     */
    public function action($action) {
        $this->attributes['action'] = $action;
        return $this;
    }

    /**
     * @param $key string
     * @param $value string
     * @return Model <code>$this</code>
     */
    public function attribute($key, $value) {
        $this->attributes[$key] = $value;
        return $this;
    }
    
    /**
     * @param $key string
     * @param $value string
     * @return Model <code>$this</code>
     */
    public function attr($key, $value) {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param \cyclone\form\model\field\BasicField $field
     * @return Model <code>$this</code>
     */
    public function field(model\field\BasicField $field) {
        if (is_null($field->name)) {
            $this->fields []= $field;
        } else {
            $this->fields[$field->name] = $field;
        }
        return $this;
    }

    /**
     * @param $view string
     * @return Model <code>$this</code>
     */
    public function view($view) {
        $this->view = $view;
        return $this;
    }

}
