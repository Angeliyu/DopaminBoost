

<div class="content-page" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <div class="content">
        <style>
            .angularFormValidationHelper .ng-invalid {
                border: 2px solid pink;
            }

            .angularFormValidationHelper .ng-valid {}
        </style>
        <!-- Start Content-->
        <div class="container-fluid">

            
            <div class="row align-items-center">
                <div>
                    <br/>
                    <h2 class="page-title" style="text-align: center;"> Login </h2>
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
                                            name="myForm">
                                            
                                            <div ng-show="errorAlert" class="alert alert-danger alert-dismissable">
                                                {{ errorMessage }}
                                            </div>

                                            <fieldset>

                                                <div class="mb-2 row">
                                                    <div class="col-12">
                                                        <label for="name"
                                                            class="form-label">Email
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="text" name="login_email" id="login_email"
                                                                ng-model="login_email" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-12">
                                                        <label for="name"
                                                            class="form-label">Password
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="password" name="login_password" id="login_password"
                                                                ng-model="login_password" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column" style="width: 440px; margin-top: 5%;">
                                                    <button class="btn btn-success mb-2 w-100" style="font-weight: bold;" ng-click="submitData()" >
                                                        LOGIN
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
                    <h4 class="page-title" style="text-align: center;"><a href="<?= base_url('register') ?>" style="color: black; text-decoration: underline;"> Register</a> | <a href="<?= base_url('forgot_password_validation') ?>" style="color: black; text-decoration: underline;"> Forgot Password </a> </h4>  
                </div>
            </div>
        </div> <!-- content -->

        <script>

            var app = angular.module("myApp",[]);
           
            app.controller("myCtrl", function($scope, $http, $timeout) {

                $scope.login_email = "";
                $scope.login_password = "";
                
                $scope.submitData = function() {

                    var tobeSubmit = {  
                        email: $scope.login_email,  
                        password: $scope.login_password  
                    };

                    console.log("tobeSubmit", tobeSubmit);
                    loadingshow();
                    $http.post("<?= base_url('api/login') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            console.log(response);
                            // console.log("id", response.data.id.id);
                            $scope.id = response.data.id.id;
                            $scope.token = response.data.id.token;
                            console.log("response", response);

                            $scope.login_email = "";
                            $scope.login_password = "";

                            location.href = '<?= base_url('profile') ?>/' + $scope.id + "/" + $scope.token;
                        } else {
                            console.log("Error", response.data);
                            $scope.errorAlert = true;
                            alert(response.data.message);
                            $scope.errorMessage = response.data.message;
                        }

                    }, function(response) {
                        loadinghide();
                        console.log("Request Failed", response);
                        alert("Something went wrong. Please try again.");
                    });

                }

                $scope.backList = function() {
                    location.href = '<?= base_url('admin_userList') ?>';
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