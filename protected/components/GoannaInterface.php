<?php

class GoannaInterface extends CApplicationComponent
{
	
	private $reporterPath = "tvoicer-beta.appspot.com";
	private $reporterUrl = "/api/3/tvoices?max_results=5&order_by=date";
	private $reporterPort = 80;
	
	public function getData() {
		$response = $this->doRequest();
		
		list($header, $data) = explode("\r\n\r\n", $response);
		$responseArray = json_decode($data, true);
		
		return $responseArray;
	}
	
	private function doRequest() {
		$host = $this->reporterPath;
		$port = $this->reporterPort;
		$url = $this->reporterUrl;
		
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
				$data .= fgets($fp, 128);
			}
			fclose($fp);
			
			return $data;
		}
		else
		{
			return "ERROR: ".$errstr;
		}
	}
}