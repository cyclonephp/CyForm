<?php

namespace cyclone\form\field;

use cyclone as cy;
use cyclone\form\FormException;
use cyclone\Form;
use cyclone\form\model\field\BasicField as Model;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package CyForm
 */
class ListField extends BasicField {

    public function  __construct(Form $form, $name, Model $model, $cfg) {
        parent::__construct($form, $name, $model, $cfg);
    }

    public function set_input($src, $saved_data = array()) {
        $this->value = $src;
        if (NULL === $this->value) {
            $this->set_data(cy\Arr::get($saved_data, $this->_model->name));
        }
        if (NULL === $this->value) {
            $this->value = array();
        }
        if ('' === $this->value) {
            $this->value = $this->_model->on_empty;
        }
    }

    public function  load_data_source() {
        if ( ! is_null($this->_model->data_source)) {
            $data_source = $this->_model->data_source;

            $result = call_user_func_array($data_source->callback
                    , $data_source->params);

            $val_field = $data_source->val_field;
            $text_field = $data_source->text_field;

            if (count($result) == 0)
                return;

            if (is_array(next($result))) {
                if (NULL === $val_field) {
                    foreach($result as $val => $row) {
                        $this->_model->items[$val] = $row[$text_field];
                    }
                } else {
                    foreach($result as $row) {
                        $this->_model->items[$row[$val_field]] = $row[$text_field];
                    }
                }
            } else {
                if (NULL === $val_field) {
                    foreach($result as $val => $row) {
                        $this->_model->items[$val] = $row->{$text_field};
                    }
                } else {
                    foreach($result as $row) {
                        $this->_model->items[$row->{$val_field}] = $row->{$text_field};
                    }
                }
            }
        }
    }

    protected function value_as_string() {
        if (is_array($this->value)) {
            $val_texts = array();
            foreach ($this->value as $val) {
                if ( ! array_key_exists($val, $this->_model->items))
                    throw new FormException("value '$val' exists in current value but is not present in possible item list");
                
                $val_texts []= $this->_model->items[$val];
            }
            return implode(', ', $val_texts);
        }
        return parent::value_as_string();
    }

    public function get_view_data() {
        $rval = array(
            'attributes' => array(),
            'errors' => $this->_model->validation->errors,
            'label' => $this->_model->label,
            'description' => $this->_model->description,
            'items' => $this->_model->items,
            'name' => $this->get_field_name($this->_model->name)
        );

        if ($this->_model->multiple && is_null($this->value)) {
            $this->value = array();
        }

        $field_name = $this->get_field_name($this->_model->name);
        if ($this->_model->multiple) {
            $field_name .= '[]';
            $rval['values'] = $this->value;
        } else {
            $rval['attributes']['value'] = $this->value;
        }
        $rval['attributes']['name'] = $field_name;

        if (NULL === $this->_model->view) {
            $this->_model->view = 'select';
        }
        if ($this->_model->view == 'buttons') {
            $this->_model->view = $this->_model->multiple ? 'checkboxlist' : 'radiogroup';
            unset($rval['attributes']['value']);
        } elseif ($this->_model->view == 'select') {
            if ($this->_model->multiple) {
                $rval['attributes']['multiple'] = 'multiple';
            } else {
                $rval['value'] = $this->value;
            }
        }

        return $rval;
    }
    
}
