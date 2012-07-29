<?php

class LayoutController extends BaseController {

	public function actionIndex($projectId, $layoutType) {
		$startTime = $this->getTime();
		
		$project = Project::model()->findByPk($projectId);
		
		if (array_key_exists($layoutType, $project->layouts)) {
			$layout = $project->layouts[$layoutType];
				
			BoxElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
			EdgeElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
		} else {
			$layout = new Layout();
			$layout->setCreationTime(new DateTime());
			$layout->projectId = $projectId;
			$layout->type = $layoutType;
			$layout->save();
		}

		// STEP 2: calculate the view layout
		
		$view = $layout->getViewClass($layoutType, $layout->id);
		$visitor = new LayoutVisitor($view);
		$root = InputNode::model()->findByPk($project->inputTreeRootId);
		$root->accept($visitor);
		
		// STEP 3: calculate absolute translations
		Yii::app()->absolutePositionCalculator->calculate($project->inputTreeRootId, $view);
		
		//print_r("Calculation time: " + $this->getTimeDifference($startTime));
		
		$this->redirect(array('project/index'));
	}
}
