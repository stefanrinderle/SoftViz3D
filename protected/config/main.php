<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'3d visualization',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.models.input.*',
		'application.models.layout.*',
		'application.controllers.*',
		'application.components.*',
		'application.components.database.*',
		'application.components.dot.*',
		'application.components.dot.parser.*',
		'application.components.goanna.*',
		'application.components.x3d.*'
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
		// db folder
		'edgeExpander'=>array(
			'class'=>'EdgeExpander',
		),
			
		// dot folder
		'dotFileParser'=>array(
			'class'=>'DotFileParser',
		),
		'dotArrayParser'=>array(
			'class'=>'DotArrayParser',
		),
		'directoryToDotParser'=>array(
			'class'=>'DirectoryToDotParser',
		),
		'dotArrayToDB'=>array(
				'class'=>'DotArrayToDB',
		),
		'dotCommand'=>array(
			'class'=>'DotCommand',
		),
		'dotWriter'=>array(
			'class'=>'DotWriter',
		),
		'jdependToDotParser'=>array(
			'class'=>'JDependToDotParser',
		),
			
		// x3d folder	
		'treeX3dCalculator'=>array(
			'class'=>'TreeX3dCalculator',
		),
		'graphX3dCalculator'=>array(
			'class'=>'GraphX3dCalculator',
		),
		'layerX3dCalculator'=>array(
			'class'=>'LayerX3dCalculator',
		),
		'vectorCalculator'=>array(
			'class'=>'VectorCalculator',
		),

		// goanna folder
		'goannaInterface'=>array(
			'class'=>'GoannaInterface',
		),
		'goannaSnapshotToDotParser'=>array(
			'class'=>'GoannaSnapshotToDotParser',
		),
			
		// yii default
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
//		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/graph.db',
//		),
 		'db'=>array(
 				'class'=>'CDbConnection',
 				'connectionString'=>'mysql:host=localhost;dbname=graph',
 				'username'=>'root',
 				'password'=>'20S10a..'
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
		'adminEmail'=>'stefan@rinderle.info',
		'currentResourceFile' => DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'actualResource.dot',
		'tempDotFile' => DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'temp.dot'
	),
);