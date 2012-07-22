<?php

class GoannaController extends BaseController
{
	public function actionIndex() {
		$projects = Yii::app()->goannaInterface->getProjects();

		$this->render('index', array('projects' => $projects));
	}

	public function actionSnapshots($id) {
		try {
			$project = Yii::app()->goannaInterface->getSnapshots($id);
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', $e->getMessage());
		}
		
		$project['snapshots'] = array_reverse($project['snapshots']);
		
		$this->render('snapshots', array('project' => $project));
	}
	
	public function actionImportSnapshot($projectId, $snapshotId, $importDependencies) {
		$warnings = Yii::app()->goannaInterface->getSnapshotWarnings($projectId, $snapshotId);
		
// 		$this->render('//dumpArray', array(dumpArray => $warnings));
		
		if ($importDependencies) {
			$dependencies = Yii::app()->goannaInterface->getLatestDependencies($projectId);
			print_r("Number of dependencies: " . count($dependencies));
		}
	
		$result = Yii::app()->goannaSnapshotToDotParser->parseToFile($warnings[locations_tree], $dependencies);
	
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
