<?php


class CyForm_Test extends Kohana_Unittest_TestCase {

    /**
     * @expectedException \cyform\Exception
     */
    public function testConstructor() { 
        $form1 = new CyForm('examples/basic');
        $form2 = new CyForm(CyForm::model());
        $form3 = new CyForm('does not exist');
    }

    public function testBasicInput() {
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('basic', 'text'))
        );
        $this->assertEquals(1, count($form->_fields));
        $this->assertTrue($form->_fields['basic'] instanceof \cyform\field\Basic);
    }

    /**
     *
     * @dataProvider providerExplicitInput
     */
    public function testExplicitInput($field_type, $input_class) {
        $form = new CyForm(CyForm::model()->field(CyForm::field('name', $field_type)));
        $this->assertInstanceOf($input_class, $form->_fields['name']);
    }

    public function testInputCheckbox() {
        $checkbox = new \cyform\field\Checkbox(new CyForm(CyForm::model()), ''
                , CyForm::field('chb', 'checkbox')
                , Config::inst()->get('cyform'));

        $arr = array(
            'chb' => 'on'
        );
        $checkbox->pick_input($arr);

        $this->assertTrue($checkbox->get_data());

        $arr = array();
        $checkbox->pick_input($arr);

        $this->assertFalse($checkbox->get_data());
    }

    public function testDataSourceLoading() {
        $form = new CyForm(CyForm::model()->field(CyForm::field('name', 'itemlist')
                ->source(CyForm::source(array($this, 'mockDataSource'))
                    ->val('id')
                    ->text('text'))));
        
        foreach ( $this->mockDataSource() as $row) {
            $this->assertEquals($form->_fields['name']->_model->items[$row['id']], $row['text']);
        }
    }

    public function testLoadInput() {
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('name1', 'text'))
                ->field(CyForm::field('name2', 'text'))
        );

        $form->set_input(array(
            'name1' => 'val1',
            'name2' => 'val2',
            'name3' => 'val3'
        ), false);
        $this->assertEquals(count($form->_fields), 2);
        $this->assertEquals($form->_fields['name1']->get_data(), 'val1');
    }

    public function testValidation() {
        //$this->markTestSkipped('CyclonePHP needs a standalone validator class');
        $form = new CyForm('examples/basic');
        $form->set_input(array('name' => 'hello'));
        $this->assertEquals(array(
                    0 => 'username hello is not unique',
                    'numeric' => 'hello: invalid number format',
                    1 => 'username hello is not unique'
                ), $form->_fields['name']->validation_errors);
    }

    public function testResult() {
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('name1', 'text'))
                ->field(CyForm::field('name2', 'checkbox'))
                ->field(CyForm::field('name3', 'itemlist')
                        ->items(array(
                            'val1' => 'text1',
                            'val2' => 'text2'
                        )))
                ->field(CyForm::field(NULL, 'submit'))
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

    public function testOnEmpty() {
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('name1', 'text')->on_empty(NULL))
        );
        $form->set_input(array('name1' => ''));
        $data = $form->get_data();
        $this->assertNull($data['name1']);
    }

    /**
     *
     * @dataProvider providerFieldDate
     */
    public function testFieldDate($date_string, $input, $date_format) {
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('mydate', 'date'))
                );
        $form->_fields['mydate']->value_format = $date_format;

        $form->set_input(array(
           'mydate_year' => $input['year'],
           'mydate_month' => $input['month'],
           'mydate_day' => $input['day']
        ));
        $data = $form->get_data();
        $this->assertEquals($data['mydate'], $date_string);

        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('mydate', 'date'))
                );
        $form->_fields['mydate']->value_format = $date_format;
        $form->set_data(array('mydate' => $date_string));
        $data = $form->get_data();
        $this->assertEquals($date_string, $data['mydate']);
    }

    public function testOnCreate() {
        $form = new CyForm(CyForm::model()
            ->field(CyForm::field('name', 'text')
                ->on_create('hide'))
        );

        $form->render();
        $this->assertFalse(array_key_exists('name', $form->_fields));

        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('name', 'text')
                        ->on_create('disable'))
        );
        $form->render();
        $this->assertEquals('disabled', $form->_fields['name']->_model->attributes['disabled']);
    }

    public function testOnEdit() {
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('name', 'text')
                        ->on_edit('hide'))
        );

        $form->set_data(array('name' => 'username'));
        $form->render();
        $this->assertFalse(array_key_exists('name', $form->_fields));
        $form = new CyForm(CyForm::model()
                ->field(CyForm::field('name', 'text')
                        ->on_edit('disable'))
        );
        
        $form->set_data(array('name' => 'username'));
        $form->render();
        $this->assertEquals('disabled', $form->_fields['name']->_model->attributes['disabled']);
    }


    /**
     *
     * @dataProvider providerEdit
     */
    public function testEdit(array $fields, array $before_data
            , $progress_id_required, $input, array $after_data) {
        $cfg = Config::inst()->get('cyform');
        unset($_SESSION[$cfg['progress_key']]);
        $form_model = CyForm::model();
        $form_model->fields = $fields;
        $form_before_submit = new CyForm($form_model);
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

        $form_model = CyForm::model();
        $form_model->fields = $fields;
        $form_after_submit = new CyForm($form_model);
        if ($progress_id_required) {
            $input[$cfg['progress_key']] = $form_before_submit->_fields[$cfg['progress_key']]->get_data();
        }
        $form_after_submit->set_input($input);
        $result = $form_after_submit->get_data();
        $this->assertEquals($result, $after_data);
    }

    public static function providerEdit() {
        $rval = array();
        $fields = array(
            CyForm::field('name1', 'text'),
            CyForm::field('name2', 'text')
        );
        $before_data = array('name1' => 'val1', 'name2' => 'val2');
        $progress_id_required = false;
        $input = array('name1' => 'val1_', 'name2' => 'val2_');
        $after_data = array('name1' => 'val1_', 'name2' => 'val2_');
        $rval []= array($fields, $before_data, $progress_id_required, $input, $after_data);

        $fields = array(
            CyForm::field('name1', 'text'),
            CyForm::field('name2', 'text')
        );
        $before_data = array('name1' => 'val1', 'name2' => 'val2', 'name3' => 'val3');
        $progress_id_required = true;
        $input = array('name1' => 'val1_', 'name2' => 'val2_');
        $after_data = array('name1' => 'val1_', 'name2' => 'val2_', 'name3' => 'val3');
        $rval []= array($fields, $before_data, $progress_id_required, $input, $after_data);

        $fields = array(
            CyForm::field('name1', 'text'),
            CyForm::field('name2', 'text')
        );
        $before_data = array('name1' => 'val1', 'name2' => 'val2', 'name3' => 'val3');
        $progress_id_required = true;
        $input = array('name1' => 'val1_');
        $after_data = array('name1' => 'val1_', 'name2' => 'val2', 'name3' => 'val3');
        $rval []= array($fields, $before_data, $progress_id_required, $input, $after_data);

        return $rval;
    }

    public function providerFieldDate() {
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

    public function providerExplicitInput() {
        return array(
            array('text', '\\cyform\\field\\Basic'),
            array('hidden', '\\cyform\\field\\Basic'),
            array('checkbox', '\\cyform\\field\\Checkbox'),
            array('password', '\\cyform\\field\\Basic'),
            array('itemlist', '\\cyform\\field\\ItemList'),
            array('submit', '\\cyform\\field\\Basic'),
            array('textarea', '\\cyform\\field\\Basic'),
            array('date', '\\cyform\\field\\Date')
        );
    }

    public static function custom_callback($username) {
        return false;
    }

}
