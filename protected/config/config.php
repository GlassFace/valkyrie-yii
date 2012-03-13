<?php

return array(
    'basePath'       => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name'           => 'Hyperion\'s Sandbox',
    'language'       => 'ru',
    'sourceLanguage' => 'en',
    // preloading 'log' component
    'preload'        => array(
        'log',
        'bootstrap',
    ),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.helpers.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.user.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'ext.bootstrap.widgets.*',
        'ext.yiiext.filters.setReturnUrl.ESetReturnUrlFilter',
    ),
    // application components
    'components' => array(
        'authManager' => array(
            'class' => 'RDbAuthManager',
            'defaultRoles'=>array('Guest'),
        ),
        'image' => array(
            'class'  => 'ext.image.ImageComponent',
            'driver' => 'Imagick',
        ),
        'db'     => array(
            'connectionString' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            //'emulatePrepare'   => true,
            'username'         => DB_USER,
            'password'         => DB_PASS,
            'charset'          => 'utf8',
            'tablePrefix'      => '',
        ),
        'db_world'         => array(
            'class'                 => 'CDbConnection',
            'connectionString'      => 'mysql:host=' . DB_WORLD_HOST . ';dbname=' . DB_WORLD_NAME,
            'username'              => DB_WORLD_USER,
            'password'              => DB_WORLD_PASS,
            'emulatePrepare'        => true,
            'charset'               => 'utf8',
            'autoConnect'           => false,
            'schemaCachingDuration' => 3600,
        ),
        'cache'                 => array(
            'class' => 'system.caching.CFileCache',
        ),
        'log'   => array(
            'class'  => 'CLogRouter',
            'routes' => array(
                array(
                    'class'     => 'CFileLogRoute',
                ),
            ),
        ),
        'bootstrap' => array(
            'class'  => 'ext.bootstrap.components.Bootstrap',
        ),
        'config' => array(
            'class'            => 'ext.CmsSettings',
            'cacheComponentId' => 'cache',
            'cacheId'          => 'cms.config',
            'cacheTime'        => 84000,
            'tableName'        => '{{config}}',
            'dbComponentId'    => 'db',
            'createTable'      => true,
            'dbEngine'         => 'InnoDB',
        ),
    ),
    'behaviors'        => array(
        'runEnd' => array(
            'class'          => 'application.components.behaviors.WebApplicationEndBehavior',
        ),
        'onBeginRequest' => array(
            'class'  => 'application.components.behaviors.BeginRequest'
        ),
    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);
