<?php
namespace IAServer\Http\Controllers\Cogiscan;

ini_set("default_socket_timeout", 120);

use DebugBar\DebugBar;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class CogiscanDB2 extends Controller
{
    /**
     * Ejecuta los metodos sin necesidad de definir las rutas
     *
     * @return mixed
     */
    public function dynamicCommands(){
        $command = Request::segment(3);
        $attributes= array_except( Request::segments() , [0,1,2]);

        $output = "";
        if(method_exists($this,$command))
        {
            $attributes = $this->normalizeAttributes($attributes);
            $output = call_user_func_array(array($this, $command), $attributes);
        } else
        {
            $output = array('error'=>'El metodo no existe');
        }

        return Response::multiple_output($output);
    }

    private function normalizeAttributes($attributes)
    {
        foreach($attributes as $index => $att)
        {
            $attributes[$index] = urldecode($att);
        }

        return $attributes;
    }

    private function services()
    {
        $class = 'IAServer\Http\Controllers\Cogiscan\CogiscanDB2';

        $array1 = get_class_methods($class);
        if($parent_class = get_parent_class($class)){
            $array2 = get_class_methods($parent_class);
            $array3 = array_diff($array1, $array2);
        }else{
            $array3 = $array1;
        }

        $output = array();

        foreach($array3 as $method)
        {
            $r = new \ReflectionMethod($class, $method);
            $params = $r->getParameters();

            $modifier = head(\Reflection::getModifierNames($r->getModifiers()));

            if($modifier=='public')
            {
                foreach ($params as $param) {
                    $output[$method][] = $param->getName() . (($param->isOptional() == true) ? ' (opcional) ' : '');
                }
            }
        }

        return $output;
    }
    private function query($db, $query) {
        $cmd =  '%cd%\db2wrapper\DB2Wrapper.exe '.$db.' '.$query;
		
        $output = array();
        exec($cmd,$output);
        $output = implode("",$output);

        return self::toJson($output);
    }
    private function toJson($xml)
    {
        try {
            $arr_xml = (array) simplexml_load_string($xml);
            $string_json = json_encode($arr_xml);
            $json_normalized = str_replace('@','',$string_json);
            $arr_json = json_decode($json_normalized,true);

            return $arr_json;
        } catch(\Exception $ex )
        {
             dd($ex->getMessage(),$xml);
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    //                          COGISCAN WEBSERVICES
    /////////////////////////////////////////////////////////////////////////////
    public function itemInfoByKey($itemKey)
    {
        $db = 'CGS';
        $query = "select * from CGS.ITEM_INFO where ITEM_KEY = $itemKey limit 1";

        return self::query($db,$query);
    }

    public function itemInfoByToolKey($toolKey)
    {
        $db = 'CGS';
        $query = "select b.BATCH_ID,p.* from CGSPCM.PRODUCT p inner join CGSPCM.PRODUCT_BATCH b on b.BATCH_KEY = p.BATCH_KEY where p.TOOL_KEY = $toolKey limit 1";
        return self::query($db,$query);
    }

    public function itemInfoByComplex($itemId)
    {
        $db = 'CGS';
        $query = "select b.BATCH_ID,p.* from CGSPCM.PRODUCT p inner join CGSPCM.PRODUCT_BATCH b on b.BATCH_KEY = p.BATCH_KEY inner join CGS.ITEM i on i.ITEM_ID = '".$itemId."' where p.TOOL_KEY = i.ITEM_KEY limit 1";
        return self::query($db,$query);
    }

    public function itemInfoById($itemId)
    {
        $db = 'CGS';
        $query = "select * from CGS.ITEM_INFO where CGS.ITEM_INFO.ITEM_ID = '$itemId' limit 1 ";

        return self::query($db,$query);
    }

    public function itemInfoInQuarentine($quarentine='Y')
    {
        $db = 'CGS';
        $query = "select * from CGS.ITEM_INFO where QUARANTINE_LOCKED = '$quarentine' ";

        return self::query($db,$query);
    }

    public function partNumber($partNumber,$itemId)
    {
        $db = 'CGS';
        $query = "select * from CGS.PART_NUMBER p left join CGS.ITEM i on i.ITEM_ID = '$itemId'  where p.PART_NUMBER = '$partNumber' ";

        return self::query($db,$query);
    }

    public function posicionesPorUbicacion($partNumber,$itemId)
    {
        $db = 'CGS';
        $query = "select * from CGSLSC.TOOL_SETUP where PRODUCT_PN_KEY = (select PART_NUMBER_KEY from CGS.PART_NUMBER where PART_NUMBER = '$partNumber' limit 1) and TOOL_KEY = (select ITEM_KEY from CGS.ITEM where ITEM_ID = '$itemId' limit 1) ";

        return self::query($db,$query);
    }

    public function itemByComplex($itemId)
    {
        $db = 'CGS';
        $query = "select * from CGS.ITEM where ITEM_ID = '$itemId'";

        return self::query($db,$query);
    }

    public function toolSetup($PRODUCT_PN_KEY,$TOOL_KEY)
    {
        $db = 'CGS';
        $query = "select * from CGSLSC.TOOL_SETUP where PRODUCT_PN_KEY = $PRODUCT_PN_KEY and TOOL_KEY = $TOOL_KEY";

        return self::query($db,$query);
    }

    public function opByPartNumber($partNumber)
    {
        $db = 'CGS';
        $query = "select * from CGSPCM.product_batch where product_pn_key = (select part_number_key from CGS.part_number where part_number = '$partNumber')";

        return self::query($db,$query);
    }
}
