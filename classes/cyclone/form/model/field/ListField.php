<?php

namespace cyclone\form\model\field;

use cyclone as cy;

/**
 * @author Bence Eros
 * @package CyForm
 */
class ListField extends BasicField {

    /**
     *
     * @var \cyclone\form\model\DataSource
     */
    public $data_source;

    /**
     * The items of the list as key-value pairs. If the @c $data_source is defined too, then
     * the items returned by @c $data_source will be appended to this array.
     *
     * @var array
     */
    public $items = array();

    /**
     * Determines if the user can select multiple entries of this list, or only one.
     *
     * @var boolean
     */
    public $multiple;

    public function __construct($name = NULL) {
        parent::__construct('list', $name);
    }

    /**
     * @param \cyclone\form\model\DataSource $data_source
     * @return ListField
     */
    public function source(cy\form\model\DataSource $data_source) {
        $this->data_source = $data_source;
        return $this;
    }

    /**
     * Overrides they @c BasicField::type() method to always throw an exception.
     * The <code>$type</code> attribute of a @c ListField is immutable.
     *
     * @param $type string
     * @throws \cyclone\form\Exception
     */
    public function type($type) {
        throw new cy\form\Exception('the type attribute of lists is immutable');
    }

    /**
     * @param array $items
     * @return ListField
     */
    public function items($items) {
        $this->items = $items;
        return $this;
    }

    /**
     * @param scalar $val
     * @param string $text
     * @return ListField
     */
    public function item($val, $text) {
        $this->items[$val] = $text;
        return $this;
    }

    /**
     * @param boolean $multiple
     * @return ListField
     */
    public function multiple($multiple = TRUE) {
        $this->multiple = $multiple;
        return $this;
    }

}