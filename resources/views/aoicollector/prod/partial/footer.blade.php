@include('iaserver.common.footer')

{!! IAScript('assets/bootstrap/js/bootstrap-switch.js') !!}

{!! IAScript('assets/adonis/ws.min.js') !!}
{!! IAScript('vendor/aoicollector/prod/prod.js') !!}

<script type="text/javascript">
    $(function(){
        var scope = angular.element($("[ng-controller='prodController']")).scope();
        var scopeHeader = angular.element($("[ng-controller='prodHeaderController']")).scope();

        shortcut.add("F2",function() {
            scopeHeader.promptAoiSet('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de aoi']) }}');
        });

        shortcut.add("F3",function() {
            scopeHeader.promptStockerSet('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de stocker']) }}');
        });

        shortcut.add("F4",function() {
            scopeHeader.promptStockerAddPanel('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de panel']) }}');
        });

        shortcut.add("F9",function() {
            scopeHeader.promptStockerRemovePanel('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de panel']) }}');
        });

        if(!scope.configprod.aoibarcode) {
            scopeHeader.promptAoiSet('{{ route('iaserver.forms.prompt') }}');
        };
    });
</script>
