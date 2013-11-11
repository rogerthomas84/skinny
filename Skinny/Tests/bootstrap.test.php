<?php
$dir = realpath(__DIR__ . '/../../');
require_once $dir . '/Skinny/Filter/HtmlEntities.php';
require_once $dir . '/Skinny/Filter/ImageSize.php';
require_once $dir . '/Skinny/Cache.php';
require_once $dir . '/Skinny/Cache/MemcacheService.php';
require_once $dir . '/Skinny/Form/Upload.php';
require_once $dir . '/Skinny/Validate/AbstractValidator.php';
require_once $dir . '/Skinny/Validate/File/Image.php';
require_once $dir . '/Skinny/Validate/Date.php';
require_once $dir . '/Skinny/Validate/EmailAddress.php';
require_once $dir . '/Skinny/Validate/NotEmpty.php';
require_once $dir . '/Skinny/Auth.php';
require_once $dir . '/Skinny/Storage.php';
require_once $dir . '/Skinny/Session.php';
require_once $dir . '/Skinny/Router.php';
require_once $dir . '/Skinny/Form.php';
require_once $dir . '/Skinny/Exception.php';

spl_autoload_register(
    function($class)
    {
        $dir = realpath(__DIR__ . '/../../');
        $target = explode('\\', $class);
        $path = $dir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $target) . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
);
