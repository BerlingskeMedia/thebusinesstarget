/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */
 var app = angular.module('businesstarget', ['ngRoute', 'ngLodash', 'checklist-model', 'LocalStorageModule', 'ng.shims.placeholder'])
 .config(['$routeProvider',
   function($routeProvider) {
     $routeProvider.
       when('/step1', {
         templateUrl: 'step1.html',
         controller: 'SignupController'
       }).
       when('/step2', {
         templateUrl: 'step2.html',
         controller: 'SignupController'
       }).
       when('/step3', {
         templateUrl: 'step3.html',
         controller: 'SignupController'
       }).
       when('/thanks', {
         templateUrl: 'thanks.html',
       }).
       when('/edit/:id', {
         templateUrl: 'edit.html',
         controller: 'EditController'
       }).
       when('/login', {
         templateUrl: 'login.html',
         controller: 'LoginController'
       }).
       when('/unsubscribe/:id', {
         templateUrl: 'unsubscribe.html',
         controller: 'UnsubscribeController'
       }).
       otherwise({
         redirectTo: '/step1'
       });
 }])
 .config(function (localStorageServiceProvider) {
  localStorageServiceProvider
    .setPrefix('businesstarget');
  })
  .factory('apiService', ['$http', function($http) {
    var doRequest = function() {
      var interestUrl = "/wp-content/themes/businesstarget/api/interests.php";
      return $http({
        url: interestUrl
      });
    };
    return {
      interests: function() { return doRequest(); },
    };
  }])
  .controller('LoginController', ['$scope', '$location', '$http', function($scope, $location, $http) {

    var base_url = "/wp-content/themes/businesstarget/api/";
    var login_url = base_url + "send_login.php";
      $scope.submit_email = function(mail) {
          if (!$scope.loginform.$valid) {
            return;
           }
          $http.post(login_url, {mail:mail}).success(function(data, status, headers, config) {

          });
        $scope.displayThanks = true;
      };

   }])
   .controller('EditController', ['$scope', '$location', '$http', '$routeParams', '$q', 'lodash', function($scope, $location, $http, $routeParams, $q, lodash) {
     var _ = lodash;
     var base_url = "/wp-content/themes/businesstarget/api/";
     var me_url = base_url + "me.php";
     var interests_url = base_url + "interests.php";
     var edit_url = base_url + "edit.php";
     var my_id = $routeParams.id;
     var me_promise = $http.post(me_url, {id: my_id});
     var interests_promise = $http.get(interests_url);

     var sanify_interests = function(interests) {
       var i_arr = interests.split("|");
       return _.map(i_arr, function(elem){
        return elem.split(":")[0];
       });
     };
     var find_choice = function(my_choices, interests, parent_id) {
       var valid_choices_objects = _.filter(interests, _.matchesProperty('interesse_parent_id', parent_id));
       var valid_choices = _.map(valid_choices_objects, _.property('interesse_id'));
       return _.intersection(valid_choices, my_choices);
     };

     $q.all([me_promise, interests_promise]).then(function(result) {
       $scope.user = result[0].data;

       var data = result[1].data.root.data;
       interests = _.filter(data.rowset, function(elem) {
         return elem['@attributes'].id === 'interests';
       });
       $scope.interests = interests[0].row;

       $scope.my_interests = sanify_interests($scope.user.interests);

       $scope.user.occupation = _.first(find_choice($scope.my_interests, $scope.interests, "343"));
       $scope.user.industry = _.first(find_choice($scope.my_interests, $scope.interests, "310"));
       $scope.user.employees = _.first(find_choice($scope.my_interests, $scope.interests, "335"));
       $scope.user.managementlevel = _.first(find_choice($scope.my_interests, $scope.interests, "391"));
       $scope.user.buyer = _.first(find_choice($scope.my_interests, $scope.interests, "400"));
       $scope.user.traveller = _.first(find_choice($scope.my_interests, $scope.interests, "403"));
       $scope.user.car = _.first(find_choice($scope.my_interests, $scope.interests, "397"));

       $scope.user.businessinterests = find_choice($scope.my_interests, $scope.interests, "407");


     });

     $scope.submit_edit = function(user) {
       if (!$scope.edit.$valid) {
         return;
        }
        var ints = [];
        ints.push(user.occupation.toString());
        ints.push(user.industry.toString());

        ints.push(user.employees.toString());
        ints.push(user.managementlevel.toString());
        ints.push(user.buyer.toString());
        ints.push(user.traveller.toString());
        ints.push(user.car.toString());
        var new_interests = ints.concat(user.businessinterests);


        var turn_off = _.difference($scope.my_interests, new_interests);
        var turn_on = new_interests;

        var turn_off_string = _.map(turn_off, function(elem) {return elem+':0';}).join('|');
        var turn_on_string = _.map(turn_on, function(elem) {return elem+':1';}).join('|');
        var int_string = turn_off_string.length > 0 ? turn_off_string + '|' + turn_on_string : turn_on_string;

        $http.post(edit_url, {user_id: my_id, first_name: user.first_name, last_name: user.last_name, zip: user.zip, ints: int_string}).success(function(data, status, headers, config) {
          window.location = "/#thanks";
        });
     };
    }])

   .controller('UnsubscribeController', ['$scope', '$location', '$http', '$routeParams', function($scope, $location, $http, $routeParams) {
       var base_url = "/wp-content/themes/businesstarget/api/";
       var unsubscribe_url = base_url + "unsubscribe.php";
       var me_url = base_url + "me.php";
       $http.post(me_url, {id: $routeParams.id}).success(function(data, status, headers, config) {
         $scope.email = data.mail;
       });
       $scope.submit_unsubscribe = function(email) {
           var nlid = businesstarget_setup.nlid + ':0';
           $http.post(unsubscribe_url, {id: $routeParams.id, nlid: nlid, email: $scope.email}).success(function(data, status, headers, config) {
             $scope.displayThanks = true;
           });

       };
    }])
 .controller('SignupController', ['$scope', '$location', '$http', 'apiService', 'lodash', 'localStorageService', function($scope, $location, $http, apiService, lodash, localStorageService) {

    var interests;
    apiService.interests().success(function(response, status) {
      var _ = lodash;
      var data = response.root.data;
      interests = _.filter(data.rowset, function(elem) {
        return elem['@attributes'].id === 'interests';
      });
      interests = interests[0].row;
      localStorageService.set("interests", interests);
      $scope.interests = interests;
    });

    $scope.interests = interests;
    $scope.businessinterests = [];
    $scope.user = {};
    var base_url = "/wp-content/themes/businesstarget/api/";
    var signup_url = base_url + 'signup.php';
    var add_url = base_url + 'add.php';
     $scope.submit_step1 = function(user) {

       if (!$scope.formstep1.$valid) {
         return;
        }
       var nlid = businesstarget_setup.nlid;
       var lid = businesstarget_setup.lid;

       var ints = [];
       ints.push(user.industry.toString());
       ints.push(user.occupation.toString());
       $http.post(signup_url, {email:user.mail, lid: lid, nlids: nlid, intids:ints.join(","), zip: user.zip, first_name:user.first_name, last_name: user.last_name, bem_permission: user.bem_permission}).
        success(function(data, status, headers, config) {
          localStorageService.set("uid", data.uid);
          localStorageService.set("state", 2);
          localStorageService.set("user", user);
          localStorageService.set("mail", user.mail);
          $location.path("step2");
        });
     };
     $scope.submit_step2 = function(user) {
       if (!$scope.formstep2.$valid) {
         return;
        }
        var ints = [];
        var lid = businesstarget_setup.lid;
        ints.push(user.employees.toString());
        ints.push(user.managementlevel.toString());
        ints.push(user.buyer.toString());
        ints.push(user.traveller.toString());
        ints.push(user.car.toString());

        var mail = localStorageService.get("mail");
        $http.post(add_url, {email:mail, lid: lid, intids:ints.join(","),}).
         success(function(data, status, headers, config) {
           localStorageService.set("state", 3);
           $location.path("step3");
         });

     };
     $scope.submit_step3 = function(user) {
       if (!$scope.formstep3.$valid) {
         return;
        }
        var mail = localStorageService.get("mail");
        var intids = "";
        var lid = businesstarget_setup.lid;
        if (typeof user.businessinterests !== "undefined" && user.businessinterests.length > 0) {
          intids = user.businessinterests.join(",");
        }
        $http.post(add_url, {email:mail, lid: lid, intids:intids}).
         success(function(data, status, headers, config) {
           localStorageService.set("state", 1);
           $location.path("thanks");
         });

     };
  }]);

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        new WOW().init();
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
