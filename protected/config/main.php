<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Hyperion\'s Sandbox',
    'language' => 'ru',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        'core',
        'wow',
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'123',
        ),

    ),

    // application components
    'components'=>array(
        'ipbBridge'=>array(
            'class'=>'IpbBridge',
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=valkyrie',
                'username' => 'root',
                'password' => '59tyr4pn',
                'charset' => 'utf8',
                'tablePrefix'=>'wow_',
            ),
        ),
        'request'=>array(
            'enableCsrfValidation'=>true,
            'enableCookieValidation'=>true,
        ),

        'user'=>array(
            'class' => 'WebUser',
            'allowAutoLogin'=>true,
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                'admin/<_m:\w+>/<_c:\w+>/<_a:\w+>/*'=>'<_m>/admin/<_c>/<_a>',
                'admin/<_m:\w+>/<_c:\w+>/*'=>'<_m>/admin/<_c>',
                'admin/<_m:\w+>'=>'<_m>/admin/default/index',
                'admin'=>'core/admin/default/index',
                '<_c:\w+>/<id:\d+>'=>'<_c>/view',
                '<_m:\w+>/<_c:\w+>/<id:\d+>'=>'<_m>/<_c>/view',
            ),
            'showScriptName' => false,
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=valkyrie',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '59tyr4pn',
            'charset' => 'utf8',
            'tablePrefix'=>'',
        ),
        'db_world'=>array(
            'class'=>'CDbConnection',
            'connectionString'=>'mysql:host=localhost;dbname=world',
            'username'=>'root',
            'password'=>'59tyr4pn',
            'emulatePrepare'=> true,
            'charset' => 'utf8',
            'autoConnect' => false,
        ),
        'db_realmd'=>array(
            'class'=>'CDbConnection',
            'connectionString'=>'mysql:host=localhost;dbname=logon',
            'username'=>'root',
            'password'=>'59tyr4pn',
            'emulatePrepare'=> true,
            'charset' => 'utf8',
            'autoConnect' => false,
        ),
        'db_chars'=>array(
            'class'=>'CDbConnection',
            'connectionString'=>'mysql:host=localhost;dbname=chars',
            'username'=>'root',
            'password'=>'59tyr4pn',
            'emulatePrepare'=> true,
            'charset' => 'utf8',
            'autoConnect' => false,
        ),
        'authManager' => array(
            'class' => 'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'mailer' => array(
            'class' => 'application.extensions.mailer.EMailer',
            'pathViews' => 'application.views.email',
            'pathLayouts' => 'application.views.email.layouts',
            'SMTPDebug' => 0,
            'SMTPAuth' => true,
            'SMTPSecure' => 'ssl',
            'Host' => 'smtp.gmail.com',
            'Port' => 465, 
            'Username' => 'test.valkyrie.wow@gmail.com',
            'CharSet' => 'UTF_8',
            'Password' => 'pdpfer56df56',
            'From' => 'valkyrie.wow@gmail.com',
            'FromName' => 'Administration of Valkyrie-wow',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CWebLogRoute',
                ),
            ),
        ),
    ),

    'params'=>array(
        'adminEmail'=>'webmaster@example.com',
        'allowExternalLogin'=>false,
        'externalLoginMethod'=>'ipb',
    ),
);