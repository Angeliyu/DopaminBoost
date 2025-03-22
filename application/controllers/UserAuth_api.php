<?php

//class name need to same with file name (first alphabet uppercase)
class UserAuth_api extends CI_Controller 
{
    private $data = array();

    public function __construct(){
        //run the parent first
        parent::__construct();

        //load kanban_details_done model
        // $this->load->model("Kanban_details_done_model");

        $this->data['main_model'] = "User_model";
        $this->load->model($this->data['main_model']);

		$this->data['primaryKey'] = $this->{$this->data['main_model']}->primaryKey;

        //load notification model
        $this->load->model("Notification_model");

    }

    public function login()
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        try {
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $email = $this->input->post("email", true);
                $password = $this->input->post("password", true);

                $userData = $this->{$this->data['main_model']}->getOne([
                    'email' => $email,
                    'is_deleted' => 0,
                ]);

                if (empty($userData)) {
                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Email not found.',
                    ]);
                    return;
                } else {
                    
                    // check password
                    $encrypted_password = md5($password);

                    if ($encrypted_password != $userData['password']) {
                        // Return JSON error response
                        echo json_encode([
                            'status' => 'ERROR',
                            'message' => 'Password Not Match.',
                            'password' => $encrypted_password,
                        ]);
                        return;
                    } else {

                        // Generate a random token  
                        $token = bin2hex(random_bytes(32));  // Generate a secure token

                        $sql['token'] = $token;
                        $sql['modified_date'] = date("Y-m-d H:i:s");

                        $this->{$this->data['main_model']}->update(array(
                            'email' => $email,
                        ), $sql);

                        $new_userDate = $this->{$this->data['main_model']}->getOne([
                            'email' => $email,
                            'is_deleted' => 0,
                        ]);

                        // create a notification
                        $this->Notification_model->insert(array(
                            'type' => 14, // login
                            'created_by' => $userData['id'],
                            'kanban_id' => null,
                            'receiver' => $userData['id'],
                            'message' => 'You logged in dopamin Boost.',
                            'created_date' => date("Y-m-d H:i:s"),
                        ));

                        echo json_encode([
                            'status' => 'OK',
                            'id' => $new_userDate,
                        ]);

                    }
                    
                }

            } else {
                throw new Exception("Invalid Parameters");
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function register()
	{

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        try {
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $username = $this->input->post("username", true);
                $email = $this->input->post("email", true);
                $password = $this->input->post("password", true);
                $register_sq1 = $this->input->post("register_sq1", true);
                $register_sq2 = $this->input->post("register_sq2", true);
                
                // encrypt password 
                $encrypted_password = md5($password);

                // check if have same email
                $checkAvailable = $this->{$this->data['main_model']}->getOne(array(
                    'email' => $email,
                ));

                // if don't have same email
                if (empty($checkAvailable)) {

                    // Generate a random token  
                    $token = bin2hex(random_bytes(32));  // Generate a secure token

                    // add new user
                    $userdata = $this->{$this->data['main_model']}->insert(array(
                        'name' => $username,
                        'email' => $email,
                        'password' => $encrypted_password,
                        'safety_word_1' => $register_sq1,
                        'safety_word_2' => $register_sq2,
                        'token' => $token
                    ));

                    $new_user_data = $this->{$this->data['main_model']}->getOne(array(
                        'email' => $email,
                        'is_deleted' => 0,
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 15, // register
                        'created_by' => $new_user_data['id'],
                        'kanban_id' => null,
                        'receiver' => $new_user_data['id'],
                        'message' => 'You registered an account in dopamin Boost.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    echo json_encode([
                        'status' => 'OK',
                        'id' => $userdata,
                    ]);

                } else if ($checkAvailable['is_deleted'] == 0) {
                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Same email found, please proceed to login',
                    ]);
                    return;
                } else if ($checkAvailable['is_deleted'] == 1) {
                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Your account has been deactivated',
                    ]);
                    return;
                }

            } else {
                // Return JSON error response
                echo json_encode([
                    'status' => 'ERROR',
                    'message' => 'Invalid Paramters',
                ]);
                return;
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }

	}

    public function forgot_password_validation()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        try {
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $answer_1 = $this->input->post("answer_1", true);
                $answer_2 = $this->input->post("answer_2", true);
                $email = $this->input->post("email", true);
                
                // check if have same email
                $checkAvailable = $this->{$this->data['main_model']}->getOne(array(
                    'email' => $email,
                ));

                // if don't have same email
                if (empty($checkAvailable)) {

                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Email not found, please proceed to register',
                    ]);

                    return;

                } else if ($checkAvailable['is_deleted'] == 0) {

                    if ($answer_1 == $checkAvailable['safety_word_1'] && $answer_2 == $checkAvailable['safety_word_2']) {

                        echo json_encode([
                            'status' => 'OK',
                            'email' => $checkAvailable['email']
                        ]);

                    } else if ($answer_1 == $checkAvailable['safety_word_1'] && $answer_2 != $checkAvailable['safety_word_2']) {

                        echo json_encode([
                            'status' => 'ERROR',
                            'message' => 'Second answer is wrong, please try again',
                        ]);

                        return;

                    } else if ($answer_1 != $checkAvailable['safety_word_1'] && $answer_2 == $checkAvailable['safety_word_2']) {

                        echo json_encode([
                            'status' => 'ERROR',
                            'message' => 'First answer is wrong, please try again',
                        ]);

                        return;

                    } else {

                        echo json_encode([
                            'status' => 'ERROR',
                            'message' => 'Both answers are wrong, please try again',
                        ]);

                        return;

                    }

                    return;
                } else if ($checkAvailable['is_deleted'] == 1) {
                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Your account has been deactivated',
                    ]);
                    return;
                }

            } else {
                throw new Exception("Invalid Parameters");
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }

	}

    public function reset_password()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        try {
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $password = $this->input->post("password", true);
                $email = $this->input->post("email", true);
                
                // encrypt password
                $encrypted_password = md5($password);

                // check if have same email
                $checkAvailable = $this->{$this->data['main_model']}->getOne(array(
                    'email' => $email,
                ));

                // if don't have same email
                if (empty($checkAvailable)) {

                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Email not found, please proceed to register',
                    ]);

                    return;

                } else if ($checkAvailable['is_deleted'] == 0) {

                    $ID = $this->{$this->data['main_model']}->update(array(
                        'email' => $email
                    ), array(
                        'password' => $encrypted_password
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 12, // information updated
                        'created_by' => $checkAvailable['id'],
                        'kanban_id' => null,
                        'receiver' => $checkAvailable['id'],
                        'message' => 'You have reset your password.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    echo json_encode([
                        'status' => 'OK',
                        'id' => $ID,
                    ]);
                    
                } else if ($checkAvailable['is_deleted'] == 1) {
                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Your account has been deactivated',
                    ]);

                    return;
                }

            } else {
                throw new Exception("Invalid Parameters");
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }

	}

    public function logout()
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        try {
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $user_id = $this->input->post("user_id", true);
                $token = $this->input->post("token", true);

                $userData = $this->{$this->data['main_model']}->getOne([
                    'id' => $user_id,
                    'token' => $token,
                    'is_deleted' => 0,
                ]);

                if (empty($userData)) {

                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Unauthorized User',
                    ]);
                    return;

                } else {
                    
                    $this->{$this->data['main_model']}->update(array(
                        'id' => $user_id,
                    ), array(
                        'token' => null
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 13, // logout
                        'created_by' => $user_id,
                        'kanban_id' => null,
                        'receiver' => $user_id,
                        'message' => 'You logged out dopamin Boost.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    echo json_encode([
                        'status' => 'OK',
                        'id' => $userData,
                    ]);

                }

            } else {
                throw new Exception("Invalid Parameters");
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }
    }


}

?>