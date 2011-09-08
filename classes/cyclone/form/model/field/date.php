<?php

namespace cyclone\form\model\field;

class Date extends Basic {


    public $min_date = array('year' => '1900', 'month' => '01', 'day' => '01');

    public $max_date = 'now';

    public function  __construct($name = NULL) {
        parent::__construct('date', $name);
    }

    public function min_date($min_date) {
        $this->min_date = $min_date;
        return $this;
    }

    public function max_date($max_date) {
        $this->max_date = $max_date;
        return $this;
    }
}