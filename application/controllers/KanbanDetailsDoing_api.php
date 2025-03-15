<?php

//class name need to same with file name (first alphabet uppercase)
require_once APPPATH . 'core/MY_apicontroller.php';
class KanbanDetailsDoing_api extends MY_apicontroller {

    public function __construct(){
        //run the parent first
        parent::__construct();

        //load kanban_details_doing model
        // $this->load->model("Kanban_details_doing_model");

        $this->data['main_model'] = "Kanban_details_doing_model";
        $this->load->model($this->data['main_model']);

		$this->data['primaryKey'] = $this->{$this->data['main_model']}->primaryKey;

        //load kanban_details_doing model
        $this->load->model("Kanban_details_todo_model");

        //load kanban_details_done model
        $this->load->model("Kanban_details_done_model");

        //load notification model
        $this->load->model("Notification_model");

        //load user model
        $this->load->model("User_model");

    }

    public function getKanbanDetailsDoingList()
    {

        //load kanban_list model
        $this->load->model("Kanban_list_model");
        
        //load user model
        $this->load->model("User_model");

        //load status model
        $this->load->model("Task_status_model");

        //load type model
        $this->load->model("Task_type_model");
        
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
                    } else if (in_array($c['data'], array("kanban_id"))) {
                        $like[$c['data']] = $filter;
                    }
                }
            }
        }

        $total_count = $this->{$this->data["main_model"]}->record_count($where, $like);

        $dataList = $this->{$this->data["main_model"]}->fetch($count, $start, $where, $like, $sorting, "id,kanban_id,created_by,content_title,status,type,due_date,created_date,modified_date");

        $userlist = $this->User_model->getIDKeyArray("name", array("is_deleted" => 0));

        $statuslist = $this->Task_status_model->getIDKeyArray("name", array("is_deleted" => 0));

        $typelist = $this->Task_type_model->getIDKeyArray("name", array("is_deleted" => 0));

        // Get the Kanban lists and their members  
		$kanbanLists = $this->Kanban_list_model->fetch2("id,name"); 
		$kanbanNames = [];  

        // Prepare associative arrays for Kanban names and members  
		foreach ($kanbanLists as $kanban) {  
			$kanbanNames[$kanban['id']] = $kanban['name']; // Keyed by Kanban ID 
		}  

        if (!empty($dataList)) {
            foreach ($dataList as $k => $v) {

                // get created user name
                $dataList[$k]['created_user'] = isset($userlist[$v['created_by']]) ? $userlist[$v['created_by']] : "N/A";

                // get task status
                $dataList[$k]['task_status'] = isset($statuslist[$v['status']]) ? $statuslist[$v['status']] : "N/A";

                // get task type
                $dataList[$k]['task_type'] = isset($typelist[$v['type']]) ? $typelist[$v['type']] : "N/A";

				$ownedKanbanNames = [];
    
				foreach ($kanbanLists as $kanban) {
					if ($kanban['id'] == $v['kanban_id']) { // Match kanban ID with id
						$ownedKanbanNames[] = $kanban['name']; // Store Kanban name
					}
				}

				// Assign the Kanban name(s) to `kanban_name`
				$dataList[$k]['kanban_name'] = !empty($ownedKanbanNames) ? implode(', ', $ownedKanbanNames) : "N/A";

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
                'kanban_doing' => $eachData,
            ));
        } catch (Exception $e) {

            $this->json_output_error($e->getMessage());
        }
    }

    public function submit()
	{

		header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$mode = $this->input->post("mode", true);
				$id = $this->input->post("id", true);

				// $adminID = $this->adminAuth($token);
				$kanban_id = $this->input->post("kanban_id", true);
                $created_by = $this->input->post("created_by", true);
                $type = $this->input->post("type", true);
                $content_title = $this->input->post("content_title", true);
                $content_description = $this->input->post("content_description", true);
				$due_date = $this->input->post("due_date", true);
                $status = $this->input->post("status", true);

				$sql = array(
					'kanban_id' => $kanban_id,
                    'created_by' => $created_by,
                    'type' => $type,
                    'content_title' => $content_title,
                    'content_description' => $content_description,
                    'due_date' => $due_date,
                    'status' => $status,
				);

                $ID = null;

                $userData = $this->User_model->getOne(array(
                    'id' => $created_by,
                ));

				switch ($mode) {
					case "Add":
						
						// $sql['created_user_id'] = $adminID;
                        $sql['status'] = 3;
						$sql['created_date'] = date("Y-m-d H:i:s");

						$ID = $this->{$this->data['main_model']}->insert($sql);

                        // create a notification
                        $this->Notification_model->insert(array(
                            'type' => 6, // task added
                            'created_by' => $created_by,
                            'kanban_id' => $kanban_id,
                            'message' => 'The task: ' . $content_title . ' has been added to Doing Category. Added by ' . $userData['name'] . '.',
                            'created_date' => date("Y-m-d H:i:s"),
                        ));

						break;
					case "Edit":

						$ID = $id;

						$eachData = $this->{$this->data['main_model']}->getOne(array(
							'id' => $ID,
						));

						if (empty($eachData)) {
							throw new Exception("Data No Exist");
						}

						// $sql['last_modified_user_id'] = $adminID;
						$sql['modified_date'] = date("Y-m-d H:i:s");

                        // if status is Go To Doing
                        if ($status == 3) {

                            $this->{$this->data['main_model']}->update(array(
                                'id' => $id,
                            ), $sql);

                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 2, // task updated
                                'created_by' => $created_by,
                                'kanban_id' => $kanban_id,
                                'message' => 'The task: ' . $content_title . ' has been updated in Doing Category. Updated by ' . $userData['name'] . '(admin).',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));

                        } else if ($status == 2) { // if status is Go To Done

                            // delete from current table
                            $sql['is_deleted'] = 1;

                            $this->{$this->data['main_model']}->update(array(
                                'id' => $id,
                            ), $sql);

                            // insert into doing table
                            unset($sql['is_deleted']);
                            $sql['created_date'] = date("Y-m-d H:i:s");

                            $this->Kanban_details_done_model->insert($sql);

                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 7, // task moved
                                'created_by' => $created_by,
                                'kanban_id' => $kanban_id,
                                'message' => 'The task: ' . $content_title . ' has been moved in Done Category. Moved by ' . $userData['name'] . '(admin).',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));

                        } else if ($status == 1) { // if status is Go To Todo

                            // delete from current table
                            $sql['is_deleted'] = 1;

                            $this->{$this->data['main_model']}->update(array(
                                'id' => $id,
                            ), $sql);

                            // insert into doing table
                            unset($sql['is_deleted']);
                            $sql['created_date'] = date("Y-m-d H:i:s");

                            $this->Kanban_details_todo_model->insert($sql);

                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 7, // task moved
                                'created_by' => $created_by,
                                'kanban_id' => $kanban_id,
                                'message' => 'The task: ' . $content_title . ' has been moved in Todo Category. Moved by ' . $userData['name'] . '(admin).',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));
                        }

						break;
				}

				$this->json_output(array(
					'id' => $ID,
				));
			} else {
				throw new Exception("Invalid Parameters");
			}
		} catch (Exception $e) {
			$this->json_output_error($e->getMessage());
		}
	}

    public function delete()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


        try {

            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));


                $ID = $this->input->post("id", true);

                // $adminID = $this->adminAuth($token);

                $eachData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));
                if (empty($eachData)) {
                    throw new Exception("data not exists");
                }


                $this->{$this->data['main_model']}->update(array(
                    $this->data['primaryKey'] => $ID,
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s"),
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

    public function edit_from_frontend()
	{

		header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$mode = $this->input->post("mode", true);
				$id = $this->input->post("id", true);

				// $adminID = $this->adminAuth($token);
				$kanban_id = $this->input->post("kanban_id", true);
                $content_title = $this->input->post("content_title", true);
                $content_description = $this->input->post("content_description", true);
				$due_date = $this->input->post("due_date", true);
                $user_id = $this->input->post("user_id", true);

				$sql = array(
					'kanban_id' => $kanban_id,
                    'content_title' => $content_title,
                    'content_description' => $content_description,
                    'due_date' => $due_date,
				);

                $ID = null;

				switch ($mode) {
					case "Edit":

						$ID = $id;

						$eachData = $this->{$this->data['main_model']}->getOne(array(
							'id' => $ID,
						));

						if (empty($eachData)) {
							throw new Exception("Data No Exist");
						}

                        $edit_userData = $this->User_model->getOne(array(
                            'id' => $user_id,
                        ));

						// $sql['last_modified_user_id'] = $adminID;
						$sql['modified_date'] = date("Y-m-d H:i:s");

                        $this->{$this->data['main_model']}->update(array(
                            'id' => $id,
                        ), $sql); 

                        // create a notification
                        $this->Notification_model->insert(array(
                            'type' => 2,
                            'created_by' => $edit_userData['id'],
                            'kanban_id' => $kanban_id,
                            'message' => 'The task: ' . $eachData['content_title'] . ' has been edited by ' . $edit_userData['name'] . '.',
                            'created_date' => date("Y-m-d H:i:s"),
                        ));

						break;
				}

				$this->json_output(array(
					'id' => $ID,
				));
			} else {
				throw new Exception("Invalid Parameters");
			}
		} catch (Exception $e) {
			$this->json_output_error($e->getMessage());
		}
	}

    public function complete()
	{

		header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$mode = $this->input->post("mode", true);
				$id = $this->input->post("id", true);

				// $adminID = $this->adminAuth($token);
				$kanban_id = $this->input->post("kanban_id", true);
                $user_id = $this->input->post("user_id", true);
                $status = 2;
                
                $ID = null;

                $userData = $this->User_model->getOne(array(
                    'id' => $user_id,
                ));

				switch ($mode) {
					case "Edit":

						$ID = $id;

						$eachData = $this->{$this->data['main_model']}->getOne(array(
							'id' => $ID,
						));

						if (empty($eachData)) {
							throw new Exception("Data No Exist");
						}

						// $sql['last_modified_user_id'] = $adminID;
						$eachData['modified_date'] = date("Y-m-d H:i:s");

                        // if status is Go To Done
                        if ($status == 2) { 

                            // delete from current table
                            $eachData['is_deleted'] = 1;

                            $this->{$this->data['main_model']}->update(array(
                                'id' => $id,
                            ), $eachData);

                            // insert into doing table
                            unset($eachData['is_deleted']);
                            unset($eachData['id']);
                            $eachData['created_date'] = date("Y-m-d H:i:s");

                            $this->Kanban_details_done_model->insert($eachData);

                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 7,
                                'created_by' => $user_id,
                                'kanban_id' => $kanban_id,
                                'message' => 'The task: ' . $eachData['content_title'] . ' has been completed by ' . $userData['name'] . '.',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));

                        } 

						break;
				}

				$this->json_output(array(
					'id' => $ID,
				));
			} else {
				throw new Exception("Invalid Parameters");
			}
		} catch (Exception $e) {
			$this->json_output_error($e->getMessage());
		}
	}

    public function delete_with_notification()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


        try {

            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));


                $ID = $this->input->post("id", true);
                $user_id = $this->input->post("user_id", true);

                // $adminID = $this->adminAuth($token);

                $eachData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));

                if (empty($eachData)) {
                    throw new Exception("data not exists");
                }

                $edit_userData = $this->User_model->getOne(array(
                    'id' => $user_id,
                ));


                $this->{$this->data['main_model']}->update(array(
                    $this->data['primaryKey'] => $ID,
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 8,
                    'created_by' => $edit_userData['id'],
                    'kanban_id' => $eachData['kanban_id'],
                    'message' => 'The task: ' . $eachData['content_title'] . ' has been deleted by ' . $edit_userData['name'] . '.',
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

}

?>