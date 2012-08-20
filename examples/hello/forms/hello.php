<?php

use cyclone\Form;


// static factory method to create form models
return Form::model()
    // adding a form field with name "name", which will be a text input.
    ->field(Form::field('name', 'text')
        // the label which will be rendered for the user
        ->label('Username: ')
        // it must be filled
        ->validator('not_empty')
    // adding a form field with name "password" which will be a password input
    )->field(Form::field('password', 'password')
        // the password which will be rendered for the user
        ->label('Password: ')
        // it must be filled
        ->validator('not_empty')
    // adding the submit button - the name of the button is not important so
    // we pass NULL here as input name
    )->field(Form::field(NULL, 'submit')
        // important: for setting the text of the button you must set "label" and not "value"
        ->label('Submit!')
);