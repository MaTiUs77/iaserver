<?php

namespace IAServer\Http\Controllers\IAServer;

use Carbon\Carbon;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class Util extends Controller
{
    /**
     * Exporta un array a csv
     *
     * @param $input_array
     * @param $output_file_name
     * @param $delimiter
     */
    public static function convert_to_csv($input_array, $output_file_name, $delimiter,$forcedownload=true,$makehead=true)
    {
        if($makehead)
        {
            array_unshift($input_array, array_keys(head($input_array)));
        }

        /** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
        $f = fopen('php://memory', 'w');
        /** loop through array  */
        foreach ($input_array as $line) {
            /** default php csv handler **/
            fputcsv($f, $line, $delimiter);
        }
        /** rewrind the "file" with the csv lines **/
        fseek($f, 0);
        if($forcedownload)
        {
            /** modify header to be downloadable csv file **/
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
            /** Send file to browser for download */
            fpassthru($f);
        } else
        {
            return $f;
        }
    }

    public static function sqlToTable ($sql) {
        $header = array_keys((array)head($sql));

        $content = array();
        foreach($sql as $c)
        {
            $content[] = array_values( (array) $c);
        }
        return compact('header','content');
    }

    public static function eloquentToTable ($eloquent,$havePaginator=false) {

        $header = array();
        $columnas = array();
        $content = array();
        $items = array();

        if($havePaginator)
        {
            $items = $eloquent->items();
            if(count($items)>0)
            {
                $columnas = head($items)->getAttributes();
            }
        } else
        {
            $columnas = array_keys((array)head($eloquent));
            $items = $eloquent;
        }

        $header = array_keys($columnas);

        foreach($items as $c)
        {
            $content[] = array_values( $c->getAttributes());
        }

        return compact('header','content');
    }

    public static function dateToEn($es_date_string)
    {
        return Carbon::createFromFormat('d-m-Y', $es_date_string )->format('Y-m-d');
    }

    public static function dateToEs($en_date_string)
    {
        return Carbon::createFromFormat('Y-m-d', $en_date_string )->format('d-m-Y');
    }

    public static function array_to_xml($the_array, &$xml_obj) {
        foreach($the_array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_obj->addChild("$key");
                    Util::array_to_xml($value, $subnode);
                }
                else{
                    $subnode = $xml_obj->addChild("item_$key");
                    Util::array_to_xml($value, $subnode);
                }
            }
            else {
                $xml_obj->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

    public static function doMerge($collection1,$collection2,$where=array())
    {
        // Adjunta a WipSerie el periodo localizado en WipSerieHistory
        if(count($collection2)>0) {
            foreach ($collection2 as $item) {
                $update = $collection1;

                foreach($where as $w)
                {
                    $update = $update->where($w, $item->getAttribute($w));
                }

                $update = $update->first();

                // Existe el periodo en WipSerie?
                if (isset($update)) {
                    // Sumar
                    $update->total += $item->total;
                } else {
                    // Crear
                    $arr1[] = $item;
                }
            }
        }

        return $collection1;
    }

}

