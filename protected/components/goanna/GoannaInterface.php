<?php

class GoannaInterface extends CApplicationComponent {
	
	private $reporterPath = "127.0.0.1";
	private $reporterPort = 1197;
	
	private $mock = true;
	
	public function getProjects() {
		if (!$this->mock) {
			$response = $this->doRequest("/api/projects");
		} else {
			$response = GoannaMock::getMockProjects();
		}
		
		return $this->getResult($response);
	}
	
	public function getSnapshots($id) {
		if (!$this->mock) {
			$response = $this->doRequest("/api/project/" . $id);
		} else {
			$response = GoannaMock::getMockSnapshots();
		}
		
		return $this->getResult($response);
	}
	
	public function getSnapshot($projectId, $snapshotId) {
		if (!$this->mock) {
			$response = $this->doRequest("/api/project/" . $projectId .  "/snapshot/" . $snapshotId);
		} else {
			$response = GoannaMock::getMockSnapshot();
		}
		
		return $this->getResult($response);
	}
	
	public function getChildsWithMetrics($projectId, $snapshotId, $root) {
		if (!$this->mock) {
			$response = $this->doRequest("/api/project/" . $projectId .  "/snapshot/" . $snapshotId . "/metric/" . $root . "/children/");
		} else {
			$response = GoannaMock::getMockChildsWithMetrics();
		}
		
		return $this->getResult($response);
	}
	
	private function getResult($jsonResponse) {
		$phpArray = json_decode($jsonResponse, true);
		
		return $phpArray[results];
	}
	
	private function doRequest($url) {
		$host = $this->reporterPath;
		$port = $this->reporterPort;
		
		$timeout = 10;
		
		$fp = fsockopen($host, $port, $errno, $errstr, $timeout);
		if($fp)
		{
			$request = "GET ".$url." HTTP/1.1\r\n";
			$request.= "Host: ".$host."\r\n";
			$request.= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; de-DE; rv:1.7.12) Gecko/20050919 Firefox/1.0.7\r\n";
			$request.= "Connection: Close\r\n\r\n";
		
			fwrite($fp, $request);
			while (!feof($fp))
			{
				$response .= fgets($fp, 128);
			}
			fclose($fp);
			
			list($header, $data) = explode("\r\n\r\n", $response);
			
			return $data;
		}
		else
		{
			return "ERROR: ".$errstr;
		}
	}
}