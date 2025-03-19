

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
                    <h3 class="page-title" ng-if="mode=='Add'"> Add Kanban </h3>
                    <h3 class="page-title" ng-if="mode=='Edit'"> Edit Kanban </h3>
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
                                                            class="form-label">Kanban Name
                                                        </label>

                                                        <div class="">
                                                            <input type="text" name="name" id="name"
                                                                ng-model="formDetail.name" class="form-control"
                                                                required>

                                                        </div>
                                                    </div>

                                                    <div class="mb-2 row">  
                                                        <label for="user_id" class="form-label">Owned By</label>  
                                                        <div class="">  
                                                            <select class="form-select" ng-model="formDetail.owned_by" name="user_id" id="user_id" data-toggle="select2">  
                                                                <option ng-repeat="user in userList" value="{{user.id}}">  
                                                                    {{user.name}}  
                                                                </option>  
                                                            </select>  
                                                        </div>  
                                                    </div>  

                                                    <div class="mb-2 row">  
                                                        <label for="user_ids" class="form-label" ng-if="formDetail.member == '' || formDetail.member == null">Members</label>  
                                                        <label for="user_ids" class="form-label" ng-if="formDetail.member.length > 0">Reselect Members</label>  
                                                        <div class="">  
                                                            <select class="form-select" ng-model="formDetail.member" name="user_ids" id="user_ids" multiple data-toggle="select2">  
                                                                <?php if (!empty($userList)) {  
                                                                    foreach ($userList as $v) { ?>  
                                                                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>  
                                                                <?php }  
                                                                } ?>  
                                                            </select>  
                                                        </div>  
                                                    </div>
                                                    
                                                    <div class="mb-2 row" ng-if="formDetail.member.length > 0">  
                                                        <label for="joined_user_ids" class="form-label">Joined Member(s)</label>  
                                                        <div>
                                                            <ul>
                                                                <?php if (!empty($userList)) {  
                                                                    foreach ($userList as $v) { ?>  
                                                                        <li ng-if="formDetail.member.includes('<?= $v['id'] ?>')">
                                                                            <?= $v['name'] ?>
                                                                        </li>  
                                                                <?php }  
                                                                } ?>  
                                                            </ul>
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

                // $scope.formDetail.member = [];

                $scope.formDetail = {
                    'member': [],
                };

                $timeout(function() {  
                    // Initialize Select2 for owned by field  
                    $('#user_id').select2().on('change', function() {  
                        $scope.$apply(function() {  
                            // Update owned_by model  
                            $scope.formDetail.owned_by = $(this).val();  
                            $scope.searchUser();  
                        });  
                    });  

                    // Initialize Select2 for members field  
                    $('#user_ids').select2().on('change', function() {  
                        $scope.$apply(function() {  
                            // Update member model  
                            $scope.formDetail.member = $(this).val();  
                            $scope.searchMultipleUser();  
                        });  
                    });  

                    // Initialize Select2 for joined members field  
                    $('#joined_user_ids').select2().on('change', function() {  
                        $scope.$apply(function() {  
                            // Update member model  
                            $scope.formDetail.member = $(this).val();  
                            $scope.searchMultipleUser();  
                        });  
                    });  

                    // Auto-select members if formDetail.member is not empty  
                    if ($scope.formDetail.member && $scope.formDetail.member.length > 0) {  
                        $('#joined_user_ids').val($scope.formDetail.member).trigger('change'); // Set the selected values  
                    }  
                    
                }); 
                
                $scope.token = "<?= isset($token) && !empty($token) ? $token : '' ?>";
				$scope.user_id = "<?= isset($user_id) && !empty($user_id) ? $user_id : '' ?>";
                $scope.mode = '<?= isset($subTitle) ? $subTitle : '' ?>';
                $scope.errorAlert = false;
                $scope.error_msg = "";
                $scope.id = <?= isset($id) && !empty($id) ? '"' . $id . '"' : '""' ?>;

                if ($scope.id != "") {

                    $http.get("<?= base_url('api/detailsKanban') ?>/" + $scope.id).then(function(response) {

                        if (response.data.status == "OK") {
                            $scope.errorAlert = false;
                            console.log("response", response);
                            $scope.formDetail = response.data.result.kanbanDetail;
                            $scope.formDetail.member = $scope.formDetail.member.split(',');
                            console.log("formDetail", $scope.formDetail);
                        } else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }

                    }, function(response) {
                        $scope.errorAlert = true;
                        $scope.error_msg = response.data.result;
                    });

                }

                $scope.delete = function() {

                    var tobeSubmit = $scope.formDetail;
                    tobeSubmit["mode"] = $scope.mode;
                    tobeSubmit["id"] = $scope.id;

                    $http.post("<?= base_url('api/deleteKanban') ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            location.href = "<?= base_url('admin_kanbanList'); ?>/" + $scope.user_id + "/" + $scope.token;
                        } else {
                            alert(response.data.result);
                        }

                    }, function(response) {

                        alert(response.data.result);

                    });

                }
                

                $scope.saveData = function() {

                    var tobeSubmit = $scope.formDetail;
                    tobeSubmit["mode"] = $scope.mode;
                    tobeSubmit["id"] = $scope.id;
                    console.log("tobeSubmit", tobeSubmit);
                    
                    loadingshow();
                    $http.post("<?= base_url('api/submitKanban') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            console.log(response);
                            location.href = "<?= base_url('admin_kanbanList'); ?>/" + user_id + "/" + token;
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

                $scope.backList = function() {
                    $http.get("<?= base_url('admin_kanbanList'); ?>/" + $scope.user_id + "/" + $scope.token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_kanbanList'); ?>/" + $scope.user_id + "/" + $scope.token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                }

                // Function to fetch available users  
                $scope.fetchAvailableUsers = function() {  
                    $http.get("<?= base_url('api/getAvailableUser') ?>").then(function(response) {  
                        if (response.data.status == "OK") {  
                            $scope.userList = response.data.result; // Store available users 
                            console.log('available user', $scope.userList);
                        } else {  
                            console.log(response.data.result);  
                        }  
                    }, function(error) {  
                        console.error("Error fetching users:", error);  
                    });  
                };  

                // Call the function when the controller initializes  
                $scope.fetchAvailableUsers();  

                $scope.searchUser = function() {

                    var tobeSubmit = {
                        'onwed_by': $scope.formDetail.owned_by,
                        'token': $scope.token,
                    };

                    $http.post("<?= base_url( "/api/userSearch") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            console.log("id", response.data.result.userData.id);
                            $scope.formDetail.owned_by = response.data.result.userData.id;
                            console.log("$scope.formDetail.owned_by", $scope.formDetail.owned_by);
                        } else {
                            alert(response.data.result);
                        }
                    }, function(response) {
                        console.log("response", response);
                        alert(response.data.result);
                    });

                }

                $scope.searchMultipleUser = function() {

                    var tobeSubmit = {
                        'member': $scope.formDetail.member,
                        'token': $scope.token,
                    };

                    $http.post("<?= base_url( "/api/multipleUserSearch") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            $scope.formDetail.owned_by = response.data.result.userData.id;
                        } else {
                            alert(response.data.result);
                        }
                    }, function(response) {
                        console.log("response", response);
                        alert(response.data.result);
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