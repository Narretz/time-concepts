<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Concepts of Time',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.task.*',
		'application.models.taskResult.*',
		'application.components.*',
		'application.modules.rights.*', 
   		'application.modules.rights.components.*',
	),

	'modules'=>array(
	  'rights'=>array( 
	   ),
	),

	// application components
	'components'=>array(
		  'facebook'=>array(
		    'class' => 'ext.yii-facebook-opengraph.SFacebook',
		    'appId'=>'', // needed for JS SDK, Social Plugins and PHP SDK
		    'secret'=>'', // needed for the PHP SDK
		  ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'RWebUser',
		),
		'authManager' => array(
			 'class'=>'RDbAuthManager',
			 'defaultRoles'=>array('Guest'),
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<subfolder:\w+>/<controller:\w+>/<id:\d+>'=>'<subfolder>/<controller>/view',
				'<subfolder:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<subfolder>/<controller>/<action>/<id>',
				'<subfolder:\w+>/<controller:\w+>/<action:\w+>'=>'<subfolder>/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'enableProfiling' => 'true',
			'enableParamLogging' => 'true',
          //  'schemaCachingDuration'=>3600,
		),

        'cache'=>array(
            'class'=>'CDbCache',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
        // enables theme based JQueryUI's
        'widgetFactory' => array(
            'widgets' => array(
                'CJuiButton' => array(
                    'themeUrl' => '', //url to the folder with the style files
                    'theme' => 'green',
                ),
			),
		),
),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'',
		'fb_app_id' => '', //Facebook App Id
	),
);