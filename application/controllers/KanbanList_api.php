<?php

//class name need to same with file name (first alphabet uppercase)
require_once APPPATH . 'core/MY_apicontroller.php';
class KanbanList_api extends MY_apicontroller {

    public function __construct()
    {
        //run the parent first
        parent::__construct();

        //load kanban_list model
        // $this->load->model("Kanban_list_model");

        $this->data['main_model'] = 'Kanban_list_model';
		$this->load->model($this->data['main_model']);

		$this->data['primaryKey'] = $this->{$this->data['main_model']}->primaryKey;

        $this->load->model("User_model");

        $this->load->model("User_kanban_model");

        $this->load->model("Notification_model");

    }

    public function getKanbanList()
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

        $dataList = $this->{$this->data['main_model']}->fetch($count, $start, $where, $like, $sorting, "id,name,owned_by,member,created_date,modified_date");

        $userlist = $this->User_model->getIDKeyArray("name", array("is_deleted" => 0));

        if (!empty($dataList)) {
            foreach ($dataList as $k => $v) {
                $dataList[$k]['created_user'] = isset($userlist[$v['owned_by']]) ? $userlist[$v['owned_by']] : "N/A";

                // Convert member IDs to names  
                $memberIds = explode(',', $v['member']); 
                $memberNames = [];  

                foreach ($memberIds as $id) {  
                    if (isset($userlist[$id])) {  
                        $memberNames[] = $userlist[$id]; // Get the corresponding name  
                    }  
                }  

                $dataList[$k]['member'] = implode('<br/>', $memberNames); // Join names with a comma  
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

				$kanban_name = $this->input->post("name", true);
                $owned_by = $this->input->post("owned_by", true);
                $raw_member = $this->input->post("member", true);
                $user_id = $this->input->post("user_id", true);

                if (is_array($raw_member)) {  
                    $member = implode(',', $raw_member); // Convert array to comma-separated string  
                } 
				
				$sql = array(
					'name' => $kanban_name,
                    'owned_by' => $owned_by,
				);

                $ID = null;

                $created_user_data = $this->User_model->getOne(array(
                    'id' => $user_id,
                ));

                $this->db->trans_start();

				switch ($mode) {
					case "Add":

                        $this->db->trans_start();
						
						$sql['created_date'] = date("Y-m-d H:i:s");

                        if (!empty($raw_member)) {
                            $sql['member'] = $member;
                        }

						$ID = $this->{$this->data['main_model']}->insert($sql);

                        // create new leader data in user_kanban table
                        $leader_data = array(
                            'kanban_id' => $ID,
                            'user_id' => $owned_by,
                            'created_date' => date("Y-m-d H:i:s")
                        );

                        $this->User_kanban_model->insert($leader_data);

                        // create new member data in user_kanban table
                        if (!empty($raw_member) && is_array($raw_member)) {
                            foreach ($raw_member as $member_id) {
                                $user_kanban_sql = array(
                                    'kanban_id' => $ID,
                                    'user_id' => $member_id,
                                    'created_date' => date("Y-m-d H:i:s")
                                );
                                $this->User_kanban_model->insert($user_kanban_sql);
                            }
                        }

                        $new_kanban = $this->{$this->data['main_model']}->getOne(array(
                            'id' => $ID,
                            'is_deleted' => 0
                        ));

                        if ($created_user_data['role'] == 1) {
                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 19, // new kanban created
                                'created_by' => $user_id,
                                'kanban_id' => $ID,
                                'receiver' => null,
                                'message' => 'User <b>'. $created_user_data['name'] .'</b> (Admin) created new kanban :<b>' . $new_kanban['name'] . '</b>.',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));
                        } else {
                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 19, // new kanban created
                                'created_by' => $user_id,
                                'kanban_id' => $ID,
                                'receiver' => null,
                                'message' => 'User <b>'. $created_user_data['name'] .'</b> created new kanban :<b>' . $new_kanban['name'] . '</b>.',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));
                        }

                        $this->db->trans_complete();

						break;
					case "Edit":

						$kanbanData = $this->{$this->data['main_model']}->getOne(array(
							'id' => $id,
						));

						if (empty($kanbanData)) {
                            // Return JSON error response
                            echo json_encode([
                                'status' => 'ERROR',
                                'message' => 'Kanban Not Exist',
                            ]);
                            return;
						}

                        $this->db->trans_start();

						$sql['modified_date'] = date("Y-m-d H:i:s");

                        if (!empty($raw_member)) {
                            $sql['member'] = $member;
                        }

						$ID = $this->{$this->data['main_model']}->update(array(
							'id' => $id,
						), $sql);
                        
                        //// update records in user_kanban
                        // combine owned_by and members
                        $all_members = array_unique(array_merge([$owned_by], $raw_member));

                        // fetch current user_kanban records by kanban_id
                        $existing_records = $this->User_kanban_model->get_where(array(
                            'kanban_id' => $id,
                            'is_deleted' => 0
                        ));

                        $existing_user_ids = array_column($existing_records, 'user_id');

                        // update user(s) not in all_members as deleted
                        foreach ($existing_user_ids as $user_id) {
                            if (!in_array($user_id, $all_members)) {

                                $this->User_kanban_model->update(array(
                                    'user_id' => $user_id,
                                    'kanban_id' => $id
                                ), array(
                                    'is_deleted' => 1,
                                    'modified_date' => date("Y-m-d H:i:s")
                                ));

                            }
                        }

                        // fetch current user_kanban records by kanban_id already soft deleted before
                        $soft_deleted_records = $this->User_kanban_model->get_where(array(
                            'kanban_id' => $id,
                            'is_deleted' => 1
                        ));

                        if (!empty($soft_deleted_records)) {

                            $soft_deleted_user_ids = array_column($soft_deleted_records, 'user_id');

                            // Check if soft deleted users exist in all_members and restore them
                            foreach ($soft_deleted_user_ids as $soft_deleted_user_id) {
                                if (in_array($soft_deleted_user_id, $all_members)) {
                                    $this->User_kanban_model->update(array(
                                        'user_id' => $soft_deleted_user_id,
                                        'kanban_id' => $id
                                    ), array(
                                        'is_deleted' => 0,
                                        'modified_date' => date("Y-m-d H:i:s")
                                    ));
                                }
                            }

                        }

                        // fetch current user_kanban records by kanban_id
                        $new_existing_records = $this->User_kanban_model->get_where(array(
                            'kanban_id' => $id,
                            'is_deleted' => 0
                        ));

                        $new_existing_user_ids = array_column($new_existing_records, 'user_id');

                        // insert new member(s) not already in user_kanban
                        foreach ($all_members as $member_id) {
                            if (!in_array($member_id, $new_existing_user_ids)) {

                                $this->User_kanban_model->insert(array(
                                    'kanban_id' => $id,
                                    'user_id' => $member_id,
                                    'created_date' => date("Y-m-d H:i:s")
                                ));

                            }
                        }
                        
                        if ($created_user_data['role'] == 1) {
                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 20, // kanban edit
                                'created_by' => $user_id,
                                'kanban_id' => $kanbanData['id'],
                                'receiver' => null,
                                'message' => 'User <b>'. $created_user_data['name'] .'</b> (Admin) edit data in kanban :<b>' . $kanbanData['name'] . '</b>.',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));
                        } else {
                            // create a notification
                            $this->Notification_model->insert(array(
                                'type' => 20, // kanban edit
                                'created_by' => $user_id,
                                'kanban_id' => $kanbanData['id'],
                                'receiver' => null,
                                'message' => 'User <b>'. $created_user_data['name'] .'</b> edit data in kanban :<b>' . $kanbanData['name'] . '</b>.',
                                'created_date' => date("Y-m-d H:i:s"),
                            ));
                        }

                        $this->db->trans_complete();
                        
						break;
				}

                $this->db->trans_complete();

				$this->json_output(array(
					'id' => $ID,
				));
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

    public function getDetail($ID)
	{

		try {

			$kanbanData = $this->{$this->data['main_model']}->getOne(array(
				'id' => $ID,
				'is_deleted' => 0,
			));

			if (empty($kanbanData)) {
                // Return JSON error response
                echo json_encode([
                    'status' => 'ERROR',
                    'message' => 'Kanban Not Found',
                ]);
                return;
			}

			$kanbanData['id'] = (int) $kanbanData['id'];

            $owned_by_Data = $this->User_model->getOne(array(
                'id' => $kanbanData['owned_by'],
                'is_deleted' => 0
            ));

            $kanbanData['owned_by_name'] = $owned_by_Data['name'];

			$this->json_output(array(
				'kanbanDetail' => $kanbanData,
			));
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

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                $this->db->trans_start();

                $this->{$this->data['main_model']}->update(array(
                    $this->data['primaryKey'] => $ID,
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                //// update records in user_kanban
                $current_records = $this->User_kanban_model->get_where(array(
                    'kanban_id' => $ID,
                    'is_deleted' => 0
                ));

                if (!empty($current_records)) {

                    // extract user_ids from the records
                    $kanban_users = array_column($current_records, 'user_id');

                    // update records to is_deleted = 1
                    foreach ($kanban_users as $user) {

                        $this->User_kanban_model->update(array(
                            'kanban_id' => $ID,
                            'user_id' => $user
                        ), array(
                            'is_deleted' => 1,
                            'modified_date' => date("Y-m-d H:i:s"),
                        ));

                    }                    

                }

                $this->db->trans_complete();

                $this->json_output(array(
                    $this->data['primaryKey'] => $ID,
                ));

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

    public function getAvailableUsers() {  
        header('Content-Type: application/json; charset=utf-8');  
        header("Access-Control-Allow-Origin: *");  
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");  
    
        try {  
            $users = $this->User_model->fetch2("id,name"); // Fetch all users  
            $ownedUsers = $this->{$this->data['main_model']}->fetch2("owned_by"); // Fetch users who own Kanban  
    
            // Extract only user IDs who own Kanban  
            $ownedUserIds = array_column($ownedUsers, 'owned_by');  
    
            // Filter out users who already own a Kanban  
            $availableUsers = array_filter($users, function($user) use ($ownedUserIds) {  
                return !in_array($user['id'], $ownedUserIds);  
            });  
    
            echo json_encode([
                'status' => 'OK', 
                'result' => array_values($availableUsers)
            ]);  
        } catch (Exception $e) {  
            $this->json_output_error($e->getMessage());  
        }  
    }  

    public function getAvailableMembers() {  
        header('Content-Type: application/json; charset=utf-8');  
        header("Access-Control-Allow-Origin: *");  
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");  
    
        try {  
            $users = $this->User_model->fetch2("id,name"); // Fetch all users  
            $kanban_members = $this->{$this->data['main_model']}->fetch2("member"); // Fetch member(s) in kanban  
    
            // Extract only user IDs who own Kanban  
            $ownedUserIds = array_column($ownedUsers, 'owned_by');  
    
            // Filter out users who already own a Kanban  
            $availableUsers = array_filter($users, function($user) use ($ownedUserIds) {  
                return !in_array($user['id'], $ownedUserIds);  
            });  
    
            echo json_encode(['status' => 'OK', 'result' => array_values($availableUsers)]);  
        } catch (Exception $e) {  
            $this->json_output_error($e->getMessage());  
        }  
    }  

    public function kanbanUserSearch()  
    {  
        header('Content-Type: application/json; charset=utf-8');  
        header("Access-Control-Allow-Origin: *");  
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");  

        try {  
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {  
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));  

                $kanban_id = $this->input->post("kanban_id", true);  

                if ($kanban_id != 0) {  
                    
                    $this->load->model("User_kanban_model");

                    $all_user_kanban = $this->User_kanban_model->get_where(array(
                        'is_deleted' => 0,
                        'kanban_id' => $kanban_id,
                    ));

                    $all_members = [];

                    foreach ($all_user_kanban as $user) {
                        
                        $user_details = $this->User_model->getOne(array(
                            'is_deleted' => 0,
                            'id' => $user['user_id']
                        ));

                        // If user exists, add to $all_members
                        if (!empty($user_details)) {
                            $all_members[] = $user_details;
                        }

                    }

                    $this->json_output(array(  
                        'userData' => $all_members,  
                    ));  
                } else {  
                    throw new Exception("Invalid Kanban ID");  
                }  
            } else {  
                throw new Exception("Invalid Parameters");  
            }  
        } catch (Exception $e) {  
            $this->json_output_error($e->getMessage());  
        }  
    }

    // frontend 
    public function checkAuthorization()
    {
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("user_id", true);
                $id = $this->input->post("kanban_id", true);
                $token = $this->input->post("token", true);

                $ID = $id;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $id,
                    'is_deleted' => 0
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                $kanban_leader = $kanbanData['owned_by'];
                $kanban_member = $kanbanData['member'];

                // Convert members to an array (handle empty case)
                $membersArray = !empty($kanban_member) ? explode(',', $kanban_member) : [];

                // Combine leader and members
                $all_members = array_unique(array_merge([$kanban_leader], $membersArray));

                // check user token available
                $user_availability = $this->User_model->getOne(array(
                    'id' => $member_id,
                    'token' => $token,
                    'is_deleted' => 0
                ));

                // Check if the user is authorized
                if (in_array($member_id, $all_members)) {

                    if ($user_availability) {

                        echo json_encode([
                            'status' => 'OK',
                            'message' => 'User is authorized'
                        ]);
                        return;

                    } else {

                        echo json_encode([
                            'status' => 'ERROR',
                            'message' => 'User did not logged in'
                        ]);
                        return;

                    }

                } else {
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'User is not authorized'
                    ]);
                    return;
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
    
    public function kanbanDetail($ID)
	{

		try {

            $this->load->model("Kanban_details_todo_model");
            $this->load->model("Kanban_details_doing_model");
            $this->load->model("Kanban_details_done_model");
            $this->load->model("Task_status_model");
            $this->load->model("Task_type_model");

			$kanbanData = $this->{$this->data['main_model']}->getOne(array(
				'id' => $ID,
				'is_deleted' => 0,
			));

            // Fetch user details for members  
            $memberIds = explode(',', $kanbanData['member']);  

            // todo task data
            $todoData = $this->Kanban_details_todo_model->get_where(array(
                'kanban_id' => $ID,
                'is_deleted' => 0,
            ));

            $leaderData = $this->User_model->getOne(array(
				'id' => $kanbanData['owned_by'],
				'is_deleted' => 0,
			));

            $userData = $this->User_model->getIDKeyArray("name", array("is_deleted" => 0));
            $statusData = $this->Task_status_model->getIDKeyArray("name", array("is_deleted" => 0));
            $typeData = $this->Task_type_model->getIDKeyArray("name", array("is_deleted" => 0));
            $memberData = $this->User_model->getIDKeyArrayComma("id,name,email", array("is_deleted" => 0));

            // Leader information  
            $leaderId = $kanbanData['owned_by']; // Get the leader's user ID  
            $kanbanData['leader_name'] = isset($leaderData['name']) ? $leaderData['name'] : "N/A"; 
            $kanbanData['leader_email'] = isset($leaderData['email']) ? $leaderData['email'] : "N/A"; 

            // Add user information for members  
            $kanbanData['members'] = [];  
            foreach ($memberIds as $memberId) {   
                if (isset($memberData[$memberId])) {  
                    // Split the string by comma and trim whitespace  
                    $memberInfo = array_map('trim', explode(',', $memberData[$memberId]));  
                    
                    // Ensure the array has at least 3 elements  
                    if (count($memberInfo) >= 3) {  
                        $kanbanData['members'][] = [  
                            'id' => $memberInfo[0],  
                            'name' => $memberInfo[1],  
                            'email' => $memberInfo[2]  
                        ];  
                    } else {  
                        // Handle cases where data is incomplete  
                        $kanbanData['members'][] = [  
                            'id' => $memberId,  
                            'name' => "N/A",  
                            'email' => "N/A"  
                        ];  
                    }  
                } else {  
                    $kanbanData['members'][] = [  
                        'id' => $memberId,  
                        'name' => "N/A",  
                        'email' => "N/A"  
                    ];  
                }  
            }  
            

            if (!empty($todoData)) {
                foreach ($todoData as $k => $v) {
                    $todoData[$k]['created_user'] = isset($userData[$v['created_by']]) ? $userData[$v['created_by']] : "N/A";
                    $todoData[$k]['task_status'] = isset($statusData[$v['status']]) ? $statusData[$v['status']] : "N/A";
                    $todoData[$k]['task_type'] = isset($typeData[$v['type']]) ? $typeData[$v['type']] : "N/A";
                }
            }

            // doing task data
            $doingData = $this->Kanban_details_doing_model->get_where(array(
                'kanban_id' => $ID,
                'is_deleted' => 0,
            ));

            if (!empty($doingData)) {
                foreach ($doingData as $k => $v) {
                    $doingData[$k]['created_user'] = isset($userData[$v['created_by']]) ? $userData[$v['created_by']] : "N/A";
                    $doingData[$k]['task_status'] = isset($statusData[$v['status']]) ? $statusData[$v['status']] : "N/A";
                    $doingData[$k]['task_type'] = isset($typeData[$v['type']]) ? $typeData[$v['type']] : "N/A";
                }
            }

            // done task data
            $doneData = $this->Kanban_details_done_model->get_where(array(
                'kanban_id' => $ID,
                'is_deleted' => 0,
            ));

            if (!empty($doneData)) {
                foreach ($doneData as $k => $v) {
                    $doneData[$k]['created_user'] = isset($userData[$v['created_by']]) ? $userData[$v['created_by']] : "N/A";
                    $doneData[$k]['task_status'] = isset($statusData[$v['status']]) ? $statusData[$v['status']] : "N/A";
                    $doneData[$k]['task_type'] = isset($typeData[$v['type']]) ? $typeData[$v['type']] : "N/A";
                }
            }

			if (empty($kanbanData)) {
                // Return JSON error response
                echo json_encode([
                    'status' => 'ERROR',
                    'message' => 'Kanban Not Exist',
                ]);
                return;
			}

			$kanbanData['id'] = (int) $kanbanData['id'];
            $kanbanData['todo'] = $todoData;
            $kanbanData['doing'] = $doingData;
            $kanbanData['done'] = $doneData;

			$this->json_output(array(
				'kanbanDetail' => $kanbanData,
			));
		} catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
	}

    public function requestLeader()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("member_id", true);
                $id = $this->input->post("kanban_id", true);

                $ID = $id;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $id,
                    'is_deleted' => 0
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                // check whether requested user owned other kanban
                $check_availablility = $this->{$this->data['main_model']}->get_where(array(
                    'owned_by' => $member_id,
                    'is_deleted' => 0
                ));

                if (!empty($check_availablility)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'You Already Owned Another Kanban',
					]);
					return;
                } else {

                    $old_leader = $kanbanData['owned_by'];
                    $members = $kanbanData['member'];

                    $old_leader_data = $this->User_model->getOne(array(
                        'id' => $old_leader,
                        'is_deleted' => 0
                    ));

                    $new_leader_data = $this->User_model->getOne(array(
                        'id' => $member_id,
                        'is_deleted' => 0
                    ));

                    $this->db->trans_start();

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 16, // leader role request
                        'created_by' => $member_id,
                        'kanban_id' => $kanbanData['id'],
                        'receiver' => $kanbanData['owned_by'],
                        'message' => 'User <b>'. $new_leader_data['name'] .'</b> request the Leader role of Kanban: <b>' . $kanbanData['name'] . '</b>.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    $this->db->trans_complete();

                    $this->json_output(array(
                        'id' => $ID,
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

    public function acceptRequest()
    {
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("leader_id", true);
                $id = $this->input->post("kanban_id", true);
                $notification_id = $this->input->post("notification_id", true);

				
                $ID = $id;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $id,
                    'is_deleted' => 0
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                // get notification data
                $notificationData = $this->Notification_model->getOne(array(
                    'id' => $notification_id,
                    'is_deleted' => 0,
                ));

                if (empty($notificationData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Notification Not Exist',
					]);
					return;
                }

                $old_leader = $kanbanData['owned_by'];

                $new_leader = $notificationData['created_by'];

                $members = $kanbanData['member'];

                $old_leader_data = $this->User_model->getOne(array(
                    'id' => $old_leader,
                    'is_deleted' => 0
                ));

                $new_leader_data = $this->User_model->getOne(array(
                    'id' => $new_leader,
                    'is_deleted' => 0
                ));

                // Convert members to an array
                $membersArray = !empty($members) ? explode(',', $members) : [];

                // Add the old leader to the members list (if not already present)
                if (!in_array($old_leader, $membersArray)) {
                    $membersArray[] = $old_leader;
                }

                // Ensure the request user (new_leader) exists in membersArray before proceeding
                if (!in_array($new_leader, $membersArray)) {

                    $this->db->trans_start();

                    // update request notification
                    $this->Notification_model->update(array(
                        'id' => $notification_id,
                        'is_deleted' => 0,
                    ), array(
                        'is_read' => 1,
                        'is_accepted' => 2,
                        'modified_date' => date("Y-m-d H:i:s"),
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 18, // reject leader role request
                        'created_by' => $member_id,
                        'kanban_id' => $kanbanData['id'],
                        'receiver' => $kanbanData['owned_by'],
                        'message' => 'User <b>'. $new_leader_data['name'] .'</b> not exist in the Kanban: <b>' . $kanbanData['name'] . '</b>. Request Failed.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    $this->db->trans_complete();

                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'Request user is not a member of this Kanban.',
                    ]);
                    return;
                } else {
                    // Remove the new leader from the members list
                    $membersArray = array_filter($membersArray, function ($value) use ($new_leader) {
                        return $value != $new_leader;
                    });

                    $sql = array(
                        'owned_by' => $new_leader,
                    );

                    $this->db->trans_start();

                    $sql['member'] = implode(',', $membersArray);
                    $sql['modified_date'] = date("Y-m-d H:i:s");


                    $this->{$this->data['main_model']}->update(array(
                        'id' => $id,
                    ), $sql);

                    // update the notification
                    $this->Notification_model->update(array(
                        'id' => $notification_id,
                        'is_deleted' => 0
                    ), array(
                        'is_read' => 1,
                        'is_accepted'=> 1,
                        'modified_date' => date("Y-m-d H:i:s")
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 17, // approve leader role request
                        'created_by' => $member_id,
                        'kanban_id' => $kanbanData['id'],
                        'receiver' => $new_leader,
                        'message' => 'User <b>'. $old_leader_data['name'] .'</b> approve the request of Leader role from <b>' . $new_leader_data['name'] . '</b> for Kanban: <b>' . $kanbanData['name'] . '</b>.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    $this->db->trans_complete();
                }

				$this->json_output(array(
					'id' => $ID,
				));
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

    public function rejectRequest()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("leader_id", true);
                $id = $this->input->post("kanban_id", true);
                $notification_id = $this->input->post("notification_id", true);

                $ID = $id;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $id,
                    'is_deleted' => 0,
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                // get notification data
                $notificationData = $this->Notification_model->getOne(array(
                    'id' => $notification_id,
                    'is_deleted' => 0,
                ));

                if (empty($notificationData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Notification Not Exist',
					]);
					return;
                }

                $old_leader = $kanbanData['owned_by'];
                $member = $notificationData['created_by'];

                $old_leader_data = $this->User_model->getOne(array(
                    'id' => $old_leader,
                ));

                $requested_member_data = $this->User_model->getOne(array(
                    'id' => $member,
                ));

                $this->db->trans_start();

                // update request notification
                $this->Notification_model->update(array(
                    'id' => $notification_id,
                    'is_deleted' => 0,
                ), array(
                    'is_read' => 1,
                    'is_accepted' => 2,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 18, // reject leader role request
                    'created_by' => $member_id,
                    'kanban_id' => $kanbanData['id'],
                    'receiver' => $kanbanData['owned_by'],
                    'message' => 'User <b>'. $old_leader_data['name'] .'</b> rejected the request of Leader role from <b>' . $requested_member_data['name'] . '</b> for Kanban: <b>' . $kanbanData['name'] . '</b>.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'id' => $ID,
				));
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

    public function transferLeader()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("member_id", true);
                $owned_by = $this->input->post("owned_by", true);
                $id = $this->input->post("id", true);

				$sql = array(
                    'owned_by' => $member_id,
				);

                $ID = $id;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $id,
                    'is_deleted' => 0
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                
                // check whether requested user owned other kanban
                $check_availablility = $this->{$this->data['main_model']}->get_where(array(
                    'owned_by' => $member_id,
                    'is_deleted' => 0
                ));

                if (!empty($check_availablility)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'This Member Already Owned Another Kanban',
					]);
					return;
                } else {

                    $old_leader = $kanbanData['owned_by'];
                    $members = $kanbanData['member'];

                    $old_leader_data = $this->User_model->getOne(array(
                        'id' => $old_leader,
                    ));

                    $new_leader_data = $this->User_model->getOne(array(
                        'id' => $member_id,
                    ));

                    // Convert members to an array
                    $membersArray = !empty($members) ? explode(',', $members) : [];

                    // Add the old leader to the members list (if not already present)
                    if (!in_array($old_leader, $membersArray)) {
                        $membersArray[] = $old_leader;
                    }

                    // Remove the new leader from the members list
                    $membersArray = array_filter($membersArray, function ($value) use ($member_id) {
                        return $value != $member_id;
                    });

                    $this->db->trans_start();

                    $sql['member'] = implode(',', $membersArray);
                    $sql['modified_date'] = date("Y-m-d H:i:s");


                    $this->{$this->data['main_model']}->update(array(
                        'id' => $id,
                    ), $sql);

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 4, // become leader
                        'created_by' => $old_leader,
                        'kanban_id' => $kanbanData['id'],
                        'message' => 'User <b>'. $new_leader_data['name'] .'</b> become the Leader of Kanban: <b>' . $kanbanData['name'] . '</b>. Transfer by: <b>' . $old_leader_data['name'] . '</b>.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    $this->db->trans_complete();

                    $this->json_output(array(
                        'id' => $ID,
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

    public function memberJoin()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$user_id = $this->input->post("user_id", true);
                $kanban_id = $this->input->post("kanban_id", true);
                $notification_id = $this->input->post("notification_id", true);

                $this->db->trans_start();

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                ));

                $joinedUserData = $this->User_model->getOne(array(
                    'id' => $user_id,
                ));

                $memberArray = [];

				if (!empty($kanbanData['member'])) {
					$memberArray = explode(',',$kanbanData['member']);
				}

				// add new member into memberArray
				if (!in_array($user_id, $memberArray)) {
					$memberArray[] = $user_id;
				}
	
				// Convert back to a comma-separated string
				$updated_members = implode(',', $memberArray);
	
                $sql = array(
                    'member' => $updated_members,
                    'modified_date' => date("Y-m-d H:i:s")
				);


				$this->{$this->data['main_model']}->update(
                    ['id' => $kanban_id]
                , $sql);

                // insert new record in user_kanban
                $this->User_kanban_model->insert(array(
                    'user_id' => $user_id,
                    'kanban_id' => $kanban_id,
                    'created_date' => date("Y-m-d H:i:s")
                ));

                // update invite notification
                $this->Notification_model->update(array(
                    'id' => $notification_id,
                ), array(
                    'is_read' => 1,
                    'is_accepted' => 1,
                ));
				
                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 5, // joined kanban
                    'created_by' => $user_id,
                    'kanban_id' => $kanbanData['id'],
                    'receiver' => $user_id,
                    'message' => 'New member <b>' . $joinedUserData['name'] . '</b> just joined the Kanban.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'kanban_id' => $kanbanData['id'],
				));
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

    public function memberReject()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$user_id = $this->input->post("user_id", true);
                $kanban_id = $this->input->post("kanban_id", true);
                $notification_id = $this->input->post("notification_id", true);

                $this->db->trans_start();

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                ));

                $UserData = $this->User_model->getOne(array(
                    'id' => $user_id,
                ));

                // update invite notification
                $this->Notification_model->update(array(
                    'id' => $notification_id,
                ), array(
                    'is_read' => 1,
                    'is_accepted' => 2,
                ));
				
                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 11, // reject invitation
                    'created_by' => $user_id,
                    'kanban_id' => $kanbanData['id'],
                    'is_read' => 0,
                    'is_accepted' => 2,
                    'message' => 'User <b>' . $UserData['name'] . '</b> rejected the invite to join kanban <b>' . $kanbanData['name'] . '</b>.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'kanban_id' => $kanbanData['id'],
				));
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

    public function removeMember()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("member_id", true);
                $kanban_id = $this->input->post("kanban_id", true);

				// $adminID = $this->adminAuth($token);
                $this->db->trans_start();

                $ID = null;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                $leaderData = $this->User_model->getOne(array(
                    'id' => $kanbanData['owned_by'],
                ));

                $memberData = $this->User_model->getOne(array(
                    'id' => $member_id,
                ));

                $members = $kanbanData['member'];

                if (!empty($members)) {
                    // Convert string into an array
                    $memberArray = explode(',', $members);
    
                    // Remove the member_id if it exists
                    $memberArray = array_filter($memberArray, function ($value) use ($member_id) {
                        return trim($value) !== trim($member_id);
                    });
    
                    // Convert back to a comma-separated string
                    $updatedMembers = implode(',', $memberArray);

                } else {

                    $updatedMembers = ''; // If no members left, store an empty string

                }

                $ID = $this->{$this->data['main_model']}->update(array(
                    'id' => $kanban_id,
                ), array(
                    'member' => $updatedMembers,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                // update record in user_kanban
                $this->User_kanban_model->update(array(
                    'user_id' => $member_id,
                    'kanban_id' => $kanban_id
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s")
                ));

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 3,
                    'created_by' => $leaderData['id'],
                    'receiver' => $member_id,
                    'kanban_id' => $kanban_id,
                    'message' => 'User <b>' . $memberData['name'] . '</b> have been remove by leader: <b>' . $leaderData['name'] . '</b>, from Kanban <b>' . $kanbanData['name'] . '</b> .',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'id' => $ID,
				));
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

    public function quitKanban()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$user_id = $this->input->post("user_id", true);
                $kanban_id = $this->input->post("kanban_id", true);

                $ID = null;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                $this->db->trans_start();

                // get current leader
                $current_leader = $kanbanData['owned_by'];

                // member list from 'member' column
                $members = $kanbanData['member'];

                // Convert members to an array
                $membersArray = !empty($members) ? explode(',', $members) : [];

                $current_user_data =  $this->User_model->getOne(array(
                    'id' => $user_id,
                ));

                // situation 1 => if user_id == owned_by id
                if ($user_id == $current_leader) {

                    // assign a new leader from 'member'
                   
                    // Pick the first member as new leader
                    // Removes and returns the first member
                    $new_leader = !empty($membersArray) ? array_shift($membersArray) : null;


                    // update the current 'owned_by' & 'member'
                    $ID = $this->{$this->data['main_model']}->update(array(
                        'id' => $kanban_id,
                    ), array(
                        'owned_by' => $new_leader,
                        'member' => implode(',', $membersArray),
                        'modified_date' => date("Y-m-d H:i:s"),
                    ));

                    $latest_kanban_info = $this->{$this->data['main_model']}->getOne(array(
                        'id' => $kanban_id,
                    ));

                    $new_leader_data =  $this->User_model->getOne(array(
                        'id' => $new_leader,
                    ));

                    // update record in user_kanban
                    $this->User_kanban_model->update(array(
                        'user_id' => $user_id,
                        'kanban_id' => $kanban_id
                    ), array(
                        'is_deleted' => 1,
                        'modified_date' => date("Y-m-d H:i:s")
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 4, // become leader
                        'created_by' => $user_id,
                        'kanban_id' => $kanbanData['id'],
                        'message' => 'User <b>'. $new_leader_data['name'] .'</b> become the Leader of Kanban: <b>' . $latest_kanban_info['name'] . '</b>. Transfer by: <b>' . $current_user_data['name'] . '</b>.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 10, // quit kanban
                        'created_by' => $user_id,
                        'kanban_id' => $kanban_id,
                        'receiver' => $user_id,
                        'message' => 'User <b>' . $current_user_data['name'] . '</b> has exited the kanban <b>' . $kanbanData['name'] . '</b>.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                } else {

                    // situation 2 => if user_id == one of the member
                    // Remove the user_id from the members list
                    $membersArray = array_filter($membersArray, function ($value) use ($user_id) {
                        return $value != $user_id;
                    });

                    // update the 'member' column
                    $ID = $this->{$this->data['main_model']}->update(array(
                        'id' => $kanban_id,
                    ), array(
                        'member' => implode(',', $membersArray),
                        'modified_date' => date("Y-m-d H:i:s"),
                    ));

                    // update record in user_kanban
                    $this->User_kanban_model->update(array(
                        'user_id' => $user_id,
                        'kanban_id' => $kanban_id
                    ), array(
                        'is_deleted' => 1,
                        'modified_date' => date("Y-m-d H:i:s")
                    ));

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 10, // quit kanban
                        'created_by' => $user_id,
                        'kanban_id' => $kanban_id,
                        'receiver' => $user_id,
                        'message' => 'User <b>' . $current_user_data['name'] . '</b> has exited the kanban <b>' . $kanbanData['name'] . '</b>.',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                }

                $this->db->trans_complete();

                $this->json_output(array(
                    'id' => $ID,
                ));
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

    public function createNewKanban()
    {
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$name = $this->input->post("new_kanban_name", true);
                $owned_by = $this->input->post("leader", true);

                $this->db->trans_start();

				$sql = array(
					'name' => $name,
                    'owned_by' => $owned_by,
				);

                $ID = null;

				$sql['created_date'] = date("Y-m-d H:i:s");

                $ID = $this->{$this->data['main_model']}->insert($sql);

                // insert new record in user_kanban
                $this->User_kanban_model->insert(array(
                    'user_id' => $owned_by,
                    'kanban_id' => $ID,
                    'created_date' => date("Y-m-d H:i:s")
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'id' => $ID,
				));
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

    public function complete()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


        try {

            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));


                $ID = $this->input->post("id", true);
                $user_id = $this->input->post("user_id", true);


                $KanbanData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));

                if (empty($KanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                $this->db->trans_start();

                $this->{$this->data['main_model']}->update(array(
                    $this->data['primaryKey'] => $ID,
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                // update records in user_kanban
                $current_records = $this->User_kanban_model->get_where(array(
                    'kanban_id' => $ID,
                    'is_deleted' => 0
                ));

                if (!empty($current_records)) {

                    // extract user_ids from the records
                    $kanban_users = array_column($current_records, 'user_id');

                    // update records to is_deleted = 1
                    foreach ($kanban_users as $user) {

                        $this->User_kanban_model->update(array(
                            'kanban_id' => $ID,
                            'user_id' => $user
                        ), array(
                            'is_deleted' => 1,
                            'modified_date' => date("Y-m-d H:i:s"),
                        ));

                    }                    

                }

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 9,
                    'created_by' => $user_id,
                    'receiver' => null,
                    'kanban_id' => $ID,
                    'message' => 'Kanban <b>' . $KanbanData['name'] . '</b> completed.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();
                
                $this->json_output(array(
                    $this->data['primaryKey'] => $ID,
                ));
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

    public function editKanbanName()
    {
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$kanban_id = $this->input->post("kanban_id", true);
                $user_id = $this->input->post("user_id", true);
                $kanban_name = $this->input->post("kanban_name", true);

				
                $ID = $kanban_id;

                $kanbanData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                    'is_deleted' => 0
                ));

                if (empty($kanbanData)) {
                    // Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
                }

                $this->db->trans_start();

                $this->{$this->data['main_model']}->update(array(
                    'id' => $kanban_id,
                    'is_deleted' => 0
                ), array(
                    'name' => $kanban_name
                ));
                
                $edited_user_data = $this->User_model->getOne(array(
                    'id' => $user_id,
                    'is_deleted' => 0
                ));

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 20, // kanban edit
                    'created_by' => $user_id,
                    'kanban_id' => $kanban_id,
                    'receiver' => null,
                    'message' => 'User <b>'. $edited_user_data['name'] .'</b> edited kanban name from <b>' . $kanbanData['name'] . '</b> to <b>' . $kanban_name . '</b>.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'id' => $ID,
				));
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