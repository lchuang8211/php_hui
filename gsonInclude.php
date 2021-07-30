<?php

    $directories = array(
        'gson-php-master/',
        
        'gson-php-master/docs/',
        
        'gson-php-master/src/Annotation/',
        'gson-php-master/src/Context/',
        'gson-php-master/src/Exception/',
        'gson-php-master/src/Exclusion/',
        'gson-php-master/src/Internal/',
        'gson-php-master/src/TypeAdapter/',
        'gson-php-master/src/',
        'gson-php-master/.phpstorm.meta.php/'
    );

    // foreach($directories['data'] as $result) {
    //     echo $result['type'], '<br>';
    // }

    foreach ($directories as $directory) {
        foreach (glob($directory . "*.php") as $class) {
            include_once $class;
        }

    //     foreach (glob($directory . "*.php") as $filename) {
    //         require $filename;
    //     }
    }

?>