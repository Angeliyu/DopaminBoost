

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
                    <h2 class="page-title" style="text-align: center;"> Reset Password </h2>
                    <br/>
                    <h2 class="page-title" style="text-align: center;"> Your Email: <br/> {{email}} </h2>

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
                                                    <div class="col-md-6">
                                                        <label for="name" class="form-label" >Your New Password
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="password" name="register_password" id="register_password"
                                                                ng-model="reset_password" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-md-6">
                                                        <label for="name"
                                                            class="form-label">Re-type New Password
                                                        </label>

                                                        <div class="" style="width: 440px;">
                                                            <input type="password" name="register_retype_password" id="register_retype_password"
                                                                ng-model="reset_retype_password" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column" style="width: 440px; margin-top: 5%;">
                                                    <button class="btn btn-success mb-2 w-100" style="font-weight: bold;" ng-click="submitData()" >
                                                        RESET PASSWORD
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

                $scope.email = "<?= isset($email) && !empty($email) ? $email : '' ?>";
                console.log('email', $scope.email);

                $scope.reset_password = "";
                $scope.reset_retype_password = "";
                
                $scope.submitData = function() {

                    // Validation rules
                    const validationFields = [
                        { field: $scope.reset_password, message: "Please Enter Your New Password" },
                        { field: $scope.reset_retype_password, message: "Please Re-type Your Password" },
                    ];

                    // Check for missing fields
                    for (let i = 0; i < validationFields.length; i++) {
                        if (!validationFields[i].field) { // Check for null, undefined, and empty string
                            alert(validationFields[i].message);
                            return;
                        }
                    }

                    if ($scope.reset_password != $scope.reset_retype_password) {
                        // empty the retype password field
                        $scope.reset_retype_password = "";
                        // give alert
                        alert("Your Passwords Do Not Match");
                        return;
                    }

                    var tobeSubmit = {  
                        email: $scope.email,  
                        password: $scope.reset_password
                    };
                    
                    console.log("tobeSubmit", tobeSubmit);
                    loadingshow();
                    $http.post("<?= base_url('api/reset_password') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            console.log(response.data);
                            location.href = '<?= base_url('') ?>';
                        } else {
                            console.log("no ok", response.data);
                            alert(response.data.message);
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