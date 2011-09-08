<?php

namespace cyclone\cyform\field;

use cyclone as cy;

/**
 * @author Bence Eros <crystal@cyclonephp.com>
 * @package CyForm
 */
class Submit extends Basic {

    public function  __construct(cy\CyForm $form, $name, cy\cyform\model\field\Basic $model, $cfg) {
        parent::__construct($form, $name, $model, 'submit', $cfg);
    }

    public function set_data($val) {

    }

    public function get_data() {
        return null;
    }

    protected function  before_rendering() {
        
        if ( ! is_null($this->_model->name)) {
            $this->_model->attributes['name'] = $this->_model->name;
        }
        $this->_model->attributes['value'] = $this->_model->label;
        if (NULL === $this->_model->view) {
            $this->_model->view = 'submit';
        }
    }

}
