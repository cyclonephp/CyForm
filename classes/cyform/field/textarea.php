<?php

namespace cyform\field;

/**
 * @author Bence Eros <crystal@cyclonephp.com>
 * @package CyForm
 */
class Textarea extends Basic {

    public function  __construct(\CyForm $form, $name, \cyform\model\field\Basic $model, $cfg) {
        parent::__construct($form, $name, $model, 'textarea', $cfg);
    }

    protected function before_rendering() {
        $this->model['errors'] = $this->validation_errors;
        
        $this->_model->value = $this->value;
        $this->_model->attributes['name'] = $this->_model->name;
        if (NULL === $this->_model->view) {
            $this->_model->view = 'textarea';
        }
    }
}
