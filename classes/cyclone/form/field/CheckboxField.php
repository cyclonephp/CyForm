<?php

namespace cyclone\form\field;

use cyclone\Form;
use cyclone\form\FormException;
use cyclone\form\model\field\BasicField as Model;
use cyclone as cy;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package CyForm
 */
class CheckboxField extends BasicField {

    public function  __construct(Form $form, $name, Model $model, $cfg) {
        if ($model->type != 'checkbox')
            throw new FormException('parameter $model->type must be checkbox');
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

    public function set_input($src, $saved_data = array()) {
        $this->value = $src !== NULL;
    }
}
