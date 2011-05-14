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
		'application.modules.user.models.*',
		'application.modules.user.components.*',
		'application.modules.user.*',
    ),

    // application components
    'components'=>array(
        'ipbBridge'=>array(
            'class'=>'IpbBridge',
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=project',
                'username' => 'root',
                'password' => '59tyr4pn',
                'charset' => 'utf8',
                'tablePrefix'=>'',
            ),
        ),
        'request'=>array(
            //'enableCsrfValidation'=>true,
            'enableCookieValidation'=>true,
        ),

        'user'=>array(
            'class' => 'application.modules.user.components.WebUser',
            'allowAutoLogin'=>true,
	    'loginUrl'=>'/user/auth',
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                'doc'=>'/doc/default/index',
                'doc/<section:\w+>/<language:\w+>/<page:[a-zA-Z0-9_\-\.]+>'=>'doc/default/view',
                'doc/<section:\w+>/<page:[a-zA-Z0-9_\-\.]+>'=>'doc/default/view',
                'admin/<_m:\w+>/<_c:\w+>/<_a:\w+>/*'=>'<_m>/admin/<_c>/<_a>',
                'admin/<_c:\w+>/<_a:\w+>'=>'admin/<_c>/<_a>',
                'admin'=>'admin/default/index',
                '<_c:\w+>/<id:\d+>'=>'<_c>/view',
                '<_m:\w+>/<_c:\w+>/<id:\d+>'=>'<_m>/<_c>/view',
                'wow/<_c:character|guild>/<_a:\w+>/<realm>/<name:\w+>'=>'wow/<_c>/<_a>',
            ),
            'showScriptName' => false,
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=cms',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '59tyr4pn',
            'charset' => 'utf8',
            'tablePrefix'=>'',
        ),
        'db_world'=>array(
            'class'=>'CDbConnection',
            'connectionString'=>'mysql:host=valkyrie-wow.com;dbname=1121_world',
            'username'=>'hyp_cp',
            'password'=>'gHXOkMSOD6',
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
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
    ),

    'params'=>require(dirname(__FILE__).'/params.php'),
);
