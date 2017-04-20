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

class RestDB2CGS extends Rest
{
    public function __construct($host="10.30.10.90",$port=1337,$url="/cogiscan/query")
    {
        parent::__construct($host,$port);
        $this->setUrl($url);
    }

    public function paginate($query,$perPage=20,$currentPage=null){
        if($currentPage==null) {
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
        }

        $total = $this->count($query);
        if($total != null)
        {
            $pagination = $this->pagination($total->TOTAL,$perPage,$currentPage);

            $query = "$query LIMIT $pagination->limit OFFSET $pagination->offset";

            $queryResult = $this->get($query);


            return new LengthAwarePaginator($queryResult, $pagination->total, $pagination->perPage);
        } else
        {
            return ['error' => 'Total devolvio NULL'];
        }
    }

    public function count($query)
    {
        $query = strtolower($query);
        $query = str_replace("\n",'',$query);
        $query = str_replace("\r",'',$query);

        $expl = explode('order',$query);
        $expl = collect($expl);

        $query = $expl->first();

        $patron = '/select (.*) from (.*) (order (.*))? /';
        $replace = 'select count(*) as total from $2';
        $countQuery = preg_replace($patron, $replace, $query);

        $result = $this->consume(['sql'=>$countQuery]);

        $result = collect($result);

        return $result->first();
    }

    public function get($query) {
        $result = $this->consume(['sql'=>$query]);
        return $result;
    }

    private function pagination($total,$perPage,$currentPage=null)
    {
        $pagination = new \stdClass();
        $pagination->total = $total;
        $pagination->perPage = $perPage;
        $pagination->currentPage = $currentPage;

        $pages = ceil($pagination->total / $pagination->perPage);

        $pagination->lastPage = $pages;

        $pagination->limit = $pagination->perPage;
        if($pagination->currentPage<=1)
        {
            $pagination->offset = 0;
        } else
        {
            $pagination->offset = ($pagination->currentPage - 1 )* $pagination->perPage;
        }

        return $pagination;
    }

}
