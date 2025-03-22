<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


// $route['default_controller'] = 'welcome';
$route['default_controller'] = 'frontend/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// frontend_view
//// register
$route['register'] = 'frontend/register';

//// forgot password validation
$route['forgot_password_validation'] = 'frontend/forgot_password_validation';

//// reset password
$route['reset_password/(:any)'] = 'frontend/reset_password/$1';


//// profile
$route['profile/(:any)/(:any)'] = 'frontend/home/$1/$2';

//// kanban
$route['kanban/(:any)/(:any)/(:any)'] = 'frontend/kanban/$1/$2/$3';


// frontend api
//// authorization api
$route['api/login'] = 'userAuth_api/login';
$route['api/register'] = 'userAuth_api/register';
$route['api/forgot_password'] = 'userAuth_api/forgot_password_validation';
$route['api/reset_password'] = 'userAuth_api/reset_password';
$route['api/logout'] = 'userAuth_api/logout';

//// profile api
$route['api/edit_user_info'] = 'user_api/edit_from_frontend';
$route['api/user_profile/(:any)/(:any)'] = 'user_api/frontendDetail/$1/$2';
$route['api/leader_kanban_notification/(:any)'] = 'notification_api/leaderNotification/$1';
$route['api/joined_kanban_notification'] = 'notification_api/joinedMemberNotification';
$route['api/general_notification/(:any)'] = 'notification_api/generalNotification/$1';
$route['api/mark_as_read'] = 'notification_api/mark_as_read';
$route['api/member_joined'] = 'kanbanList_api/memberJoin';
$route['api/member_rejected'] = 'kanbanList_api/memberReject';

//// kanban api
$route['api/kanban_details/(:any)'] = 'kanbanList_api/kanbanDetail/$1';
$route['api/getCurrentUser/(:any)'] = 'user_api/getCurrentUser/$1';
$route['api/transferLeader'] = 'kanbanList_api/transferLeader';
$route['api/removeMember'] = 'kanbanList_api/removeMember';
$route['api/quitKanban'] = 'kanbanList_api/quitKanban';
$route['api/getAvailableUsers'] = 'user_api/getAvailableUsers';
$route['api/inviteUser'] = 'user_api/inviteUser';
$route['api/kanban_notification/(:any)'] = 'notification_api/kanbanNotification/$1';
$route['api/createNewKanban'] = 'kanbanList_api/createNewKanban';
$route['api/completeKanban'] = 'kanbanList_api/complete';
$route['api/requestLeader'] = 'kanbanList_api/requestLeader';
$route['api/acceptRequest'] = 'kanbanList_api/acceptRequest';
$route['api/rejectRequest'] = 'kanbanList_api/rejectRequest';
$route['api/checkAuthorization'] = 'kanbanList_api/checkAuthorization';
$route['api/editKanbanName'] = 'kanbanList_api/editKanbanName';

//// kanban todo task api
$route['api/editKanbanDetailsTodo'] = 'kanbanDetailsTodo_api/edit_from_frontend';
$route['api/go_to_doing'] = 'kanbanDetailsTodo_api/go_to_doing';
$route['api/deleteTodo'] = 'kanbanDetailsTodo_api/delete_with_notification';


//// kanban doing task api
$route['api/editKanbanDetailsDoing'] = 'kanbanDetailsDoing_api/edit_from_frontend';
$route['api/completed'] = 'kanbanDetailsDoing_api/complete';
$route['api/deleteDoing'] = 'kanbanDetailsDoing_api/delete_with_notification';


// backend_view
//// user
$route['admin_userList/(:any)/(:any)'] = 'backend/admin_userList/$1/$2';
$route['admin_add_user/(:any)/(:any)'] = 'backend/add_user/$1/$2';
$route['admin_edit_user/(:any)/(:any)/(:any)'] = 'backend/add_user/$1/$2/$3';

//// kanban
$route['admin_kanbanList/(:any)/(:any)'] = 'backend/admin_kanbanList/$1/$2';
$route['admin_add_kanban/(:any)/(:any)'] = 'backend/add_kanban/$1/$2';
$route['admin_edit_kanban/(:any)/(:any)/(:any)'] = 'backend/add_kanban/$1/$2/$3';

//// kanban details todo
$route['admin_kanban_details_todo/(:any)/(:any)'] = 'backend/admin_kanban_details_todo/$1/$2';
$route['admin_add_kanban_details_todo/(:any)/(:any)'] = 'backend/add_kanban_details_todo/$1/$2';
$route['admin_edit_kanban_details_todo/(:any)/(:any)/(:any)'] = 'backend/add_kanban_details_todo/$1/$2/$3';

//// kanban details doing
$route['admin_kanban_details_doing/(:any)/(:any)'] = 'backend/admin_kanban_details_doing/$1/$2';
$route['admin_add_kanban_details_doing/(:any)/(:any)'] = 'backend/add_kanban_details_doing/$1/$2';
$route['admin_edit_kanban_details_doing/(:any)/(:any)/(:any)'] = 'backend/add_kanban_details_doing/$1/$2/$3';

//// kanban details done
$route['admin_kanban_details_done/(:any)/(:any)'] = 'backend/admin_kanban_details_done/$1/$2';
$route['admin_add_kanban_details_done/(:any)/(:any)'] = 'backend/add_kanban_details_done/$1/$2';
$route['admin_edit_kanban_details_done/(:any)/(:any)/(:any)'] = 'backend/add_kanban_details_done/$1/$2/$3';

//// notification
$route['admin_notificationList/(:any)/(:any)'] = 'backend/admin_notificationList/$1/$2';

// backend api
//// backend_api user
$route['api/getUserList'] = 'user_api/getUserList';
$route['api/submitUser'] = 'user_api/submit';
$route['api/detailsUser/(:any)'] = 'user_api/getDetail/$1';
$route['api/deleteUser'] = 'user_api/delete';
$route['api/userSearch'] = 'user_api/userSearch';
$route['api/multipleUserSearch'] = 'user_api/multipleUserSearch';
$route['api/getAllAvailableUser'] = 'user_api/getAllAvailableUser';
$route['api/getAllAvailableUserExceptLeader'] = 'user_api/getAllAvailableUserExceptLeader';

//// backend_api kanban list
$route['api/getKanbanList'] = 'kanbanList_api/getKanbanList';
$route['api/detailsKanban/(:any)'] = 'kanbanList_api/getDetail/$1';
$route['api/submitKanban'] = 'kanbanList_api/submit';
$route['api/deleteKanban'] = 'kanbanList_api/delete';
$route['api/getAvailableUser'] = 'kanbanList_api/getAvailableUsers';
$route['api/kanbanUserSearch'] = 'kanbanList_api/kanbanUserSearch';

//// backend_api kanban details todo
$route['api/getKanbanDetailsTodoList'] = 'kanbanDetailsTodo_api/getKanbanDetailsTodoList';
$route['api/submitKanbanDetailsTodo'] = 'kanbanDetailsTodo_api/submit';
$route['api/detailsKanbanDetailTodo/(:any)'] = 'kanbanDetailsTodo_api/getDetail/$1';
$route['api/deleteKanbanDetailTodo'] = 'kanbanDetailsTodo_api/delete';

//// backend_api kanban details doing
$route['api/getKanbanDetailsDoingList'] = 'kanbanDetailsDoing_api/getKanbanDetailsDoingList';
$route['api/submitKanbanDetailsDoing'] = 'kanbanDetailsDoing_api/submit';
$route['api/detailsKanbanDetailsDoing/(:any)'] = 'kanbanDetailsDoing_api/getDetail/$1';
$route['api/deleteKanbanDetailsDoing'] = 'kanbanDetailsDoing_api/delete';

//// backend_api kanban details done
$route['api/getKanbanDetailsDoneList'] = 'kanbanDetailsDone_api/getKanbanDetailsDoneList';
$route['api/submitKanbanDetailsDone'] = 'kanbanDetailsDone_api/submit';
$route['api/detailsKanbanDetailsDone/(:any)'] = 'kanbanDetailsDone_api/getDetail/$1';
$route['api/deleteKanbanDetailsDone'] = 'kanbanDetailsDone_api/delete';

//// backend_api notification list
$route['api/getNotificationList'] = 'notification_api/getNotificationList';




