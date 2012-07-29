<?php 
if (array_key_exists($layoutType, $layoutArray)) {
	echo "view <br />";
	echo $layoutArray[$layoutType]->getCreationTime() . "<br />";
	echo CHtml::link('recalculate', array('layout/index', 'projectId' => $project->id, 'layoutType' => $layoutType));
} else if ($project->getFileUpdateTime() != -1) {
	echo CHtml::link('create layout', array('layout/index', 'projectId' => $project->id, 'layoutType' => $layoutType));
} else {
	echo "error";
}
?>