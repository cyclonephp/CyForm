<?php

namespace cyclone\form;

use cyclone as cy;
use cyclone\Form;


class CyFormTest extends \Kohana_Unittest_TestCase {

    /**
     * @expectedException \cyclone\form\Exception
     */
    public function test_constructor() {
        $form1 = new Form('examples/basic');
        $form2 = new Form(Form::model());
        $form3 = new Form('does not exist');
    }

    public function test_basic_input() {
        $form = new Form(Form::model()
                ->field(Form::field('basic', 'text'))
        );
        $this->assertEquals(1, $form->get_field_count());
        $this->assertTrue($form->get_field('basic') instanceof cy\form\field\BasicField);
    }

    public function provider_explicit_input() {
        return array(
            array('text', '\\cyclone\\form\\field\\BasicField'),
            array('hidden', '\\cyclone\\form\\field\\BasicField'),
            array('checkbox', '\\cyclone\\form\\field\\CheckboxField'),
            array('password', '\\cyclone\\form\\field\\BasicField'),
            array('list', '\\cyclone\\form\\field\\ListField'),
            array('submit', '\\cyclone\\form\\field\\BasicField'),
            array('textarea', '\\cyclone\\form\\field\\BasicField'),
            array('date', '\\cyclone\\form\\field\\DateField')
        );
    }

    /**
     *
     * @dataProvider provider_explicit_input
     */
    public function test_explicit_input($field_type, $input_class) {
        $form = new Form(Form::model()
                ->field(Form::field('name', $field_type)));
        $this->assertInstanceOf($input_class, $form->get_field('name'));
    }

    public function test_load_input() {
        $form = new Form(Form::model()
                ->field(Form::field('name1', 'text'))
                ->field(Form::field('name2', 'text'))
        );

        $form->set_input(array(
            'name1' => 'val1',
            'name2' => 'val2',
            'name3' => 'val3'
        ), false);
        $this->assertEquals($form->get_field_count(), 2);
        $this->assertEquals($form->get_field('name1')->get_data(), 'val1');
    }

    public function test_validation() {
        $form = new Form('examples/basic');
        $form->set_input(array('name' => 'hello'));
        $this->assertEquals(array(
                    0 => 'hello: invalid number format',
                    1 => 'username hello is not unique',
                    2 => 'username hello is not unique'
                ), $form->get_field('name')->_model->validation->errors);
    }

    public function test_result() {
        $form = new Form(Form::model()
                ->field(Form::field('name1', 'text'))
                ->field(Form::field('name2', 'checkbox'))
                ->field(Form::field('name3', 'list')
                        ->items(array(
                            'val1' => 'text1',
                            'val2' => 'text2'
                        )))
                ->field(Form::field(NULL, 'submit'))
        );

        $form->set_input(array(
            'name1' => 'val1',
            'name2' => true,
            'name3' => 'val2'
        ));
        $this->assertEquals((array)$form->get_data(), array(
            'name1' => 'val1',
            'name2' => true,
            'name3' => 'val2'
        ));
    }

    public function test_on_empty() {
        $form = new Form(Form::model()
                ->field(Form::field('name1', 'text')->on_empty(NULL))
        );
        $form->set_input(array('name1' => ''));
        $data = $form->get_data();
        $this->assertNull($data['name1']);
    }

    public function test_on_create() {
        $form = new Form(Form::model()
            ->field(Form::field('name', 'text')
                ->on_create('hide'))
        );

        $form->render();
        try {
            $form->get_field('name');
            $this->fail();
        } catch (Exception $ex) {

        }

        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                        ->on_create('disable'))
        );
        $view_data = $form->get_field('name')->get_view_data();
        $this->assertEquals('disabled', $view_data['attributes']['disabled']);
    }

    public function test_on_edit() {
        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                        ->on_edit('hide'))
        );

        $form->set_data(array('name' => 'username'));
        $form->render();
        try {
            $form->get_field('name');
            $this->fail();
        } catch (Exception $ex) {

        }
        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                        ->on_edit('disable'))
        );
        
        $form->set_data(array('name' => 'username'));
        $view_data = $form->get_field('name')->get_view_data();
        $this->assertEquals('disabled', $view_data['attributes']['disabled']);
    }

    public function test_subform() {

    }


    /**
     *
     * @dataProvider provider_edit
     */
    public function test_edit(array $fields, array $before_data
            , $progress_id_required, $input, array $after_data) {
        $cfg = \cyclone\Config::inst()->get('cyform');
        unset($_SESSION[$cfg['progress_key']]);
        $form_model = Form::model();
        $form_model->fields = $fields;
        $form_before_submit = new Form($form_model);
        $form_before_submit->set_data($before_data);

        if ($progress_id_required) {
            try {
                $form_before_submit->get_field($cfg['progress_key']);
            } catch (Excepion $ex) {
               $this->fail();
            }
        } else {
            foreach ($before_data as $k => $v) {
                try {
                    $field = $form_before_submit->get_field($k);
                    $this->assertEquals($v, $field->get_data());
                } catch (Exception $ex) {
                    $this->fail();
                }
            }
        }

        $form_model = Form::model();
        $form_model->fields = $fields;
        $form_after_submit = new Form($form_model);
        if ($progress_id_required) {
            $input[$cfg['progress_key']] = $form_before_submit->get_field($cfg['progress_key'])->get_data();
        }
        $form_after_submit->set_input($input);
        $result = $form_after_submit->get_data();
        $this->assertEquals($result, $after_data);
    }

    public static function provider_edit() {
        $rval = array();
        $fields = array(
            Form::field('name1', 'text'),
            Form::field('name2', 'text')
        );
        $before_data = array('name1' => 'val1', 'name2' => 'val2');
        $progress_id_required = false;
        $input = array('name1' => 'val1_', 'name2' => 'val2_');
        $after_data = array('name1' => 'val1_', 'name2' => 'val2_');
        $rval []= array($fields, $before_data, $progress_id_required, $input, $after_data);

        $fields = array(
            Form::field('name1', 'text'),
            Form::field('name2', 'text')
        );
        $before_data = array('name1' => 'val1', 'name2' => 'val2', 'name3' => 'val3');
        $progress_id_required = true;
        $input = array('name1' => 'val1_', 'name2' => 'val2_');
        $after_data = array('name1' => 'val1_', 'name2' => 'val2_', 'name3' => 'val3');
        $rval []= array($fields, $before_data, $progress_id_required, $input, $after_data);

        $fields = array(
            Form::field('name1', 'text'),
            Form::field('name2', 'text')
        );
        $before_data = array('name1' => 'val1', 'name2' => 'val2', 'name3' => 'val3');
        $progress_id_required = true;
        $input = array('name1' => 'val1_');
        $after_data = array('name1' => 'val1_', 'name2' => 'val2', 'name3' => 'val3');
        $rval []= array($fields, $before_data, $progress_id_required, $input, $after_data);

        return $rval;
    }

    public static function custom_callback($username) {
        return false;
    }

}
