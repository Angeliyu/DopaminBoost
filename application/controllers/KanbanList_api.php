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

        $userlist = $this->{$this->data['main_model']}->getIDKeyArray("name", array("is_deleted" => 0));

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

				// $adminID = $this->adminAuth($token);
				$name = $this->input->post("name", true);
                $owned_by = $this->input->post("owned_by", true);
                $raw_member = $this->input->post("member", true);

                if (is_array($raw_member)) {  
                    $member = implode(',', $raw_member); // Convert array to comma-separated string  
                } 
				
				$sql = array(
					'name' => $name,
                    'owned_by' => $owned_by,
				);

                $ID = null;

				switch ($mode) {
					case "Add":
						
						// $sql['created_user_id'] = $adminID;
						$sql['created_date'] = date("Y-m-d H:i:s");

                        if (!empty($raw_member)) {
                            $sql['member'] = $member;
                        }

						$ID = $this->{$this->data['main_model']}->insert($sql);

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

                        if (!empty($raw_member)) {
                            $sql['member'] = $member;
                        }

						$this->{$this->data['main_model']}->update(array(
							'id' => $id,
						), $sql);
						
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

    public function getDetail($ID)
	{

		try {

			// $adminID = $this->adminAuth($token);

			$eachData = $this->{$this->data['main_model']}->getOne(array(
				'id' => $ID,
				'is_deleted' => 0,
			));

			if (empty($eachData)) {
				throw new Exception("Data Not Found");
			}

			$eachData['id'] = (int) $eachData['id'];

			$this->json_output(array(
				'kanbanDetail' => $eachData,
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
                    // Get the kanban details  
                    $kanban = $this->{$this->data['main_model']}->getOne(array(  
                        "is_deleted" => 0,  
                        $this->data['primaryKey'] => $kanban_id,  
                    ));  

                    // 'member' column contains comma-separated user IDs  
                    $memberIds = explode(',', $kanban['member']);  
                    

                     // Use get_where with where_in for member IDs  
                    $members = $this->User_model->fetch2("id,name");

                    // Filter members by the extracted IDs  
                    $members = array_filter($members, function($member) use ($memberIds) {  
                        return in_array($member['id'], $memberIds);  
                    });  

                    // Return the members as part of the response  
                    $this->json_output(array(  
                        'userData' => $members,  
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
    public function kanbanDetail($ID)
	{

		try {

			// $adminID = $this->adminAuth($token);

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
				throw new Exception("Data Not Found");
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

    public function transferLeader($ID)
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member_id = $this->input->post("member_id", true);

				// $adminID = $this->adminAuth($token);
                $owned_by = $this->input->post("owned_by", true);
                $id = $this->input->post("id", true);

                
				$sql = array(
                    'owned_by' => $member_id,
				);

                $ID = $id;

                $eachData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $id,
                ));

                if (empty($eachData)) {
                    throw new Exception("Data No Exist");
                }

                $old_leader = $eachData['owned_by'];
                $members = $eachData['member'];

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


                // $sql['last_modified_user_id'] = $adminID;
                $sql['member'] = implode(',', $membersArray);
                $sql['modified_date'] = date("Y-m-d H:i:s");


                $this->{$this->data['main_model']}->update(array(
                    'id' => $id,
                ), $sql);

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 4, // become leader
                    'created_by' => $old_leader,
                    'kanban_id' => $eachData['id'],
                    'message' => 'User <b>'. $new_leader_data['name'] .'</b> become the Leader of Kanban: ' . $eachData['name'] . '. Transfer by: ' . $old_leader_data['name'],
                    'created_date' => date("Y-m-d H:i:s"),
                ));


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
	
				// Update the Kanban list with new members
				$update_data = ['member' => $updated_members];


				$this->{$this->data['main_model']}->update(
                    ['id' => $kanban_id]
                , $update_data);

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
                    'message' => 'New member <b>' . $joinedUserData['name'] . '</b> just joined the Kanban.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'kanban_id' => $kanbanData['id'],
				));
			} else {
				throw new Exception("Invalid Parameters");
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
	
				// Update the Kanban list with new members
				$update_data = ['member' => $updated_members];


				$this->{$this->data['main_model']}->update(
                    ['id' => $kanban_id]
                , $update_data);

                // update invite notification
                $this->Notification_model->update(array(
                    'id' => $notification_id,
                ), array(
                    'is_read' => 1,
                    'is_accepted' => 2,
                ));
				
                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 5, // joined kanban
                    'created_by' => $user_id,
                    'kanban_id' => $kanbanData['id'],
                    'message' => 'User <b>' . $UserData['name'] . '</b> rejected the invite.',
                    'created_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

				$this->json_output(array(
					'kanban_id' => $kanbanData['id'],
				));
			} else {
				throw new Exception("Invalid Parameters");
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

                $eachData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                ));

                if (empty($eachData)) {
                    throw new Exception("Data No Exist");
                }

                $leaderData = $this->User_model->getOne(array(
                    'id' => $eachData['owned_by'],
                ));

                $members = $eachData['member'];

                if (!empty($members)) {
                    // Convert string into an array
                    $memberArray = explode(',', $members);
    
                    // Remove the member_id if it exists
                    $memberArray = array_filter($memberArray, function ($value) use ($member_id) {
                        return trim($value) !== trim($member_id);
                    });
    
                    // Convert back to a comma-separated string
                    $updatedMembers = implode(',', $memberArray);

                    // create a notification
                    $this->Notification_model->insert(array(
                        'type' => 3,
                        'created_by' => $leaderData['id'],
                        'receiver' => $member_id,
                        'kanban_id' => $kanban_id,
                        'message' => 'You have been remove by leader: ' . $leaderData['name'] . ', from Kanban ' . $eachData['name'] . ' .',
                        'created_date' => date("Y-m-d H:i:s"),
                    ));

                } else {

                    $updatedMembers = ''; // If no members left, store an empty string

                }

                $ID = $this->{$this->data['main_model']}->update(array(
                    'id' => $kanban_id,
                ), array(
                    'member' => $updatedMembers,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                $this->db->trans_complete();

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

    public function quitKanban($ID)
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$user_id = $this->input->post("user_id", true);
                $kanban_id = $this->input->post("kanban_id", true);

				// $adminID = $this->adminAuth($token);

                $ID = null;

                $eachData = $this->{$this->data['main_model']}->getOne(array(
                    'id' => $kanban_id,
                ));

                if (empty($eachData)) {
                    throw new Exception("Data No Exist");
                }

                // get current leader
                $current_leader = $eachData['owned_by'];

                // member list from 'member' column
                $members = $eachData['member'];

                // Convert members to an array
                $membersArray = !empty($members) ? explode(',', $members) : [];

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

				$sql = array(
					'name' => $name,
                    'owned_by' => $owned_by,
				);

                $ID = null;

				$sql['created_date'] = date("Y-m-d H:i:s");

                $ID = $this->{$this->data['main_model']}->insert($sql);

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


                $ID = $this->input->post("id", true);
                $user_id = $this->input->post("user_id", true);

                // $adminID = $this->adminAuth($token);

                $KanbanData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));

                if (empty($KanbanData)) {
                    throw new Exception("kanban not exists");
                }

                $this->{$this->data['main_model']}->update(array(
                    $this->data['primaryKey'] => $ID,
                ), array(
                    'is_deleted' => 1,
                    'modified_date' => date("Y-m-d H:i:s"),
                ));

                // create a notification
                $this->Notification_model->insert(array(
                    'type' => 9,
                    'created_by' => $user_id,
                    'receiver' => null,
                    'kanban_id' => $ID,
                    'message' => 'Kanban <b>' . $KanbanData['name'] . '</b> completed.',
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