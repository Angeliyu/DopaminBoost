<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_apicontroller extends CI_Controller
{

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');

        $this->data['starttime'] = microtime(true);

        $this->selfConstruct();
    }

    public function selfConstruct()
    {
    }

    protected function json_output($result)
    {
        $this->data['endtime'] = microtime(true);
        $timediff = ($this->data['endtime'] - $this->data['starttime']);
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        echo json_encode(array(
            'status'    => "OK",
            'result'    => $result,
            'comment'    => "",
            'duration'    => $timediff,
        ));
    }

    protected function json_output_error($result)
    {
        $this->data['endtime'] = microtime(true);
        $timediff = ($this->data['endtime'] - $this->data['starttime']);
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        echo json_encode(array(
            'status'    => "ERROR",
            'result'    => 'ERROR:' . $result,
            'comment'    => "",
            'duration'    => $timediff,
        ));
    }

    //Admin Authentication
    public function adminAuth($token)
    {

        //Authentication
        $tokenData = $this->User_login_token_model->getOne(array(
            'token' => $token,
            'expired >' => time(),
        ));

        if (!empty($tokenData)) {

            $this->User_login_token_model->update(array('id' => $tokenData['id']), array(
                'expired'   => time() + 7 * 24 * 3600,
                'modified_date' => date("Y-m-d H:i:s"),
            ));
            return $tokenData['user_id'];
        } else {
            throw new Exception("You are not allow to view this data");
        }
    }


    //Delete API
    public function delete()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


        try {

            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));


                $ID = $this->input->post("id", true);
                $token = $this->input->post("token", true);

                $adminID = $this->adminAuth($token);

                $eachData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));
                if (empty($eachData)) {
                    throw new Exception("data not exists");
                }

                $right = $this->User_level_model->checkPrivilege($adminID, $eachData['company_id'], $this->data['audit_section'], 'delete');
                if (!$right) {
                    throw new Exception("Access Denined");
                }

                $this->{$this->data['main_model']}->update(array(
                    $this->data['primaryKey'] => $ID,
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                //Audit Trail
                $this->Audit_trail_model->insert(array(
                    'adminID' => $adminID,
                    'section' => $this->data['audit_section'],
                    'itemID' => $ID,
                    'action' => "Delete",
                    'beforeData' => json_encode(array('is_deleted' => 0)),
                    'afterData' => json_encode(array('is_deleted' => 1)),
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->json_output(array(
                    $this->data['primaryKey'] => $ID,
                ));
            } else {
                throw new Exception("Invalid param");
            }
        } catch (Exception $e) {
            $this->json_output_error($e->getMessage());
        }
    }

    //getDetail
    public function getDetail($ID)
    {

        try {

            // $adminID = $this->adminAuth($token);

            $eachData = $this->{$this->data['main_model']}->getOne(array(
                $this->data['primaryKey'] => $ID,
                'is_deleted' => 0,
            ));

            if (empty($eachData)) {
                throw new Exception("Data Not Found!");
            }


            $eachData[$this->data['primaryKey']] = (int)$eachData[$this->data['primaryKey']];

            $this->json_output(array(
                $this->data['audit_section'] . 'Detail' => $eachData,
            ));
        } catch (Exception $e) {

            $this->json_output_error($e->getMessage());
        }
    }

    //User Authentication
    public function userAuth($user_id, $token)
    {

        $tokenData = $this->User_login_token_model->getOne(array(
            'user_id'   => $user_id,
            'token'     => $token,
            'expired >' => time(),
        ));

        if (!empty($tokenData)) {

            $this->User_login_token_model->update(array('id' => $tokenData['id']), array(
                'expired'   => time() + 7 * 24 * 3600,
                'modified_date' => date("Y-m-d H:i:s"),
            ));
            return $tokenData['user_id'];
        } else {
            throw new Exception("Your login session has expired. Please login.");
        }
    }

    //check whether the user is logged in
    public function userIsLogin($token)
    {

        $tokenData = $this->User_login_token_model->getOne(array(
            'token'     => $token,
            'expired >' => time(),
        ));
        if (!empty($tokenData)) {

            $this->User_login_token_model->update(array('id' => $tokenData['id']), array(
                'expired'   => time() + 7 * 24 * 3600,
                'modified_date' => date("Y-m-d H:i:s"),
            ));
            return $tokenData['user_id'];
        } else {
            return "";
        }
    }

}
