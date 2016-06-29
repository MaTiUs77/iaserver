<div class="input-group input-group-lg" ng-controller="promptController">
    <span class="input-group-addon">{{ Request::has('label') ? Request::get('label') : 'Codigo' }}</span>
    <input ng-enter="promptEnter(promptInput)" ng-model="promptInput" type="text" class="form-control focus" placeholder="{{ Request::has('holder') ? Request::get('holder') : 'Ingresar codigo' }}">
</div>
