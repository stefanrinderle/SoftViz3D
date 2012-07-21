<?php

class FileController extends BaseController
{
	function getTime()
	{
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function actionIndex() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		$content = array();
		$depthString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$depth = "";
		
		if (file_exists($filename) && is_readable($filename)) {
			$fh = fopen($filename, "r");
			while (!feof($fh)) {
				$line = fgets($fh);
				
				if (strpos($line, "}")) {
					$depth = substr($depth, 0, strlen($depth) - strlen($depthString));
				}
				
				array_push($content, $depth . $line);
				
				if (strpos($line, "{")) {
					$depth .= $depthString;
				} 
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
	
	public function actionCheck() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		try {
			$result =Yii::app()->dotFileParser->parse($filename);
			
			Yii::app()->user->setFlash('success', 'File valid');
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Check failed: ' . $e->getMessage());
		}
		
		$this->render('check', array(result => $result, exception => $exception));
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
