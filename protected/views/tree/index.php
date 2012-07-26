<?php
$this->pageTitle=Yii::app()->name . ' - Tree';

$this->breadcrumbs=array(
		'Tree', 'Index'
);

$this->widget('application.widgets.x3dom.X3domWidget',
		array('type' => X3domWidget::$TYPE_TREE, 'layoutId' => $layoutId));

$this->widget('application.widgets.sidebar.Sidebar');
?>