<?php

class ViewController extends BaseController {
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public function actionIndex($projectId, $viewType) {
		$startTime = $this->getTime();
		
		$project = Project::model()->findByPk($projectId);
		
		if ($viewType == Layout::$TYPE_STRUCTURE) {
			if (array_key_exists(Layout::$TYPE_STRUCTURE, $project->layouts)) {
				$layout = $project->layouts[Layout::$TYPE_STRUCTURE];
				
				BoxElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
				EdgeElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
			} else {
				$layout = new Layout();
				$layout->projectId = $projectId;
				$layout->type = Layout::$TYPE_STRUCTURE;
				$layout->save();
			}
		}

		print_r("Delete old layout: " . $this->getTimeDifference($startTime));
		
		// STEP 2: calculate the view layout
		
		$view = new StructureView($layout->id);
		$visitor = new LayoutVisitor($view);
		$root = InputNode::model()->findByPk($project->inputTreeRootId);
		$root->accept($visitor);
		
		// STEP 3: calculate absolute translations
		Yii::app()->absolutePositionCalculator->calculate($project->inputTreeRootId, $view);
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$this->render('//tree/index', array('layoutId' => $layout->id));
	}
}
