<?php

//class name need to same with file name (first alphabet uppercase)
require_once APPPATH . 'core/MY_apicontroller.php';
class User_api extends MY_apicontroller {

    public function __construct(){
        //run the parent first
        parent::__construct();

        //load user model
        // $this->load->model("User_model");
		$this->data['main_model'] = 'User_model';
		$this->load->model($this->data['main_model']);

		$this->data['primaryKey'] = $this->{$this->data['main_model']}->primaryKey;

		//load notification model
        $this->load->model("Notification_model");

    }

    public function getUserList(){

		//load kanban_list model
        $this->load->model("Kanban_list_model");

		//load user_role model
        $this->load->model("User_role_model");
        
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

        $dataList = $this->{$this->data['main_model']}->fetch($count, $start, $where, $like, $sorting, "id,name,email,role,created_date,modified_date");

		$rolelist = $this->User_role_model->getIDKeyArray("name", array("is_deleted" => 0));

		// Get the Kanban lists and their members  
		$kanbanLists = $this->Kanban_list_model->fetch2("id,name,member,owned_by"); 
		$kanbanNames = [];  
		$kanbanMembers = [];  

		// Prepare associative arrays for Kanban names and members  
		foreach ($kanbanLists as $kanban) {  
			$kanbanNames[$kanban['id']] = $kanban['name']; // Keyed by Kanban ID  
			$kanbanMembers[$kanban['id']] = explode(',', $kanban['member']); // Convert member IDs to an array  
		}  

        if (!empty($dataList)) {
            foreach ($dataList as $k => $v) {

				$ownedKanbanNames = [];
    
				foreach ($kanbanLists as $kanban) {
					if ($kanban['owned_by'] == $v['id']) { // Match user ID with owned_by
						$ownedKanbanNames[] = $kanban['name']; // Store Kanban name
					}
				}

				// Assign the Kanban name(s) to `own_kanban`
				$dataList[$k]['own_kanban'] = !empty($ownedKanbanNames) ? implode(', ', $ownedKanbanNames) : "N/A";

				// get task status
                $dataList[$k]['user_role'] = isset($rolelist[$v['role']]) ? $rolelist[$v['role']] : "N/A";

				// Initialize the joined kanban names array  
				$joinedKanbanNames = [];  

				foreach ($kanbanMembers as $kanbanId => $members) {  
					// Check if the user ID is in the members array  
					if (in_array($v['id'], $members)) {  
						// If yes, push the Kanban name to the joined array  
						$joinedKanbanNames[] = $kanbanNames[$kanbanId];  
					}  
				}  
		
				// Join Kanban names into a single string  
				$dataList[$k]['joined_kanban'] = !empty($joinedKanbanNames) ? implode('<br/>', $joinedKanbanNames) : "N/A";  
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

				$name = $this->input->post("name", true);
                $email = $this->input->post("email", true);
				$role = $this->input->post("role", true);
				$user_id = $this->input->post("user_id", true);
				
				$sql = array(
					'name' => $name,
                    'email' => $email,
					'role' => $role,
				);

                $ID = null;

				switch ($mode) {
					case "Add":

						$check_same_email = $this->{$this->data['main_model']}->getOne(array(
							'email' => $email,
							'is_deleted' => 0
						));

						if ($check_same_email) {
							// Return JSON error response
							echo json_encode([
								'status' => 'ERROR',
								'message' => 'Same Email Exist',
							]);
							return;
						}
						
						$sql['created_date'] = date("Y-m-d H:i:s");

						$ID = $this->{$this->data['main_model']}->insert($sql);

						$created_user_id = $this->User_model->getOne(array(
							'id' => $user_id,
						));

						// create a notification
						$this->Notification_model->insert(array(
							'type' => 21, // information updated
							'created_by' => $user_id,
							'kanban_id' => null,
							'receiver' => null,
							'message' => 'User <b>' . $created_user_id['name'] . '</b>(Admin) created a new user.',
							'created_date' => date("Y-m-d H:i:s"),
						));

						break;
					case "Edit":

						$ID = $id;

						$userData = $this->{$this->data['main_model']}->getOne(array(
							'id' => $ID,
						));

						if (empty($userData)) {

							// Return JSON error response
							echo json_encode([
								'status' => 'ERROR',
								'message' => 'User No Exist',
							]);
							return;
						}

						// Check if email exists for another record
						$check_same_email = $this->{$this->data['main_model']}->getOne(array(
							'email' => $email,
							'id !=' => $ID, // Exclude current record
							'is_deleted' => 0
						));

						if ($check_same_email) {
							// Return JSON error response
							echo json_encode([
								'status' => 'ERROR',
								'message' => 'Same Email Exist for Another User',
							]);
							return;
						}

						$sql['modified_date'] = date("Y-m-d H:i:s");

						$this->{$this->data['main_model']}->update(array(
							'id' => $id,
						), $sql);

						$edited_user_id = $this->User_model->getOne(array(
							'id' => $user_id,
						));

						$this->Notification_model->insert(array(
							'type' => 12, // information updated
							'created_by' => $user_id,
							'kanban_id' => null,
							'receiver' => $id,
							'message' => 'User <b>' . $edited_user_id['name'] . '</b>(Admin) updated user <b>' . $userData['name'] . '</b> information.',
							'created_date' => date("Y-m-d H:i:s"),
						));
						
						break;
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

	public function delete()
    {

        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


        try {

            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));


                $ID = $this->input->post("id", true);

                $userData = $this->{$this->data['main_model']}->getOne(array(
                    $this->data['primaryKey'] => $ID,
                ));
                if (empty($userData)) {

					// Return JSON error response
                    echo json_encode([
                        'status' => 'ERROR',
                        'message' => 'User not exists',
                    ]);
                    return;
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
				// Return JSON error response
				echo json_encode([
					'status' => 'ERROR',
					'message' => 'Invalid parameters',
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

			$userData = $this->{$this->data['main_model']}->getOne(array(
				'id' => $ID,
				'is_deleted' => 0,
			));

			if (empty($userData)) {
				// Return JSON error response
				echo json_encode([
					'status' => 'ERROR',
					'message' => 'User Not Exist',
				]);
				return;
			}

			$userData['id'] = (int) $userData['id'];

			$this->json_output(array(
				'userDetail' => $userData,
			));

		} catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
	}

    public function userSearch()
	{
		header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {
			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$onwed_by = $this->input->post("onwed_by", true);

				if ($onwed_by != 0) {

					$userData = $this->{$this->data['main_model']}->getOne(
						array(
							"is_deleted" => 0,
							"id" => $onwed_by,
						)
					);

					if (empty($userData)) {
						// Return JSON error response
						echo json_encode([
							'status' => 'ERROR',
							'message' => 'User Not Found',
						]);
						return;
					}

				} else {

					$userData = [
						'id' => 0,
						'name' => 'Not Assigned'
					];
				}

				$this->json_output(
					array(
						'userData' => $userData,
					)
				);

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

	public function multipleUserSearch()
	{
		header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {
			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				$member = $this->input->post("member", true);

				if ($member != 0) {	

					$userData = [];

					foreach ($member as $id) {  
						$user = $this->User_model->getOne(array("is_deleted" => 0, "id" => $id));  
						if (!empty($user)) {  
							$userData[] = $user; // Collect user data  
						}  
					}  

					if (empty($userData)) {
						// Return JSON error response
						echo json_encode([
							'status' => 'ERROR',
							'message' => 'User Not Found',
						]);
						return;
					}

				} else {

					$userData = [
						'id' => 0,
						'name' => 'Not Assigned'
					];
				}

				$this->json_output(
					array(
						'userData' => $userData,
					)
				);
			} else {
				// Return JSON error response
				echo json_encode([
					'status' => 'ERROR',
					'message' => 'Invalid Parameter',
				]);
				return;
			}
		} catch (Exception $e) {
			$this->json_output_error($e->getMessage());
		}
	}

	// used in kanban edit (backend)
	public function getAllAvailableUser()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				//load kanban_list model
				$this->load->model("Kanban_list_model");

                $kanban_id = $this->input->post("kanban_id", true);

				$kanban_data = $this->Kanban_list_model->getOne(array(
					'id' => $kanban_id,
					'is_deleted' => 0,
				));

				if (empty($kanban_data)) {
					// Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
				}

				// get all user of current kanban
				$users_in_kanban = [];

				$leader_in_kanban = [];
				
				// add leader user into array
				if (!empty($kanban_data['owned_by'])) {
					$leader_in_kanban[] = $kanban_data['owned_by'];
				}

				$all_users = $this->{$this->data['main_model']}->fetch2('id,name');

				// filters all_users to exclude the user in $leader_in_kanban
				$available_users = array_filter($all_users, function ($user) use ($leader_in_kanban) {
					return !in_array($user['id'], $leader_in_kanban);
				});

				// reset array keys
				$available_users = array_values($available_users);

                $this->json_output(array(
                    'available_user' => $available_users,
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

	// used in kanban add (backend)
	public function getAllAvailableUserExceptLeader()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

                $user_id = $this->input->post("user_id", true);

				// get all user of current kanban
				$users_in_kanban = [];

				$selected_leader = [] ;

				// append selected leader user id
				$selected_leader[] = $user_id;

				// fetch all users
				$all_users = $this->{$this->data['main_model']}->fetch2('id,name');

				// filters all_users to exclude the user in $selected_leader
				$available_users = array_filter($all_users, function ($user) use ($selected_leader) {
					return !in_array($user['id'], $selected_leader);
				});

				// reset array keys
				$available_users = array_values($available_users);

                $this->json_output(array(
                    'available_user' => $available_users,
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

	// frontend
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

				$name = $this->input->post("name", true);
                $email = $this->input->post("email", true);
				$safety_answer_1 = $this->input->post("safety_answer_1", true);
				$safety_answer_2 = $this->input->post("safety_answer_2", true);
				
				$sql = array(
					'name' => $name,
                    'email' => $email,
					'safety_word_1' => $safety_answer_1,
					'safety_word_2' => $safety_answer_2,
				);

                $ID = null;

				switch ($mode) {
					case "Edit":

						$ID = $id;

						$userData = $this->{$this->data['main_model']}->getOne(array(
							'id' => $ID,
						));

						if (empty($userData)) {
							// Return JSON error response
							echo json_encode([
								'status' => 'ERROR',
								'message' => 'User No Exist',
							]);
							return;
						}

						// Check if email exists for another record
						$check_same_email = $this->{$this->data['main_model']}->getOne(array(
							'email' => $email,
							'id !=' => $ID, // Exclude current record
							'is_deleted' => 0
						));

						if ($check_same_email) {
							// Return JSON error response
							echo json_encode([
								'status' => 'ERROR',
								'message' => 'Same Email Exist for Another User',
							]);
							return;
						}

						// $sql['last_modified_user_id'] = $adminID;
						$sql['modified_date'] = date("Y-m-d H:i:s");

						$this->{$this->data['main_model']}->update(array(
							'id' => $id,
						), $sql);

						// create a notification
                        $this->Notification_model->insert(array(
                            'type' => 12, // information updated
                            'created_by' => $id,
                            'kanban_id' => null,
							'receiver' => $id,
                            'message' => 'You have updated your information.',
                            'created_date' => date("Y-m-d H:i:s"),
                        ));
						
						break;
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

	public function frontendDetail($ID, $token)
	{

		try {

			//load kanban_list model
			$this->load->model("Kanban_list_model");

			//load notification model
			$this->load->model("Notification_model");

			if ($token == null || $token == "") {

				// Return JSON error response
				echo json_encode([
					'status' => 'ERROR',
					'message' => 'Unauthorized User!',
				]);
				return;
				
			}

			$userData = $this->{$this->data['main_model']}->getOne(array(
				'id' => $ID,
				'token' => $token,
				'is_deleted' => 0,
			));

			$allUser = $this->{$this->data['main_model']}->get_where(array(
				'is_deleted' => 0,
			));

			$ownKanban = $this->Kanban_list_model->getOne(array(
				'owned_by' => $ID,
				'is_deleted' => 0,
			));

			// Fetch kanban lists  
			$kanbanLists = $this->Kanban_list_model->fetch2("id,name,member,owned_by");  

			if (empty($userData)) {
				// Return JSON error response
				echo json_encode([
					'status' => 'ERROR',
					'message' => 'Unauthorized User!',
				]);
				return;
			}

			// If there is an ownKanban, add the user's name to it  
			if (!empty($ownKanban)) {  
				// Assign user name to owned Kanban  
				$ownKanban['owned_by_name'] = $userData['name']; // Add the user name to ownKanban  
			}  

			// Filter Kanban lists where the user ID is present in the member string  
			$userKanbans = array_filter($kanbanLists, function($kanban) use ($ID) {  
				// Convert member string to an array of IDs  
				$members = explode(',', $kanban['member']);  
				// Check if the user ID is in the member array  
				return in_array($ID, array_map('trim', $members)); // Trim to remove any whitespace  
			});  

			// Create a mapping of user IDs to names  
			$userMap = [];  
			foreach ($allUser as $user) {  
				$userMap[$user['id']] = $user['name']; // 'id' is the user ID and 'name' is the user name  
			}  

			// Update userKanbans with the owned_by name   
			foreach ($userKanbans as &$kanban) {  
				if (isset($kanban['owned_by']) && isset($userMap[$kanban['owned_by']])) {  
					$kanban['owned_by_name'] = $userMap[$kanban['owned_by']]; // Add the user name for owned_by  
				}  
			}  

			// Add user Kanbans to userData  
			$userData['userKanbans'] = array_values($userKanbans); // Re-index the array
	
			$userData['id'] = (int) $userData['id'];

			$userData['ownKanban'] = $ownKanban;

			$this->json_output(array(
				'userDetail' => $userData,
			));
		} catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
	}

	public function getCurrentUser($ID)
	{
		try {

			$userData = $this->{$this->data['main_model']}->getOne(array(
				'id' => $ID,
				'is_deleted' => 0,
			));

			$this->json_output(array(
				'userDetail' => $userData,
			));

		} catch (Exception $e) {

			$this->json_output_error($e->getMessage());
		}
	}

	public function getAvailableUsers()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				//load kanban_list model
				$this->load->model("Kanban_list_model");

                $kanban_id = $this->input->post("kanban_id", true);

				$kanban_data = $this->Kanban_list_model->getOne(array(
					'id' => $kanban_id,
					'is_deleted' => 0,
				));

				if (empty($kanban_data)) {
					// Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'Kanban Not Exist',
					]);
					return;
				}

				// get all user of current kanban
				$users_in_kanban = [];

				// add leader user into array
				if (!empty($kanban_data['owned_by'])) {
					$users_in_kanban[] = $kanban_data['owned_by'];
				}

				if (!empty($kanban_data['member'])) {
					$memberArray = explode(',',$kanban_data['member']);
					$users_in_kanban = array_merge($users_in_kanban, $memberArray);
				}

				$all_users = $this->{$this->data['main_model']}->fetch2('id,name,email');

				// filters all_users to exclude those already in $users_in_kanban
				$available_users = array_filter($all_users, function ($user) use ($users_in_kanban) {
					return !in_array($user['id'], $users_in_kanban);
				});

				// reset array keys
				$available_users = array_values($available_users);

                $this->json_output(array(
                    'available_user' => $available_users,
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

	public function inviteUser()
	{
        header('Content-Type: application/json; charset=utf-8');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


		try {

			if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

				//load notification model
				$this->load->model("Notification_model");

				//load kanban_list model
				$this->load->model("Kanban_list_model");

				$user_id = $this->input->post('invited_user_id', true);
				$current_user_id = $this->input->post('current_user_id', true);
				$kanban_id = $this->input->post('kanban_id', true);

				$this->db->trans_start();

				$user_data = $this->{$this->data['main_model']}->getOne(array(
					'id' => $user_id,
					'is_deleted' => 0,
				));

				$kanban_data = $this->Kanban_list_model->getOne(array(
					'id' => $kanban_id,
					'is_deleted' => 0,
				));
				
				$leader_data = $this->{$this->data['main_model']}->getOne(array(
					'id' => $kanban_data['owned_by'],
					'is_deleted' => 0,
				));

				$ID = null;

				if (empty($user_data)) {
					// Return JSON error response
					echo json_encode([
						'status' => 'ERROR',
						'message' => 'User Not Exist',
					]);
					return;
				}

				// insert notification for invited user
				$notification_id = $this->Notification_model->insert(array(
					'type' => 1, // invite user
					'created_by' => $current_user_id,
					'receiver' => $user_id,
					'kanban_id' => $kanban_id,
					'message' => "User <b>" . $user_data['name'] . "</b> invited by <b>" . $leader_data['name'] . "</b> to join Kanban <b>" . $kanban_data['name'] . "</b>.",
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