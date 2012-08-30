<?php
namespace cyclone\form;

use cyclone\Form;
use cyclone\form\field\BasicField;

class BasicFieldTest extends \Kohana_Unittest_TestCase {

    public function test_get_view_data() {
        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                ->on_create('disable'))
        );
        $view_data = $form->get_field('name')->get_view_data();
        $attrs = $view_data['attributes'];
        $this->assertEquals('disabled', $attrs['disabled']);
        $this->assertSame(NULL, $attrs['value']);
        $this->assertEquals('name', $attrs['name']);
        $this->assertEquals('text', $attrs['type']);
    }

}