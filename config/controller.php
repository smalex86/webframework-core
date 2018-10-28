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
 * Template to describe controller list element:
 * 
 * $config['controller'] = [
 *  [
 *       'name' => '', // controller name from get param "page"
 *       'type' => '', // controller type: page, menu, component
 *       'class' => '', // class of controller, full path
 *       'action' => [ // action array for this controller, this array overrides default settings
 *           'action1' => 'viewClassName1', // action1 needs view this class viewClassName1
 *           'action2' => 'viewClassName2', // full path for class of view
 *       ]
 *   ],
 * ];
 * 
 */

// настройки контроллеров
$config['controller'] = [
    // static
    [
        'name' => 'staticPage',
        'type' => 'page',
        'class' => 'smalex86\\webframework\\core\\packageStatic\\controller\\Page',
        'action' => [
            'view' => 'smalex86\\webframework\\core\\packageStatic\\view\\Page'
        ]
    ],
    [
        'name' => 'staticMenu',
        'type' => 'menu',
        'class' => 'smalex86\\webframework\\core\\packageStatic\\controller\\Menu',
        'action' => [
            'view' => 'smalex86\\webframework\\core\\packageStatic\\view\\Menu'
        ]
    ],
    [
        'name' => 'staticComponent',
        'type' => 'component',
        'class' => 'smalex86\\webframework\\core\\packageStatic\\controller\\Component',
        'action' => [
            'view' => 'smalex86\\webframework\\core\\packageStatic\\view\\Component'
        ]
    ],
    // session
    [
        'name' => 'session',
        'type' => 'page',
        'class' => 'smalex86\\webframework\\core\\session\\controller\\Session',
        'action' => [
            'view' => '',
            'delPostMsg' => ''
        ]
    ],
    // user
    [
        'name' => 'user',
        'type' => 'component',
        'class' => 'smalex86\\webframework\\core\\user\\controller\\User',
        'action' => [
            'login' => 'smalex86\\webframework\\core\\user\\view\\Login',
            'info' => 'smalex86\\webframework\\core\\user\\view\\Info'
        ]
    ],
    [
        'name' => 'user',
        'type' => 'page',
        'class' => 'smalex86\\webframework\\core\\user\\controller\\User',
        'action' => [
            'post' => ''
        ]
    ],
];

return $config;
