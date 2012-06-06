<?php

class TreeController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex() {
		$startTime = $this->getTime();
		
		// STEP 1: Load input dot file
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		try {
			$result = Yii::app()->dotFileParser->parseFile($filename);
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
			//TODO render another layout file and exit
		}
		
		// STEP 2: Write parsed data into database
		Yii::app()->dotInfoToDb->writeToDb($result);

		// STEP 4: calculate the view layout
		$layout = new LayoutVisitor(LayoutVisitor::$TYPE_TREE);
		$root = TreeElement::model()->findByPk(1);
		$root->accept($layout);
		
		// STEP 5: calculate absolute translations
		Yii::app()->layerX3dCalculator->calculate($root);
		$layers = TreeElement::model()->findAllByAttributes(array('isLeaf'=>0));
		
		print_r("Calculation time: " + $this->getTimeDifference($startTime));

		// STEP 5: show the calculated layout
		$this->render('index', array(root => $root, layers=>$layers));
	}
	
	public function actionGetLayer($id = null) {
		$root = TreeElement::model()->findByPk($id);
		
		$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
				'layer' => $root, 'type' => 'tree'
		));
	}

	public function actionGetLayerInfo($id = null) {
		$this->widget('application.widgets.sidebar.LayerInfo', array('layerId' => $id));
	}
	
	public function actionGetLeafInfo($id = null) {
		$this->widget('application.widgets.sidebar.LeafInfo', array('leafId' => $id));
	}
}
