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
       when('/edit/:ekstern_id', {
         templateUrl: 'edit.html',
         controller: 'EditController'
       }).
       when('/login', {
         templateUrl: 'login.html',
         controller: 'LoginController'
       }).
       otherwise({
         redirectTo: '/step1'
       });
 }])
  .factory('apiService', ['$http', function($http) {
    var BASE_URL = '/wp-content/themes/businesstarget/api/';
    var getInterests = function() {
      return $http.get(BASE_URL + 'interests.php').then(_httpUnwrapper);
    };
    var doubleoptkey;

    function get_doubleoptkey() {
      return doubleoptkey;
    }

    var getUser = function(ekstern_id) {
      return $http.post(BASE_URL + 'me.php', {ekstern_id: ekstern_id}).then(_httpUnwrapper);
    };

    var signup = function(user) {
      return $http.post(BASE_URL + 'signup.php', user)
      .then(_httpUnwrapper)
      .then(function(data) {
        doubleoptkey = data.double_opt_key;
        return data;
      });
    };

    var addInterests = function(user) {
      return $http.post(BASE_URL + 'add.php', user).then(_httpUnwrapper);
    };

    function _httpUnwrapper(response) {
      return response.data;
    }

    return {
      getInterests: getInterests,
      getUser: getUser,
      signup: signup,
      addInterests: addInterests,
      get_doubleoptkey: get_doubleoptkey
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
   .controller('EditController', ['$scope', '$location', '$http', '$routeParams', '$q', 'apiService', 'lodash', function($scope, $location, $http, $routeParams, $q, apiService, lodash) {
     var _ = lodash;
     var base_url = "/wp-content/themes/businesstarget/api/";
     var interests_url = base_url + "interests.php";
     var edit_url = base_url + "edit.php";
     var ekstern_id = $routeParams.ekstern_id;
     var my_user_promise = apiService.getUser($routeParams.ekstern_id);
     var interests_promise = apiService.getInterests();

     var find_choice = function(my_choices, interests, parent_id) {
       var valid_choices_objects = _.filter(interests, _.matchesProperty('interesse_parent_id', parseInt(parent_id)));
       var valid_choices = _.map(valid_choices_objects, _.property('interesse_id'));
       return _.intersection(valid_choices, my_choices);
     };

     function remove_TBT(tbt, my_interests) {
       var valid_choices = _.map(tbt, _.property('interesse_id'));
       var tbt_intersection = _.intersection(valid_choices, my_interests);
       var without_tbt = [];
       for (var i = 0; i < my_interests.length; i++) {
         var interest = my_interests[i];
         if (tbt_intersection.indexOf(interest) === -1) {
           without_tbt.push(interest);
         }
       }
       return without_tbt;
     }

     $q.all([my_user_promise, interests_promise]).then(function(result) {
        $scope.user = result[0];

        $scope.interests = result[1];
        var user = $scope.user;

       $scope.user.occupation = _.first(find_choice(user.interesser, $scope.interests, "343"));
       $scope.user.industry = _.first(find_choice(user.interesser, $scope.interests, "310"));
       $scope.user.employees = _.first(find_choice(user.interesser, $scope.interests, "335"));
       $scope.user.managementlevel = _.first(find_choice(user.interesser, $scope.interests, "391"));
       $scope.user.buyer = _.first(find_choice(user.interesser, $scope.interests, "400"));
       $scope.user.traveller = _.first(find_choice(user.interesser, $scope.interests, "403"));
       $scope.user.car = _.first(find_choice(user.interesser, $scope.interests, "397"));
       $scope.user.businessinterests = find_choice(user.interesser, $scope.interests, "407");

      $scope.user.interesser = remove_TBT($scope.interests, $scope.user.interesser);

     });

     $scope.submit_edit = function(user) {
       if (!$scope.edit.$valid) {
         return;
        }
        var interesser = _.clone(user.interesser);
        interesser.push(user.occupation);
        interesser.push(user.industry);
        interesser.push(user.employees);
        interesser.push(user.managementlevel);
        interesser.push(user.buyer);
        interesser.push(user.traveller);
        interesser.push(user.car);

        interesser = interesser.concat(user.businessinterests);


        var payload = {};
        payload.ekstern_id = user.ekstern_id;
        payload.fornavn = user.fornavn;
        payload.efternavn = user.efternavn;
        payload.nyhedsbreve = user.nyhedsbreve;
        payload.postnummer_dk = user.postnummer_dk;
        payload.email = user.email;
        payload.interesser = interesser;

        $http.post(edit_url, payload).success(function(data, status, headers, config) {
          window.location = "/#thanks";
        });
     };
    }])

 .controller('SignupController', ['$scope', '$location', '$http', 'apiService', 'lodash', function($scope, $location, $http, apiService, lodash) {
   if (!apiService.get_doubleoptkey()) {
     $location.path("step1");
   }

    apiService.getInterests().then(function(interests) {
      $scope.interests = interests;
    });

     $scope.submit_step1 = function(user) {

       if (!$scope.formstep1.$valid) {
         return;
        }
       var nlid = businesstarget_setup.nlid;
       var lid = businesstarget_setup.lid;

       var ints = [];
       ints.push(user.industry.toString());
       ints.push(user.occupation.toString());
       user.interesser = ints;

       var payload = lodash.clone(user);
       delete payload.occupation;
       delete payload.industry;

       apiService.signup(payload).then(function(response) {
         console.log(response);
         $scope.doubleoptkey = response.double_opt_key;
         console.log($scope.doubleoptkey);
         $location.path("step2");
       });
     };

     $scope.submit_step2 = function(user) {
       console.log($scope.doubleoptkey);

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


        $http.post(add_url, {email:mail, lid: lid, intids:ints.join(","),}).
         success(function(data, status, headers, config) {
           $location.path("step3");
         });

     };
     $scope.submit_step3 = function(user) {
       if (!$scope.formstep3.$valid) {
         return;
        }

        var intids = "";
        var lid = businesstarget_setup.lid;
        if (typeof user.businessinterests !== "undefined" && user.businessinterests.length > 0) {
          intids = user.businessinterests.join(",");
        }
        $http.post(add_url, {email:mail, lid: lid, intids:intids}).
         success(function(data, status, headers, config) {

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
})(jQuery);
