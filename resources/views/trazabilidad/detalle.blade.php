@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Trazabilidad')
@section('body')
    @if($modo=='serie')
    <h3>WIP_SERIE</h3>
    <div class="table-responsive"  style="height:550px;">
        <table id="wip" class="table table-bordered table-striped">
            <thead>
                <tr>
                    @foreach($serie_table['header'] as $d)
                        <th>{{ $d }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($serie_table['content'] as $d)
                    <tr>
                        @foreach($d as $key => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        @if($serie_trans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $serie_trans->links() !!}
        @endif
    @endif

    @if($modo=='history')
        <h3>WIP_SERIE HISTORY</h3>
        <div class="table-responsive"  style="height:550px;">
        <table id="wip" class="table table-bordered table-striped">
            <thead>
            <tr>
                @foreach($history_table['header'] as $d)
                    <th>{{ $d }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
                @foreach($history_table['content'] as $d)
                    <tr>
                        @foreach($d as $key => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @if($history_trans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $history_trans->links() !!}
        @endif
    @endif

    @include('trazabilidad.partial.footer')

    <script>
        $(function() {
            $("#wip").DataTable( {
                "paging":   false,
                "info":     false
            } );
        });
    </script>
@endsection