<?php

use cyclone as cy;
use cyclone\Form;


class CyForm_Test extends Kohana_Unittest_TestCase {

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
        $this->assertEquals(1, count($form->_fields));
        $this->assertTrue($form->_fields['basic'] instanceof cy\form\field\BasicField);
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
        $this->assertInstanceOf($input_class, $form->_fields['name']);
    }

    public function test_input_checkbox() {
        $checkbox = new cy\form\field\CheckboxField(new Form(Form::model()), ''
                , Form::field('chb', 'checkbox')
                , cy\Config::inst()->get('cyform'));

        $arr = array(
            'chb' => 'on'
        );
        $checkbox->pick_input($arr);

        $this->assertTrue($checkbox->get_data());

        $arr = array();
        $checkbox->pick_input($arr);

        $this->assertFalse($checkbox->get_data());
    }

    public function test_data_sourceLoading() {
        $form = new Form(Form::model()
                ->field(Form::field('name', 'list')
                ->source(Form::source(array($this, 'mockDataSource'))
                    ->val('id')
                    ->text('text'))));
        
        foreach ( $this->mockDataSource() as $row) {
            $this->assertEquals($form->_fields['name']->_model->items[$row['id']], $row['text']);
        }
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
        $this->assertEquals(count($form->_fields), 2);
        $this->assertEquals($form->_fields['name1']->get_data(), 'val1');
    }

    public function test_validation() {
        $form = new Form('examples/basic');
        $form->set_input(array('name' => 'hello'));
        $this->assertEquals(array(
                    0 => 'hello: invalid number format',
                    1 => 'username hello is not unique',
                    2 => 'username hello is not unique'
                ), $form->_fields['name']->_model->validation->errors);
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
                ->result('stdClass')
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
        $this->assertInstanceOf('stdClass', $form->get_data('stdClass'));
    }

    public function test_on_empty() {
        $form = new Form(Form::model()
                ->field(Form::field('name1', 'text')->on_empty(NULL))
        );
        $form->set_input(array('name1' => ''));
        $data = $form->get_data();
        $this->assertNull($data['name1']);
    }

    /**
     *
     * @dataProvider provider_field_date
     */
    public function test_field_date($date_string, $input, $date_format) {
        $form = new Form(Form::model()
                ->field(Form::field('mydate', 'date')->format($date_format))
                );

        $form->set_input(array(
           'mydate_year' => $input['year'],
           'mydate_month' => $input['month'],
           'mydate_day' => $input['day']
        ));
        $data = $form->get_data();
        $this->assertEquals($data['mydate'], $date_string);

        $form = new Form(Form::model()
                ->field(Form::field('mydate', 'date')->format($date_format))
                );
        $form->set_data(array('mydate' => $date_string));
        $data = $form->get_data();
        $this->assertEquals($date_string, $data['mydate']);
    }

    public function test_on_create() {
        $form = new Form(Form::model()
            ->field(Form::field('name', 'text')
                ->on_create('hide'))
        );

        $form->render();
        $this->assertFalse(array_key_exists('name', $form->_fields));

        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                        ->on_create('disable'))
        );
        $form->render();
        $this->assertEquals('disabled', $form->_fields['name']->_model->attributes['disabled']);
    }

    public function test_on_edit() {
        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                        ->on_edit('hide'))
        );

        $form->set_data(array('name' => 'username'));
        $form->render();
        $this->assertFalse(array_key_exists('name', $form->_fields));
        $form = new Form(Form::model()
                ->field(Form::field('name', 'text')
                        ->on_edit('disable'))
        );
        
        $form->set_data(array('name' => 'username'));
        $form->render();
        $this->assertEquals('disabled', $form->_fields['name']->_model->attributes['disabled']);
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
            $this->assertArrayHasKey($cfg['progress_key'], $form_before_submit->_fields);
        } else {
            $form_fields = $form_before_submit->_fields;
            foreach ($before_data as $k => $v) {
                $this->assertArrayHasKey($k, $form_fields);
                $this->assertEquals($form_fields[$k]->get_data(), $v);
            }
        }

        $form_model = Form::model();
        $form_model->fields = $fields;
        $form_after_submit = new Form($form_model);
        if ($progress_id_required) {
            $input[$cfg['progress_key']] = $form_before_submit->_fields[$cfg['progress_key']]->get_data();
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

    public function provider_field_date() {
        return array(
            array('2010-09-17', array('year' => '2010', 'month' => '09', 'day' => '17'), 'year-month-day'),
            array('09/17/2010', array('year' => '2010', 'month' => '09', 'day' => '17'), 'month/day/year'),
            array('2010.09.17', array('year' => '2010', 'month' => '09', 'day' => '17'), 'year.month.day')
        );
    }


    public function mockDataSource() {
        return array(
            array('id' => 1, 'text' => 'txt1'),
            array('id' => 2, 'text' => 'txt2')
        );
    }

    public static function custom_callback($username) {
        return false;
    }

}
