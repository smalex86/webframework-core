<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Template to describe link variable list element:
 * 
 * $config['linkVariable'] = [
 *  [
 *       'name' => '', // var name
 *       'value' => '', // var value, if it doesn't set value will be init from "init" in bootsrap
 *       'init' => [ // array for init value
 *           'class' => '', // class name with namespace
 *           'static' => true/false, // if static then method will be call as static method
 *           'method' => '' // class method
 *       ]
 *   ],
 * ];
 * 
 */

// variable config array
$config['linkVariable'] = [
    [
        'name' => 'script',
        'value' => '',
        'init' => [
            'class' => 'smalex86\\webframework\\core\\FunctionList',
            'static' => true,
            'method' => 'getScriptName'
        ]
    ],
    [
        'name' => 'scriptTest',
        'value' => $_SERVER['SCRIPT_NAME'],
        'init' => [
            'class' => '',
            'static' => true,
            'method' => ''
        ]
    ],
    [
        'name' => 'scriptTest2',
        'value' => 'test',
        'init' => [
            'class' => '',
            'static' => true,
            'method' => ''
        ]
    ],
];

return $config;
