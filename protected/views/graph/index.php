<?php
$this->pageTitle=Yii::app()->name . ' - Graph';

$this->breadcrumbs=array(
	'Tree', 'Index'
);

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);

?>

<?php
	$this->widget('ext.x3dom.EX3domWidget',array(
	    'tree'=> $tree, 'type' => 'graph'
	));
?>