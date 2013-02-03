<?php
$this->pageTitle=Yii::app()->name . ' - Tree';

$this->breadcrumbs=array(
		'Tree', 'Index'
);

$this->widget('application.widgets.x3dom.X3domWidget',
		array('layout' => $layout));

//$this->widget('application.widgets.sidebar.Sidebar');
?>