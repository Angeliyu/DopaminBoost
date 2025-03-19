
    
	<style>
		#basic-datatable_filter {
			display: none;
		}
		.my_list_btn i {  
			color: white; /* Change icon color to white */  
		}  
		
	</style>

	<div class="content-page" ng-app="myApp" ng-controller="myCtrl" ng-cloak>

		<div style="display: flex; justify-content: space-around; width: 100%; font-size: 18px; font-weight: bold; border-bottom: 2px black solid;">
		<a href="#" ng-click="check_admin_userlist_access(user_id, token)">User List</a>
		<a href="#" ng-click="check_admin_kanbanlist_access(user_id, token)">Kanban List</a>
		<a href="#" ng-click="check_admin_todoDetails_access(user_id, token)">Kanban Details Todo List</a>
		<a href="#" ng-click="check_admin_doingDetails_access(user_id, token)">Kanban Details Doing List</a>
		<a href="#" ng-click="check_admin_doneDetails_access(user_id, token)">Kanban Details Done List</a>
		<a href="#" ng-click="check_admin_notification_access(user_id, token)">Notification List</a>
		</div>

        <div class="content">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<div class="row">
								<div class="col-xs-12 d-flex justify-content-between align-items-center">
									<a class="btn btn-success" href="#" ng-click="back_to_profile(user_id, token)">Back To Profile</a>
									<a href="#" ng-click="check_addRecord_access(user_id, token)" class="btn btn-info" style="color: white;">Create User</a>
								</div>
							</div>
							<br/>

							<!-- User list table -->
							<table id="basic-datatable" class="table dt-responsive w-100 mt-1 my_datatable text-center" >
								<h3 class="page-title"></i>
								User List
								<thead class="table-light">
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Email</th>
										<th>Role</th>
                                        <th>Own Kanban</th>
                                        <th>Joined Kanban</th>
										<th>Created Date</th>
										<th>Modified Date</th>
										<th>Action</th>

									</tr>
								</thead>
							</table>
							<!-- User list table end -->
						</div> <!-- end card body-->
					</div> <!-- end card -->
				</div><!-- end col-->
			</div>
			

        </div>

        <script>
			function actionData(data, action) {
				angular.element($(".content-page")).scope()[action](data);
			}

			//angularjs
			var app = angular.module("myApp", []);
            app.controller("myCtrl", function($scope, $http, $timeout) {

				$scope.token = "<?= isset($token) && !empty($token) ? $token : '' ?>";
				$scope.user_id = "<?= isset($user_id) && !empty($user_id) ? $user_id : '' ?>";

				$scope.check_admin_userlist_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_userList'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_userList'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.check_admin_kanbanlist_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_kanbanList'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_kanbanList'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.check_admin_todoDetails_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_kanban_details_todo'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_kanban_details_todo'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.check_admin_doingDetails_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_kanban_details_doing'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_kanban_details_doing'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.check_admin_doneDetails_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_kanban_details_done'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_kanban_details_done'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.check_admin_notification_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_notificationList'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_notificationList'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.back_to_profile = function(user_id, token) {
                    $http.get("<?= base_url('profile'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                        } else {
                            window.location.href = "<?= base_url('profile'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

				$scope.check_addRecord_access = function(user_id, token) {
                    $http.get("<?= base_url('admin_add_user'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_add_user'); ?>/" + user_id + "/" + token;
                        }
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                        alert(error);
                    });
                };

                $scope.datatable = $('#basic-datatable').DataTable({
					"serverSide": true,
					"stateSave": true,
					"lengthChange": false,
					"processing": true,
					"searching": true,
					"pageLength": 25,
					"order": [],
					"ajax": {
						"url": '<?= base_url('api/getUserList') ?>',
						"type": 'POST',
						"dataSrc": function(response) {
							var json = [];
							if (response.status == "OK") {

								json = response.result;

							} else {

								alert(response.result);
							}
							//console.log(res);
							return json;
						}

					},
					"columns": [{
							"data": "id",
							'responsivePriority': 1
						},
						{
							"data": "name",
							'responsivePriority': 1,
					
						},
						{
							"data": "email",
							'responsivePriority': 1,
					
						},
						{
							"data": "role",
							'responsivePriority': 1,
							"render": function(data, type, row, meta) {
								return row.user_role;
							}
					
						},
						{
							"data": "own_kanban",
							'responsivePriority': 1,
					
						},
						{
							"data": "joined_kanban",
							'responsivePriority': 1,
					
						},
						{
							"data": "created_date",
							'responsivePriority': 2,
						},
						{
							"data": "modified_date",
							'responsivePriority': 3
						},
						{
							"data": "action",
							'responsivePriority': 1,
							'orderable': false,
							"render": function(data, type, row, meta) {
								$button = '';
								
								$button += '<a class="btn my_list_btn btn-info btn-xs" onclick="actionData(\'' + row.id + '\',\'chosenData\')"><i class="bi bi-pencil"></i></a>';
								$button += "&nbsp;";
								$button += '<a class="btn my_list_btn btn-danger btn-xs" onclick="actionData(\'' + row.id + '\',\'deleteData\')"><i class="bi bi-trash"></i></a>';
								

								return $button;

							}
						}
					],

				});

				$scope.chosenData = function(data) {
					$scope.datatable.state.save();
					location.href = "<?= base_url('admin_edit_user') ?>/" + $scope.user_id + "/" + $scope.token + "/" + data;
				}

				$scope.deleteData = function(id) {
					var c = confirm("Are You Sure Want To Delete This User?");
					if (c) {
						var tobeSubmit = {};
						tobeSubmit["id"] = id;


						$http.post("<?= base_url('api/deleteUser') ?>", tobeSubmit).then(function(response) {

							if (response.data.status == "OK") {
								$scope.datatable.draw();
							} else {
								alert(response.data.result);
							}

						}, function(response) {

							alert(response.data.result);

						});
					}

				}

            })

        </script>
    </div>

    </body>


</html>
