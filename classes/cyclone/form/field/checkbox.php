<?php

namespace cyclone\form\field;

use cyclone\form;
use cyclone\form\model\field;
use cyclone as cy;

/**
 * @author Bence Eros <crystal@cyclonephp.com>
 * @package CyForm
 */
class Checkbox extends Basic {

    public function  __construct(cy\Form $form, $name, field\Basic $model, $cfg) {
        if ($model->type != 'checkbox')
            throw new form\Exception('parameter $model->type must be checkbox');
        parent::__construct($form, $name, $model, $cfg);
    }

    /**
     * converts 'on' value to true, eveything else to false
     *
     * @param string $val
     */
    public function set_data($val) {
        $this->value = (boolean) $val;
    }

    public function  push_input(&$src) {
        if ($this->value) {
            $src[$this->_model->name] = 'on';
        }
    }

    public function pick_input(&$src, &$saved_data = array()) {
        $this->value = array_key_exists($this->_model->name, $src);
    }
}
