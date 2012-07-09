<?php

class BaseX3dController extends BaseController {
	
	protected function loadFiletoDb() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		try {
			/* reset database */
			TreeElement::model()->deleteAll();
			EdgeElement::model()->deleteAll();
				
			// STEP 2: Load input dot file into db
			$result = Yii::app()->ownDotParser->parse($filename);
			
			$result[rootId] = $this->removeStartingEmptyLayers($result[rootId]);
			return $result;
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
			//TODO render another layout file and exit
		}
	}
	
	private function removeStartingEmptyLayers($rootId) {
		$childLayer = LayerElement::model()->findAllByAttributes(
				array('parent_id'=>$rootId));
			
		while (count($childLayer) == 1) {
			$rootId = $childLayer[0]->id;
			$childLayer = LayerElement::model()->findAllByAttributes(
					array('parent_id'=>$rootId));
		}
		
		return $rootId;
	}
		
}