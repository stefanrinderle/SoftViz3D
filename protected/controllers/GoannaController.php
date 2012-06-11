<?php

class GoannaController extends BaseController
{
	public function actionIndex() {
		
		$projects = Yii::app()->goannaInterface->getProjects();
		
		$this->render('index', array('projects' => $projects));
	}

	public function actionSnapshots($id) {
		$project = Yii::app()->goannaInterface->getSnapshots($id);
		
		$this->render('snapshots', array('project' => $project));
	}
	
	public function actionImportSnapshot($projectId, $snapshotId) {
		$snapshot = Yii::app()->goannaInterface->getSnapshot($projectId, $snapshotId);
		
		$result = Yii::app()->goannaSnapshotToDotParser->parseToFile($snapshot[locations_tree]);
		
		if ($result) {
			Yii::app()->user->setFlash('success', 'Snapshot successful imported.');
		} else {
			Yii::app()->user->setFlash('error', 'Snapshot not valid.');
		}
		
		$this->render('//import/index', array());
	}
	
	public function actionDumpSnapshot() {
		$snapshot = Yii::app()->goannaInterface->getChildsWithMetrics(1, 1, 1);
		
		$this->render('//dumpArray', array("dumpArray" => $snapshot));
	}
}
