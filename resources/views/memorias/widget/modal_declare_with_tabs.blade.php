<div>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active">
            <a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home">Declaracion</a>
        </li>
        <li role="presentation">
            <a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile">Informacion</a>
        </li>
        <li role="presentation">
            <a href="#wip" role="tab" id="wip-tab" data-toggle="tab" aria-controls="wip">Detalle Wip</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home" >
            @include('memorias.widget.declare',['smt'=>$smt,'wip'=>$smt->wip])
        </div>

        <div role="tabpanel" class="tab-pane" id="profile">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <blockquote>
                        @include('memorias.widget.meminfo',[$smt,'btnDeclarar'=>false,'btnDetalle'=>false])
                    </blockquote>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <blockquote>
                        @include('trazabilidad.widget.resumen_transacciones',['wip'=>$smt->wip])

                        @include('memorias.widget.ingenieria',[$ingenieria])
                    </blockquote>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="wip" >
            @include('trazabilidad.widget.detalle_transacciones',['wip'=>$smt->wip])
        </div>
    </div>
</div>

