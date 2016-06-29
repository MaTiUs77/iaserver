<?php
namespace IAServer\Http\Controllers\Trazabilidad\Declaracion;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WipExtAssemblyTrx extends Controller
{
    public static function declarar($organization,$op,$semielaborado,$cantidad, $source_transaction_id, $source_line_id )
    {
        $query = "
            INSERT INTO [New_Traza_Material].[dbo].[xx_wip_ext_assembly_trx]
               (
               [SOURCE_CODE]
               ,[SOURCE_TRANSACTION_ID]
               ,[TRANSACTION_TYPE]
               ,[SOURCE_LINE_ID]
               ,[ORGANIZATION_CODE]
               ,[ITEM_CODE]
               ,[JOB_NUMBER]
               ,[QUANTITY]
               )
         VALUES
               (
               'TRAZA_MEMORIAS'
               ,".$source_transaction_id."
               ,'WIP COMPLETION'
               ,".$source_line_id."
               ,'".$organization."'
               ,'".$semielaborado."'
               ,'".$op."'
               ,".$cantidad."
               );
            ";

        $sql = DB::connection('traza_dev')->statement($query);
        if($sql)
        {
            $sql = DB::connection('traza_dev')->select("SELECT @@IDENTITY as lastid");
        }
        return head($sql);
    }
}
