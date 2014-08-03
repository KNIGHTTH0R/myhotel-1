<?php

return array(
    'resources' => array(
        'view' => array(), 
        'FrontController' => array(
            'moduleDirectory' => APPLICATION_PATH . '/modules',
            'defaultModule' => 'default',
            'params' => array(
                'displayExceptions' => 1
            ),
        ),
        'modules' => '',
    ),
    'phpSettings' => array(
        'display_startup_errors' => 0,
        'display_errors' => 0,
        'date' => array(
            'timezone' => 'Asia/Ho_Chi_Minh',
        )
    ),
    'bootstrap' => array(
        'path' => APPLICATION_PATH . "/Bootstrap.php",
        'class' => 'Bootstrap',
    ),
    'autoloaderNamespaces' => array(
        '0' => "MyZend_",
    )
)
?>
