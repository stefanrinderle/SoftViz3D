<?php 

$this->pageTitle=Yii::app()->name . ' - Graph';

$this->breadcrumbs=array(
		'Graph', 'Index'
);

$this->widget('application.widgets.x3dom.X3domWidget',
		array('type' => X3domWidget::$TYPE_GRAPH, 'layoutId' => $layoutId));

$this->widget('application.widgets.sidebar.Sidebar');

?>