<?php

class FileController extends Controller
{
	public $layout='//layouts/column2';
	
	function getTime()
	{
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function actionIndex() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		$content = array();
		
		if (file_exists($filename) && is_readable ($filename)) {
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
	
}
