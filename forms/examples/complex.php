<?php

use cyclone as cy;

return cy\CyForm::model()->theme('cyform/daffodil')
        ->title('Complex CyForm example')
        //->action('formtest/ajaxsave')
        ->field(cy\CyForm::field('name')
            ->label('username'))

        ->field(cy\CyForm::field('password', 'password')
            ->label('password')
        )->field(cy\CyForm::field('role', 'list')
            ->label('role')
            ->view('select')
            ->items(array(
                '0' => 'user',
                '1' => 'admin'
            ))
        )->field(cy\CyForm::field('enabled', 'checkbox')
             ->label('enabled')
        )->field(cy\CyForm::field('about', 'textarea')
                ->label('about')
        )->field(cy\CyForm::field('gender', 'list')
            ->label('gender')
            ->view('buttons')
            ->validator('not_empty')
            ->items(array(
                'f' => 'female',
                'm' => 'male'
            ))
        )->field(cy\CyForm::field('groups', 'list')
                ->label('groups')
                ->multiple(TRUE)
                ->view('buttons')
                ->items(array(
                    '1' => 'group 01',
                    '2' => 'group 02',
                    '3' => 'group 03'
                ))
        )->field(cy\CyForm::field('expires', 'date')
                ->label('expires')
                //->min_date('now')
                ->max_date(array('year' => '2015', 'month' => '05', 'day' => '22'))
        )->field(cy\CyForm::field(NULL, 'submit')
                ->label('Ok'))


;