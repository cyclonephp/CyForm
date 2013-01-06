<?php

namespace cyclone\form\model;

/**
 * @author Bence Eros
 * @package CyForm
 */
class DataSource {

    public $callback;

    public $val_field;

    public $text_field;

    public $params = array();

    public function  __construct($callback = NULL) {
        $this->callback = $callback;
    }

    /**
     * @param string $val_field
     * @return DataSource
     */
    public function val($val_field) {
        $this->val_field = $val_field;
        return $this;
    }

    /**
     * @param string $text_field
     * @return DataSource
     */
    public function text($text_field) {
        $this->text_field = $text_field;
        return $this;
    }

    /**
     * @param mixed $params
     * @param mixed ...
     * @return DataSource
     */
    public function params($params) {
        $this->params = func_get_args();
        return $this;
    }

}