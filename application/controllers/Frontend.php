<?php

//class name need to same with file name (first alphabet uppercase)
class Frontend extends CI_Controller {


    private $data = [];

    public function __construct(){
        //run the parent first
        parent::__construct();

        //load user model
        $this->load->model("User_model");

    }

    //===login page controller===//
    public function login() {

        //load the header
        $this->load->view("frontend_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("login", $this->data);
        
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===login page controller end===//

    //===register page controller===//
    public function register() {

        //load the header
        $this->load->view("frontend_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("register", $this->data);
        
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===register page controller end===//

    //===forgot password validation page controller===//
    public function forgot_password_validation() {

        //load the header
        $this->load->view("frontend_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("forgot_password_validation", $this->data);
        
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===forgot password validation page controller end===//

    //===reset password page controller===//
    public function reset_password($email) {

        $this->data['email'] = $email;

        //load the header
        $this->load->view("frontend_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("reset_password", $this->data);
        
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===reset password page controller end===//

    //===profile page controller===//
    public function home($id) {

        $userData = $this->User_model->getOne(array(
            'id' => $id,
        ));

        $this->data['userData'] = $userData;
        $this->data['id'] = $id;

        //load the header
        $this->load->view("frontend_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("frontend_profile", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===profile page controller end===//

    //===kanban page controller===//
    public function kanban($id) {

        //load kanban model
        $this->load->model("Kanban_list_model");

        $kanbanData = $this->Kanban_list_model->getOne(array(
            'id' => $id,
        ));

        $this->data['kanbanData'] = $kanbanData;
        $this->data['id'] = $id;

        //load the header
        $this->load->view("frontend_kanban_header", $this->data);
 
        //this will redirect to the content php of the home page
        //no s for view, even it is from views folder
        $this->load->view("frontend_kanban", $this->data);
 
        //load the footer
        $this->load->view("common_footer", $this->data);
    }
    //===kanban page controller end===//
}

?>