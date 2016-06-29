<?php
namespace IAServer\Http\Controllers\Cogiscan;

use Exception;
use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
use IAServer\Exceptions\CogiscanExceptionHandler;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class CogiscanCommand extends Controller
{
	private $serviceResponseXml;
	private $serviceResponseArray;
	
	public function __construct($command) {
		$this->serviceResponseArray = $this->executeCommand($command);
	}
	
	public function result() {
		return $this->serviceResponseArray;
	}

	private function executeCommand($command) {

		try
		{
			SoapWrapper::service('cogiscan', function ($service) use($command) {
				$this->serviceResponseXml = $service->call('executeCommand', $command );
			});

			$arr_xml = (array) simplexml_load_string($this->serviceResponseXml);
			$string_json = json_encode($arr_xml);
			$json_normalized = str_replace('@','',$string_json);
			$arr_json = json_decode($json_normalized,true);

			return $arr_json;
		} catch(Exception $e)
		{
			$output = serialize($command);
			throw new CogiscanExceptionHandler($output,$e->getMessage(),500);
		}
	}
}
