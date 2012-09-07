<?php
namespace cyclone\form;

use cyclone\form\field\SubmitField;
use cyclone\Form;

class SubmitFieldTest extends \Kohana_Unittest_TestCase {

    public function test_get_view_data() {
        $form_model = Form::model()->field(Form::field(NULL, 'submit')->label('lbl')
            ->attribute('hello', 'world'));
        $form = new Form($form_model);
        $view_data = $form->get_field(0)->get_view_data();
        $this->assertEquals('lbl', $view_data['attributes']['value']);
        $this->assertEquals('world', $view_data['attributes']['hello']);
        $form_model = Form::model()->field(Form::field('x', 'submit')->label('lbl'));
        $form = new Form($form_model);
        $view_data = $form->get_field('x')->get_view_data();
        $this->assertEquals('x', $view_data['attributes']['name']);
    }

}