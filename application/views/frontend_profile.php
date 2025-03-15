

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
                    <table style="margin-top: 0px; width: 100%;">
                        <thead>
                        </thead>
                        <tbody style="border-bottom: 2px black solid;">
                        <tr style="width: 100%; font-size: 18px;">
                            <td style="width: 10%; text-align: left;"><h2 class="page-title" style="font-weight: bold;"> Profile </h2></td>
                            <td style="width: 80%; text-align: right;" ng-if="formDetail.role == 1"><a href="<?= base_url('admin_userList'); ?>" style="color: black; text-decoration: underline;">Admin Backend System</a></td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- <h2 class="page-title" style="font-weight: bold;"> Profile </h2> -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <!-- User Information -->
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">

                                        <form class="form-horizontal angularFormValidationHelper" role="form"
                                            name="myForm">
                                            <fieldset>

                                                <h3><u>User Information</u></h3><br/>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name" class="form-label">
                                                            User Name : <b> {{ formDetail.name }} </b>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="mb-2 row">
                                                    <div class="col-md-4">
                                                        <label for="name"  class="form-label">
                                                            User Email : <b> {{ formDetail.email }} </b>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12" style="margin-top: -10%; margin-botton: 5%;">
                                                    <div class="d-flex flex-column align-items-end" style="width: 160px; margin-left: 85%;">
                                                        <button class="btn btn-success mb-2 w-100" style="font-weight: bold;" ng-click="editUserInfoData(formDetail)" >
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-info mb-2 w-100" style="color: white; font-weight: bold;" ng-click="passwordValidationData(formDetail.email)">
                                                            Change Password
                                                        </button>
                                                        <button class="btn btn-danger w-100" style="font-weight: bold;" ng-click="backList()">
                                                            Logout
                                                        </button>
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

                    <!-- General Notification -->
                    <div class="col-md-12" style="margin-top: 5%;">  
                        <div class="card">  
                            <div class="card-body">  
                                <div class="row"> <!-- Use a row for layout -->  
                                    <div class="col-md-12" style="border-left: 5px solid black;"> <!-- Second table on the right side -->  
                                        <h3><u>General Notification</u></h3>
                                        <span><button class="btn btn-info mb-2" style="margin-top: -2%; color: white; float: right;" ng-click="openCompleteNotification_general()">View More</button></span>
                                        <div class="mt-2 mb-2">  
                                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                                <thead>  
                                                    <tr>  
                                                        <th scope="col" width="45%">Message</th>  
                                                        <th scope="col" width="15%">Date</th>
                                                        <th scope="col" width="15%">Action</th>  
                                                        <th scope="col" width="15%">Status</th>  
                                                    </tr>  
                                                </thead>  
                                                <tbody>  
                                                    <tr ng-repeat="notification in general_notification | orderBy:'-created_date' | limitTo: 3">  
                                                        <td class="align-middle" ng-bind-html="notification.message"></td>  
                                                        <td class="align-middle">{{ notification.created_date }}</td>
                                                        <td class="align-middle" ng-if="notification.type == 1 && notification.is_accepted == 0">
                                                            <button class="btn btn-success me-2" style="color: white; font-weight: bold;" ng-click="joinKanban(id, notification.kanban_id, notification.id)">
                                                                Accept
                                                            </button>
                                                            <button class="btn btn-danger me-2" style="font-weight: bold;" ng-click="rejectKanban(id, notification.kanban_id, notification.id)" >
                                                                Reject
                                                            </button>
                                                        </td>
                                                        <td class="align-middle" ng-if="notification.type == 1 && notification.is_accepted == 1">
                                                        </td>
                                                        <td class="align-middle" ng-if="notification.type != 1">
                                                            <button class="btn btn-info me-2" style="color: white; font-weight: bold;" ng-click="">
                                                                Mark As Read
                                                            </button>
                                                        </td>  
                                                        <td class="align-middle">
                                                            <p ng-if="notification.is_read == 1"><span class="badge bg-info">Read</span></p>
                                                            <p ng-if="notification.is_read == 0"><span class="badge bg-warning">Unread</span></p>
                                                            <p ng-if="notification.is_accepted == 1"><span class="badge bg-success">Accepted</span></p>
                                                        </td>
                                                    </tr>  
                                                    <tr ng-show='false'>  
                                                        <td class="align-middle" colspan="3" align="center" data-title=" ">  
                                                            <strong>-- No Notifications Found --</strong>  
                                                        </td>  
                                                    </tr>  
                                                </tbody>  
                                                <tfoot>  
                                                </tfoot>  
                                            </table>  
                                        </div>  
                                    </div>  
                                </div> <!-- End of row -->  
                            </div>  
                        </div>  
                    </div>

                    <div style="margin-top: 5%; margin-bottom: -5%;" ng-if="formDetail.ownKanban == null">
                        <button class="btn btn-success mb-2 w-100" style="font-weight: bold;" ng-click="openAddNewKanban()" >
                            Create New Kanban
                        </button>
                    </div>

                    <!-- Leader Kanban -->
                    <div class="col-md-12" style="margin-top: 5%;">  
                        <div class="card">  
                            <div class="card-body">  
                                <div class="row"> <!-- Use a row for layout -->  
                                    <div class="col-md-6"> <!-- First table on the left side -->  
                                        <h3><u>You As Leader</u></h3>
                                        <div class="mt-2 mb-2">  
                                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                                <thead>  
                                                    <tr>  
                                                        <th scope="col" width="15%">Kanban Name</th>  
                                                        <th scope="col" width="15%">Leader</th>  
                                                    </tr>  
                                                </thead>  
                                                <tbody>  
                                                    <tr>  
                                                        <td class="align-middle"><a ng-href="<?= base_url('kanban/') ?>{{ownKanbanId}}?userId={{id}}">{{formDetail.ownKanban.name}}</a></td>
                                                        <td class="align-middle">{{formDetail.ownKanban.owned_by_name}}</td>
                                                    </tr>  
                                                    <tr ng-show="productPriceLog.length == 0">  
                                                        <td class="align-middle" colspan="2" align="center" data-title=" ">  
                                                            <strong>-- No Data Found --</strong>  
                                                        </td>  
                                                    </tr>  
                                                </tbody>  
                                                <tfoot>  
                                                </tfoot>  
                                            </table>  
                                        </div>  
                                    </div>  
                                    <div class="col-md-6" style="border-left: 5px solid black;"> <!-- Second table on the right side -->  
                                        <h3><u>Kanban Notification</u></h3>
                                        <span><button class="btn btn-info mb-2" style="margin-top: -5%; color: white; float: right;" ng-click="openCompleteNotification_leader()">View More</button></span>
                                        <div class="mt-2 mb-2">  
                                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                                <thead>  
                                                    <tr>  
                                                        <th scope="col" width="25%">Message</th>  
                                                        <th scope="col" width="25%">Date</th>  
                                                    </tr>  
                                                </thead>  
                                                <tbody>  
                                                    <tr ng-repeat="notification in leader_notification | orderBy:'-created_date' | limitTo: 1">  
                                                        <td class="align-middle" ng-bind-html="notification.message"></td>  
                                                        <td class="align-middle">{{ notification.created_date }}</td>  
                                                    </tr>  
                                                    <tr ng-show="leader_notification.length == 0">  
                                                        <td class="align-middle" colspan="3" align="center" data-title=" ">  
                                                            <strong>-- No Notifications Found --</strong>  
                                                        </td>  
                                                    </tr>  
                                                </tbody>  
                                                <tfoot>  
                                                </tfoot>  
                                            </table>  
                                        </div>  
                                    </div>  
                                </div> <!-- End of row -->  
                            </div>  
                        </div>  
                    </div>

                    <!-- Joined Kanban Table -->
                    <div class="col-md-12" style="margin-top: 5%; margin-bottom: 5%;">  
                        <div class="card">  
                            <div class="card-body">  
                                <div class="row"> <!-- Use a row for layout -->  
                                    <div class="col-md-6"> <!-- First table on the left side -->  
                                        <h3><u>You As Member</u></h3>
                                        <div class="mt-2 mb-2">  
                                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                                <thead>  
                                                    <tr>  
                                                        <th scope="col" width="15%">Kanban Name</th>  
                                                        <th scope="col" width="15%">Leader</th>  
                                                    </tr>  
                                                </thead>  
                                                <tbody ng-if="formDetail.userKanbans.length > 0">  
                                                    <tr ng-repeat="kanban in formDetail.userKanbans">  
                                                        <td class="align-middle"><a ng-href="<?= base_url('kanban/') ?>{{ kanban.id }}?userId={{ id }}">{{ kanban.name }}</a></td>  
                                                        <td class="align-middle">{{ kanban.owned_by_name }}</td>  
                                                    </tr> 
                                                </tbody>  
                                                <tbody ng-if="formDetail.userKanbans.length == 0">  
                                                    <tr>  
                                                        <td class="align-middle" colspan="2" align="center" data-title=" ">  
                                                            <strong>-- No Data Found --</strong>  
                                                        </td>  
                                                    </tr>  
                                                </tbody>  
                                                <tfoot>  
                                                </tfoot>  
                                            </table>  
                                        </div>  
                                    </div>  
                                    <div class="col-md-6" style="border-left: 5px solid black;"> <!-- Second table on the right side -->  
                                        <h3><u>Kanban Notification</u></h3>
                                        <span><button class="btn btn-info mb-2" style="margin-top: -5%; color: white; float: right;" ng-click="openCompleteNotification_member()">View More</button></span>
                                        <div class="mt-2 mb-2">  
                                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                                <thead>  
                                                    <tr>  
                                                        <th scope="col" width="25%">Message</th>  
                                                        <th scope="col" width="25%">Date</th>
                                                        <th scope="col" width="25%">Kanban</th>  
                                                    </tr>  
                                                </thead>  
                                                <tbody>  
                                                    <tr ng-repeat="item in joined_kanban_notification | orderBy:'-created_date' | limitTo: 2">  
                                                        <td class="align-middle" ng-bind-html="item.message"></td>
                                                        <td class="align-middle">{{ item.created_date }}</td>  
                                                        <td class="align-middle">{{ item.kanban_name }}</td>  
                                                    </tr>  
                                                    <tr ng-show="notificationList.length == 0">  
                                                        <td class="align-middle" colspan="3" align="center" data-title=" ">  
                                                            <strong>-- No Notifications Found --</strong>  
                                                        </td>  
                                                    </tr>  
                                                </tbody>  
                                                <tfoot>  
                                                </tfoot>  
                                            </table>  
                                        </div>  
                                    </div>  
                                </div> <!-- End of row -->  
                            </div>  
                        </div>  
                    </div>
                    

                </div>
                <!-- end row -->

            </div> <!-- container -->
        </div> <!-- content -->

        <!-- Edit User Information Modal -->  
        <div class="modal fade" id="editUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="editUserInfoModal" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="editUserInfoModal">Edit User Information</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="closeEditUserInfoData()">  
                            <span aria-hidden="true">&times;</span>  
                        </button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="editUserInfoForm">
                            <div class="form-group">  
                                <label for="edit_profile_user_name">User Name</label>  
                                <input type="text" class="form-control" id="edit_profile_user_name" ng-model="userInfoEdit.name" required>  
                            </div>  
                            <div class="form-group">  
                                <label for="edit_profile_user_email">Email</label>  
                                <input type="text" class="form-control" id="edit_profile_user_email" ng-model="userInfoEdit.email" required>  
                            </div>    
                            
                            <input type="hidden" ng-model="userInfoEdit.id"> <!-- Hidden input for user ID -->  
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closeEditUserInfoData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitEditUserInfo()">Save changes</button>  
                    </div>  
                </div>  
            </div>  
        </div>

        <!-- Password Edit Validation Modal -->  
        <div class="modal fade" id="passwordValidationModal" tabindex="-1" role="dialog" aria-labelledby="passwordValidationModal" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="passwordValidationModal">Password Edit Validation</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="closePasswordValidationData()">  
                            <span aria-hidden="true">&times;</span>  
                        </button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="passwordValidationForm">
                            <div class="form-group">  
                                <label for="safety_validation_1">Safety Question 1</label>  
                                <p style="font-weight: bold;">Which song do you most often play on repeat?</p>
                                <input type="text" class="form-control" id="safety_validation_1" ng-model="passwordValidation.sq1" required>  
                            </div>  
                            <br/>
                            <div class="form-group">  
                                <label for="safety_validation_2">Safety Question 2</label>  
                                <p style="font-weight: bold;">Where is your hometown?</p>
                                <input type="text" class="form-control" id="safety_validation_2" ng-model="passwordValidation.sq2" required>  
                            </div>    
                            
                            <input type="hidden" ng-model="userInfoEdit.id"> <!-- Hidden input for user email -->  
                        </form>
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closePasswordValidationData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitPasswordValidation()">Validate</button>  
                    </div>  
                </div>  
            </div>  
        </div>

        <!-- Edit User Password Modal -->  
        <div class="modal fade" id="editUserPwModal" tabindex="-1" role="dialog" aria-labelledby="editUserPwModal" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="editUserPwModal">Edit Password</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="closePasswordEditData()">  
                            <span aria-hidden="true">&times;</span>  
                        </button>  
                    </div>  
                    <div class="modal-body">  
                        <h6><i class="text-info">Validated Successful! Please type your new password below</i></h6>
                        <br/>
                        <form id="editPasswordForm">
                            <div class="form-group">  
                                <label for="profile_edit_password">Password</label>  
                                <input type="text" class="form-control" id="profile_edit_password" ng-model="passwordEdit.password" required>  
                            </div>  
                            <br/>
                            <div class="form-group">  
                                <label for="profile_edit_password_retype">Retype Password</label>  
                                <input type="text" class="form-control" id="profile_edit_password_retype" ng-model="passwordEdit.retype_password" required>  
                            </div>  
                            

                            <input type="hidden" ng-model="formDetail.email"> <!-- Hidden input for user email -->  
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closePasswordEditData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitPasswordEdit()">Reset Password</button>  
                    </div>  
                </div>  
            </div>  
        </div>

        <!-- Notification Modal (general notification)-->
        <div class="modal fade" id="profileGeneralNotificationModal" tabindex="-1" aria-labelledby="profileGeneralNotificationModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileGeneralNotificationModal">Notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 mb-2">  
                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                <thead>  
                                    <tr>  
                                        <th scope="col" width="75%">Message</th>  
                                        <th scope="col" width="15%">Date</th>
                                        <th scope="col" width="15%">Action</th>  
                                        <th scope="col" width="15%">Status</th>  
                                    </tr>  
                                </thead>  
                                <tbody>  
                                    <tr ng-repeat="notification in general_notification | orderBy:'-created_date'">  
                                        <td class="align-middle">{{ notification.message }}</td>  
                                        <td class="align-middle">{{ notification.created_date }}</td>
                                        <td class="align-middle" ng-if="notification.type == 1 && notification.is_accepted == 0">
                                            <button class="btn btn-success me-2" style="color: white; font-weight: bold;" ng-click="joinKanban(id, notification.kanban_id, notification.id)">
                                                Accept
                                            </button>
                                            <button class="btn btn-danger me-2" style="font-weight: bold;" ng-click="" >
                                                Reject
                                            </button>
                                        </td>
                                        <td class="align-middle" ng-if="notification.type == 1 && notification.is_accepted == 1">
                                        </td>
                                        <td class="align-middle" ng-if="notification.type != 1">
                                            <button class="btn btn-info me-2" style="color: white; font-weight: bold;" ng-click="">
                                                Mark As Read
                                            </button>
                                        </td>  
                                        <td class="align-middle">
                                            <p ng-if="notification.is_read == 1"><span class="badge bg-info">Read</span></p>
                                            <p ng-if="notification.is_read == 0"><span class="badge bg-warning">Unread</span></p>
                                            <p ng-if="notification.is_accepted == 1"><span class="badge bg-success">Accepted</span></p>
                                        </td>
                                    </tr>  
                                    <tr ng-show='false'>  
                                        <td class="align-middle" colspan="3" align="center" data-title=" ">  
                                            <strong>-- No Notifications Found --</strong>  
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
        </div>

        <!-- Notification Modal (as leader)-->
        <div class="modal fade" id="profileLeaderNotificationModal" tabindex="-1" aria-labelledby="profileLeaderNotificationModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileLeaderNotificationModal">Notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 mb-2">  
                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                <thead>  
                                    <tr>  
                                        <th scope="col" width="75%">Message</th>  
                                        <th scope="col" width="15%">Date</th>
                                    </tr>  
                                </thead>  
                                <tbody>  
                                    <tr ng-repeat="item in leader_notification">  
                                        <td class="align-middle"  ng-bind-html="item.message"></td>  
                                        <td class="align-middle">{{ item.created_date }}</td>
                                    </tr>  
                                    <tr ng-show='false'>  
                                        <td class="align-middle" colspan="3" align="center" data-title=" ">  
                                            <strong>-- No Notifications Found --</strong>  
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
        </div>

        <!-- Notification Modal (as member)-->
        <div class="modal fade" id="profileJoinedKanbanNotificationModal" tabindex="-1" aria-labelledby="profileJoinedKanbanNotificationModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileJoinedKanbanNotificationModal">Notification</h5>
                        <button type="button" class="btn-close" aria-label="Close" ng-click="closeCompleteNotification_member()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 mb-2">  
                            <table class="tablesaw table mb-0" wt-responsive-table>  
                                <thead>  
                                    <tr>  
                                        <th scope="col" width="75%">Message</th>  
                                        <th scope="col" width="15%">Date</th>
                                        <th scope="col" width="25%">Kanban</th>  
                                    </tr>  
                                </thead>  
                                <tbody>  
                                    <tr ng-repeat="item in joined_kanban_notification">  
                                        <td class="align-middle" ng-bind-html="item.message"></td>  
                                        <td class="align-middle">{{ item.created_date }}</td>
                                        <td class="align-middle">{{ item.kanban_name }}</td>  
                                    </tr>  
                                    <tr ng-show='false'>  
                                        <td class="align-middle" colspan="3" align="center" data-title=" ">  
                                            <strong>-- No Notifications Found --</strong>  
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
        </div>

        <!-- Add New InfKanbanormation Modal -->  
        <div class="modal fade" id="addKanbanModal" tabindex="-1" role="dialog" aria-labelledby="addKanbanModal" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="addKanbanModal">Add New Kanban</h5>  
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" ng-click="closeAddNewKanban()"></button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="addNewKanbanForm">
                            <div class="form-group">  
                                <label for="new_kanban_name">Kanban Name</label>  
                                <input type="text" class="form-control" id="new_kanban_name" ng-model="addNewKanban.new_kanban_name" required>  
                            </div>  
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closeAddNewKanban()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="createNewKanban()">Create</button>  
                    </div>  
                </div>  
            </div>  
        </div>

        <script>
            var app = angular.module("myApp",['ngSanitize']);
           
            app.controller("myCtrl", function($scope, $http, $timeout, $sce) {

                $scope.errorAlert = false;
                $scope.error_msg = "";
                $scope.id = "<?= isset($id) && !empty($id) ? $id : '' ?>";
                $scope.ownKanbanId = null;

                //// Add New Kanban model
                $scope.openAddNewKanban = function() {  
                    $('#addKanbanModal').modal('show'); // Open the modal  
                }; 

                $scope.closeAddNewKanban = function() {  
                    $('#addKanbanModal').modal('hide'); // Close the modal  
                }; 

                $scope.addNewKanban = {};
                $scope.addNewKanban['new_kanban_name'] = '';
                $scope.addNewKanban['leader'] = $scope.id;

                $scope.createNewKanban = function() {

                    if ($scope.addNewKanban.new_kanban_name) {

                        const data = {  
                            new_kanban_name: $scope.addNewKanban.new_kanban_name,  
                            leader: $scope.addNewKanban.leader, 
                        };

                        console.log("data", data);
                                
                        // Make the API call  
                        $http.post("<?= base_url('api/createNewKanban') ?>", data).then(function(response) {  
                            if (response.data.status == "OK") {
                                // Handle success  
                                console.log('Kanban created successfully:', response.data);  
                                location.href = '<?= base_url('profile/') ?>' + $scope.id;
                            } else {
                                $scope.errorAlert = true;
                                $scope.error_msg = response.data.result;
                                console.log("error", response);
                                alert(response.data.message); 
                            }
                        })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error creating kanban:', error);  
                        });                         

                    }

                }

                //// Notification model (general notification)
                $scope.openCompleteNotification_general = function() {  
                    $('#profileGeneralNotificationModal').modal('show'); // Open the modal  
                }; 

                //// Notification model (as leader)
                $scope.openCompleteNotification_leader = function() {  
                    $('#profileLeaderNotificationModal').modal('show'); // Open the modal  
                }; 

                //// Notification model (as leader)
                $scope.openCompleteNotification_member = function() {  
                    $('#profileJoinedKanbanNotificationModal').modal('show'); // Open the modal  
                }; 

                $scope.closeCompleteNotification_member = function() {  
                    $('#profileJoinedKanbanNotificationModal').modal('hide'); // Close the modal  
                }; 

                // reset password start
                $scope.passwordEdit = {}; 
                $scope.passwordEdit['password'] = "";
                $scope.passwordEdit['retype_password'] = "";

                //// Function to close the modal
                $scope.closePasswordEditData = function() {  
                    $('#editUserPwModal').modal('hide'); // Close the modal  
                };  

                //// Function to send the new password data to api
                $scope.submitPasswordEdit = function() {  

                    if ($scope.passwordEdit.retype_password != $scope.passwordEdit.password) {
                        // empty the retype password field
                        $scope.passwordEdit.retype_password = "";
                        // give alert
                        alert("Your Passwords Do Not Match");

                        return;
                    }
                    
                    const data = {  
                        mode: "Edit",  
                        email: $scope.passwordValidation.email,  
                        password: $scope.passwordEdit.password, 
                    };

                    console.log("data", data);
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/reset_password') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('Reset password successfully:', response.data);  
                            alert("Password Reset Successfully."); 
                            location.href = '<?= base_url('profile/') ?>' + $scope.id;
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error resetting user password:', error);  
                        });  
                };  
                // new password end

                // password validation start
                $scope.passwordValidation = {}; 
                $scope.passwordValidation['sq1'] = "";
                $scope.passwordValidation['s21'] = "";
                $scope.passwordValidation['email'] = "";

                
                //// Copy the data from formDetail and open modal
                $scope.passwordValidationData = function(email) {  

                    $scope.passwordValidation.email = email;

                    $('#passwordValidationModal').modal('show'); // Show the modal  

                };

                //// Function to send the safety question answer data to api
                $scope.submitPasswordValidation = function() {  
                    
                    const data = {  
                        mode: "Edit",  
                        email: $scope.passwordValidation.email,  
                        answer_1: $scope.passwordValidation.sq1, 
                        answer_2: $scope.passwordValidation.sq2,                         
                    };

                    console.log("data", data);
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/forgot_password') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('Account validation successfully:', response.data);  
                            $('#passwordValidationModal').modal('hide'); // Hide the validation modal  
                            $('#editUserPwModal').modal('show'); // Show the modal  
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                            console.log("error", response);
                            alert(response.data.message); 
                            $scope.passwordValidation.sq1 = "";
                            $scope.passwordValidation.sq2 = "";
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error validate user:', error);  
                        });  
                };  
                // safety question answer end

                //// Function to close the modal
                $scope.closePasswordValidationData = function() {  
                    $('#passwordValidationModal').modal('hide'); // Close the modal  
                };  

                // edit user information start
                $scope.userInfoEdit = {}; 

                //// Copy the data from formDetail and open modal
                $scope.editUserInfoData = function(data) {  
                    $scope.userInfoEdit = angular.copy(data); 

                    $('#editUserInfoModal').modal('show'); // Show the modal  
                };

                //// Function to send the edited user information data to api
                $scope.submitEditUserInfo = function() {  
                    
                    const data = {  
                        mode: "Edit",  
                        id: $scope.userInfoEdit.id,  
                        name: $scope.userInfoEdit.name, 
                        email: $scope.userInfoEdit.email, 
                        
                    };

                    console.log("data", data);
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/edit_user_info') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('User information edit successfully:', response.data);  
                            location.href = '<?= base_url('profile/') ?>' + $scope.id;
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error editing user information:', error);  
                        });  
                };  
                // edit user information end
                
                //// Function to close the modal
                $scope.closeEditUserInfoData = function() {  
                    $('#editUserInfoModal').modal('hide'); // Close the modal  
                };  
               
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

                    $http.get("<?= base_url('api/user_profile') ?>/" + $scope.id ).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            $scope.errorAlert = false;
                            $scope.formDetail = response.data.result.userDetail;
                            console.log("formDetail", $scope.formDetail);

                            // Extract all userKanban IDs into an array
                            $scope.userKanbanIds = $scope.formDetail.userKanbans.map(kanban => kanban.id);

                            console.log("Extracted userKanban IDs:", $scope.userKanbanIds);

                            if ($scope.formDetail.ownKanban != null) {
                                $scope.ownKanbanId = $scope.formDetail.ownKanban.id;
                                console.log("own kanban id", $scope.ownKanbanId);
                            }

                            if ($scope.ownKanbanId != null) {

                                $http.get("<?= base_url('api/leader_kanban_notification') ?>/" + $scope.ownKanbanId).then(function(response) {

                                    if (response.data.status == "OK") {
                                        console.log("response", response);
                                        $scope.errorAlert = false;
                                        $scope.leader_notification = response.data.result.notificationDetail;
                                        console.log("leader notification", $scope.leader_notification);

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

                            if ($scope.userKanbanIds != null) {

                                let idsString = $scope.userKanbanIds.join(","); // Convert array to string

                                $http.get("<?= base_url('api/joined_kanban_notification') ?>?ids=" + idsString).then(function(response) {

                                    if (response.data.status == "OK") {
                                        console.log("response in joined kanban notification", response);
                                        $scope.errorAlert = false;
                                        $scope.joined_kanban_notification = response.data.result.notificationDetail;
                                        console.log("joined kanban notification", $scope.joined_kanban_notification);

                                        $scope.joined_kanban_notification.forEach(function(item) {
                                            item.message = $sce.trustAsHtml(item.message);
                                        });

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

                        } else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }

                    }, function(response) {
                        console.log("response", response);
                        $scope.errorAlert = true;
                        $scope.error_msg = response.data.result;
                    });

                    $http.get("<?= base_url('api/general_notification') ?>/" + $scope.id).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            $scope.errorAlert = false;
                            $scope.general_notification = response.data.result.notificationDetail;
                            console.log("general notification", $scope.general_notification);

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

                // join kanban
                $scope.joinKanban = function(userID, kanbanID, notificationID) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        user_id: userID,
                        kanban_id: kanbanID,
                        notification_id: notificationID,
                    };  

                    if (confirm("Are you sure you want to join this Kanban?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/member_joined') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                alert("Joined Kanban successfully!"); 
                                $scope.target_kanban_id = response.data.result.kanban_id;
                                console.log('target_kanban_id', $scope.target_kanban_id);
                                location.href = '<?= base_url('kanban/') ?>' + $scope.target_kanban_id + '?userId=' + $scope.id; 
                            } else {  
                                alert(response.data.result);  
                            }  
                        }, function(response) {  
                            alert("Error: " + response.data.result);  
                        });  
                    } else {
                        return; 
                    }
                }; 

                // reject to join kanban
                $scope.rejectKanban = function(userID, kanbanID, notificationID) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        user_id: userID,
                        kanban_id: kanbanID,
                        notification_id: notificationID,
                    };  

                    if (confirm("Are you sure you want to reject the invite?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/member_rejected') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                alert("Invite Rejected"); 
                                $scope.target_kanban_id = response.data.result.kanban_id;
                                console.log('target_kanban_id', $scope.target_kanban_id);
                                location.href = '<?= base_url('kanban/') ?>' + $scope.target_kanban_id + '?userId=' + $scope.id; 
                            } else {  
                                alert(response.data.result);  
                            }  
                        }, function(response) {  
                            alert("Error: " + response.data.result);  
                        });  
                    } else {
                        return; 
                    }
                }; 

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