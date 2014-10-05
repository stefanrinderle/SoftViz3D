<?php

class DotCommand extends CApplicationComponent
{
	public function execute($sourceFilePath, $layout) {
		// Create adot file
		$command  = Yii::app()->params['dotFolder'];
 		$command .=  ' -K' . $layout;
		$command .= ' '  . escapeshellarg($sourceFilePath);
			
		// output dot file in $msg
		exec($command, $msg, $return_val);
		
		// strange problem with trailing } at the end of the output
		array_pop($msg);
		
		if ($return_val != 0) {
			throw new Exception("dot command error: " . $msg);
		}
		
		return $msg;
	}
}