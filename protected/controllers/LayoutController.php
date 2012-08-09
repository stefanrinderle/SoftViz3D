<?php

class LayoutController extends BaseController {

	public function actionIndex($projectId, $layoutType) {
		$startTime = $this->getTime();
		
		$project = Project::model()->findByPk($projectId);
		
		$layoutArray = $project->getLayoutTypeArray();
		
		if (array_key_exists($layoutType, $layoutArray)) {
			$layout = $layoutArray[$layoutType];
			$layout->setCreationTime(new DateTime());
			$layout->save();
			
			BoxElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
			EdgeElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
			EdgeSectionElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
		} else {
			$layout = new Layout();
			$layout->setCreationTime(new DateTime());
			$layout->projectId = $projectId;
			$layout->type = $layoutType;
			$layout->save();
		}

		// STEP 2: calculate the view layout
		$view = $layout->getViewClass();
		$visitor = new LayoutVisitor($view, $projectId);
		$root = InputNode::model()->findByPk($project->inputTreeRootId);
		$root->accept($visitor);
		
		// STEP 3: calculate absolute translations
		Yii::app()->absolutePositionCalculator->calculate($project->inputTreeRootId, $view);
		
		$this->redirect(array('project/index'));
	}
}
