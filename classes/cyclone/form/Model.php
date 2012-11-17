<?php

namespace cyclone\form;

/**
 * <p>A@c Model instance is the representation of a form model. It has attributes representing
 * the form and builder methods.</p>
 *
 * <p>The @c Model itself is not responsible for handling user input or business data. The model
 * is used and managed by @c \cyclone\form\Form instances.</p>
 *
 * @author Bence Eros
 * @package CyForm
 */
class Model {

    public $result_type = 'array';

    public $theme;

    public $title;

    public $name;

    public $type = 'subform';

    public $on_empty = array();

    public $on_create;

    public $on_edit;

    public $attributes = array(
        'method' => 'post',
        'action' => ''
    );

    /**
     * The model objects representing the fields of the form.
     *
     * @var array<\cyclone\form\model\field\BasicField>
     */
    public $fields = array();

    public $view = 'form';

    /**
     * @param $name string
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @param $result_type string
     * @return Model <code>$this</code>
     */
    public function result_type($result_type) {
        $this->result_type = $result_type;
        return $this;
    }

    /**
     * @param $theme string
     * @return Model
     */
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
     * @param \cyclone\form\model\field\BasicField | \cyclone\form\Model $field
     * @return Model <code>$this</code>
     */
    public function field($field) {
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

    public function is_subform() {
        return ! empty($this->name);
    }

}
