<?php

namespace cyclone\form\field;

use cyclone as cy;
use cyclone\Form;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package CyForm
 */
class SubmitField extends BasicField {

    public function  __construct(Form $form, $name, cy\form\model\field\BasicField $model, $cfg) {
        parent::__construct($form, $name, $model, 'submit', $cfg);
    }

    public function set_data($val) {

    }

    public function get_data() {
        return null;
    }

    public function get_view_data() {
        $rval = array(
            'attributes' => $this->_model->attributes
        );
        if ( ! is_null($this->_model->name)) {
            $rval['attributes']['name'] = $this->get_field_name($this->_model->name);
        }
        $rval['attributes']['value'] = $this->_model->label;
        if (NULL === $this->_model->view) {
            $this->_model->view = 'submit';
        }
        return $rval;
    }

}
