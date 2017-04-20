<?php

namespace IAServer\Providers;

use IAServer\Exceptions\XmlExceptionHandler;
use IAServer\Http\Controllers\IAServer\Util;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('multiple', function($output, $viewPath=null, $status = 200, array $header = array())
        {
            $type = '';

            if($viewPath!=null) {
                $type = 'view';
            }

            $all = Input::all();
            if(array_key_exists('json',$all)) {
                $type = 'json';
            }

            if(array_key_exists('xml',$all)) {
                $type = 'xml';
            }

            switch($type) {
                case 'xml':
                    $xml = new \SimpleXMLElement('<service/>');
                    $header['Content-Type'] = 'application/xml';

                    // Verifiar dartos en POSTMAN aplicando filtro y limitando resultados
                    //$vars = collect($vars['reparacion'])->take(139);
                    //return $vars;

                    $prepareOutput = json_encode($output);

                    try
                    {
                        $decode = json_decode($prepareOutput  ,true);
                        Util::array_to_xml($decode,$xml);
                        return Response::make($xml->asXML(), $status, $header);
                    } catch(\Exception $e)
                    {
                        throw new XmlExceptionHandler($prepareOutput,$e->getMessage(),500);
                    }
                break;
                case 'view':
                    return view($viewPath, $output);
                break;
                case 'json':
                default:
                    return response()->json($output)->header('Access-Control-Allow-Origin', '*');
                break;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
