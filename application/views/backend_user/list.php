
    
	<style>
		#basic-datatable_filter {
			display: none;
		}
		.my_list_btn i {  
			color: white; /* Change icon color to white */  
		}  
		
	</style>

	<div class="content-page" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
        <div class="content">

			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<div class="row">
                                <div class="col-xs-12" style="text-align: right;">

                                    <div>

                                        <h3 class="page-title"> </h3>
       
										<a href="<?= base_url('admin_add_user') ?>" class="btn btn-info btn-sm" style="color: white;">Create User</a>
                                       
                                    </div>

                                </div>
							</div>

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
					location.href = "<?= base_url('admin_edit_user') ?>/" + data;
				}

				$scope.deleteData = function(id) {
					var c = confirm("Are You Sure Want To Delete This User?");
					if (c) {
						var tobeSubmit = {};
						// tobeSubmit["token"] = $scope.token;
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
