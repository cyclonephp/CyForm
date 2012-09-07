<?php
namespace cyclone\form;

use cyclone\Form;

class TextareaFieldTest extends \Kohana_Unittest_TestCase {

    public function test_get_view_data() {
        $form_model = Form::model()->field(
            Form::field('my', 'textarea')->attributes(array('cols' => 10, 'rows' => 20))
        );
        $form = new Form($form_model);
        $view_data = $form->get_field('my')->get_view_data();
    }
}