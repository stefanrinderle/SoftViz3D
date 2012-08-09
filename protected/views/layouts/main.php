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
				array('label'=>'Projects', 'url'=>array('/project/index'), 'visible'=>!Yii::app()->user->isGuest),
				//array('label'=>'Import', 'url'=>array('/import/index')),
				array('label'=>'Goanna browser', 'url'=>array('/goanna/index')),
				//array('label'=>'File viewer', 'url'=>array('/file/index')),
				//array('label'=>'Test', 'url'=>array('/test/index')),
				array('label'=>'-----', 'url'=>array('')),
				array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Signup', 'url'=>array('/user/signup'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'-----', 'url'=>array('')),
				array('label'=>'Feedback', 'url'=>array('/site/feedback')),
					array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about'))
			),
		)); ?>
	</div><!-- mainmenu -->
	
	<?php 
	if(isset($this->breadcrumbs)) {
		$this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		));
	}
	?>

	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Stefan Rinderle.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
