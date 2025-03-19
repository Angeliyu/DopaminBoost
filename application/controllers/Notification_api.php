<?php

//class name need to same with file name (first alphabet uppercase)
require_once APPPATH . 'core/MY_apicontroller.php';
class Notification_api extends MY_apicontroller 
{

    public function __construct(){
        //run the parent first
        parent::__construct();

        $this->data['main_model'] = "Notification_model";
        $this->load->model($this->data['main_model']);

		$this->data['primaryKey'] = $this->{$this->data['main_model']}->primaryKey;

        $this->load->model("User_model");
        $this->load->model("Kanban_list_model");
        $this->load->model("Notification_type_model");

    }

    public function getNotificationList()
    {
        
        $draw = $this->input->post("draw", true);
        $columns = $this->input->post("columns", true);
        $orderby = $this->input->post("order", true);
        $start = $this->input->post("start", true);
        $count = $this->input->post("length", true);
        $search = $this->input->post("search", true);

        if (empty($orderby)) {
            $sorting = array();
        } else {
            foreach ($orderby as $v) {
                $sorting = array($columns[$v["column"]]["data"] => $v["dir"]);
            }
        }

        $where = array("is_deleted" => 0);
        $like = array();
        $where_in = [];
        $searched = 0;

        if (!empty($columns)) {
            foreach ($columns as $c) {
                $filter = $c['search']['value'];
                if (!empty($filter) && strlen($filter) > 0) {
                    if (in_array($c['data'], array("id"))) {
                        $where[$c['data']] = $filter;
                    } else if (in_array($c['data'], array("name"))) {
                        $like[$c['data']] = $filter;
                    }
                }
            }
        }

        $total_count = $this->{$this->data['main_model']}->record_count($where, $like);

        $dataList = $this->{$this->data['main_model']}->fetch($count, $start, $where, $like, $sorting, "id,type,created_by,receiver,kanban_id,message,is_read,is_accepted,created_date,modified_date");

        $userlist = $this->User_model->getIDKeyArray("name", array("is_deleted" => 0));
        
        $kanbanlist = $this->Kanban_list_model->getIDKeyArray("name", array("is_deleted" => 0));

        $notification_type_list = $this->Notification_type_model->getIDKeyArray("name", array("is_deleted" => 0));


        if (!empty($dataList)) {
            foreach ($dataList as $k => $v) {
                $dataList[$k]['type_name'] = isset($notification_type_list[$v['type']]) ? $notification_type_list[$v['type']] : "N/A";
                $dataList[$k]['created_user'] = isset($userlist[$v['created_by']]) ? $userlist[$v['created_by']] : "N/A";
                $dataList[$k]['receiver_user'] = isset($userlist[$v['receiver']]) ? $userlist[$v['receiver']] : "N/A";
                $dataList[$k]['kanban_name'] = isset($kanbanlist[$v['kanban_id']]) ? $kanbanlist[$v['kanban_id']] : "Completed or Deleted";
                
            }
        }

        echo json_encode(array(
            'status' => "OK",
            "draw" => $draw,
            "recordsTotal" => $total_count,
            "recordsFiltered" => $total_count,
            "result" => $dataList,
        ));

    }

    // frontend profile
    public function generalNotification($ID)
    {
        try {

            $general_kanban_notification = $this->{$this->data['main_model']}->get_where(array(
                'receiver' => $ID,
                'is_deleted' => 0,
            ));

            $this->json_output(array(
				'notificationDetail' => $general_kanban_notification,
			));

        } catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
    }

    public function leaderNotification($ID)
    {
        try {

            $leader_kanban_notification = $this->{$this->data['main_model']}->get_where(array(
                'kanban_id' => $ID,
                'is_read' => 0,
                'is_deleted' => 0,
            ));

            $this->json_output(array(
				'notificationDetail' => $leader_kanban_notification,
			));

        } catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
    }

    public function joinedMemberNotification()
    {
        try {

            //load kanban_list model
            $this->load->model("Kanban_list_model");

            $IDs = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];

            if (empty($IDs)) {
                // Return JSON error response
                echo json_encode([
                    'status' => 'ERROR',
                    'message' => 'No Kanban IDs provided',
                ]);
                return;
            }

            $kanbanData = $this->Kanban_list_model->getIDKeyArray("name", array("is_deleted" => 0));
            
            $joined_member_kanban_notification = $this->{$this->data['main_model']}->get_where_in(
                ['is_deleted' => 0], // WHERE conditions
                ['kanban_id' => $IDs] // WHERE IN condition
            );

            if (!empty($joined_member_kanban_notification)) {

                foreach ($joined_member_kanban_notification as $k => $v) {
                    
                    $joined_member_kanban_notification[$k]['kanban_name'] = isset($kanbanData[$v['kanban_id']]) ? $kanbanData[$v['kanban_id']] : "N/A";
    
                }
                
            }

            $this->json_output(array(
				'notificationDetail' => $joined_member_kanban_notification,
			));

        } catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
    }

    // frontend kanban
    public function kanbanNotification($ID)
    {
        try {

            $kanban_notification = $this->{$this->data['main_model']}->get_where(array(
                'kanban_id' => $ID,
                'is_deleted' => 0,
            ));

            $this->json_output(array(
				'notificationDetail' => $kanban_notification,
			));

        } catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
    }

    public function mark_as_read()
    {
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $notification_id = $this->input->post('notification_id', true);

                $kanban_notification = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $notification_id,
                    'is_deleted' => 0,
                    'is_read' => 0,
                ));

                if (empty($kanban_notification)) {
                    // Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Notification Not Exist',
                    ]);
                    return;
                } else {

                    $updated_notification = $this->{$this->data['main_model']}->update(array(
                        'id' => $notification_id,
                    ), array(
                        'is_read' => 1,
                    ));

                    $this->json_output(array(
                        'notification_updated' => $updated_notification,
                    ));

                }
                
            } else {
				// Return JSON error response
				echo json_encode([
					'status' => 'ERROR',
					'message' => 'Invalid Parameters',
				]);
				return;
			}

        } catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
    }


}

?>