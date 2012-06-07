<?php
$this->pageTitle=Yii::app()->name . ' - Tree';

$this->breadcrumbs=array(
		'Tree', 'Index'
);

$this->widget('application.widgets.x3dom.X3domWidget',array(
		'root' => $root, 'layers' => $layers, 'type' => 'tree'
));

$this->widget('application.widgets.sidebar.Sidebar');
?>