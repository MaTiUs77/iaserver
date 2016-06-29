<?php

namespace IAServer\Http\Controllers\MonitorOp;

use Carbon\Carbon;
use IAServer\Http\Controllers\IAServer\Filter;
use IAServer\Http\Controllers\IAServer\Util;
use IAServer\Http\Controllers\P2i\Model\Carga;

use IAServer\Http\Controllers\P2i\Model\General;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Model\XXEWipOt;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MonitorOpView extends Controller
{
    public function index($modelo)
    {
        $wip = new GetWipOtInfo();
        $resume = $wip->infoOp($modelo);

        $output = compact('resume');
        return Response::multiple_output($output,'monitorop.op_habilitadas');
    }
    public function indexInsaut()
    {
        $wip = new GetWipOtInfo();
        $resume = $wip->infoOpInsaut();

        $output = compact('resume');
        return Response::multiple_output($output,'monitorop.op_habilitadas');

    }
}
