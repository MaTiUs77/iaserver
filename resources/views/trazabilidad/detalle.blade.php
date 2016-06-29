@extends('angular')
@section('ng','app')
@section('title','Trazabilidad')
@section('body')
    <style>
        /* .table-striped tbody tr:nth-child(2n+1) > td {
             background-color: #F7F7F7;
         }

         .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
             background-color: #FFFDD1;
         }*/

        .table tbody tr td {
            text-align: center;

        }

        thead.panel th {
            background-color: #2D6CA2;
            color: white;
            text-align: center;
        }
    </style>

    <div>
        <div>
            @if($modo=='serie')
                <h3>WIP_SERIE</h3>
                <div>
                    <table class="table table-bordered table-striped">
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

                    @if($serie_trans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {!! $serie_trans->links() !!}
                    @endif
                </div>
            @endif

            @if($modo=='history')
                <h3>WIP_SERIE HISTORY</h3>
                <div>
                    <table class="table table-bordered table-striped">
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

                    @if($history_trans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {!! $history_trans->links() !!}
                    @endif
                </div>
            @endif
        </div>
    </div>

    @include('trazabilidad.partial.footer')
@endsection