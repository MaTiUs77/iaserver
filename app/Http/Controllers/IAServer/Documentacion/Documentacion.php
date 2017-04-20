<?php

namespace IAServer\Http\Controllers\IAServer\Documentacion;

use IAServer\Http\Controllers\Controller;
use IAServer\Http\Requests;

class Documentacion extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $xml = new \SimpleXMLElement("../docs/IAServer_xml/structure.xml", null, true);

        $namespaces = [];

        foreach ($xml->file as $file)
        {
            $class = (array)$file->class;
            $attributes = null;

            if (isset($class['@attributes']['namespace'])) {
                $attributes = $class['@attributes']['namespace'];
                $namespaces[str_replace('IAServer\\Http\\Controllers\\','',$attributes)][] = $class;
            }
        }
        ksort($namespaces);

        $output = compact('namespaces');

        return view('iaserver.documentacion',$output);
    }
}

