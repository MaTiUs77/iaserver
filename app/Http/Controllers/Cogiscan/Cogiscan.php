<?php
namespace IAServer\Http\Controllers\Cogiscan;

ini_set("default_socket_timeout", 120);

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class Cogiscan extends Controller
{
    public $wdsl = "http://arus3ap07/cgsrpc/RPCServices?WSDL";

    public function __construct() {
        try
        {
            SoapWrapper::add(function ($service) {
                $service
                    ->name('cogiscan')
                    ->wsdl($this->wdsl);
            });
        } catch(\Exception $ex)
        {

        }
    }

    /**
     * Ejecuta los metodos sin necesidad de definir las rutas
     *
     * @return mixed
     */
    public function dynamicCommands()
    {
        $output = array();

        $command = Request::segment(2);
        $attributes= array_except( Request::segments() , [0,1]);
        if(method_exists($this,$command))
        {
            $attributes = $this->normalizeAttributes($attributes);

            $items = call_user_func_array(array($this, $command), $attributes);
            $output = $items;
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
        $class = 'IAServer\Http\Controllers\Cogiscan\Cogiscan';

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

    /////////////////////////////////////////////////////////////////////////////
    //                          COGISCAN WEBSERVICES
    /////////////////////////////////////////////////////////////////////////////

    public function queryItem($itemId)
    {
        $param = [
            'queryItem',
            '<Parameters>
                <Parameter name="itemId">'.$itemId.'</Parameter>
            </Parameters>
        '];

        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }
    public function releaseProduct($modelo,$route,$op,$sn1,$sn2)
    {

           $param = ['releaseProduct',
                  '<Parameters>
                      <Parameter name="assembly">'.$modelo.'</Parameter>
                      <Parameter name="route">'.$route.'</Parameter>
                      <Parameter name="batchId">'.$op.'</Parameter>
                      <Parameter name="maxReleaseQty">15</Parameter>
                       <Extensions>
                        <ProductGroup>
                          <Product location="A1">'.$sn1.'</Product>
                          <Product location="A2">'.$sn2.'</Product>
                        </ProductGroup>
                      </Extensions>
                    </Parameters>'];

        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

    public function startOperation($productId, $operationName, $toolId="")
    {
        if(!empty($toolId)) {
            $toolId = '<Parameter name="toolId">'.$toolId.'</Parameter>';
        }
        $param = [
            'startOperation',
            '<Parameters>
                <Parameter name="productId">'.$productId.'</Parameter>
                <Parameter name="operationName">'.$operationName.'</Parameter>
                '.$toolId.'
            </Parameters>
        '];
        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

    public function endOperation($productId, $operationName, $toolId="")
    {
        if(!empty($toolId)) {
            $toolId = '<Parameter name="toolId">'.$toolId.'</Parameter>';
        }
        $param = [
            'endOperation',
            '<Parameters>
                <Parameter name="productId">'.$productId.'</Parameter>
                <Parameter name="operationName">'.$operationName.'</Parameter>
                '.$toolId.'
            </Parameters>
        '];
        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

    public function completeProduct($productId)
    {
        $param = [
            'completeProduct',
            '<Parameters>
                <Parameter name="productId">'.$productId.'</Parameter>
            </Parameters>
        '];
        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

    public function setProcessStepStatus($itemInstanceId, $processStepId, $status, $codeDefect = "", $description = "")
    {
        $param = [
            'setProcessStepStatus',
            '<Parameters>
                <Extensions>
                    <ProcessStepStatus
                        itemInstanceId="'.$itemInstanceId.'"
                        processStepId="'.$processStepId.'"
                        status="'.$status.'"
                    >
                    <Indictment
                    indictmentId="'.$codeDefect.'"
                    description="'.$description.'"/>
                    </ProcessStepStatus>
                </Extensions>
            </Parameters>
        '];

        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

    public function load($contentId,$containerId,$location="",$unloadPrevious=true,$deletePrevious=false) {
        $param = [
            'load',
            '<Parameters>
                <Parameter name="contentId">'.$contentId.'</Parameter>
                <Parameter name="containerId">'.$containerId.'</Parameter>
                <Parameter name="location">'.$location.'</Parameter>
                <Parameter name="unloadPrevious">'.$unloadPrevious.'</Parameter>
                <Parameter name="deletePrevious">'.$deletePrevious.'</Parameter>
            </Parameters>'
        ];
        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

    public function unload($contentId,$containerId,$location="",$deleteContent=false)
    {
        $param = [
            'unload',
            '<Parameters>
                <Parameter name="contentId">' . $contentId . '</Parameter>
                <Parameter name="containerId">' . $containerId . '</Parameter>
                <Parameter name="location">' . $location . '</Parameter>
                <Parameter name="deleteContent">' . $deleteContent . '</Parameter>
            </Parameters>'
        ];
        $cmd = new CogiscanCommand($param);
        return $cmd->result();
    }

    public function updateQuantity($itemId,$quantity)
    {
        $param = [
            'updateQuantity',
            '<Parameters>
                <Parameter name="itemId">' . $itemId. '</Parameter>
                <Parameter name="quantity">' . $quantity. '</Parameter>
            </Parameters>'
        ];
        $cmd = new CogiscanCommand($param);
        return $cmd->result();
    }

    public function initializeRawMaterial($itemId,$partNumber,$msd,$containerType,$quantity) {
        $param = [
            'initializeRawMaterial',
            '<Parameters>
                <Parameter name="itemId">'. $itemId . '</Parameter>
                <Parameter name="partNumber">' . $partNumber . '</Parameter>
                <Parameter name="msLevel">'.$msd.'</Parameter>
                <Parameter name="containerType">'.$containerType.'</Parameter>
                <Parameter name="supplierId">Default</Parameter>
                <Parameter name="quantity">' . $quantity . '</Parameter>
                <Parameter name="tagId"></Parameter>
                <Parameter name="tagModel"></Parameter>
            </Parameters>'
        ];
        $cmd = new CogiscanCommand($param);
        return $cmd->result();
    }

    public function initializeTooling($itemId,$itemType,$partNumber) {
        $param = [
            'initializeTooling',
            '<Parameters>
            <Parameter name="itemId">'.$itemId.'</Parameter>
            <Parameter name="itemType">'.$itemType.'</Parameter>
            <Parameter name="partNumber">'.$partNumber.'</Parameter>
            </Parameters>'
        ];
        $cmd = new CogiscanCommand($param);
        return $cmd->result();
    }

    public function getContents($itemId) {
        $param = [
            'getContents',
            '<Parameters>
                <Parameter name ="containerId">'.$itemId.'</Parameter>
            </Parameters>'
        ];
        $cmd = new CogiscanCommand($param);
        return $cmd->result();
    }

    public function getPrinter($printerName) {
        return $this->getContents($printerName);
    }

    public function getPrinters($desde="",$hasta="") {
        $output = array();
        if(!$desde) { $desde = 1;}
        if(!$hasta) { $hasta = 20;}
        for ($i = $desde; $i <= $hasta; $i++) {
            $name = 'DEKL' . $i;
            $output[$name] = $this->getContents($name);
        }
        return $output;
    }

    public function getConsBatchId($batchId,$desde){
        $param = [
            'getProductionAndConsumptionData',
            '<Parameters>
                <Parameter name="fromDate">'.$desde.'</Parameter>
                <Parameter name="batchId">'.$batchId.'</Parameter>
                <Parameter name="groupBy">recipe,toolId</Parameter>
                <Parameter name="include">rawMatPN</Parameter>
            </Parameters>'
        ];
        $cmd = new  CogiscanCommand($param);
        return $cmd->result();
    }

    public function getConsRawMat($rawMatPN,$desde){
        $param = [
            'getProductionAndConsumptionData',
            '<Parameters>
                <Parameter name="fromDate">'.$desde.'</Parameter>
                <Parameter name="rawMatPN">'.$rawMatPN.'</Parameter>
                <Parameter name="groupBy">recipe,batchId,toolId</Parameter>
            </Parameters>'
        ];
        $cmd = new  CogiscanCommand($param);
        return $cmd->result();
    }

    public function getProductionCount($batchId,$desde,$wipData=false){
        $param = [
            'getProductionAndConsumptionData',
            '<Parameters>
                <Parameter name="fromDate">'.$desde.'</Parameter>
                <Parameter name="batchId">'.$batchId.'</Parameter>
                <Parameter name="groupBy">toolId,recipe</Parameter>
            </Parameters>'
        ];
        $cmd = new  CogiscanCommand($param);
        $cogiscan = $cmd->result();

        // Obtiene datos WIP de la OP, solo si se define el 3er parametro con cualquier valor
        if($wipData)
        {
            $wip = new Wip();
            $cogiscan['Wip'] = $wip->findOp($batchId);
        }

        return $cogiscan;
    }

    public function aoicollectorPassed($panelBarcode)
    {
        $release = $this->releaseProduct('modelotest','routemain','OP-123456',$panelBarcode,'0001007942');
        $startSmt = $this->startOperation($panelBarcode,'SMT');
        $endSmt = $this->endOperation($panelBarcode,'SMT');
        $start = $this->startOperation($panelBarcode,'AOI');
        $set = $this->setProcessStepStatus($panelBarcode,'AOI','PASSED');
        $end = $this->endOperation($panelBarcode,'AOI');

        return compact('release','startSmt','endSmt','start','set','end');
    }

    public function aoicollectorFailed($panelBarcode,$ipcError,$descripcion='Default')
    {
        $start = $this->startOperation($panelBarcode,'AOI');
        sleep(1);
        $set = $this->setProcessStepStatus($panelBarcode,'AOI','FAILED',$ipcError,$descripcion);
        sleep(1);
        $end = $this->endOperation($panelBarcode,'AOI');

        return compact('start','set','end');
    }
}
