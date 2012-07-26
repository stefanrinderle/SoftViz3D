<?php

class GraphController extends BaseX3dController
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		/* reset layout database */
		BoxElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		EdgeSectionElement::model()->deleteAll();
		
		$startTime = $this->getTime();
		
		$rootId = $this->loadFiletoDb();
		
		//echo "Calculation time write to db: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 2: Normalize edges
		$projectId = 0;
		Yii::app()->dependencyExpander->execute($projectId);
		
		//echo "Calculation time edge expander: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 3: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_GRAPH);
		$root = InputNode::model()->findByPk($rootId);
		$root->accept($layout);
		
		//echo "Calculation time Layoutvisitor: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 4: calculate absolute translations
		Yii::app()->absolutePositionCalculator->calculate($rootId);
		
		//echo "Calculation time absolute translations: " . $this->getTimeDifference($startTime) . "<br />";
		
		// STEP 5: show the calculated layout
		$this->render('index', array('root' => $root, 'layoutId' => 1));
	}
}
