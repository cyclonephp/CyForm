<?php
namespace cyclone\form;

use cyclone as cy;
use cyclone\Form;
use cyclone\form\field\CheckboxField;

class CheckboxFieldTest extends \Kohana_Unittest_TestCase {

    public function test_input_checkbox() {
        $checkbox = new CheckboxField(new Form(Form::model()), ''
            , Form::field('chb', 'checkbox')
            , cy\Config::inst()->get('cyform'));

        $checkbox->set_input('on');

        $this->assertTrue($checkbox->get_data());

        $checkbox->set_input(NULL);

        $this->assertFalse($checkbox->get_data());
    }

}