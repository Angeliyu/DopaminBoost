

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

                                                    <div class="mb-2 row" ng-if="mode == 'Add'">  
                                                        <div class="col-md-4">
                                                        
                                                            <label for="add_user_id" class="form-label">Owned By</label>  
                                                          
                                                            <select class="form-select" ng-model="formDetail.owned_by" name="add_user_id" id="add_user_id">  
                                                                <option ng-repeat="user in user_list" value="{{user.id}}">  
                                                                    {{user.name}}  
                                                                </option>  
                                                            </select>  

                                                        </div>  
                                                    </div>

                                                    <div class="mb-2 row" ng-if="mode == 'Edit'">  
                                                        <div class="col-md-4">

                                                            <label for="owned_by_id" class="form-label">Owned By</label>  

                                                            <input type="text" name="owned_by_id" id="owned_by_id" ng-model="formDetail.owned_by_name" class="form-control"
                                                                ng-disabled="formDetail.owned_by_name != null || formDetail.owned_by_name != ''">

                                                            <br/>
                                                            <a class="btn btn-info btn-sm" ng-click="edit_owned_by_user()" ng-if="edit_ownedBy == false">Edit Owned By User</a>
                                                            <a class="btn btn-info btn-sm" ng-click="edit_owned_by_user()" ng-if="edit_ownedBy == true">Cancel Edit Owned By User</a>
                                                            <br/>
                                                            <br/>

                                                            <!-- <select class="form-select" ng-model="formDetail.owned_by" name="user_id" id="user_id">  
                                                                <option ng-repeat="user in userList" value="{{user.id}}">  
                                                                    {{user.name}}  
                                                                </option>  
                                                            </select>   -->
                                                        
                                                            <!-- <select class="form-select" ng-model="formDetail.owned_by" name="user_id" id="user_id" data-toggle="select2" ng-if="edit_ownedBy == true">  
                                                                <option ng-repeat="user in userList" value="{{user.id}}">  
                                                                    {{user.name}}  
                                                                </option>  
                                                            </select>   -->

                                                        </div>  
                                                    </div>
                                                    
                                                    <div class="mb-2 row" ng-if="edit_ownedBy == true && mode == 'Edit'">  
                                                        <div class="col-md-4">

                                                            <label for="user_id" class="form-label">Owned By</label>  

                                                            <select class="form-select" ng-model="new_owned_by" name="user_id" id="user_id" ng-if="edit_ownedBy == true"  ng-change="searchUser(new_owned_by)">  
                                                                <option ng-repeat="user in user_list" ng-value="{{user.id}}">  
                                                                    {{user.name}}  
                                                                </option>  
                                                            </select>  

                                                        </div>  
                                                    </div>

                                                    <!-- <div class="mb-2 row" ng-if="mode == 'Add'">  
                                                        <div class="col-md-4">
                                                            <label for="add_user_ids" class="form-label" ng-if="formDetail.member == '' || formDetail.member == null">Members</label>  
                                                            <label for="add_user_ids" class="form-label" ng-if="formDetail.member.length > 0">Reselect Members</label>  
                                                        
                                                            <select class="form-select" ng-model="formDetail.member" name="add_user_ids" id="add_user_ids" multiple data-toggle="select2">  
                                                                <?php if (!empty($userList)) {  
                                                                    foreach ($userList as $v) { ?>  
                                                                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>  
                                                                <?php }  
                                                                } ?>  
                                                            </select>  
                                                        </div>  
                                                    </div> -->

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

                                                    <div class="mb-2 row">  
                                                        <div class="col-md-4">
                                                            <label for="user_ids" class="form-label" ng-if="formDetail.member == '' || formDetail.member == null">Members</label>  
                                                            <label for="user_ids" class="form-label" ng-if="formDetail.member.length > 0">Reselect Members</label>

                                                            <br/>
                                                            <button class="btn btn-secondary" type="button" ng-click="toggleDropdown()" ng-if="mode == 'Edit'">
                                                                Select Members <span class="caret"></span>
                                                            </button>
                                                            <button class="btn btn-secondary" type="button" ng-click="getAllExceptLeader()" ng-if="mode == 'Add'">
                                                                Select Member(s) <span class="caret"></span>
                                                            </button>
                                                            <br/>

                                                            <div ng-if="dropdownOpen == true">
                                                                <ul>
                                                                    <li ng-repeat="user in all_available_users">
                                                                        <label>
                                                                            <input type="checkbox" ng-checked="isSelected(user.id)" ng-click="toggleSelection(user.id)"> 
                                                                            {{ user.name }}
                                                                        </label>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <br/>
                                                            <div ng-if="all_available_users_except_leader.length > 0">
                                                                <ul>
                                                                    <li ng-repeat="user in all_available_users_except_leader">
                                                                        <label>
                                                                            <input type="checkbox" ng-checked="NewIsSelected(user.id)" ng-click="NewToggleSelection(user.id)"> 
                                                                            {{ user.name }}
                                                                        </label>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <!-- <select class="form-multi-select"
                                                                ng-model="reselect_member"
                                                                name="user_ids"
                                                                id="user_ids"
                                                                multiple
                                                                ng-options="user.id as user.name for user in all_available_users track by user.id">
                                                            </select> -->

                                                            <!-- <ng-select class="form-multi-select"
                                                                multiple
                                                                ng-model="formDetail.member"
                                                                name="user_ids"
                                                                id="user_ids"
                                                                options="user.id as user.name for user in userList"
                                                                placeholder="Select options">
                                                            </ng-select> -->

                                                            <!-- <select class="form-select" ng-model="formDetail.member" name="user_ids" id="user_ids" multiple>  
                                                                <?php if (!empty($userList)) {  
                                                                    foreach ($userList as $v) { ?>  
                                                                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>  
                                                                <?php }  
                                                                } ?>  
                                                            </select>   -->
                                                        
                                                            <!-- <select class="form-select" ng-model="formDetail.member" name="user_ids" id="user_ids" multiple data-toggle="select2" ng-change="searchMultipleUser(formDetail.member)">  
                                                                <?php if (!empty($userList)) {  
                                                                    foreach ($userList as $v) { ?>  
                                                                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>  
                                                                <?php }  
                                                                } ?>  
                                                            </select>   -->

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

                $scope.formDetail = {
                    member : [],
                };

                $timeout(function() {  

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
                $scope.edit_ownedBy = false;
                $scope.new_owned_by = '';
                $scope.userList = <?= json_encode($userList ?? []) ?>;
                $scope.reselect_member = [];
                $scope.dropdownOpen = false;  // Controls dropdown visibility


                console.log("Member list:", $scope.userList);

                // Toggle dropdown visibility
                $scope.toggleDropdown = function () {
                    $scope.dropdownOpen = !$scope.dropdownOpen;
                    console.log("able dropdown", $scope.dropdownOpen);
                };

                // Check if user is selected (Edit)
                $scope.isSelected = function (id) {
                    console.log("selected", $scope.reselect_member);
                    return $scope.reselect_member.includes(id);
                };

                // Check if user is selected (Add)
                $scope.NewIsSelected = function (id) {
                    console.log("selected", $scope.formDetail.member);
                    return $scope.formDetail.member.includes(id);
                };

                // Toggle user selection (Edit)
                $scope.toggleSelection = function (id) {
                    let idx = $scope.reselect_member.indexOf(id);
                    if (idx > -1) {
                        $scope.reselect_member.splice(idx, 1); // Remove if already selected
                    } else {
                        $scope.reselect_member.push(id); // Add if not selected
                    }
                };

                // Toggle user selection (Add)
                $scope.NewToggleSelection = function (id) {
                    let idx = $scope.formDetail.member.indexOf(id);
                    if (idx > -1) {
                        $scope.formDetail.member.splice(idx, 1); // Remove if already selected
                    } else {
                        $scope.formDetail.member.push(id); // Add if not selected
                    }
                };

                $scope.edit_owned_by_user = function() {
                    $scope.edit_ownedBy = !$scope.edit_ownedBy;
                    console.log("edit_ownedBy", $scope.edit_ownedBy);
                }

                if ($scope.id != "") {

                    $http.get("<?= base_url('api/detailsKanban') ?>/" + $scope.id).then(function(response) {

                        if (response.data.status == "OK") {
                            $scope.errorAlert = false;
                            console.log("response", response);
                            $scope.formDetail = response.data.result.kanbanDetail;
                            $scope.formDetail.member = $scope.formDetail.member.split(',');
                            console.log("formDetail", $scope.formDetail);

                            if ($scope.formDetail.id != null) {

                                var tobeSubmit = {
                                    'kanban_id': $scope.formDetail.id,
                                };

                                $http.post("<?= base_url( "/api/getAllAvailableUser") ?>", tobeSubmit).then(function(response) {

                                    if (response.data.status == "OK") {
                                        console.log("get current members response", response);

                                        $scope.all_available_users = response.data.result.available_user;
                                        $scope.all_available_users = response.data.result.available_user.map(user => ({
                                            id: parseInt(user.id), // Convert ID to integer
                                            name: user.name
                                        }));
                                        console.log("all members", $scope.all_available_users);
                                    } else {
                                        alert(response.data.result);
                                    }
                                }, function(response) {
                                    console.log("response", response);
                                    alert(response.data.result);
                                });

                            }

                        } else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }

                    }, function(response) {
                        $scope.errorAlert = true;
                        $scope.error_msg = response.data.result;
                    });

                }

                $scope.getAllExceptLeader = function() {

                    console.log("owned by", $scope.formDetail.owned_by);

                    var tobeSubmit = {
                        'user_id': $scope.formDetail.owned_by,
                    };

                    $http.post("<?= base_url( "/api/getAllAvailableUserExceptLeader") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("get all members except leader response", response);

                            $scope.all_available_users_except_leader = response.data.result.available_user;
                            $scope.all_available_users_except_leader = response.data.result.available_user.map(user => ({
                                id: parseInt(user.id), // Convert ID to integer
                                name: user.name
                            }));
                            console.log("all members", $scope.all_available_users_except_leader);
                        } else {
                            alert(response.data.result);
                        }
                    }, function(response) {
                        console.log("response", response);
                        alert(response.data.result);
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

                    console.log("leader user", $scope.formDetail.owned_by);
                    console.log("original member users", $scope.formDetail.member);

                    console.log("reselect members", $scope.reselect_member);

                    if ($scope.mode == 'Edit') {
                        if ($scope.reselect_member.length > 0) {  // check if array is not empty
                            $scope.formDetail.member = angular.copy($scope.reselect_member); // Copy the array
                        } 
                    }

                    console.log("new selected users", $scope.formDetail.member);

                    if ($scope.formDetail.owned_by == '' || $scope.formDetail.owned_by == null) {
                        alert("Please Select A Leader User");
                        return;
                    } else if ($scope.formDetail.member.length < 0) {
                        alert("Please Select At Least One Member User");
                        return;
                    }

                    var tobeSubmit = $scope.formDetail;
                    tobeSubmit["mode"] = $scope.mode;
                    tobeSubmit["id"] = $scope.id;
                    console.log("tobeSubmit", tobeSubmit);
                    
                    loadingshow();
                    $http.post("<?= base_url('api/submitKanban') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            console.log(response);
                            location.href = "<?= base_url('admin_kanbanList'); ?>/" + $scope.user_id + "/" + $scope.token;
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
                            $scope.user_list = response.data.result; // Store available users 
                            console.log('available user', $scope.user_list);
                        } else {  
                            console.log(response.data.result);  
                        }  
                    }, function(error) {  
                        console.error("Error fetching users:", error);  
                    });  
                };  

                // Call the function when the controller initializes  
                $scope.fetchAvailableUsers(); 
                
                $scope.getCurrentMember = function() {

                    console.log("kanban id", $scope.formDetail.id);

                    var tobeSubmit = {
                        'kanban_id': $scope.formDetail.id,
                    };

                    $http.post("<?= base_url( "/api/getCurrentMembers") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("get current members response", response);
                            console.log("id", response.data.result.available_user.id);
                            // $scope.formDetail.member = 
                        } else {
                            alert(response.data.result);
                        }
                    }, function(response) {
                        console.log("response", response);
                        alert(response.data.result);
                    });

                }


                $scope.searchUser = function(id) {

                    console.log("new owned by", $scope.new_owned_by);

                    var tobeSubmit = {
                        'onwed_by': id,
                    };

                    $http.post("<?= base_url( "/api/userSearch") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("user search response", response);
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

                $scope.searchMultipleUser = function(member) {

                    console.log("selected member(s)", member);

                    var tobeSubmit = {
                        'member': member,
                    };

                    $http.post("<?= base_url( "/api/multipleUserSearch") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            $scope.formDetail.member = response.data.result.userData.id;
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