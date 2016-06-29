<?php

namespace IAServer\Providers;

use IAServer\Exceptions\XmlExceptionHandler;
use IAServer\Http\Controllers\IAServer\Util;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('multiple_output', function($vars, $viewPath=null, $status = 200, array $header = array())
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
                    $output = json_encode($vars);

                    try
                    {
                        Util::array_to_xml(json_decode($output,true),$xml);
                        return Response::make($xml->asXML(), $status, $header);
                    } catch(\Exception $e)
                    {
                        throw new XmlExceptionHandler($output,$e->getMessage(),500);
                    }
                break;
                case 'view':
                    return view($viewPath, $vars);
                break;
                case 'json':
                default:
                    return response()->json($vars)->header('Access-Control-Allow-Origin', '*');
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
        require_once app_path('Http\Controllers\IAServer\Provider\IAHelpers.php');
    }
}
