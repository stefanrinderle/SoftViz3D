<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/x3d.css" />

	<?php 
		// include jquery libraries
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCoreScript('jquery.ui');
		
		Yii::app()->clientScript->registerCssFile(
				Yii::app()->clientScript->getCoreScriptUrl().
				'/jui/css/base/jquery-ui.css'
		);
	?>	
	
	<!-- local x3dom files -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/x3dom-nightly/x3dom.css" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/x3dom-nightly/x3dom-full.debug.js"></script>
	
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Examples', 'url'=>array('/site/page', 'view'=>'examples')),
				array('label'=>'About structure', 'url'=>array('/site/page', 'view'=>'structure')),
				array('label'=>'About dependencies', 'url'=>array('/site/page', 'view'=>'dependencies')),
				array('label'=>'About the layout', 'url'=>array('/site/page', 'view'=>'layout')),
				array('label'=>'About the UI', 'url'=>array('/site/page', 'view'=>'userInterface')),
				array('label'=>'Import', 'url'=>array('/import/index')),
				array('label'=>'Goanna browser', 'url'=>array('/goanna/index')),
				array('label'=>'File viewer', 'url'=>array('/file/index')),
				array('label'=>'Test', 'url'=>array('/test/index')),
				array('label'=>'-----', 'url'=>array('')),
				array('label'=>'Projects', 'url'=>array('/project/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Signup', 'url'=>array('/user/signup'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'-----', 'url'=>array('')),
				array('label'=>'Admin', 'url'=>array('/site/page', 'view'=>'admin')),
				array('label'=>'-----', 'url'=>array('')),
				array('label'=>'Thesis', 'url'=>array('/site/page', 'view'=>'thesis')),
				array('label'=>'Feedback', 'url'=>array('/site/feedback'))
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php 
	/*
	 * if(isset($this->breadcrumbs)) {
		$this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		));
		}
	 */
	?>

	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; 2015 by Stefan Rinderle.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
