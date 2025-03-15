
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

            <!-- Kanban list table -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">

							<div class="row">
							</div>

							<table id="basic-datatable" class="table dt-responsive w-100 mt-1 my_datatable text-center">
								<h3 class="page-title"></i>
								Notification List
								<thead class="table-light">
									<tr>
										<th>ID</th>
										<th>Type</th>
                                        <th>Message</th>
                                        <th>Kanban</th>
                                        <th>Created By</th>
										<th>Receiver</th>
										<th>Created Date</th>
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

                $scope.datatable = $('#basic-datatable').DataTable({
					"serverSide": true,
					"stateSave": true,
					"lengthChange": false,
					"processing": true,
					"searching": true,
					"pageLength": 25,
					"order": [],
					"ajax": {
						"url": '<?= base_url('api/getNotificationList') ?>',
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
							"data": "type",
							'responsivePriority': 1,
                            "render": function(data, type, row, meta) {
								return row.type_name;
							}
					
						},
                        {
							"data": "message",
							'responsivePriority': 3
						},
                        {
							"data": "kanban_id",
							'responsivePriority': 2,
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
							"data": "receiver",
							'responsivePriority': 1,
                            "render": function(data, type, row, meta) {
								return row.receiver_user;
							}
					
						},
						{
							"data": "created_date",
							'responsivePriority': 3
						},
					],

				});

				$scope.chosenData = function(data) {
					$scope.datatable.state.save();
					location.href = "<?= base_url('admin_edit_kanban') ?>/" + data;
				}

				$scope.deleteData = function(id) {
					var c = confirm("Are You Sure Want To Delete This Kanban?");
					if (c) {
						var tobeSubmit = {};
						// tobeSubmit["token"] = $scope.token;
						tobeSubmit["id"] = id;


						$http.post("<?= base_url('api/deleteKanban') ?>", tobeSubmit).then(function(response) {

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
