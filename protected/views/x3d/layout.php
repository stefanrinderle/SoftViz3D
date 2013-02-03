<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

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
	
	<!-- include x3dom scripts -->
	<!-- <link rel="stylesheet" type="text/css" href="http://x3dom.org/download/x3dom.css" />
	<script type="text/javascript" src="http://x3dom.org/download/x3dom.js"></script> -->
	
	<!-- local x3dom files -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/x3dom/x3dom.css" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/x3dom/x3dom-full.js"></script>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

	<?php echo $content; ?>

</body>
</html>
