<div class="row">
    <div class="col-xs-12">
        <h3>
            Unidades {{ $allstocker->sum('paneles') * $allstocker->first()->bloques }}
        </h3>
    </div>

    @foreach($allstocker as $stocker)
        <div class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <span class="label pull-right" ng-class="{{ $stocker->id_stocker_route }} == 1 ? 'label-success' : 'label-primary'" style="padding:5px;">{{ $stocker->paneles * $stocker->bloques }}</span>
                    {{ $stocker->barcode }}

                    <div style="padding-top: 5px;border-top:1px solid #e2e2e2;">
                        <div class="label" ng-class="{{ $stocker->id_stocker_route }} == 1 ? 'label-success' : 'label-primary'">{{ $stocker->name }}</div>
                    </div>

                    {{ $stocker->linea }}

                </div>
                <div style="color: #727272;font-size: 10px;text-align: center;background-color: #e3e3e3;">
                    {{ $stocker->created_at }}
                </div>
            </div>
        </div>
    @endforeach
</div>