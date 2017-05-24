@extends('adminlte/theme')
@section('ng','app')
@section('title','Aoicollector - Produccion')
@section('mini',false)
@section('body')
@section('bodytag','ng-controller="scannerController" ng-keydown="scannerEvent($event)"')
    <style>
        .f15 {
            font-size: 15px;
        }

        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }

        .animate-show {
            opacity: 1;
        }
        .animate-show.ng-hide {
            opacity: 0;
        }

        .animate-show.ng-hide-add.ng-hide-add-active,
        .animate-show.ng-hide-remove.ng-hide-remove-active {
            -webkit-transition: all linear 0.2s;
            transition: all linear 0.2s;
        }
    </style>

    <div id="prodController" ng-controller="prodController">
        @include('aoicollector.prod.partial.header')
        <div class="container">
            <div ng-show="configprod.aoibarcode">
                <div class="well"  ng-show="aoiService.error">
                    <b>ATENCION:</b> @{{ aoiService.error }}
                </div>
            </div>

            <div class="row" ng-hide="aoiService.error">
                @include('aoicollector.prod.widget.production_angular')
            </div>
        </div>

        <code class="pull-right">@{{ socketserver }}</code>
    </div>

    @include('aoicollector.prod.partial.footer')

@endsection
