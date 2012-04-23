<?php

class DotLayout extends CApplicationComponent
{
	
	public function layout($sourceFilePath, $destinationFilePath) {
		// Load source file
		$error = "";
		if (!file_exists($sourceFilePath)) {
			$error = "Error loading source dot file: " + $sourceFilePath;
		} else {
			// Create adot file
			$command  = '/usr/local/bin/dot';
			$command .= ' -o ' . escapeshellarg($destinationFilePath);
			$command .= ' '  . escapeshellarg($sourceFilePath);
			$command .= ' 2>&1';

			exec($command, $msg, $return_val);

			if ($return_val != 0) {
				$error = "failure creating adot file: " . $msg[0];
			}
		}

		return $error;
	}
}