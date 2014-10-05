<?php

class BaseProjectFileController extends BaseController {
	
	protected function saveFile($projectId, $fileContent) {
		$project = Project::model()->findByPk($projectId);
			
		if ($project && $project->userId == Yii::app()->user->getId()) {
			$project->saveNewFileString($fileContent);
	
			if ($project->inputTreeRootId) {
				InputTreeElement::model()->deleteAllByAttributes(array('projectId' => $project->id));
				InputDependency::model()->deleteAllByAttributes(array('projectId' => $project->id));
				
				$rootId = $this->loadFiletoDb($project, $project->getFileStringArray());
			} else {
				$rootId = $this->loadFiletoDb($project, $project->getFileStringArray());
			}
			
			Yii::app()->dependencyExpander->execute($projectId);
			
			$project->inputTreeRootId = $rootId;
			$project->save();
			
			Yii::app()->user->setFlash('success', 'File successful imported.');
		} else {
			Yii::app()->user->setFlash('error', 'File could not be loaded.');
		}
	}
	
	private function loadFiletoDb(Project $project, $content) {
		try {
			$parseResult = Yii::app()->dotArrayParser->parse($content);
			$parseResult = $this->removeEmptyStartLayers($parseResult);
	
			return Yii::app()->dotArrayToDB->save($project->id, $parseResult);
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