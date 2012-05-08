<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'3d Virtualisation',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.dotToX.*',
		'application.components.xToDot.*',
		'application.components.dotToX.fileParser.DotFileParser'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'20S10a..',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		)*/
	),

	// application components
	'components'=>array(
	
		'x3dCalculator'=>array(
			'class'=>'X3dCalculator',
		),
		'dotWriter'=>array(
			'class'=>'DotWriter',
		),
		'adotArrayParser'=>array(
			'class'=>'AdotArrayParser',
		),
		'adotFileParser'=>array(
			'class'=>'AdotFileParser',
		),
		'dotArrayParser'=>array(
				'class'=>'DotArrayParser',
		),
		'dotFileParser'=>array(
				'class'=>'DotFileParser',
		),
		'dotLayout'=>array(
			'class'=>'DotLayout',
		),
		'vectorCalculator'=>array(
			'class'=>'VectorCalculator',
		),
		'directoryToDotParser'=>array(
			'class'=>'DirectoryToDotParser',
		),
		'directoryToDotParser2'=>array(
			'class'=>'DirectoryToDotParser2',
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/graph.db',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
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
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);