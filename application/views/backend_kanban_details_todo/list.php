
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

            <!-- Kanban list table -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<div class="row">
								<div class="col-xs-12 d-flex justify-content-between align-items-center">
									<a class="btn btn-success" href="#" ng-click="back_to_profile(user_id, token)">Back To Profile</a>
									<a href="#" ng-click="check_addRecord_access(user_id, token)" class="btn btn-info" style="color: white;">Create Kanban Details Todo</a>
								</div>
							</div>
							<br/>
							
							<table id="basic-datatable" class="table dt-responsive w-100 mt-1 my_datatable text-center">
								<h3 class="page-title"></i>
								Kanban Details Todo
								<thead class="table-light">
									<tr>
										<th>ID</th>
										<th>Kanban Name</th>
                                        <th>Created By</th>
                                        <th>Status</th>
										<th>Type</th>
										<th>Content Title</th>
										<th>Due Date</th>
										<th>Created Date</th>
										<th>Modified Date</th>
										<th>Action</th>

									</tr>
								</thead>
							</table>
						</div> <!-- end card body-->
					</div> <!-- end card -->
				</div><!-- end col-->
			</div>
			<!-- Kanban list table end -->

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
                    $http.get("<?= base_url('admin_add_kanban_details_todo'); ?>/" + user_id + "/" + token)
                    .then(function(response) {
                        if (response.data.error) {
                            alert(response.data.error); // Show alert for unauthorized access
                            window.history.back(); // Go back to the previous page
                        } else {
                            window.location.href = "<?= base_url('admin_add_kanban_details_todo'); ?>/" + user_id + "/" + token;
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
						"url": '<?= base_url('api/getKanbanDetailsTodoList') ?>',
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
							"data": "kanban_id",
							'responsivePriority': 1,
							"render": function(data, type, row, meta) {
								return row.kanban_name;
							}
					
						},
						{
							"data": "created_by",
							'responsivePriority': 1,
							"render": function(data, type, row, meta) {
								return row.created_user;
							}
					
						},
						{
							"data": "status",
							'responsivePriority': 1,
							"render": function(data, type, row, meta) {
								return row.task_status;
							}
					
						},
						{
							"data": "type",
							'responsivePriority': 1,
							"render": function(data, type, row, meta) {
								return row.task_type;
							}
					
						},
						{
							"data": "content_title",
							'responsivePriority': 1,
					
						},
						{
							"data": "due_date",
							'responsivePriority': 1,
							"render": function(data, type, row, meta) {
								if (!data) return 'N/A'; // Handle empty due_date

								var currentDate = new Date();
								var dueDate = new Date(data); // Convert due_date string to Date object
								
								var borderClass = (dueDate < currentDate) ? "border-danger" : "border-success";
								
								return `<span class="d-block p-2 ${borderClass}" style="border: 2px solid;">${data}</span>`;
							}
					
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
					location.href = "<?= base_url('admin_edit_kanban_details_todo') ?>/" + $scope.user_id + "/" + $scope.token + "/" + data;
				}

				$scope.deleteData = function(id) {
					var c = confirm("Are You Sure Want To Delete This Todo Task?");
					if (c) {
						var tobeSubmit = {};
						// tobeSubmit["token"] = $scope.token;
						tobeSubmit["id"] = id;
						


						$http.post("<?= base_url('api/deleteKanbanDetailTodo') ?>", tobeSubmit).then(function(response) {

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
