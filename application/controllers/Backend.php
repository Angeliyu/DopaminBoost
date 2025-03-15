<?php

//class name need to same with file name (first alphabet uppercase)
class Backend extends CI_Controller {


    private $data = [];

    public function __construct(){
        //run the parent first
        parent::__construct();

    }

    //===user list page controller===//
    public function admin_userList() {

        //load the header
        $this->load->view("common_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_user/list", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===user list controller end===//

    //===user add page controller===//
    public function add_user($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}

        //load user model
        $this->load->model("User_model");

        //load user_role model
        $this->load->model("User_role_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $roleList = $this->User_role_model->get_where([
			'is_deleted' => 0,
		]);

        $this->data['userList'] = $userList;
        $this->data['roleList'] = $roleList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_user/add", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===user add controller end===//

    //===kanban list page controller===//
    public function admin_kanbanList() {

        //load the header
        $this->load->view("common_header", $this->data);
    
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_list/list", $this->data);
    
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban list controller end===//

    //===kanban add page controller===//
    public function add_kanban($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}
        

        //load kanban list model
        $this->load->model("Kanban_list_model");

        //load user model
        $this->load->model("User_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanList = $this->Kanban_list_model->get_where([
			'is_deleted' => 0,
		]);

        $this->data['userList'] = $userList;
        $this->data['kanbanList'] = $kanbanList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_list/add", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban add controller end===//

    //===kanban details todo page controller===//
    public function admin_kanban_details_todo() {

        //load the header
        $this->load->view("common_header", $this->data);
    
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_details_todo/list", $this->data);
    
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban details todo controller end===//

    //===kanban details todo add page controller===//
    public function add_kanban_details_todo($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}
        

        //load kanban details todo model
        $this->load->model("Kanban_details_todo_model");

        //load user model
        $this->load->model("User_model");

        //load kanban list model
        $this->load->model("Kanban_list_model");

        //load task type model
        $this->load->model("Task_type_model");

        //load task status model
        $this->load->model("Task_status_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanDetailsTodoList = $this->Kanban_details_todo_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanList = $this->Kanban_list_model->get_where([
			'is_deleted' => 0,
		]);

        $typeList = $this->Task_type_model->get_where([
			'is_deleted' => 0,
		]);

        $statusList = $this->Task_status_model->get_where([
			'is_deleted' => 0,
		]);


        $this->data['userList'] = $userList;
        $this->data['kanbanDetailsTodoList'] = $kanbanDetailsTodoList;
        $this->data['kanbanList'] = $kanbanList;
        $this->data['typeList'] = $typeList;
        $this->data['statusList'] = $statusList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_details_todo/add", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban details todo add controller end===//

    //===kanban details doing page controller===//
    public function admin_kanban_details_doing($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}
        

        //load kanban details todo model
        $this->load->model("Kanban_details_doing_model");

        //load user model
        $this->load->model("User_model");

        //load kanban list model
        $this->load->model("Kanban_list_model");

        //load task type model
        $this->load->model("Task_type_model");

        //load task status model
        $this->load->model("Task_status_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanDetailsDoingList = $this->Kanban_details_doing_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanList = $this->Kanban_list_model->get_where([
			'is_deleted' => 0,
		]);

        $typeList = $this->Task_type_model->get_where([
			'is_deleted' => 0,
		]);

        $statusList = $this->Task_status_model->get_where([
			'is_deleted' => 0,
		]);


        $this->data['userList'] = $userList;
        $this->data['kanbanDetailsDoingList'] = $kanbanDetailsDoingList;
        $this->data['kanbanList'] = $kanbanList;
        $this->data['typeList'] = $typeList;
        $this->data['statusList'] = $statusList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
    
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_details_doing/list", $this->data);
    
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban details doing controller end===//

    //===kanban details doing add page controller===//
    public function add_kanban_details_doing($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}
        

        //load kanban details todo model
        $this->load->model("Kanban_details_doing_model");

        //load user model
        $this->load->model("User_model");

        //load kanban list model
        $this->load->model("Kanban_list_model");

        //load task type model
        $this->load->model("Task_type_model");

        //load task status model
        $this->load->model("Task_status_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanDetailsDoingList = $this->Kanban_details_doing_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanList = $this->Kanban_list_model->get_where([
			'is_deleted' => 0,
		]);

        $typeList = $this->Task_type_model->get_where([
			'is_deleted' => 0,
		]);

        $statusList = $this->Task_status_model->get_where([
			'is_deleted' => 0,
		]);


        $this->data['userList'] = $userList;
        $this->data['kanbanDetailsDoingList'] = $kanbanDetailsDoingList;
        $this->data['kanbanList'] = $kanbanList;
        $this->data['typeList'] = $typeList;
        $this->data['statusList'] = $statusList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_details_doing/add", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban details doing add controller end===//

    //===kanban details done page controller===//
    public function admin_kanban_details_done($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}
        

        //load kanban details todo model
        $this->load->model("Kanban_details_todo_model");

        //load user model
        $this->load->model("User_model");

        //load kanban list model
        $this->load->model("Kanban_list_model");

        //load task type model
        $this->load->model("Task_type_model");

        //load task status model
        $this->load->model("Task_status_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanDetailsTodoList = $this->Kanban_details_todo_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanList = $this->Kanban_list_model->get_where([
			'is_deleted' => 0,
		]);

        $typeList = $this->Task_type_model->get_where([
			'is_deleted' => 0,
		]);

        $statusList = $this->Task_status_model->get_where([
			'is_deleted' => 0,
		]);


        $this->data['userList'] = $userList;
        $this->data['kanbanDetailsTodoList'] = $kanbanDetailsTodoList;
        $this->data['kanbanList'] = $kanbanList;
        $this->data['typeList'] = $typeList;
        $this->data['statusList'] = $statusList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
    
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_details_done/list", $this->data);
    
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban details done controller end===//

    //===kanban details done add page controller===//
    public function add_kanban_details_done($id = false, $mode = "") {

        if ($id == false) {
			$this->data['subTitle'] = "Add";
		} else {
			$this->data['subTitle'] = "Edit";
		}

        if (!empty($mode)) {
			$this->data['subTitle'] = $mode;
		}
        

        //load kanban details todo model
        $this->load->model("Kanban_details_done_model");

        //load user model
        $this->load->model("User_model");

        //load kanban list model
        $this->load->model("Kanban_list_model");

        //load task type model
        $this->load->model("Task_type_model");

        //load task status model
        $this->load->model("Task_status_model");

        $userList = $this->User_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanDetailsDoneList = $this->Kanban_details_done_model->get_where([
			'is_deleted' => 0,
		]);

        $kanbanList = $this->Kanban_list_model->get_where([
			'is_deleted' => 0,
		]);

        $typeList = $this->Task_type_model->get_where([
			'is_deleted' => 0,
		]);

        $statusList = $this->Task_status_model->get_where([
			'is_deleted' => 0,
		]);


        $this->data['userList'] = $userList;
        $this->data['kanbanDetailsDoneList'] = $kanbanDetailsDoneList;
        $this->data['kanbanList'] = $kanbanList;
        $this->data['typeList'] = $typeList;
        $this->data['statusList'] = $statusList;

        //load the header
        $this->load->view("common_header", $this->data);

        $this->data['mode'] = $mode;

		$this->data['id'] = $id;
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_kanban_details_done/add", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban details done add controller end===//

    //===kanban list page controller===//
    public function admin_notificationList() {

        //load the header
        $this->load->view("common_header", $this->data);
    
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("backend_notification_list/list", $this->data);
    
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban list controller end===//
}

?>