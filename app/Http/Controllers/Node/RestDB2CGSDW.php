<?php
namespace IAServer\Http\Controllers\Node;

ini_set("default_socket_timeout", 120);

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

/*
 * USO
 *
    $rest = new RestDB2();
    $result = $rest->paginate($query,50);
    //$result = $rest->get($query);
*/

class RestDB2CGSDW extends RestDB2CGS
{
    public function __construct($host="10.30.10.90",$port=1337,$url="/cogiscan/query")
    {
        parent::__construct($host,$port);
        $this->setUrl($url);
    }

    public function get($query) {
        $result = $this->consume(['sql'=>$query,'db'=>'cgsdw']);
        return $result;
    }
}
