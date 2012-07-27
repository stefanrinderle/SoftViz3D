<?php

class TreeController extends BaseController {
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex($projectId, $viewType) {
		$startTime = $this->getTime();
		
		$project = Project::model()->findByPk($projectId);
		
		if (!$project->inputTreeRootId) {
			$this->loadFiletoDb($project);
		}
		
		if ($viewType == Layout::$TYPE_STRUCTURE) {
			if (array_key_exists($project->layouts, Layout::$TYPE_STRUCTURE)) {
				$layout = $project->layouts[Layout::$TYPE_STRUCTURE];
				
				BoxElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
				EdgeElement::model()->deleteAllByAttributes(array('layoutId'=>$layout->id));
			}
		}
		
		print_r("Delete old layout: " + $this->getTimeDifference($startTime));
		
		$rootId = $this->loadFiletoDb();

		// STEP 2: calculate the view layout
		
		$view = new StructureView();
		$visitor = new LayoutVisitor($view);
		$root = InputNode::model()->findByPk($rootId);
		$root->accept($visitor);
		
		// STEP 3: calculate absolute translations
		Yii::app()->absolutePositionCalculator->calculate($rootId, $view);
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$layoutId = 1;
		$this->render('index', array('layoutId' => $layoutId));
	}
	
	protected function loadFiletoDb(Project $project) {
		try {
			/* reset database */
			InputTreeElement::model()->deleteAll();
			InputDependency::model()->deleteAll();
				
			$parseResult = Yii::app()->dotFileParser->parse($filename, $includeEdges);
				
			$parseResult = $this->removeEmptyStartLayers($parseResult);
				
			return Yii::app()->dotArrayToDB->save($parseResult);
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
			//TODO render another layout file and exit
		}
	}
	
	private function removeEmptyStartLayers($parseResult) {
		while (count($parseResult['content']) == 1) {
			$edges = $parseResult['edges'];
			$parseResult = $parseResult['content'][0];
			$parseResult['edges'] = $edges;
		}
	
		return $parseResult;
	}
}
