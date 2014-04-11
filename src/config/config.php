<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Standard
    |--------------------------------------------------------------------------
    |
    | One or more coding standard do check for violations.
    | Available options are: Zend, PEAR, Squiz, PHPCS, MySource, PSR2 and PSR1
    |
    */
    'standard' => array(
        'PSR2',
    ),

    /*
    |--------------------------------------------------------------------------
    | Files to watch
    |--------------------------------------------------------------------------
    |
    | One or more files and/or directories to check
    |
    */
    'files' => array(
        'app/models',
        'app/controllers',
        'app/commands',
    ),
);
