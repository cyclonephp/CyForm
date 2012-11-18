<?php

use cyclone\Form;

return Form::model()->theme('cyform/daffodil')
        ->title('Complex CyForm example')
        //->action('formtest/ajaxsave')
        ->field(Form::field('name')
            ->label('username'))

        ->field(Form::field('password', 'password')
            ->label('password')
        )->field(Form::field('role', 'list')
            ->label('role')
            ->view('select')
            ->items(array(
                '0' => 'user',
                '1' => 'admin'
            ))
        )->field(Form::field('enabled', 'checkbox')
             ->label('enabled')
        )->field(Form::field('about', 'textarea')
                ->label('about')
        )->field(Form::field('gender', 'list')
            ->label('gender')
            ->view('buttons')
            ->validator('not_empty')
            ->items(array(
                'f' => 'female',
                'm' => 'male'
            ))
        )->field(Form::field('groups', 'list')
                ->label('groups')
                ->multiple(TRUE)
                ->view('buttons')
                ->items(array(
                    '1' => 'group 01',
                    '2' => 'group 02',
                    '3' => 'group 03'
                ))
        )->field(Form::field('expires', 'date')
                ->label('expires')
                //->min_date('now')
                ->max_date(array('year' => '2015', 'month' => '05', 'day' => '22'))
        )->field(Form::model('mysubform')->title('mysubform')
            ->field(Form::field('subfield1')->label('subfield1'))
            ->field(Form::field('subfield2')->label('subfield2'))
            ->field(Form::field('subdate', 'date'))
        )->field(Form::field(NULL, 'submit')
                ->label('Ok'))


;