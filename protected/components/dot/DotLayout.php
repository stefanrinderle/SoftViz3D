<?php

class DotLayout extends CApplicationComponent
{
	public function layout($sourceFilePath) {
		// Create adot file
		$command  = '/usr/local/bin/dot';
		$command .=  ' -Kneato';
		//$command .=  ' -s1';
		//$command .= ' -o ' . escapeshellarg($destinationFilePath);
		$command .= ' '  . escapeshellarg($sourceFilePath);
		//$command .= ' 2>&1';
			
		// output dot file in $msg
		exec($command, $msg, $return_val);

		// strange problem with trailing } at the end of the output
		array_pop($msg);
		
		return $msg;
	}
}