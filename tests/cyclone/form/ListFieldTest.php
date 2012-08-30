<?php
namespace cyclone\form;

use cyclone\Form;

class ListFieldTest extends \Kohana_Unittest_TestCase {

    public function test_data_sourceLoading() {
        $form = new Form(Form::model()
            ->field(Form::field('name', 'list')
            ->source(Form::source(array($this, 'mock_datasource'))
            ->val('id')
            ->text('text'))));

        foreach ( $this->mock_datasource() as $row) {
            $this->assertEquals($form->_fields['name']->_model->items[$row['id']], $row['text']);
        }
    }

    public function mock_datasource() {
        return array(
            array('id' => 1, 'text' => 'txt1'),
            array('id' => 2, 'text' => 'txt2')
        );
    }

}