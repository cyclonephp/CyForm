<?php

use cyclone as cy;

return cy\Form::model()->theme('cyform/daffodil')
        ->title('Complex CyForm example')
        //->action('formtest/ajaxsave')
        ->field(cy\Form::field('name')
            ->label('username'))

        ->field(cy\Form::field('password', 'password')
            ->label('password')
        )->field(cy\Form::field('role', 'list')
            ->label('role')
            ->view('select')
            ->items(array(
                '0' => 'user',
                '1' => 'admin'
            ))
        )->field(cy\Form::field('enabled', 'checkbox')
             ->label('enabled')
        )->field(cy\Form::field('about', 'textarea')
                ->label('about')
        )->field(cy\Form::field('gender', 'list')
            ->label('gender')
            ->view('buttons')
            ->validator('not_empty')
            ->items(array(
                'f' => 'female',
                'm' => 'male'
            ))
        )->field(cy\Form::field('groups', 'list')
                ->label('groups')
                ->multiple(TRUE)
                ->view('buttons')
                ->items(array(
                    '1' => 'group 01',
                    '2' => 'group 02',
                    '3' => 'group 03'
                ))
        )->field(cy\Form::field('expires', 'date')
                ->label('expires')
                //->min_date('now')
                ->max_date(array('year' => '2015', 'month' => '05', 'day' => '22'))
        )->field(cy\Form::field(NULL, 'submit')
                ->label('Ok'))


;