<?php 
$this->pageTitle=Yii::app()->name . ' - Graph';

$this->breadcrumbs=array(
		'Graph', 'Index'
);

$this->widget('application.widgets.x3dom.X3domWidget',array(
		'root' => $root, 'layers' => $layers, 'type' => 'graph'
));

$this->widget('application.widgets.sidebar.Sidebar');
?>