<?php

namespace cyclone\form\field;

use cyclone as cy;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package CyForm
 */
class ListField extends BasicField {

    public function  __construct(cy\Form $form, $name, cy\form\model\field\BasicField $model, $cfg) {
        parent::__construct($form, $name, $model, 'list', $cfg);
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
                    throw new cy\form\Exception("value '$val' exists in current value but is not present in possible item list");
                
                $val_texts []= $this->_model->items[$val];
            }
            return implode(', ', $val_texts);
        }
        return parent::value_as_string();
    }

    protected function before_rendering() {
        $this->_model->errors = $this->validation_errors;

        if ($this->_model->multiple && is_null($this->value)) {
            $this->value = array();
        }
        
        $this->_model->attributes['name'] = $this->_model->name;

        if ($this->_model->multiple) {
            $this->_model->attributes['name'] .= '[]';
            $this->_model->values = $this->value;
        } else {
            $this->_model->attributes['value'] = $this->value;
        }

        if (NULL === $this->_model->view) {
            $this->_model->view = 'select';
        }
        if ($this->_model->view == 'buttons') {
            $this->_model->view = $this->_model->multiple ? 'checkboxlist' : 'radiogroup';
            unset($this->_model->attributes['value']);
        } elseif ($this->_model->view == 'select' && $this->_model->multiple) {
            $this->_model->attributes['multiple'] = 'multiple';
        } elseif ($this->_model->view == 'select') {
            if ($this->_model->multiple) {
                $this->_model->attributes['multiple'] = 'multiple';
            } else {
                $this->_model->value = $this->value;
            }
        }
    }
    
}
