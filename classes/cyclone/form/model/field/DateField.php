<?php

namespace cyclone\form\model\field;

use cyclone\form\FormException;

/**
 * @author Bence Eros <crystal@cyclonephp.org>
 * @package cyform
 */
class DateField extends BasicField {

    public static $default_format = 'year-month-day';

    public $min_date = array('year' => '1900', 'month' => '01', 'day' => '01');

    public $max_date = 'now';

    public $format;

    public $value = array(
        'year' => NULL,
        'month' => NULL,
        'day' => NULL
    );

    public function  __construct($name = NULL) {
        parent::__construct('date', $name);
        $this->format = self::$default_format;
    }

    /**
     * @param array $min_date
     * @return DateField
     */
    public function min_date($min_date) {
        $this->min_date = $min_date;
        return $this;
    }

    /**
     * @param array $max_date
     * @return DateField
     */
    public function max_date($max_date) {
        $this->max_date = $max_date;
        return $this;
    }

    /**
     * @param string $format
     * @return DateField
     */
    public function format($format) {
        $this->format = $format;
        return $this;
    }

    public function value($value) {
        if ( ! is_array($value)
            || ! (isset($value['year'])
                && isset($value['month'])
                && isset($value['day'])))
            throw new FormException("date input default value must be an array with 'year', 'month' and 'day' keys");

        return parent::value($value);
    }


}