<?php

class FileController extends Controller
{
	function getTime()
	{
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function actionIndex() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		$content = array();
		
		if (file_exists($filename) && is_readable($filename)) {
			$fh = fopen($filename, "r");
			while (!feof($fh)) {
				$line = fgets($fh);
				array_push($content, $line);
			}
			# Processing
			fclose($fh);
		} else {
			// show error message
		}
		
		$this->render('index', array(
                filename => $filename,
				fileContent => $content
		));
	}
	
	public function actionEdit() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		$editform = new CForm('application.views.file._editForm', new EditFileForm());
		
		if ($editform->submitted('submit') && $editform->validate()) {
			$content = $editform->model->content;
		
			$fileName = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
			if (file_put_contents($fileName, $content)) {
				$this->redirect(array('file/index')); 
			} else {
				Yii::app()->user->setFlash('error', 'Error on saving file. ' . $fileName . ' must be writable by the server.');
			}
			
		}
		
		$content = "";
	
		if (file_exists($filename) && is_writable($filename)) {
			$fh = fopen($filename, "r");
			while (!feof($fh)) {
				$line = fgets($fh);
				$content .= $line;
			}
			# Processing
			fclose($fh);
		} else {
			Yii::app()->user->setFlash('error', 'Target file not writeable. ' . $fileName . ' must be writable by the server.');
		}
		
		$editform->model->content = $content;
		
		$this->render('edit', array(
			form => $editform
		));
		}
	
}
