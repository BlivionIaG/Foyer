
angular.module('starter',[
  'ionic',
  'ionic.service.core',
  'ionic.service.analytics',
  'ngSanitize',
  'ngLocale',
  'starter.controllers',
  'starter.services',
  'starter.directives',
  'starter.filters'
  ])

.constant('CONFIG', {
  API_URL: 'http://isenclub.fr/foyer/api/'
})

.config(function($stateProvider, $urlRouterProvider, $httpProvider) {


  $stateProvider.state('identification', {
    url: '/identification',
    templateUrl: 'templates/identification.html',
    controller: 'identificationController'
  });

  $stateProvider.state('tab', {
    url: '/tab',
    abstract: true,
    templateUrl: 'templates/tabs.html'
  })

  .state('tab.home', {
    url: '/home',
    views: {
      'tab-home': {
        templateUrl: 'templates/home.html',
        controller: 'homeController'
      }
    }
  })

  .state('tab.products', {
    url: '/products',
    views: {
      'tab-products': {
        templateUrl: 'templates/products.html',
        controller: 'productsController'
      }
    }
  })

  .state('tab.product', {
    url: '/products/:productId',
    views: {
      'tab-products': {
        templateUrl: 'templates/product.html',
        controller: 'productController'
      }
    }
  })
  
  .state('tab.commands', {
    url: '/commands',
    views: {
      'tab-commands': {
        templateUrl: 'templates/commands.html',
        controller: 'commandsController'
      }
    }
  })

  .state('tab.command', {
    url: '/commands/:commandId',
    views: {
      'tab-commands': {
        templateUrl: 'templates/command.html',
        controller: 'commandController'
      }
    }
  })

  .state('tab.cart', {
    url: '/cart',
    views: {
      'tab-cart': {
        templateUrl: 'templates/cart.html'
      }
    }
  })

  .state('tab.cart-form', {
    url: '/cart-form',
    views: {
      'tab-cart': {
        templateUrl: 'templates/cart-form.html',
        controller: 'cartController'
      }
    }
  });

  $urlRouterProvider.otherwise('/tab/home');

  //Serialization de la requete
  $httpProvider.defaults.transformRequest = [function(data){
    var param = function(obj){
      var query = '';
      var name, value, fullSubName, subName, subValue, innerObj, i;
      
      for(name in obj){
        value = obj[name];
        
        if(value instanceof Array){
          for(i=0; i<value.length; ++i){
            subValue = value[i];
            fullSubName = name + '[' + i + ']';
            innerObj = {};
            innerObj[fullSubName] = subValue;
            query += param(innerObj) + '&';
          }
        }else if(value instanceof Object){
          for(subName in value){
            subValue = value[subName];
            fullSubName = name + '[' + subName + ']';
            innerObj = {};
            innerObj[fullSubName] = subValue;
            query += param(innerObj) + '&';
          }
        }
        else if(value !== undefined && value !== null){
          query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }
      }
      return query.length ? query.substr(0, query.length - 1) : query;
    };
    return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
  }];

  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
  $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
  
})

.run(function($ionicPlatform, $ionicAnalytics) {
  $ionicPlatform.ready(function() {

    $ionicAnalytics.register();

    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      StatusBar.styleDefault();
    }
  });
});


