<?php

function IAScript($path) {
    return '<script type="text/javascript" src="'.asset($path).'"></script>';
}

function IAStyle($path)
{
    return '<link href="'.asset($path).'" media="all" rel="stylesheet" type="text/css" />';
}

function IABtnDropDown($route,$model)
{
    $routeId = str_replace(".","",$route).$model->id;
    $output = '
    <div class="btn-group">
      <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-gear" aria-hidden="true"></i> Admin <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li>
            <a href="'.route($route.'.edit',$model->id).'"><i class="fa fa-edit" aria-hidden="true"></i> Editar</a>
        </li>
        <li role="separator" class="divider"></li>
        <li>
            <script>function Click'.$routeId.'() { if(confirm("Confirma eliminacion?")) { $("#Destroy'.$routeId.'").submit(); }}</script>
            <form method="POST" id="Destroy'.$routeId.'"  action="'.route($route.'.destroy',$model->id).'">
                <input name="_method" type="hidden" value="DELETE" class="">
            </form>
            <a href="#" onclick="Click'.$routeId.'()"><i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar</a>
        </li>
      </ul>
    </div>
    ';

    return $output;
}

function IABtnDelete($route,$style='btn-sm btn-danger',$icon='glyphicon-remove')
{
    $output = '
    <form method="POST" action="'.$route.'">
        <input name="_method" type="hidden" value="DELETE" class="">
        <button type="submit" class="btn '.$style.'" tooltip="Borrar"><span class="glyphicon '.$icon.'"></span></button>
    </form>';

    return $output;
}

function multipleSort($collection,$criteria=array())
{
    $makeComparer = function($criteria) {
        $comparer = function ($first, $second) use ($criteria) {
            foreach ($criteria as $key => $orderType) {
                // normalize sort direction
                $orderType = strtolower($orderType);
                if ($first[$key] < $second[$key]) {
                    return $orderType === "asc" ? -1 : 1;
                } else if ($first[$key] > $second[$key]) {
                    return $orderType === "asc" ? 1 : -1;
                }
            }
            // all elements were equal
            return 0;
        };
        return $comparer;
    };

    $comparer = $makeComparer($criteria);
    return $collection->sort($comparer);
}

function hasRole($role)
{
    if(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->hasRole($role))
    {
        return true;
    } else
    {
        return false;
    }
}

function isAdmin()
{
    return hasRole('admin');
}
function isInventoryOper()
{
    return hasRole('inventario_operador');
}

function dateToEs($date)
{
    return \IAServer\Http\Controllers\IAServer\Util::dateToEs($date);
}