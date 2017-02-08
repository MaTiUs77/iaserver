<?php
namespace IAServer\Http\Controllers\Node;

ini_set("default_socket_timeout", 120);

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

/*
 * Esta clase se encarga de consumir un ApiRest y retorna los resultados en JSON
 */
class Rest extends Controller
{
    protected $restHost = '';
    protected $restPort = 1337;
    protected $restUrl = '';

    public function __construct($host="",$port="")
    {
        if(!empty($host)) {
            $this->restHost = $host;
        }

        if(!empty($port)) {
            $this->restPort = $port;
        }
    }

    public function setUrl($newUrl) {
        $this->restUrl = $newUrl;
    }

    /*
     * $params = ['page' => 1, 'op' => 'OP-123456']
     */
    public function consume($params=array())
    {
        $client = new Client();
        $apiRest = "http://$this->restHost:$this->restPort".$this->restUrl;
        try {
            $res = $client->request('GET', $apiRest, [
                'query' => $params
            ]);

            $content = json_decode($res->getBody()->getContents());

            return $content;

        } catch (RequestException $err) {

            return ['error'=>$err->getMessage()];
        }
    }
}
