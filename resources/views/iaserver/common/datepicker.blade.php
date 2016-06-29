@if(isset($from_session) && isset($to_session))
    <form method="GET" action="{{ $route }}">
        <div class="row">
            <div class="col-sm-2 col-md-2 col-lg-2" ng-init="from_session = '{{ $from_session }}';" ng-controller="datapickerController">
                    <input type="text" name="from_session" placeholder="Desde fecha" class="form-control" ng-model="from_session" datepicker-popup="dd-MM-yyyy" is-open="datepickerOpened" ng-required="true" show-button-bar="false" ng-click="open($event)"/>
            </div>

            <div class="col-sm-2 col-md-2 col-lg-2" ng-init="to_session = '{{ $to_session }}';" ng-controller="datapickerController">
                    <input type="text" name="to_session" placeholder="Hasta fecha" class="form-control" ng-model="to_session" datepicker-popup="dd-MM-yyyy" is-open="datepickerOpened" ng-required="true" show-button-bar="false" ng-click="open($event)"/>
            </div>

            <div class="col-sm-2 col-md-2 col-lg-2">
                <span>
                    <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Filtrar</button>
                </span>
            </div>
        </div>
    </form>


@else
    <!-- DATEPICKER -->
    <div ng-init="date_session = '{{ isset($custom_session)  ? Session::get($custom_session) : $date_session }}';" ng-controller="datapickerController">
        <form method="GET" action="{{ $route }}">
            <div class="input-group" style="width: 320px;">
                <input type="text" name="{{ isset($custom_session)  ? $custom_session : 'date_session' }}" placeholder="Seleccionar fecha" class="form-control" ng-model="date_session" datepicker-popup="dd-MM-yyyy" is-open="datepickerOpened" ng-required="true" show-button-bar="false" ng-click="open($event)"/>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> {{ isset($button)  ? $button : 'Filtrar' }}</button>
                </span>
            </div>
        </form>
    </div>
    <!-- END DATEPICKER -->

@endif
