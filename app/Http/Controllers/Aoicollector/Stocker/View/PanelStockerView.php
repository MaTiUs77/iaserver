<?php
namespace IAServer\Http\Controllers\Aoicollector\Stocker\View;

use IAServer\Http\Controllers\Aoicollector\Stocker\Controller\PanelStockerController;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Response;

class PanelStockerView extends PanelStockerController
{
    public function view_addPanel($panelBarcode,$aoibarcode)
    {
        $output = $this->addPanel($panelBarcode,$aoibarcode);
        return Response::multiple_output($output);
    }

    public function view_addPanelManual($panelBarcode,$aoibarcode)
    {
        $output = $this->addPanelManual($panelBarcode,$aoibarcode);
        return Response::multiple_output($output);
    }

    public function view_removePanel($panelBarcode)
    {
        $output = $this->removePanel($panelBarcode);
        return Response::multiple_output($output);
    }
}
