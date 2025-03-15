
<style>
    .header-image {  
    padding: 2%;  
    width: 100%;  
    height: 100%; 
    } 
</style>

<div class="content-page" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <div class="content">
        <style>
            .angularFormValidationHelper .ng-invalid {
                border: 2px solid pink;
            }

            .angularFormValidationHelper .ng-valid {}

            .sidebar {  
                background-color: #f8f9fa; /* Sidebar background */  
                border-right: 1px solid #ddd; /* Right border */  
                padding: 15px;  
                display: flex;              /* Enable flexbox */  
                flex-direction: column;     /* Arrange items in a column */  
            }  

            .card {  
                margin-bottom: 20px; /* Spacing between cards */  
            }  

            .flex-container {  
                display: flex;  
            }  

            .flex-container .sidebar {  
                width: 20%; 
            }  

            .flex-container .main-content {  
                width: 80%; 
                display: flex;  
                justify-content: space-between; /* Space out the cards */  
            }  

            .column {  
                flex: 1; /* Each column takes equal space */  
                margin: 0 10px; /* Margin between columns */  
            }  

            .spacer {  
                flex-grow: 1;/* Take up available space */  
            }  
        </style>
        <!-- Start Content-->
        <div class="container-fluid">

            <div style="margin-bottom: 2%;">
                <table style="margin-top: 0px; margin-left: 1%;">
                    <thead>
                    </thead>
                    <tbody style="border-bottom: 2px black solid;">
                        <tr style="width: 100%; font-size: 18px;">
                            <td style="width: 20%; text-align: left;" class="header_image"><img src="<?= base_url('assets/img/Dopamin_Boost_logo.png') ?>" alt="Logo" style="max-width: 80%; margin-top: 0.7%; margin-left: -1%;" /></td>
                            <td style="width: 60%; text-align: center;"><p style="text-align: bottom; color: grey;">Kanban Name</p> <br/> <h2 class="page-title"><b> {{ formDetail.name }} </b> <br> <button class="btn btn-success" style="font-size: 20px; border-radius: 12px; padding: 5px;" ng-if="formDetail.todo.length < 1 && formDetail.doing.length < 1 && formDetail.done.length > 1" ng-click="kanban_complete()">COMPLETE KANBAN</button> </h2></td>
                            <td style="width: 10%; text-align: center; word-wrap: normal;"><i class="fa fa-bell" style="cursor: pointer; font-size: 50px; color: #0d6efd;" ng-click="openNotificationData()"></i><br/>Notification</td>
                            <td style="width: 10%; text-align: center; word-wrap: normal;"><i class="fa fa-users" style="cursor: pointer; font-size: 50px; color: #0d6efd;" ng-click="showMember()"></i><br/>Member Lists</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex-container">  
                <div class="sidebar">  
                    <h5>Sidebar</h5>  
                    <a ng-href="<?= base_url('profile/') ?>{{userId}}" style="font-size: 25px;">Profile</a>
                    <br/>
                    <a href="#" style="font-size: 25px;">Logout</a>
                    <br/>
                    <div class="spacer"></div> <!-- Spacer to create space at the bottom -->  
                    <p style="text-align: center; font-size: 25px;">Username: <br/> <b> {{userData.name}} </b></p> <!-- Display user's name -->  
                </div>  

                <div class="main-content">  
                    <!-- Todo Category -->
                    <div class="column">  
                        <div class="card">  
                            <div class="card-body">  
                                <h5 class="card-title">Todo (Total task: <b>{{ formDetail.todo.length }}</b>) <i class="fa-solid fa-square-plus" style="float:right; color: #1361e7;" ng-click="addTodoData()"></i></h5>  
                                <!-- Content for Todo -->  
                                <p ng-if="formDetail.todo.length < 1">Todo task will list here</p>  
                                <div ng-repeat="task in formDetail.todo" class="card mb-2">  <!-- Repeat for each task -->  
                                    <div class="card-body">  
                                        <h5 style="border-bottom: 1px solid black;">Title: <br/>{{ task.content_title }}</h5>  
                                        <br/>  
                                        <div ng-if="task.type == 2"  style="border-bottom: 1px solid black;">  <!-- Check if task type is checkbox -->  
                                            <p>Check List:</p>  
                                            <ul>  
                                                <li ng-repeat="item in task.todo_parsedDescription">
                                                    {{ item.item }}
                                                    <span ng-if="item.checked == 'true'">
                                                        <i class="bi bi-check-circle text-success"></i> <!-- Bootstrap Icons -->
                                                    </span>
                                                </li>
                                            </ul>  
                                        </div> 
                                        <div ng-if="task.type == 1">  <!-- Check if task type is plain text -->  
                                            <p style="border-bottom: 1px solid black;">Description: <br/>{{ task.content_description }}</p>  
                                        </div>  
                                        <br/>  
                                        <p style="border-bottom: 1px solid black;">Due Date: <br/>{{ task.due_date }}</p>  
                                        <br/>  
                                        <p style="border-bottom: 1px solid black;"><i>Created by: <br/> {{ task.created_user }}</i></p>  
                                        <br/>
                                        <div class="btn-group w-100" role="group">
                                            <button class="btn btn-danger me-2" style="color: white; font-weight: bold;" ng-click="deleteTodoTask(task.id)">
                                                Delete
                                            </button>
                                            <button class="btn btn-info me-2" style="font-weight: bold;" ng-click="editTodoData(task)" >
                                                Edit
                                            </button>
                                            <button class="btn btn-success me-2" style="font-weight: bold;" ng-click="moveToDoing(task.id)" >
                                                Move To Doing
                                            </button>
                                        </div>
                                    </div>  
                                </div>  
                            </div>  
                        </div>  
                    </div>  

                    <!-- Doing Category -->
                    <div class="column">  
                        <div class="card">
                            <div class="card-body">  
                                <h5 class="card-title">Doing (Total task: <b>{{ formDetail.doing.length }}</b>) <i class="fa-solid fa-square-plus" style="float:right; color: #1361e7;" ng-click="addDoingData()"></i></h5>  
                                <!-- Content for Doing -->  
                                <p ng-if="formDetail.doing.length < 1">Doing task will list here</p>  
                                <div ng-repeat="task in formDetail.doing" class="card mb-2">  <!-- Repeat for each task -->  
                                    <div class="card-body">  
                                        <h5 style="border-bottom: 1px solid black;">Title: <br/>{{ task.content_title }}</h5>  
                                        <br/>  
                                        <div ng-if="task.type == 2" style="border-bottom: 1px solid black;">  <!-- Check if task type is checkbox -->  
                                            <p>Check List:</p>  
                                            <ul>  
                                                <li ng-repeat="item in task.doing_parsedDescription">
                                                    {{ item.item }}
                                                    <span ng-if="item.checked == 'true'">
                                                        <i class="bi bi-check-circle text-success"></i> <!-- Bootstrap Icons -->
                                                    </span>
                                                </li>
                                            </ul>  
                                        </div> 
                                        <div ng-if="task.type == 1">  <!-- Check if task type is plain text -->  
                                            <p style="border-bottom: 1px solid black;">Description: <br/>{{ task.content_description }}</p>  
                                        </div>    
                                        <br/>  
                                        <p style="border-bottom: 1px solid black;">Due Date: <br/>{{ task.due_date }}</p>  
                                        <br/>  
                                        <p style="border-bottom: 1px solid black;"><i>Created by: <br/>{{ task.created_user }}</i></p>  
                                        <br/>
                                        <div class="btn-group w-100" role="group">
                                            <button class="btn btn-danger me-2" style="color: white; font-weight: bold;" ng-click="deleteDoingTask(task.id)">
                                                Delete
                                            </button>
                                            <button class="btn btn-info me-2" style="font-weight: bold;" ng-click="editDoingData(task)" >
                                                Edit
                                            </button>
                                            <button class="btn btn-success me-2" style="font-weight: bold;" ng-click="complete(task.id)" >
                                                Complete
                                            </button>
                                        </div>
                                    </div>  
                                </div>  
                            </div>  
                        </div>  
                    </div>  

                    <!-- Done Category -->
                    <div class="column">  
                        <div class="card">  
                            <div class="card-body">  
                                <h5 class="card-title">Done (Total task: <b>{{ formDetail.done.length }}</b>) </h5>  
                                <!-- Content for Done -->  
                                <p ng-if="formDetail.done.length < 1">Completed tasks</p>
                                <div ng-repeat="task in formDetail.done" class="card mb-2">  <!-- Repeat for each task -->  
                                    <div class="card-body">  
                                        <h5 style="border-bottom: 1px solid black;">Title: <br/>{{ task.content_title }}</h5>  
                                        <br/>  
                                        <div ng-if="task.type == 2"  style="border-bottom: 1px solid black;">  <!-- Check if task type is checkbox -->  
                                            <p>Check List:</p>  
                                            <ul>  
                                                <li ng-repeat="item in task.done_parsedDescription">
                                                    {{ item.item }}
                                                    <span ng-if="item.checked == 'true'">
                                                        <i class="bi bi-check-circle text-success"></i> <!-- Bootstrap Icons -->
                                                    </span>
                                                </li>
                                            </ul>  
                                        </div> 
                                        <div ng-if="task.type == 1">  <!-- Check if task type is plain text -->  
                                            <p style="border-bottom: 1px solid black;">Description: <br/>{{ task.content_description }}</p>  
                                        </div>  
                                        <br/>  
                                        <p style="border-bottom: 1px solid black;">Due Date: <br/>{{ task.due_date }}</p>  
                                        <br/>  
                                        <p style="border-bottom: 1px solid black;"><i>Created by: <br/>{{ task.created_user }}</i></p>  
                                    </div>  
                                </div>    
                            </div>  
                        </div>  
                    </div>  
                </div>  
            </div> <!-- end of flex-container -->  
        
        </div> <!-- container -->  

        <!-- Edit Todo Task Modal -->  
        <div class="modal fade" id="editTodoTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTodoTaskModalLabel" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="editTodoTaskModalLabel">Edit Todo Task {{ editedTask.id }}</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="closeEditTodoData()">  
                            <span aria-hidden="true">&times;</span>  
                        </button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="editTaskForm">
                            <div class="form-group">  
                                <label for="taskTitle">Title</label><small style="color:red;"><i> *required</i></small>
                                <input type="text" class="form-control" id="taskTitle" ng-model="editedTask.content_title" required>  
                            </div>  
                            <div class="form-group" ng-if="editedTask.type == 1">  
                                <label for="taskDescription">Description</label><small style="color:red;"><i> *required</i></small>
                                <textarea class="form-control" id="taskDescription" ng-model="editedTask.content_description" required></textarea>  
                            </div>  
                            <div class="form-group" ng-if="editedTask.type == 2">  
                                <label for="taskBulletDescription">Check List</label><small style="color:red;"><i> *required</i></small>
                                <br/>
                                <small style="color: red;"><i>Enter Checkbox Details One By One</i></small>
                                <ul class="list-unstyled">
                                    <li ng-repeat="item in editedTask.todo_parsedDescription">
                                        <input type="checkbox" ng-model="item.checked" ng-true-value="'true'" ng-false-value="'false'"> <!-- Checkbox -->
                                        <input type="text" class="form-control d-inline-block w-75" ng-model="item.item" placeholder="Enter task detail">
                                        <button type="button" class="btn btn-danger btn-sm" ng-click="removeTodoTaskDetail($index)">x</button>
                                    </li>
                                </ul>

                                <button type="button" class="btn btn-primary btn-sm mt-2" ng-click="addTodoTaskDetail()">+ Add Item</button>
                            </div>  
                            <div class="form-group">  
                                <label for="taskDueDate">Due Date</label>
                                <br/>
                                <small style="color: red;"><i>Keep Empty If Still Same</i></small>
                                <input type="datetime-local" class="form-control" id="taskDueDate" ng-model="editedTask.due_date" required>  
                            </div> 
                            <input type="hidden" ng-model="editedTask.id"> <!-- Hidden input for task ID -->  
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closeEditTodoData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitEditTodoTask()" ng-disabled="(editedTask.type == 1 && !editedTask.content_description) || (editedTask.type == 2 && !editedTask.todo_parsedDescription) || !editedTask.content_title || !editedTask.type || !editedTask.due_date">Save changes</button>  
                    </div>  
                </div>  
            </div>  
        </div>  

        <!-- Edit Doing Task Modal -->  
        <div class="modal fade" id="editDoingTaskModal" tabindex="-1" role="dialog" aria-labelledby="editDoingTaskModalLabel" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="editDoingTaskModalLabel">Edit Doing Task {{ editedDoingTask.id }}</h5>  
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="closeEditDoingData()">  
                            <span aria-hidden="true">&times;</span>  
                        </button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="editDoingTaskForm">
                            <div class="form-group">  
                                <label for="doingtaskTitle">Title</label><small style="color:red;"><i> *required</i></small>
                                <input type="text" class="form-control" id="doingtaskTitle" ng-model="editedDoingTask.content_title" required>  
                            </div>  
                            <div class="form-group" ng-if="editedDoingTask.type == 1">  
                                <label for="doingtaskDescription">Description</label><small style="color:red;"><i> *required</i></small>
                                <textarea class="form-control" id="doingtaskDescription" ng-model="editedDoingTask.content_description" required></textarea>  
                            </div>  
                            <div class="form-group" ng-if="editedDoingTask.type == 2">  
                                <label for="taskBulletDescription">Check List</label><small style="color:red;"><i> *required</i></small>
                                <br/>
                                <small style="color: red;"><i>Enter Checkbox Details One By One</i></small>
                                <ul class="list-unstyled">
                                    <li ng-repeat="item in editedDoingTask.doing_parsedDescription">
                                        <input type="checkbox" ng-model="item.checked" ng-true-value="'true'" ng-false-value="'false'"> <!-- Checkbox -->
                                        <input type="text" class="form-control d-inline-block w-75" ng-model="item.item" placeholder="Enter task detail">
                                        <button type="button" class="btn btn-danger btn-sm" ng-click="removeDoingTaskDetail($index)">x</button>
                                    </li>
                                </ul>

                                <button type="button" class="btn btn-primary btn-sm mt-2" ng-click="addDoingTaskDetail()">+ Add Item</button>
                            </div>  
                            <div class="form-group">  
                                <label for="doingtaskDueDate">Due Date</label>
                                <br/>
                                <small style="color: red;"><i>Keep Empty If Still Same</i></small>
                                <input type="datetime-local" class="form-control" id="doingtaskDueDate" ng-model="editedDoingTask.due_date" required>  
                            </div> 
                            <input type="hidden" ng-model="editedDoingTask.id"> <!-- Hidden input for task ID -->  
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closeEditDoingData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitEditDoingTask()">Save changes</button>  
                    </div>  
                </div>  
            </div>  
        </div>  

        <!-- Add Todo Task Modal -->  
        <div class="modal fade" id="addTodoTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTodoTaskModalLabel" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="addTodoTaskModalLabel">Add Todo Task</h5>  
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" ng-click="closeAddTodoData()"></button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="addTaskForm">
                            <div class="form-group">  
                                <label for="add_todo_taskTitle">Title</label><small style="color:red;"><i> *required</i></small>
                                <input type="text" class="form-control" id="add_todo_taskTitle" ng-model="addTodoTask.content_title" required>  
                            </div>  
                            <div class="form-group">  
                                <label>Task Type</label><small style="color:red;"><i> *required</i></small>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="addTodoTask.type" value="1"> Description
                                    </label>
                                    <label class="radio-inline ml-3">
                                        <input type="radio" ng-model="addTodoTask.type" value="2"> Bullet Points
                                    </label>
                                </div>
                            </div>  
                            <div class="form-group" ng-if="addTodoTask.type == 1">  
                                <label for="add_todo_taskDescription">Description</label><small style="color:red;"><i> *required</i></small>
                                <textarea class="form-control" id="add_todo_taskDescription" ng-model="addTodoTask.content_description" required></textarea>  
                            </div>  
                            <div class="form-group" ng-if="addTodoTask.type == 2">  
                                <label for="add_todo_taskBulletDescription">Bullet Points</label><small style="color:red;"><i> *required</i></small>
                                <br/>
                                <small style="color: red;"><i>Enter Checkbox Details One By One</i></small>
                                <ul class="list-unstyled">
                                    <li ng-repeat="item in addTodoTask.add_todo_checkList">
                                        <input type="checkbox" ng-model="item.checked" ng-true-value="'true'" ng-false-value="'false'"> <!-- Checkbox -->
                                        <input type="text" class="form-control d-inline-block w-75" ng-model="item.item" placeholder="Enter task details">
                                        <button type="button" class="btn btn-danger btn-sm" ng-click="removeAddTodoTaskDetail($index)">x</button>
                                    </li>
                                </ul>

                                <button type="button" class="btn btn-primary btn-sm mt-2" ng-click="addTodoTaskDetail_new()">+ Add Item</button>
                            </div>  
                            <div class="form-group">  
                                <label for="taskDueDate">Due Date</label><small style="color:red;"><i> *required</i></small>
                                <br/>
                                <input type="datetime-local" class="form-control" id="taskDueDate" ng-model="addTodoTask.due_date" required>  
                            </div> 
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closeAddTodoData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitAddTodoTask()" ng-disabled="(addTodoTask.type == 1 && !addTodoTask.content_description) || (addTodoTask.type == 2 && !addTodoTask.add_todo_checkList) || !addTodoTask.content_title || !addTodoTask.type || !addTodoTask.due_date">Create Task</button>  
                    </div>  
                </div>  
            </div>  
        </div>  

        <!-- Add Doing Task Modal -->  
        <div class="modal fade" id="addDoingTaskModal" tabindex="-1" role="dialog" aria-labelledby="addDoingTaskModal" aria-hidden="true">  
            <div class="modal-dialog" role="document">  
                <div class="modal-content">  
                    <div class="modal-header">  
                        <h5 class="modal-title" id="addDoingTaskModal">Add Doing Task</h5>  
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" ng-click="closeAddDoingData()"></button>  
                    </div>  
                    <div class="modal-body">  
                        <form id="addTaskDoingForm">
                            <div class="form-group">  
                                <label for="add_doing_taskTitle">Title</label><small style="color:red;"><i> *required</i></small>
                                <input type="text" class="form-control" id="add_doing_taskTitle" ng-model="addDoingTask.content_title" required>  
                            </div>  
                            <div class="form-group">  
                                <label>Task Type</label><small style="color:red;"><i> *required</i></small>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="addDoingTask.type" value="1"> Description
                                    </label>
                                    <label class="radio-inline ml-3">
                                        <input type="radio" ng-model="addDoingTask.type" value="2"> Bullet Points
                                    </label>
                                </div>
                            </div>  
                            <div class="form-group" ng-if="addDoingTask.type == 1">  
                                <label for="add_doing_taskDescription">Description</label><small style="color:red;"><i> *required</i></small>
                                <textarea class="form-control" id="add_doing_taskDescription" ng-model="addDoingTask.content_description" required></textarea>  
                            </div>  
                            <div class="form-group" ng-if="addDoingTask.type == 2">  
                                <label for="add_doing_taskBulletDescription">Bullet Points</label><small style="color:red;"><i> *required</i></small>
                                <br/>
                                <small style="color: red;"><i>Enter Checkbox Details One By One</i></small>
                                <ul class="list-unstyled">
                                    <li ng-repeat="item in addDoingTask.add_todo_checkList">
                                        <input type="checkbox" ng-model="item.checked" ng-true-value="'true'" ng-false-value="'false'"> <!-- Checkbox -->
                                        <input type="text" class="form-control d-inline-block w-75" ng-model="item.item" placeholder="Enter task details">
                                        <button type="button" class="btn btn-danger btn-sm" ng-click="removeAddDoingTaskDetail($index)">x</button>
                                    </li>
                                </ul>

                                <button type="button" class="btn btn-primary btn-sm mt-2" ng-click="addDoingTaskDetail_new()">+ Add Item</button>
                            </div>  
                            <div class="form-group">  
                                <label for="addtaskDoingDueDate">Due Date</label><small style="color:red;"><i> *required</i></small>
                                <br/>
                                <input type="datetime-local" class="form-control" id="addtaskDoingDueDate" ng-model="addDoingTask.due_date" required>  
                            </div> 
                        </form>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="closeAddDoingData()">Close</button>  
                        <button type="button" class="btn btn-primary" ng-click="submitAddDoingTask()" ng-disabled="(addDoingTask.type == 1 && !addDoingTask.content_description) || (addDoingTask.type == 2 && !addDoingTask.add_todo_checkList) || !addDoingTask.content_title || !addDoingTask.type || !addDoingTask.due_date">Create Task</button>  
                    </div>  
                </div>  
            </div>  
        </div> 

        <!-- Members Modal -->
        <div class="modal fade" id="membersModal" tabindex="-1" aria-labelledby="membersModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="membersModalLabel">Members List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <div>
                                <strong>Leader Name: {{ formDetail.leader_name }}</strong><br>
                                <small>Leader Email: {{ formDetail.leader_email }}</small>
                            </div>
                            <div ng-if="userId != formDetail.owned_by">
                                <button class="btn btn-info btn-sm">Request Leader</button>
                            </div>
                            <br/>
                            <b>Members:</b>
                            <li class="list-group-item d-flex justify-content-between align-items-center" ng-repeat="member in formDetail.members">
                                <div>
                                    <strong>Name: {{ member.name }}</strong><br>
                                    <small>Email: {{ member.email }}</small>
                                </div>
                                <div ng-if="userId == formDetail.owned_by">
                                    <button class="btn btn-info btn-sm" ng-click="transferOwner(member.id)">Transfer</button>
                                    <button class="btn btn-danger btn-sm" ng-click="removeMember(member.id)">Remove</button>
                                </div>
                            </li>
                            <br/>
                            <div class="btn-group w-100" role="group">
                                <button class="btn btn-danger me-2" style="color: white; font-weight: bold;" ng-click="leaveKanban">
                                    Leave Kanban
                                </button>
                                <button class="btn btn-success" style="font-weight: bold;" ng-click="openInvite()" >
                                    Invite Member
                                </button>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invite Modal -->
        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="inviteModalLabel">Available Users</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center" ng-repeat="user in available_users">
                                <div>
                                    <strong>Name: {{ user.name }}</strong><br>
                                    <small>Email: {{ user.email }}</small>
                                </div>
                                <div>
                                    <button class="btn btn-info btn-sm" ng-click="inviteUser(user.id)">Invite</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Modal -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">Notification</h5>
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
                                    <tr ng-repeat="item in notification">  
                                        <td class="align-middle" ng-bind-html="item.message"></td>  
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



        <script>
            var app = angular.module("myApp",['720kb.datepicker', 'ui.bootstrap', 'ngSanitize']);
           
            app.controller("myCtrl", function($scope, $http, $timeout, $location, $sce) {

                $scope.errorAlert = false;
                $scope.error_msg = "";
                $scope.id = "<?= isset($id) && !empty($id) ? $id : '' ?>";
                $scope.minDate = new Date().toISOString().split("T")[0]; // "YYYY-MM-DD" 

                // get user id from url start
                //// Get the full URL  
                var fullPath = $location.absUrl();   

                //// Regular expression to match userId  
                var match = fullPath.match(/[?&]userId=(\d+)/); // Matches userId= followed by digits  

                if (match) {  
                    $scope.userId = match[1]; // Get the first capturing group  
                } else {  
                    $scope.userId = null; // If no match found  
                }  

                //// Get the full URL end

                //// Log the userId for confirmation  
                console.log("userId:", $scope.userId); 
                // get user id from url end

                // $scope.token = getCookie("token");

                $scope.kanban_complete = function() {

                    const data = {  
                        id: $scope.id, 
                        user_id: $scope.userId,
                    };

                    if (confirm("Are you sure you completed this kanban?")) {

                        // Call the moving data API  
                        $http.post("<?= base_url('api/completeKanban') ?>", data).then(function(response) {  

                            if (response.data.status == "OK") {  
                                
                                // Trigger fireworks animation
                                triggerFireworks();

                                alert("Congratulation! You're Completed This Kanban!");

                                // Delay redirect to let animation play
                                setTimeout(function() {
                                    location.href = '<?= base_url('profile/') ?>' + $scope.userId;
                                }, 5050);  

                            } else {  
                                alert(response.data.result);  
                            }  

                        }, function(response) {  
                            alert("Error: " + response.data.result);  
                        });  
                        } else {
                        return; 
                        }
                }

                $scope.showMember = function() {  

                    $('#membersModal').modal('show'); // Show the modal  
                };  

                // add todo task start
                $scope.addTodoTask = {};

                //// open model
                $scope.addTodoData = function(task) {  
                    $('#addTodoTaskModal').modal('show'); // Show the modal  
                };  

                //// Function to close the modal
                $scope.closeAddTodoData = function(task) {  
                    $scope.addTodoTask = {};
                    $('#addTodoTaskModal').modal('hide'); // Close the modal 
                };                  

                //// Function to add a new task item in add todo task modal
                $scope.addTodoTaskDetail_new = function() {
                    $scope.addTodoTask.add_todo_checkList = [];
                    $scope.addTodoTask.add_todo_checkList.push({ item: "", checked: false });
                };

                //// Function to remove a task item
                $scope.removeAddTodoTaskDetail = function(index) {
                    $scope.addTodoTask.add_todo_checkList.splice(index, 1);
                };

                //// Function to send the add data to api
                $scope.submitAddTodoTask = function() {  
                    console.log("date", $scope.addTodoTask.due_date);

                    // Convert due_date to required format
                    if ($scope.addTodoTask.due_date) {
                        let dateObj = new Date($scope.addTodoTask.due_date);
                        let year = dateObj.getFullYear();
                        let month = String(dateObj.getMonth() + 1).padStart(2, "0"); // Month is 0-based
                        let day = String(dateObj.getDate()).padStart(2, "0");
                        let hours = String(dateObj.getHours()).padStart(2, "0");
                        let minutes = String(dateObj.getMinutes()).padStart(2, "0");
                        let seconds = "00"; // If you don't have seconds input

                        $scope.addTask_todo_formatted_due_date = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                    }

                    // Convert bullet points into the desired format if type is 2  
                    if ($scope.addTodoTask.type == 2) {
                        $scope.addTodoTask.content_description = JSON.stringify($scope.addTodoTask.add_todo_checkList);
                    }

                    const data = {  
                        mode: "Add",  
                        id: $scope.addTodoTask.id,  
                        kanban_id: $scope.id, 
                        content_title: $scope.addTodoTask.content_title,  
                        content_description: $scope.addTodoTask.content_description,  
                        due_date: $scope.addTask_todo_formatted_due_date,  
                        created_by: $scope.userId,
                        type: $scope.addTodoTask.type,
                        status: 1,
                    };

                    console.log("data", data);
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/submitKanbanDetailsTodo') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('Task added successfully:', response.data);
                            location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error adding task:', error);  
                        });  
                };  
                // add todo task end

                //// add doing task start
                $scope.addDoingTask = {};

                //// open model
                $scope.addDoingData = function(task) {  
                    $('#addDoingTaskModal').modal('show'); // Show the modal  
                };  

                //// Function to close the modal
                $scope.closeAddDoingData = function(task) {  
                    $scope.addDoingTask = {};
                    $('#addDoingTaskModal').modal('hide'); // Close the modal 
                };                  

                //// Function to add a new task item in add todo task modal
                $scope.addDoingTaskDetail_new = function() {
                    $scope.addDoingTask.add_todo_checkList = [];
                    $scope.addDoingTask.add_todo_checkList.push({ item: "", checked: false });
                };

                //// Function to remove a task item
                $scope.removeAddDoingTaskDetail = function(index) {
                    $scope.addDoingTask.add_todo_checkList.splice(index, 1);
                };

                //// Function to send the add data to api
                $scope.submitAddDoingTask = function() {  
                    console.log("date", $scope.addDoingTask.due_date);

                    // Convert due_date to required format
                    if ($scope.addDoingTask.due_date) {
                        let dateObj = new Date($scope.addDoingTask.due_date);
                        let year = dateObj.getFullYear();
                        let month = String(dateObj.getMonth() + 1).padStart(2, "0"); // Month is 0-based
                        let day = String(dateObj.getDate()).padStart(2, "0");
                        let hours = String(dateObj.getHours()).padStart(2, "0");
                        let minutes = String(dateObj.getMinutes()).padStart(2, "0");
                        let seconds = "00"; // If you don't have seconds input

                        $scope.addTask_doing_formatted_due_date = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                    }

                    // Convert bullet points into the desired format if type is 2  
                    if ($scope.addDoingTask.type == 2) {
                        $scope.addDoingTask.content_description = JSON.stringify($scope.addDoingTask.add_todo_checkList);
                    }

                    const data = {  
                        mode: "Add",  
                        id: $scope.addDoingTask.id,  
                        kanban_id: $scope.id, 
                        content_title: $scope.addDoingTask.content_title,  
                        content_description: $scope.addDoingTask.content_description,  
                        due_date: $scope.addTask_doing_formatted_due_date,  
                        created_by: $scope.userId,
                        type: $scope.addDoingTask.type,
                        status: 1,
                    };

                    console.log("data", data);
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/submitKanbanDetailsDoing') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('Task added successfully:', response.data);
                            location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error adding task:', error);  
                        });  
                };  
                // add doing task end


                // edit todo task start
                $scope.editedTask = {}; // Object to hold the edited task data  

                //// Copy the data from task and open modal
                $scope.editTodoData = function(task) {  
                    $scope.editedTask = angular.copy(task); // Copy the task data to the editedTask object  

                    // If the task is of type 2, convert the JSON string back to an array of objects  
                    if (task.type == 2 && task.todo_parsedDescription) {  
                        $scope.editedTask.todo_parsedDescription = task.todo_parsedDescription;
                    }  

                    $('#editTodoTaskModal').modal('show'); // Show the modal  
                };  

                //// Function to close the modal
                $scope.closeEditTodoData = function(task) {  
                    $('#editTodoTaskModal').modal('hide'); // CLose the modal  
                };  

                //// Ensure it initializes as an array
                $scope.editedTask.todo_parsedDescription = $scope.editedTask.todo_parsedDescription || []; 

                //// Function to add a new task item in edit modal
                $scope.addTodoTaskDetail = function() {
                    $scope.editedTask.todo_parsedDescription.push({ item: "", checked: false });
                };

                //// Function to remove a task item
                $scope.removeTodoTaskDetail = function(index) {
                    $scope.editedTask.todo_parsedDescription.splice(index, 1);
                };

                //// Function to send the edited data to api
                $scope.submitEditTodoTask = function() {  
                    console.log("date", $scope.editedTask.due_date);

                    // Convert due_date to required format
                    if ($scope.editedTask.due_date) {
                        let dateObj = new Date($scope.editedTask.due_date);
                        let year = dateObj.getFullYear();
                        let month = String(dateObj.getMonth() + 1).padStart(2, "0"); // Month is 0-based
                        let day = String(dateObj.getDate()).padStart(2, "0");
                        let hours = String(dateObj.getHours()).padStart(2, "0");
                        let minutes = String(dateObj.getMinutes()).padStart(2, "0");
                        let seconds = "00"; // If you don't have seconds input

                        $scope.todo_formatted_due_date = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                    }

                    // Convert bullet points into the desired format if type is 2  
                    if ($scope.editedTask.type == 2) {
                        $scope.editedTask.content_description = JSON.stringify($scope.editedTask.todo_parsedDescription);
                    }

                    const data = {  
                        mode: "Edit",  
                        id: $scope.editedTask.id,  
                        kanban_id: $scope.id, 
                        content_title: $scope.editedTask.content_title,  
                        content_description: $scope.editedTask.content_description,  
                        due_date: $scope.todo_formatted_due_date,  
                        user_id: $scope.userId,
                    };

                    console.log("data", data);
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/editKanbanDetailsTodo') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('Task updated successfully:', response.data);  
                            $('#editTodoTaskModal').modal('hide'); // Hide the modal  
                            location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error updating task:', error);  
                        });  
                };  
                // edit todo task end

                // edit doing task start
                $scope.editedDoingTask = {}; // Object to hold the edited task data  

                //// Copy the data from task and open modal
                $scope.editDoingData = function(task) {  
                    $scope.editedDoingTask = angular.copy(task); // Copy the task data to the editedTask object  

                    // If the task is of type 2, convert the JSON string back to an array of objects  
                    if (task.type == 2 && task.doing_parsedDescription) {  
                        $scope.editedDoingTask.doing_parsedDescription = task.doing_parsedDescription;
                    }  

                    $('#editDoingTaskModal').modal('show'); // Show the modal  
                };  

                //// Function to close the modal
                $scope.closeEditDoingData = function(task) {  
                    $('#editDoingTaskModal').modal('hide'); // CLose the modal  
                };  

                //// Ensure it initializes as an array
                $scope.editedDoingTask.doing_parsedDescription = $scope.editedDoingTask.doing_parsedDescription || []; 

                //// Function to add a new task item in edit modal
                $scope.addDoingTaskDetail = function() {
                    $scope.editedDoingTask.doing_parsedDescription.push({ item: "", checked: false });
                };

                //// Function to remove a task item
                $scope.removeDoingTaskDetail = function(index) {
                    $scope.editedDoingTask.todo_parsedDescription.splice(index, 1);
                };

                //// Function to send the edited data to api
                $scope.submitEditDoingTask = function() {  
                    console.log("date", $scope.editedDoingTask.due_date);

                    // Convert bullet points into the desired format if type is 2  
                    if ($scope.editedDoingTask.type == 2) {
                        $scope.editedDoingTask.content_description = JSON.stringify($scope.editedDoingTask.doing_parsedDescription);
                    }

                    // Convert due_date to required format
                    if ($scope.editedDoingTask.due_date) {
                        let dateObj = new Date($scope.editedDoingTask.due_date);
                        let year = dateObj.getFullYear();
                        let month = String(dateObj.getMonth() + 1).padStart(2, "0"); // Month is 0-based
                        let day = String(dateObj.getDate()).padStart(2, "0");
                        let hours = String(dateObj.getHours()).padStart(2, "0");
                        let minutes = String(dateObj.getMinutes()).padStart(2, "0");
                        let seconds = "00"; // If you don't have seconds input

                        $scope.doing_formatted_due_date = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                    }

                    const data = {  
                        mode: "Edit",  
                        id: $scope.editedDoingTask.id,  
                        kanban_id: $scope.id, 
                        content_title: $scope.editedDoingTask.content_title,  
                        content_description: $scope.editedDoingTask.content_description,  
                        due_date: $scope.doing_formatted_due_date,  
                        user_id: $scope.userId,
                    };
                          
                    // Make the API call  
                    $http.post("<?= base_url('api/editKanbanDetailsDoing') ?>", data).then(function(response) {  
                        if (response.data.status == "OK") {
                            // Handle success  
                            console.log('Task updated successfully:', response.data);  
                            $('#editDoingTaskModal').modal('hide'); // Hide the modal  
                            location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
                        }   else {
                            $scope.errorAlert = true;
                            $scope.error_msg = response.data.result;
                        }
                    })  
                        .catch(function(error) {  
                            // Handle error  
                            console.error('Error updating task:', error);  
                        });  
                };  
                // edit doing task end

                $scope.moveToDoing = function(taskId) {  

                    // Prepare the data to be sent to the API  
                    const data = {  
                        mode: "Edit",  
                        id: taskId,  
                        kanban_id: $scope.id,
                        user_id: $scope.userId,
                    }; 

                    if (confirm("Are you sure you want to move this task from Todo to Doing?")) {

                        // Call the moving data API  
                        $http.post("<?= base_url('api/go_to_doing') ?>", data).then(function(response) {  
                            if (response.data.status == "OK") {  
                                alert("Task moved successfully!");  
                                location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
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

                $scope.complete = function(taskId) {  

                    // Prepare the data to be sent to the API  
                    const data = {  
                        mode: "Edit",  
                        id: taskId,  
                        kanban_id: $scope.id,
                        user_id: $scope.userId,
                    }; 

                    if (confirm("Are you sure you completed the task?")) {

                        // Call the moving data API  
                        $http.post("<?= base_url('api/completed') ?>", data).then(function(response) {  
                            if (response.data.status == "OK") {  
                                  
                                // Trigger fireworks animation
                                triggerFireworks();

                                alert("Congratulation!");

                                // Delay redirect to let animation play for 3 seconds
                                setTimeout(function() {
                                    location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
                                }, 5000);  


                                // location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId;
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

                // Function to trigger fireworks using confetti.js
                function triggerFireworks() {
                    if (typeof window.confetti === 'function') { // Check if confetti is available
                        var duration = 3 * 1000; // 3 seconds
                        var end = Date.now() + duration;

                        function frame() {
                            confetti({ // Use window.confetti
                                particleCount: 5, 
                                spread: 80,
                                origin: { y: 0.6 }
                            });

                            if (Date.now() < end) {
                                requestAnimationFrame(frame);
                            }
                        }
                        frame();
                    } else {
                        console.error("Confetti library not loaded.");
                    }
                }

                // transfer owner of kanban (only by current leader)
                $scope.transferOwner = function(memberId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        member_id: memberId,
                        id: $scope.formDetail.id,
                        owned_by: $scope.formDetail['owned_by'],
                    };  

                    if (confirm("Are you sure you want to transfer your leader role to this member?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/transferLeader') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                alert("Leader Transfer successfully!"); 
                                location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId; 
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

                // remove member from kanban
                $scope.removeMember = function(memberId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        member_id: memberId,
                        kanban_id: $scope.formDetail.id,
                    };  

                    if (confirm("Are you sure you want to remove this member?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/removeMember') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                alert("Member Removed successfully!"); 
                                location.href = '<?= base_url('kanban/') ?>' + $scope.id + '?userId=' + $scope.userId; 
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

                // open invite modal and get available users
                $scope.openInvite = function() {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        kanban_id: $scope.formDetail.id,
                    };  

                    if (confirm("Do You Want To Invite New Member?")) {

                        // Call the get available users API   
                        $http.post("<?= base_url('api/getAvailableUsers') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                $scope.available_users = response.data.result.available_user;
                                console.log('available user', $scope.available_users);
                                $('#inviteModal').modal('show'); // Show the modal  
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

                // invite user
                $scope.inviteUser = function(invited_userId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        kanban_id: $scope.formDetail.id,
                        invited_user_id: invited_userId,
                        current_user_id: $scope.userId,
                    };  

                    if (confirm("Are You Sure You Want To Invite This User?")) {

                        // Call the invite member API   
                        $http.post("<?= base_url('api/inviteUser') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                $('#inviteModal').modal('hide'); // hide the modal
                                $('#membersModal').modal('hide');  
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

                // leave kanban
                $scope.leaveKanban = function(memberId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        user_id: $scope.userId,
                        kanban_id: $scope.formDetail.id,
                    };  

                    if (confirm("Are you sure you want to leave this kanban?")) {

                        // Call the quit API   
                        $http.post("<?= base_url('api/quitKanban') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                alert("You Are Quit This Kanban."); 
                                location.href = '<?= base_url('profile/') ?>' + $scope.userId; 
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

                // Function delete (soft delete) a Todo Task
                $scope.deleteTodoTask = function(taskId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        id: taskId,
                        user_id: $scope.userId,
                    };  

                    if (confirm("Are you sure you want to delete this task from Todo?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/deleteTodo') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                $scope.formDetail.todo = $scope.formDetail.todo.filter(task => task.id !== taskId);  
                                alert("Task deleted successfully!");  
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

                // Function delete (soft delete) a Doing Task
                $scope.deleteDoingTask = function(taskId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        id: taskId,
                        user_id: $scope.userId,
                    };  

                    if (confirm("Are you sure you want to delete this task from Doing?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/deleteDoing') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                // Optionally, remove the task from the UI if needed  
                                $scope.formDetail.doing = $scope.formDetail.doing.filter(task => task.id !== taskId);  
                                alert("Task deleted successfully!");  
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

                // Function delete (soft delete) a Done Task
                $scope.deleteDoneTask = function(taskId) {  
                    // Prepare the data to be sent to the API  
                    var dataToSend = {  
                        id: taskId  
                    };  

                    if (confirm("Are you sure you want to delete this task from Done?")) {

                        // Call the delete API  
                        $http.post("<?= base_url('api/deleteKanbanDetailsDone') ?>", dataToSend).then(function(response) {  
                            if (response.data.status == "OK") {  
                                // Optionally, remove the task from the UI if needed  
                                $scope.formDetail.done = $scope.formDetail.done.filter(task => task.id !== taskId);  
                                alert("Task deleted successfully!");  
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

                $scope.openNotificationData = function(task) {  
                    $('#notificationModal').modal('show'); // Show the modal  
                };  

                console.log("id", $scope.id);
                console.log("subtitle", $scope.mode);
                
                if ($scope.id != "") {

                    $http.get("<?= base_url('api/kanban_details') ?>/" + $scope.id ).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("kanban response", response);
                            $scope.errorAlert = false;
                            $scope.formDetail = response.data.result.kanbanDetail;
                            console.log("formDetail", $scope.formDetail);
                            
                            // Initialize an array to hold parsed descriptions  
                            $scope.todo_parsedDescriptions = [];
                            $scope.doing_parsedDescriptions = [];
                            $scope.done_parsedDescriptions = [];  

                            // Iterate over each task in the todo array  
                            $scope.formDetail.todo.forEach(function(task) {  
                                if (task.type == 2) { // Check if task type is checkbox  
                                    try {  
                                        // Parse the content_description and store it in the array  
                                        task.todo_parsedDescription = JSON.parse(task.content_description);  
                                    } catch (e) {  
                                        console.error("Error parsing JSON for task todo:", task, e);  
                                        task.todo_parsedDescription = []; // Fallback to an empty array if parsing fails  
                                    }  
                                } else {  
                                    task.todo_parsedDescription = null; // For other task types, set to null or any default value  
                                }  
                            });
                            
                            // Iterate over each task in the todo array  
                            $scope.formDetail.doing.forEach(function(task) {  
                                if (task.type == 2) { // Check if task type is checkbox  
                                    try {  
                                        // Parse the content_description and store it in the array  
                                        task.doing_parsedDescription = JSON.parse(task.content_description);  
                                    } catch (e) {  
                                        console.error("Error parsing JSON for task doing:", task, e);  
                                        task.doing_parsedDescription = []; // Fallback to an empty array if parsing fails  
                                    }  
                                } else {  
                                    task.doing_parsedDescription = null; // For other task types, set to null or any default value  
                                }  
                            });

                            // Iterate over each task in the todo array  
                            $scope.formDetail.done.forEach(function(task) {  
                                if (task.type == 2) { // Check if task type is checkbox  
                                    try {  
                                        // Parse the content_description and store it in the array  
                                        task.done_parsedDescription = JSON.parse(task.content_description);  
                                    } catch (e) {  
                                        console.error("Error parsing JSON for task done:", task, e);  
                                        task.done_parsedDescription = []; // Fallback to an empty array if parsing fails  
                                    }  
                                } else {  
                                    task.done_parsedDescription = null; // For other task types, set to null or any default value  
                                }  
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

                    $http.get("<?= base_url('api/kanban_notification') ?>/" + $scope.id ).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("kanban notification response", response);
                            $scope.errorAlert = false;
                            $scope.notification = response.data.result.notificationDetail;
                            console.log("notification", $scope.notification);

                            $scope.notification.forEach(function(item) {
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

                if ($scope.userId != "") {

                    $http.get("<?= base_url('api/getCurrentUser') ?>/" + $scope.userId ).then(function(response) {

                        if (response.data.status == "OK") {
                            console.log("response", response);
                            $scope.errorAlert = false;
                            $scope.userData = response.data.result.userDetail;
                            console.log("response userData", $scope.userData);
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