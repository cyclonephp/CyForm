<?php
namespace cyclone\form\field;

/**
 * @package cyclone
 * @author Bence Erős <crystal@cyclonephp.org>
 */
interface FormField {

    public function get_data();

    public function set_data($val);

    public function set_input($src, $saved_data = array());

    public function load_data_source();

    public function validate();

    public function render();

}