<?php

use cyclone\Form;

return Form::model()
    ->field(Form::field('name', 'text')
        ->validator('not_empty')
        ->validator('regex', array('/[a-z]*/'))
        ->validator('numeric', array(), ':value: invalid number format')
        ->validator(array('cyclone\\form\\CyFormTest', 'custom_callback')
                , array('asd')
                , 'username :value is not unique')
        ->validator(array('cyclone\\form\\CyFormTest', 'custom_callback')
                , array('asd')
                , 'username :value is not unique')
);