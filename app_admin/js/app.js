var App = angular

.module('foyerApp', ['ngRoute'])

.constant('CONFIG', {
  'API_URL': 'http://192.168.1.173/Foyer/api/'
})

//gestion des routes
.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  //$locationProvider.html5Mode(true);
  $routeProvider

  .when('/', {
    templateUrl : 'pages/home.html',
    controller  : 'mainController'
  })

  .when('/product', {
    templateUrl : 'pages/product.html',
    controller  : 'productController'
  })

  .when('/product/edit/:id_product', {
    templateUrl : 'pages/product-form.html',
    controller  : 'productFormController'
  })

  .when('/product/add', {
    templateUrl : 'pages/product-form.html',
    controller  : 'productFormController'
  })

  .when('/command', {
    templateUrl : 'pages/command.html',
    controller  : 'commandController'
  })

  .when('/command/edit/:id_commande', {
    templateUrl : 'pages/command-form.html',
    controller  : 'commandFormController'
  })

  .when('/command/add', {
    templateUrl : 'pages/command-form.html',
    controller  : 'commandFormController'
  })

  .otherwise({
    redirectTo: '/'
  });
}])
//ajout de headers des requetes
.config(['$httpProvider', function($httpProvider) {
  $httpProvider.defaults.transformRequest = function(data){
    console.log(data);
    if (data === undefined) {
      return data;
    }
    console.log(jQuery.param(data));
    return jQuery.param(data);
  };
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
  $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
  console.log($httpProvider.defaults.headers);
}])

//ajout de la directive ng-confirm-click
.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          if(message && !window.confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    };
  }
])

.controller('mainController', function($scope) {

})

//controlleur de la page product
.controller('productController', function($scope, $http, $window, CONFIG) {
  //recuperation des produits
  $http.get(CONFIG.API_URL+'product/').success(function(data){
    $scope.products = data;
    $scope.loaded = true;
  });
  //suppression d'un produit
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'product/'+item).success(function(data) {
      $window.location.reload();
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
})

//controlleur du form de produit
.controller('productFormController', function($scope, $http, CONFIG, $routeParams, $document, $location) {
  $scope.product = {};
  $scope.action = 'add';

  //recuperation du produit
  if ($routeParams.id_product) {
    $http.get(CONFIG.API_URL+'product/id_product/'+$routeParams.id_product).success(function(data){
      $scope.product = data;
      $scope.action = 'edit';
    });
  }

  //Post du formulaire
  $scope.submitForm = function(item, event) {
    //edit
    if($scope.action == 'edit'){
      console.log($scope.product);
      $http.put(CONFIG.API_URL+'product/'+$scope.product.id_product, $scope.product).success(function(data) {
        console.log(data);
        $location.path('#product');
      }).error(function(data) {
        console.log(data);
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'product/', $scope.product).success(function(data) {
        $location.path('#product');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
  //reinitialisation du form
  $scope.reinitialiser = function(item, event) {
    $scope.product = null;
  };
  //suppression du produit
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'product/'+$scope.product.id_product).success(function(data) {
      $location.path('#product');
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
})

//controller de commande
.controller('commandController', function($scope, $http, $window, CONFIG) {
  //recuperation des commandes
  $http.get(CONFIG.API_URL+'command/').success(function(data){
    $scope.commands = data;
    $scope.loaded = true;
  });

  //suppression d'une commande
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'command/'+item).success(function(data) {
      $window.location.reload();
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
})

//controller de form commande
.controller('commandFormController', function($scope, $http, CONFIG, $routeParams, $document, $location) {
  $scope.command = {};
  $scope.action = 'add';

  //recuperation des users
  $http.get(CONFIG.API_URL+'user/').success(function(data){
    $scope.users = data;
  });

  //recuperation de la commande
  if ($routeParams.id_commande) {
    $http.get(CONFIG.API_URL+'command/id_commande/'+$routeParams.id_commande).success(function(data){
      $scope.command = data;
      $scope.action = 'edit';
    });
  }

  //Post du formulaire
  $scope.submitForm = function(item, event) {
    //edit
    if($scope.action == 'edit'){
      $http.put(CONFIG.API_URL+'command/'+$scope.command.id_commande, $scope.command).success(function(data) {
        console.log(data);
        $location.path('#command');
      }).error(function(data) {
        console.log(data);
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
    //ajout
    else{
      $http.post(CONFIG.API_URL+'command/', $scope.command).success(function(data) {
        $location.path('#command');
      }).error(function(data) {
        $scope.alert = data;
        $document.scrollTop(0, 250);
      });
    }
  };
  //reinitialisation du form
  $scope.reinitialiser = function(item, event) {
    $scope.command = null;
  };
  //supression de la commande
  $scope.delete = function(item, event) {
    $http.delete(CONFIG.API_URL+'command/'+$scope.command.id_commande).success(function(data) {
      $location.path('#command');
    }).error(function(data) {
      $scope.alert = data;
      $document.scrollTop(0, 250);
    });
  };
});