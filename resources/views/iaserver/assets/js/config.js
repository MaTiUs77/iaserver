var app = angular.module("app", ['ngRoute','chieffancypants.loadingBar','ngAnimate','ui.bootstrap','angular-toasty','cfp.loadingBar']);

app.config(['cfpLoadingBarProvider',function(cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
}]);

app.config(['toastyConfigProvider', function(toastyConfigProvider) {
    toastyConfigProvider.setConfig({
        sound: true,
        shake: false,
        showClose: false,
        clickToClose: true,
        theme: "bootstrap"
    });
}]);