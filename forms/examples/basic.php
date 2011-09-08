<?php

use cyclone as cy;

return cy\CyForm::model()
    ->field(cy\CyForm::field('name', 'text')
        ->validator('not_empty')
        ->validator('regex', array('/[a-z]*/'))
        ->validator('numeric', array(), ':1: invalid number format')
        ->validator(array('CyForm_Test', 'custom_callback')
                , array('asd')
                , 'username :1 is not unique')
        ->validator(array('CyForm_Test', 'custom_callback')
                , array('asd')
                , 'username :1 is not unique')
);