<?php

class BaseX3dController extends BaseController {
	
	protected function loadFiletoDb($includeEdges = true) {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		try {
			/* reset database */
			TreeElement::model()->deleteAll();
			EdgeElement::model()->deleteAll();
			
			$parseResult = Yii::app()->dotFileParser->parse($filename, $includeEdges);
			
			return Yii::app()->dotArrayToDB->save($parseResult);
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
			//TODO render another layout file and exit
		}
	}
	
}