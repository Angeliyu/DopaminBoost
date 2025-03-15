
<style>  
    .input-group .input-group-addon {  
        cursor: pointer; /* Change cursor to pointer for the icon */  
    }  
    .input-group {  
        position: relative;  
    }  
    .input-group-addon {  
        position: absolute;  
        right: 10px; /* Adjust as needed */  
        top: 50%;  
        transform: translateY(-50%);  
        pointer-events: none; /* Prevent icon from blocking input */  
    }  
</style> 

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
                    <h3 class="page-title" ng-if="mode=='Add'"> Add Done Task</h3>
                    <h3 class="page-title" ng-if="mode=='Edit'"> Edit Done Task</h3>
                </div>
                
                <div class="col-md-12 text-end" ng-hide="errorAlert">  

                    <h3>
                        <button class="btn btn-danger btn-md" confirmed-click="delete()"
                            ng-confirm-click="Are you sure want to delete this record?"
                            ng-show="mode=='Edit'">Delete</button>
                  
                        <button class="btn btn-success btn-md" ng-click="saveData()"
                            ng-disabled="myForm.$invalid">Save</button>
                        <button class="btn btn-info btn-md"
                            ng-click="backList()">Cancel</button>
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

                                                    <div class="mb-2 row">
                                                        <h4>This Belongs To</h4>
                                                        <label for="done_kanban_id"
                                                            class="form-label">Kanban</label>
                                                        <div class="">
                                                            <select class="form-select" ng-model="formDetail.kanban_id"
                                                                name="done_kanban_id" id="done_kanban_id"
                                                                data-toggle="select2" required>
                                                                <?php
                                                                if (!empty($kanbanList)) {
                                                                    foreach ($kanbanList as $v) {
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

                                                    <div class="mb-2 row">
                                                        <label for="done_created_id" class="form-label">Created by</label>
                                                        <div>
                                                            <select class="form-select" ng-model="formDetail.created_by"
                                                                name="done_created_id" id="done_created_id"
                                                                data-toggle="select2" ng-options="user.id as user.name for user in userList" required>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2 row" ng-if="mode=='Edit'">  
                                                        <label for="done_task_status" class="form-label">Task Status</label>  
                                                        <div class="">  
                                                            <select class="form-select" ng-model="formDetail.status" name="done_task_status" id="done_task_status" data-toggle="select2" required>  
                                                                <?php
                                                                if (!empty($statusList)) {
                                                                    foreach ($statusList as $v) {
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

                                                    <div class="mb-2 row">  
                                                        <label for="done_task_type" class="form-label">Task Type</label>  
                                                        <div class="">  
                                                            <select class="form-select" ng-model="formDetail.type" name="done_task_type" id="done_task_type" data-toggle="select2" required>  
                                                                <?php
                                                                if (!empty($typeList)) {
                                                                    foreach ($typeList as $v) {
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

                                                    <div class="mb-2 row">
                                                        <div class="col-md-4">
                                                            <label for="done_task_title"
                                                                class="form-label">Task Title
                                                            </label>

                                                            <div class="">
                                                                <input type="text" name="done_task_title" id="done_task_title"
                                                                    ng-model="formDetail.content_title" class="form-control"
                                                                    placeholder="Please enter title of task"
                                                                    required>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2 row" ng-show="formDetail.type == 1">
                                                        <label for="done_task_desc" class="form-label">Task Description</label>
                                                        <div class="textarea">
                                                            <textarea rows="5" name="done_task_desc" id="done_task_desc" placeholder="Please enter description of task" ng-model="done_task_desc" class="form-control"></textarea>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12" ng-if="formDetail.type == 2" style="margin-top: 2%; margin-bottom: 2%;">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="page-title"><u>Add Checkbox List</u></h4>
                                                                <hr />
                                                                <div class="mb-2 row">
                                                                    

                                                                    <div class="p-2">
                                                                        <button type="button" class="btn btn-success" ng-click="mycontrol.add_checkbox_details = !mycontrol.add_checkbox_details">
                                                                            add details +
                                                                        </button>
                                                                    </div>

                                                                    <div class="mt-2 mb-2" ng-show="mycontrol.add_checkbox_details">
                                                                        <table class="tablesaw table mb-0" wt-responsive-table>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="align-middle" scope="col">Checkbox Details
                                                                                    </th>
                                                                                    <th class="align-middle" scope="col"></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="align-middle">
                                                                                        <input class="form-control"
                                                                                            type="text"
                                                                                            placeholder="input detail"
                                                                                            ng-model="adddetect.add_checkbox_details">
                                                                                    </td>

                                                                                    <td class="align-middle">
                                                                                        <button type="button" class="btn btn-success" ng-click="add_checkbox_details_table()">
                                                                                            ADD
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                                <hr />
                                                                <div class="mt-2 mb-2">
                                                                    <table class="tablesaw table mb-0" wt-responsive-table>
                                                                        <thead>
                                                                            <tr>
                                                                                <th>
                                                                                    No.
                                                                                </th>
                                                                                <th class="align-middle" scope="col">
                                                                                    Details
                                                                                </th>
                                                                                <th class="align-middle" scope="col"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <!--if no data show template-->
                                                                        <tbody ng-if="done_checkbox_details == '' || done_checkbox_details == null">
                                                                            <tr ng-repeat="row in checkbox_details_row track by $index">
                                                                                <td class="align-middle">
                                                                                    {{$index + 1}}
                                                                                </td>

                                                                                <td class="align-middle">
                                                                                    <input class="form-control"
                                                                                        type="text"
                                                                                        placeholder="insert detail"
                                                                                        ng-model="row.item"
                                                                                        value="{{row.item}}">
                                                                                </td>

                                                                                <td class="align-middle">
                                                                                    <a class="btn btn-danger btn-xs"
                                                                                        ng-click="deleteRow(checkbox_details_row, $index)">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <!--if have data-->
                                                                        <tbody ng-if="done_checkbox_details.length > 0">
                                                                            <tr ng-repeat="row in checkbox_details_row track by $index">
                                                                                <td class="align-middle">
                                                                                    {{$index + 1}}
                                                                                </td>

                                                                                <td class="align-middle">
                                                                                    <input class="form-control"
                                                                                        type="text"
                                                                                        placeholder="insert detail"
                                                                                        ng-model="row.item"
                                                                                        value="{{row.item}}">
                                                                                </td>

                                                                                <td class="align-middle">
                                                                                    <a class="btn btn-danger btn-xs"
                                                                                        ng-click="deleteRow(checkbox_details_row, $index)">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <tfoot>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="mb-2 row">
                                                        <label for="done_due_date"
                                                            class="form-label">Task Due Date</label>
                                                        <span><small>*Due date only able to select start tomorrow</small></span>
                                                        <datepicker date-format="yyyy-MM-dd" datepicker-mobile="true"
                                                            selector="form-control" date-min-limit="{{ minDate }}">
                                                            <input type="text" name="done_due_date"
                                                                ID="done_due_date"
                                                                ng-model="done_due_date"
                                                                class="form-control" data-date-time-picker required>
                                                        </datepicker>
                                                    </div>

                                                    <div class="mb-2 row">
                                                        <label for="done_due_time" class="form-label">Task Due Time</label>
                                                        <span><small style="color: red;">*Click clock icon to select due time</small></span>
                                                        <input type="time" name="done_due_time" id="done_due_time" ng-model="done_due_time" class="form-control" required>
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

            var app = angular.module('myApp', ['720kb.datepicker', 'ui.bootstrap']);
           
            app.controller("myCtrl", function($scope, $http, $timeout) {

                $timeout(function() { 
                    // Initialize Select2 for created_by field  
                    $('#created_id').select2().on('change', function() {  
                        // Update created_by model  
                        $scope.formDetail.created_by = $(this).val();  
                        $scope.searchUser();  
                    });  
                }); 
                

                $scope.mode = '<?= isset($subTitle) ? $subTitle : '' ?>';
                $scope.errorAlert = false;
                $scope.error_msg = "";
                $scope.id = <?= isset($id) && !empty($id) ? '"' . $id . '"' : '""' ?>;
                $scope.userList = [];  // Initialize user list
                $scope.formDetail = {  
                    due_date: '',
                    status: 1,  
                };  
                $scope.done_due_date = '';

                $scope.minDate = new Date().toISOString().split("T")[0]; // "YYYY-MM-DD"  

                $scope.done_due_time = '';

                $scope.mycontrol = {};
                $scope.mycontrol.add_checkbox_details = false;

                $scope.adddetect = {};
                $scope.adddetect.add_checkbox_details = "";

                $scope.checkbox_details_row = [];

                $scope.minDate = new Date().toISOString().split("T")[0]; // Gets today's date in YYYY-MM-DD format

                $scope.add_checkbox_details_table = function() {

                    // Log the values before checking
                    console.log("add_checkbox_details:", $scope.adddetect.add_checkbox_details);

                    // Check if the inputs are not empty
                    if ($scope.adddetect.add_checkbox_details) {
                        // Append the new specification to the array
                        $scope.checkbox_details_row.push({
                            item: $scope.adddetect.add_checkbox_details,
                        });

                        // Clear the input fields
                        $scope.adddetect.add_checkbox_details = '';

                        // Hide the input form
                        $scope.mycontrol.add_checkbox_details = false;

                        console.log("Updated add_checkbox_details: ", $scope.add_checkbox_details);

                    } else {
                        alert("Please fill in the content of checkbox details");
                    }

                    $scope.mycontrol.add_checkbox_details = false;
                    console.log("add_checkbox_details: ", $scope.mycontrol.add_checkbox_details);
                }

                $scope.deleteRow = function(list, index) {

                    if (list && index >= 0 && index < list.length) {
                        // get the item to remove
                        var item = list[index];

                        // remove it
                        list.splice(index, 1);
                    }
                }

                // Function to store the time in 24-hour format
                $scope.updateTime = function () {
                    if ($scope.formDetail.due_time) {
                        let timeParts = $scope.done_due_time.split(":");
                        let hours = timeParts[0].padStart(2, '0'); // Ensure two-digit format
                        let minutes = timeParts[1].padStart(2, '0');
                        $scope.done_due_time = `${hours}:${minutes}`;
                    }
                };

                $scope.$watch("done_due_time", function(newVal) {
                    console.log("Selected Time:", newVal); // See the stored time in the console
                });

                $scope.$watch("done_due_date", function(newVal) {
                    console.log("Selected Date:", newVal); // See the stored date in the console
                });

                $scope.$watch("formDetail.type", function(newVal) {
                    console.log("Selected type:", newVal); // See the stored type in the console
                });

                $scope.$watchGroup(["done_due_date", "done_due_time"], function(newValues) {
                    let date = newValues[0]; // Selected date
                    let time = newValues[1]; // Selected time (could be a Date object)

                    if (date && time) {
                        let timeString = extractTimeString(time);
                        let formattedDateTime = combineDateTime(date, timeString);
                        $scope.formDetail.due_date = formattedDateTime;
                        console.log("Combined DateTime:", formattedDateTime); // Debugging
                    }
                });

                function extractTimeString(time) {
                    if (typeof time === 'string') {
                        return time; // Already in correct format
                    }
                    
                    if (time instanceof Date) {
                        let hours = time.getHours().toString().padStart(2, '0');
                        let minutes = time.getMinutes().toString().padStart(2, '0');
                        return `${hours}:${minutes}`;
                    }
                    
                    return "00:00"; // Fallback in case of invalid time
                }

                function combineDateTime(date, time) {
                    let timeParts = time.split(":"); // Extract HH:mm
                    let hours = timeParts[0].padStart(2, '0');
                    let minutes = timeParts[1].padStart(2, '0');

                    let formattedDate = `${date} ${hours}:${minutes}:00`; // Append seconds
                    return formattedDate;
                }

                if ($scope.id != "") {

                    $http.get("<?= base_url('api/detailsKanbanDetailsDone') ?>/" + $scope.id).then(function(response) {

                        if (response.data.status == "OK") {
                            $scope.errorAlert = false;
                            console.log("response", response);
                            $scope.formDetail = response.data.result.kanban_done;

                            // if type is Plain Text
                            if ($scope.formDetail.type == 1) {

                                $scope.done_task_desc = $scope.formDetail.content_description

                            } else if ($scope.formDetail.type == 2) { // if type is Checkbox

                                $scope.checkbox_details_row = JSON.parse($scope.formDetail.content_description);
                            }

                            // set due_date
                            // Split date and time
                            var due_date = $scope.formDetail.due_date;

                            var parts = due_date.split(" ");
                            $scope.done_due_date = parts[0]; // "2025-01-25"
                           
                            // Convert time string to JavaScript Date object for ng-model compatibility
                            var timeParts = parts[1].split(":"); // ["16", "37", "00"]
                            var dateObject = new Date();
                            dateObject.setHours(timeParts[0], timeParts[1], 0); // Set HH:mm:ss

                            $scope.done_due_time = dateObject; // Assign as Date object

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

                    $http.post("<?= base_url('') ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {
                            location.href = '<?= base_url('admin_kanban_details_done') ?>';
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
                    tobeSubmit["due_date"] = $scope.formDetail.due_date;

                    if ($scope.formDetail.type == 1) {
                        tobeSubmit["content_description"] = $scope.done_task_desc;
                    } else if ($scope.formDetail.type == 2) {
                        tobeSubmit["content_description"] = JSON.stringify($scope.checkbox_details_row);
                    }

                    console.log("tobeSubmit", tobeSubmit);
                    
                    loadingshow();
                    $http.post("<?= base_url('api/submitKanbanDetailsDone') ?>", tobeSubmit).then(function(response) {
                        loadinghide();
                        if (response.data.status == "OK") {
                            console.log(response);
                            location.href = '<?= base_url('admin_kanban_details_done') ?>';
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
                    location.href = '<?= base_url('admin_kanban_details_done') ?>';
                }

                $scope.searchUser = function() {

                    var tobeSubmit = {
                        'kanban_id': $scope.formDetail.kanban_id,
                        'token': $scope.token,
                    };

                    $http.post("<?= base_url( "/api/kanbanUserSearch") ?>", tobeSubmit).then(function(response) {

                        if (response.data.status == "OK") {  
                            console.log("response", response);  

                            
                            $scope.userList = response.data.result.userData;
                            console.log("$scope.userList", $scope.userList);

                            // Set created_by if available
                            if ($scope.userList.length > 0) {
                                $scope.formDetail.created_by = $scope.userList[0].id;
                            }

                            console.log("$scope.formDetail.created_by", $scope.formDetail.created_by);

                            // $timeout(function() {
                            //     $('#created_id').trigger('change'); // Refresh Select2
                            // });
                            
                        } else {
                            alert(response.data.result);
                        }
                    }, function(response) {
                        console.log("response", response);
                        alert(response.data.result);
                    });

                }

                // Watch for changes on kanban_id  
                $scope.$watch('formDetail.kanban_id', function(newVal) {  
                    if (newVal) {  
                        $scope.searchUser();  
                    }  
                });  


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