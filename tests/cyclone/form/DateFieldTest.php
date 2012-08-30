<?php
namespace cyclone\form;

use cyclone\Form;

class DateFieldTest extends \Kohana_Unittest_TestCase {

    /**
     *
     * @dataProvider provider_field_date
     */
    public function test_field_date($date_string, $input, $date_format) {
        $form = new Form(Form::model()
                ->field(Form::field('mydate', 'date')->format($date_format))
        );

        $form->set_input(array(
            'mydate' => $input
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

    public function provider_field_date() {
        return array(
            array('2010-09-17', array('year' => '2010', 'month' => '09', 'day' => '17'), 'year-month-day'),
            array('09/17/2010', array('year' => '2010', 'month' => '09', 'day' => '17'), 'month/day/year'),
            array('2010.09.17', array('year' => '2010', 'month' => '09', 'day' => '17'), 'year.month.day')
        );
    }

}