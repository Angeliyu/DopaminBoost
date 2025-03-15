

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
                    <h3 class="page-title" ng-if="mode=='Add'"> Add User </h3>
                    <h3 class="page-title" ng-if="mode=='Edit'"> Edit User </h3>
                </div>
                
                <div class="col-md-12 text-end" ng-hide="errorAlert">  
                    <h3>
                        <button class="btn btn-danger btn-md" confirmed-click="delete()"
                            ng-confirm-click="Are you sure want to delete this record?"
                            ng-show="mode=='Edit'">Delete</button>
                  
                        <button class="btn btn-success btn-md" ng-click="saveData()"
                            ng-disabled="myForm.$invalid">Save</button>
                        <button class="btn btn-info btn-md"
                            ng-click="backList()">Back To List</button>
                    </h3>
                </div>
            </div>




            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">


                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">

                                        <form class="form-horizontal angularFormValidationHelper" role="form"
                                            name="myForm" ng-hide="errorAlert ">
                                            <fieldset>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">User Name
                                                        </label>

                                                        <div class="">
                                                            <input type="text" name="name" id="name"
                                                                ng-model="formDetail.name" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"
                                                            class="form-label">User Email
                                                        </label>

                                                        <div class="">
                                                            <input type="text" name="email" id="email"
                                                                ng-model="formDetail.email" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">  
                                                    <div class="col-md-4">
                                                        <label for="role" class="form-label">User Role</label>  
                                                            <div class="">  
                                                                <select class="form-select" ng-model="formDetail.role" name="role" id="role" data-toggle="select2" required>  
                                                                    <?php
                                                                    if (!empty($roleList)) {
                                                                        foreach ($roleList as $v) {
                                                                    ?>
                                                                            <option value="<?= $v['id'] ?>">
                                                                                <?= $v['name'] ?>
                                                                            </option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>  
                                                            </div>
                                                        </div>  
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
        </div> <!-- content -->

        <script>
            var app = angular.module("myApp",[]);
           
            app.controller("myCtrl", function($scope, $http, $timeout) {

                $scope.mode = '<?= isset($subTitle) ? $subTitle : '' ?>';
                $scope.errorAlert = false;
                $scope.error_msg = "";
                $scope.id = "<?= isset($id) && !empty($id) ? $id : '' ?>";
                // $scope.token = getCookie("token");

               
                $scope.delete = function() {

                    var tobeSubmit = $scope.formDetail;
                    tobeSubmit["mode"] = $scope.mode;
                    // tobeSubmit["token"] = $scope.token;
                    tobeSubmit["id"] = $scope.id;

                    $http.post("<?= base_url('') ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            // location.href = '<?= base_url('') ?>';
                        } else {
                            alert(response.data.result);
                        }

                    }, function(response) {

                        alert(response.data.result);

                    });

                }

                console.log("id", $scope.id);
                console.log("subtitle", $scope.mode);
                
                if ($scope.id != "") {

                    $http.get("<?= base_url('api/detailsUser') ?>/" + $scope.id ).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            $scope.errorAlert = false;
                            $scope.formDetail = response.data.result.userDetail;

                        } else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }

                    }, function(response) {
                        console.log("response", response);
                        $scope.errorAlert = true;
                        $scope.error_msg = response.data.result;
                    });

                }

                $scope.saveData = function() {


                    var tobeSubmit = $scope.formDetail;
                    tobeSubmit["mode"] = $scope.mode;
                    tobeSubmit["id"] = $scope.id;
                    console.log("tobeSubmit", tobeSubmit);
                    loadingshow();
                    $http.post("<?= base_url('api/submitUser') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            location.href = '<?= base_url('admin_userList') ?>';
                            // console.log(response.data);
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

                $scope.delete = function() {

                    var tobeSubmit = $scope.formDetail;
                    tobeSubmit["mode"] = $scope.mode;
                    tobeSubmit["id"] = $scope.id;

                    $http.post("<?= base_url('api/deleteUser') ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            location.href = '<?= base_url('admin_userList') ?>';
                        } else {
                            alert(response.data.result);
                        }

                    }, function(response) {

                        alert(response.data.result);

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