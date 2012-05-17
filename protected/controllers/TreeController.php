<?php

class TreeController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public $layout='//layouts/column1';
	
	public function actionIndex()
	{
		/* reset database */
		$connection=Yii::app()->db;
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
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

		// STEP 3: Normalize edges
		Yii::app()->edgeExpander->execute();
		
		// STEP 4: calculate the view layout
		$layout = new LayoutVisitor();
		$root = TreeElement::model()->findByPk(1);
		$root->accept($layout);
		
		// STEP 5: show the calculated layout
		$this->render('index', array(tree=>$root));
	}
}
