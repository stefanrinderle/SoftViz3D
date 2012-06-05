<?php
class EX3domWidget extends CWidget {
	
	public $root;
	public $layers;
	// can be "tree" or "graph"
	public $type;
	
	public function run()
	{
		// include jquery libraries
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCoreScript('jquery.ui');
		
		Yii::app()->clientScript->registerCssFile(
				Yii::app()->clientScript->getCoreScriptUrl().
				'/jui/css/base/jquery-ui.css'
		);
		
		$this->render('x3domStart', array(root => $this->root));
		
		foreach ($this->layers as $layer) {
			$this->generateX3DOM($layer);
		}
		
		$this->render('x3domEnd', array(root => $this->root));
		
		// include own javascript files
		$naviFile = Yii::app()->baseUrl.'/js/navigation.js';
		$zoomFile = Yii::app()->baseUrl.'/js/zoom.js';
		Yii::app()->clientScript->registerScriptFile($naviFile);
		Yii::app()->clientScript->registerScriptFile($zoomFile);
	}
	
	private function generateX3DOM($node) {
		$x3dInfos = $node->getX3dInfos();
		
		if ($this->type == "tree") {
			$this->render('x3dTreeLayer', array(graph=>$x3dInfos));
		} else {
			$this->render('x3dGraphLayer', array(graph=>$x3dInfos, translation=>$x3dInfos->bb[translation]));
		}
	}
}

?>