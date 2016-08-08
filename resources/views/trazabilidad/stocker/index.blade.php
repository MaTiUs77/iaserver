@extends('angular')
@section('ng','app')
@section('title','Trazabilidad - Stocker')
@section('body')
    <div ng-controller="trazaController">

        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;">
                    @include('trazabilidad.partial.header')

                    @{{ modal }}
                    <div style="padding: 5px;">
                        <!-- will be used to show any messages -->
                        @if (Session::has('message'))
                            <div class="alert alert-info">{{ Session::get('message') }}</div>
                        @endif

                        @include('trazabilidad.stocker.stocker_widget')
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @include('trazabilidad.partial.footer')
@endsection