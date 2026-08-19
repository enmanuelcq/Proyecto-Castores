<?php
// Config/autoloader.php - Autoload personalizado sin Composer

spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/Config',
        ROOT_PATH . '/Model/Entities',
        ROOT_PATH . '/Model/Repository',
        ROOT_PATH . '/Model/Interfaces',
        ROOT_PATH . '/Model/Service',
        ROOT_PATH . '/Controller',
    ];

    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
