<?php

namespace cyclone\form\field;

use cyclone as cy;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package CyForm
 */
class TextareaField extends BasicField {

    public function  __construct(cy\Form $form, $name, cy\form\model\field\BasicField $model, $cfg) {
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
