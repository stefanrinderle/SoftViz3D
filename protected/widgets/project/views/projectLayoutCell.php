<?php 
if (array_key_exists($layoutType, $layoutArray)) {
	echo CHtml::link('view', array('x3d/index', 'layoutId' => $layoutArray[$layoutType]->id), array('target' => '_blank')) . "<br />";
	echo $layoutArray[$layoutType]->getCreationTime() . "<br />";
	
	//TODO: recalcuation not working properly (why?)
	echo CHtml::link('recalculate', array('layout/index', 'projectId' => $project->id, 'layoutType' => $layoutType));
} else if ($project->getFileUpdateTime() != -1) {
	echo CHtml::link('create layout', array('layout/index', 'projectId' => $project->id, 'layoutType' => $layoutType));
} else {
	echo "error";
}
?>