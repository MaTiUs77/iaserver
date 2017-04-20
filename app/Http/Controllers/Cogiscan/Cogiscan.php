<?php
namespace IAServer\Http\Controllers\Cogiscan;

ini_set("default_socket_timeout", 120);

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

/**
 * Webservice para comunicacion WSDL de cogiscan tiene un default_socket_timeout 120seg
 *
 * Lista de metodos disponibles {@url http://arushap34/iaserver/public/cogiscan/services}
 *
 * @author Matias Flores <matius77@gmail.com>
 * @author Diego Maidana <dmaidana@newsan.com.ar>
 * @author Jose Maria Cassarotto <jmcasarotto@newsan.com.ar>
 *
 * @package IAServer\Http\Controllers\Cogiscan
 */
class Cogiscan extends Controller
{

    /**
     * @var string Ruta al servidor WSDL de Cogiscan
     */
    public $wdsl = "http://arus3ap07/cgsrpc/RPCServices?WSDL";

    /**
     * Genera una conexion al service WSDL de Cogiscan mediante el controlador SoapWrapper
     */
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
     * @return Response Response::multiple()
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

        return Response::multiple($output);
    }

    /**
     * @param array $uriSegments Segmentos de la url, se ejecutan como parametros del metodo, definido en el primer segmento
     *
     * @ignore
     * @return array
     */
    protected function normalizeAttributes($uriSegments=array())
    {
        foreach($uriSegments as $index => $att) {
            $uriSegments[$index] = urldecode($att);
        }
        return $uriSegments;
    }

    /**
     * Lista de metodos disponibles por el webservice, se debe consultar por URL {@url http://arushap34/iaserver/public/cogiscan/services}
     * @return array
     */
    protected function services()
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


    /**
     * @param string $itemId
     * @return mixed Retorna JSON o XML
     */
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

    public function getLowLevel()
    {
        $param = [
                'getComponentLowLevelWarnings',
                '<Parameters></Parameters>
        '];

        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

	public function releaseProduct($modelo,$route,$op,$qty,$panelBarcode,$sn=array())
    {
        $sns = "";
        foreach($sn as $index => $n) {
            $index++;
            $sns .= '
            <Product location="A'.$index.'">'.$n.'</Product>
            ';
        }

       $param = ['releaseProduct',
          '<Parameters>
              <Parameter name="assembly">'.$modelo.'</Parameter>
              <Parameter name="route">'.$route.'</Parameter>
              <Parameter name="batchId">'.$op.'</Parameter>
              <Parameter name="maxReleaseQty">'.$qty.'</Parameter>
               <Extensions>
                <ProductGroup barcode="'.$panelBarcode.'">
                    '.$sns.'
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

    public function unload($contentId,$containerId="",$location="",$deleteContent=false)
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

    /**
     * Obtiene lista de printers
     *
     * @param string $desde Opcional
     * @param string $hasta Opcional
     * @return array
     *
     */
    public function getPrinters($desde="", $hasta="") {
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

                <Parameter name="byRawMatId">true</Parameter>
                <Parameter name="groupBy">recipe,batchId,toolId</Parameter>
                <Parameter name="include">rawMatPN</Parameter>
            </Parameters>'
        ];
        $cmd = new  CogiscanCommand($param);
        return $cmd->result();
    }

    public function getConsRawMat($rawMatPN,$desde,$op){

        $param = [
            'getProductionAndConsumptionData',
            '<Parameters>
                <Parameter name="fromDate">'.$desde.'</Parameter>
                <Parameter name="rawMatPn">'.$rawMatPN.'</Parameter>
                <Parameter name="batchId">'.$op.'</Parameter>
                <Parameter name="byRawMatId">true</Parameter>
                <Parameter name="groupBy">recipe,batchId,toolId</Parameter>
            </Parameters>'
        ];
        $cmd = new  CogiscanCommand($param);
        return $cmd->result();
    }

    public function getConsRawMatByLine($fromDate,$toDate,$line,$rawMat=""){
        if ($rawMat!=="")
        {
            $param = [
                'getProductionAndConsumptionData',
                '<Parameters>
                <Parameter name="fromDate">'.$fromDate.'</Parameter>
                <Parameter name="toDate">'.$toDate.'</Parameter>
                <Parameter name="lineName">'.$line.'</Parameter>
                <Parameter name="rawMatPN">'.$rawMat.'</Parameter>
                <Parameter name="groupBy">batchId</Parameter>
                <Parameter name="include">rawMatPN</Parameter>
            </Parameters>'
            ];
        }
        else
        {
            $param = [
                'getProductionAndConsumptionData',
                '<Parameters>
                <Parameter name="fromDate">'.$fromDate.'</Parameter>
                <Parameter name="toDate">'.$toDate.'</Parameter>
                <Parameter name="lineName">'.$line.'</Parameter>
                <Parameter name="groupBy">batchId</Parameter>
                <Parameter name="include">rawMatPN</Parameter>
            </Parameters>'
            ];
        }

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
        //$release = $this->releaseProduct('modelotest','routemain','OP-123456',$panelBarcode,'0001007942');
        //$startSmt = $this->startOperation($panelBarcode,'SMT');
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

    public function queryPartNumberProduct($partNumber) {
        $param = [
            'queryPartNumber',
            '<Parameters>
                <Parameter name="itemTypeClass">Product</Parameter>
                <Parameter name="partNumber">'.$partNumber.'</Parameter>
            </Parameters>
        '];

        $cmd = new CogiscanCommand($param);

        return $cmd->result();
    }

}
