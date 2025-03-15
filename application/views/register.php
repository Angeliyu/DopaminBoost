

<div class="content-page" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <div class="content">
        <style>
            .angularFormValidationHelper .ng-invalid {
                border: 2px solid pink;
            }

            .angularFormValidationHelper .ng-valid {}
        </style>
        <!-- Start Content-->
        <div class="container-fluid" style="margin-bottom: 5%;">

            <div class="row align-items-center">
                <div>
                    <br/>
                    <h2 class="page-title" style="text-align: center;"> Register </h2>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">


                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2 justify-content-center d-flex">

                                        <form class="form-horizontal angularFormValidationHelper" role="form"
                                            name="myForm" ng-hide="errorAlert ">
                                            <fieldset>

                                                
                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">User Name
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="text" name="register_username" id="register_username"
                                                                ng-model="register_username" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">Email
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="text" name="register_email" id="register_email"
                                                                ng-model="register_email" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">Password
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="password" name="register_password" id="register_password"
                                                                ng-model="register_password" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">Re-type Password
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="password" name="register_retype_password" id="register_retype_password"
                                                                ng-model="register_retype_password" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <small style="color: red;"><i>*Safety questions used for Forgot Password</i></small>

                                                <div class="mb-2 row" style="width: 300%;">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">Safety Question 1
                                                        </label>

                                                        <p style="font-weight: bold;">Which song do you most often play on repeat?</p>

                                                        <div class="" style="width: 440px;">
                                                            <input type="text" name="register_sq1" id="register_sq1"
                                                                ng-model="register_sq1" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row" style="width: 300%;">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">Safety Question 2
                                                        </label>

                                                        <p style="font-weight: bold;">Where is your hometown?</p>

                                                        <div class="" style="width: 440px;">
                                                            <input type="text" name="register_sq2" id="register_sq2"
                                                                ng-model="register_sq2" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column" style="width: 440px; margin-top: 5%;">
                                                    <button class="btn btn-success mb-2 w-100" style="font-weight: bold;" ng-click="submitData()" >
                                                        REGISTER
                                                    </button>
                                                </div>

                                            </fieldset>
                                        </form>
                                    </div>
                                    

                                </div>
                                <!-- end row -->
                            </div>
                        </div> <!-- end card -->
                    </div><!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

            <div class="row align-items-center">
                <div>
                    <br/>
                    <h4 class="page-title" style="text-align: center;"><a href="<?= base_url('') ?>" style="color: black; text-decoration: underline;"> Login</a> | <a href="" style="color: black; text-decoration: underline;"> Forgot Password </a> </h4>  
                </div>
            </div>
        </div> <!-- content -->

        <script>
            var app = angular.module("myApp",[]);
           
            app.controller("myCtrl", function($scope, $http, $timeout) {

                $scope.register_email = "";
                $scope.register_password = "";
                $scope.register_retype_password = "";
                $scope.register_sq1 = "";
                $scope.register_sq2 = "";
                
                $scope.submitData = function() {

                    if ($scope.register_password != $scope.register_retype_password) {
                        // empty the retype password field
                        $scope.register_retype_password = "";
                        // give alert
                        alert("Your Passwords Do Not Match");
                    }

                    var tobeSubmit = {  
                        username: $scope.register_username,
                        email: $scope.register_email,  
                        password: $scope.register_password,
                        register_sq1: $scope.register_sq1,
                        register_sq2: $scope.register_sq2,
                    };
                    

                    console.log("tobeSubmit", tobeSubmit);
                    loadingshow();
                    $http.post("<?= base_url('api/register') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            console.log(response.data);
                            $scope.id = response.data.id;
                            console.log('id', $scope.id);
                            location.href = '<?= base_url('profile') ?>/' + $scope.id;
                            
                        } else {
                            console.log("no ok", response.data);
                            alert(response.data.result);
                        }

                    }, function(response) {
                        loadinghide();
                        console.log("failed", response.data);
                        alert(response);

                    });

                }

            }).directive('ngConfirmClick', [
                function() {
                    return {
                        link: function(scope, element, attr) {
                            var msg = attr.ngConfirmClick || "Are you sure want to execute this action?";
                            var clickAction = attr.confirmedClick;
                            element.bind('click', function(event) {
                                if (window.confirm(msg)) {
                                    scope.$eval(clickAction)
                                }
                            });
                        }
                    };
                }
            ]);
        </script>