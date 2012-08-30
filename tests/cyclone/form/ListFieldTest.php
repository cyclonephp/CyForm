<?php
namespace cyclone\form;

use cyclone\Form;
use cyclone\form\field\DateField;

class ListFieldTest extends \Kohana_Unittest_TestCase {

    public function test_get_view_data() {
        $form = new Form(Form::model()
            ->field(Form::field('mylist', 'list')
                ->items(array(
                    0 => 'itm1',
                    1 => 'itm2',
                    3 => 'itm3'
                ))
                ->value(1)
            )
        );
        $view_data = $form->get_field('mylist')->get_view_data();
        $attrs = $view_data['attributes'];
        $this->assertEquals('mylist', $attrs['name']);
        $this->assertEquals(1, $attrs['value']);

        $form = new Form(Form::model()
                ->field(Form::field('mylist', 'list')
                    ->items(array(
                    0 => 'itm1',
                    1 => 'itm2',
                    3 => 'itm3'
                ))
                ->value(1)
                ->view('select')
                ->multiple(TRUE)
            )
        );
        $view_data = $form->get_field('mylist')->get_view_data();
        $attrs = $view_data['attributes'];
        $this->assertEquals('mylist[]', $attrs['name']);
    }

    public function test_data_sourceLoading() {
        $form = new Form(Form::model()
            ->field(Form::field('name', 'list')
            ->source(Form::source(array($this, 'mock_datasource'))
            ->val('id')
            ->text('text'))));

        foreach ( $this->mock_datasource() as $row) {
            $this->assertEquals($form->get_field('name')->_model->items[$row['id']], $row['text']);
        }
    }

    public function mock_datasource() {
        return array(
            array('id' => 1, 'text' => 'txt1'),
            array('id' => 2, 'text' => 'txt2')
        );
    }

}