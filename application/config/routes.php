<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = 'user/index';
$route['404_override'] = '';
/* API */
//User Login
$route['API/V1/login'] = 'API/Authentication_API/validate_credentials';
$route['API/V1/logout'] = 'API/Authentication_API/logout';

//Basic Data
$route['API/V1/region'] = 'API/BasicData_API/city_state_list';
$route['API/V1/roles'] = 'API/BasicData_API/role_list';
$route['API/V1/service_centers'] = 'API/BasicData_API/service_center_list';

//$route['API/V1/dsr/area'] = 'DSR_API/customer_area_list';
$route['API/V1/dsr/city/outlets'] = 'API/DSR_API/customer_list_by_city';
$route['API/V1/target/customer'] = 'API/Target_API/customer_details';
$route['API/V1/firms'] = 'API/BasicData_API/firm_list';
$route['API/V1/packages/products'] = 'API/BasicData_API/package_plan_list';

//Users
$route['API/V1/users'] = 'API/Users_API/user_list';
$route['API/V1/register'] = 'API/Users_API/user_add';
$route['API/V1/user/add'] = 'API/Users_API/user_add';
$route['API/V1/user/update/(:any)'] = 'API/Users_API/user_update/$1';
$route['API/V1/user/delete/(:any)'] = 'API/Users_API/user_delete/$1';
$route['API/V1/users/role/(:any)'] = 'API/Users_API/user_list_by_role/$1';
$route['API/V1/user/update/password'] = 'API/Users_API/user_change_password';
$route['API/V1/device-token/update'] = 'API/Users_API/user_update_device_token';

//Customers
$route['API/V1/outlets'] = 'API/Customer_API/customer_list';
$route['API/V1/outlets/add'] = 'API/Customer_API/customer_add';
$route['API/V1/outlets/update/(:any)'] = 'API/Customer_API/customer_update/$1';
$route['API/V1/outlets/delete/(:any)'] = 'API/Customer_API/customer_delete/$1';

$route['API/V1/dealers'] = 'API/Customer_API/customer_list';
$route['API/V1/dealers/add'] = 'API/Customer_API/customer_add';
$route['API/V1/dealers/update/(:any)'] = 'API/Customer_API/customer_update/$1';
$route['API/V1/dealers/delete/(:any)'] = 'API/Customer_API/customer_delete/$1';

//DSR
$route['API/V1/dsr'] = 'API/DSR_API/dsr_list';
$route['API/V1/dsr/add'] = 'API/DSR_API/dsr_add';
$route['API/V1/dsr/update/(:any)'] = 'API/DSR_API/dsr_update/$1';
$route['API/V1/dsr/delete/(:any)'] = 'API/DSR_API/dsr_delete/$1';

//Trainee
$route['API/V1/trainee'] = 'API/Trainee_DSR_API/trainee_list';
$route['API/V1/trainee/add'] = 'API/Trainee_DSR_API/trainee_add';
$route['API/V1/trainee/update/(:any)'] = 'API/Trainee_DSR_API/trainee_update/$1';
$route['API/V1/trainee/delete/(:any)'] = 'API/Trainee_DSR_API/trainee_delete/$1';

$route['API/V1/products'] = 'API/Product_API/product_list';

//Target
$route['API/V1/target'] = 'API/Target_API/target_list';
$route['API/V1/target/add'] = 'API/Target_API/target_add';
$route['API/V1/target/update/(:any)'] = 'API/Target_API/target_update/$1';
$route['API/V1/target/delete/(:any)'] = 'API/Target_API/target_delete/$1';

//Claim
$route['API/V1/claim'] = 'API/Claim_API/claim_list';
$route['API/V1/claim/all'] = 'API/Claim_API/claim_list_all';
$route['API/V1/claim/add'] = 'API/Claim_API/claim_add';
$route['API/V1/claim/update/(:any)'] = 'API/Claim_API/claim_update/$1';
$route['API/V1/claim/delete/(:any)'] = 'API/Claim_API/claim_delete/$1';

//Claim Pickup
$route['API/V1/claim/pickup/list'] = 'API/Claim_API/pickup_list';
$route['API/V1/claim/pickup/add'] = 'API/Claim_API/pickup_add';

//Claim Drop to Service Center
$route['API/V1/claim/service_center/list'] = 'API/Claim_API/drop_to_service_center_list';
$route['API/V1/claim/service_center/add'] = 'API/Claim_API/drop_to_service_center_add';

//Claim Pickup from Service Center
$route['API/V1/claim/service_center_pickup/list'] = 'API/Claim_API/pickup_from_service_center_list';
$route['API/V1/claim/service_center_pickup/add'] = 'API/Claim_API/pickup_from_service_center_add';

//Claim Drop to Customer
$route['API/V1/claim/deliverd/list'] = 'API/Claim_API/drop_to_customer_list';
$route['API/V1/claim/deliverd/add'] = 'API/Claim_API/drop_to_customer_add';

//Notification
$route['API/V1/notification/list'] = 'API/Notification_API/notification_list';
$route['API/V1/notification/add'] = 'API/Notification_API/notification_add';

//Location Tracker
$route['API/V1/location/list'] = 'API/Location_API/location_list';
$route['API/V1/location/add'] = 'API/Location_API/location_add';

/*admin*/
$route['admin'] = 'user/index';
$route['admin/signup'] = 'user/signup';
$route['admin/create_member'] = 'user/create_member';
$route['admin/login'] = 'user/index';
$route['admin/logout'] = 'user/logout';
$route['admin/login/validate_credentials'] = 'user/validate_credentials';

$route['admin/registered/users'] = 'admin_users/registered_user_list';
    $route['admin/registered/users/edit/(:any)'] = 'admin_users/registered_user_edit/$1';
$route['admin/registered/users/(:any)'] = 'admin_users/registered_user_list/$1';
    

$route['admin/users'] = 'admin_users/index';
$route['admin/users/add'] = 'admin_users/add';
$route['admin/users/update/(:any)'] = 'admin_users/update/$1';
$route['admin/users/delete/(:any)'] = 'admin_users/delete/$1';
$route['admin/users/getparent'] = 'admin_users/getparent';
    $route['admin/users/registered_user_list_csv'] = 'admin_users/registered_user_list_csv/$1'; //$1 = page number
    $route['admin/users/users_csv'] = 'admin_users/users_csv/$1'; //$1 = page number
$route['admin/users/(:any)'] = 'admin_users/index/$1'; //$1 = page number

$route['admin/generate-pdf/(:any)'] = 'admin_users/generate_pdf/$1';

$route['admin/dealers'] = 'admin_dealers/index';
$route['admin/dealers/add'] = 'admin_dealers/add';
$route['admin/dealers/update/(:any)'] = 'admin_dealers/update/$1';
$route['admin/dealers/delete/(:any)'] = 'admin_dealers/delete/$1';
$route['admin/dealers/csv'] = 'admin_dealers/csv';
    $route['admin/dealers/dealers_csv'] = 'admin_dealers/dealers_csv/$1';
$route['admin/dealers/(:any)'] = 'admin_dealers/index/$1'; //$1 = page number
    
$route['admin/country'] = 'admin_country/index';
$route['admin/country/add'] = 'admin_country/add';
$route['admin/country/update/(:any)'] = 'admin_country/update/$1';
$route['admin/country/delete/(:any)'] = 'admin_country/delete/$1';
$route['admin/country/(:any)'] = 'admin_country/index/$1'; //$1 = page number

$route['admin/state'] = 'admin_state/index';
$route['admin/state/add'] = 'admin_state/add';
$route['admin/state/fetchState'] = 'admin_state/fetchState';
$route['admin/state/fetchCity'] = 'admin_state/fetchCity';
$route['admin/state/fetchArea'] = 'admin_state/fetchArea';
$route['admin/state/update/(:any)'] = 'admin_state/update/$1';
$route['admin/state/delete/(:any)'] = 'admin_state/delete/$1';
    $route['admin/state/state_csv'] = 'admin_state/state_csv/$1'; //$1 = page number
$route['admin/state/(:any)'] = 'admin_state/index/$1'; //$1 = page number

$route['admin/city'] = 'admin_city/index';
$route['admin/city/add'] = 'admin_city/add';
$route['admin/city/update/(:any)'] = 'admin_city/update/$1';
$route['admin/city/delete/(:any)'] = 'admin_city/delete/$1';
    $route['admin/city/city_csv'] = 'admin_city/city_csv/$1'; //$1 = page number
$route['admin/city/(:any)'] = 'admin_city/index/$1'; //$1 = page number

$route['admin/area'] = 'admin_area/index';
$route['admin/area/add'] = 'admin_area/add';
$route['admin/area/update/(:any)'] = 'admin_area/update/$1';
$route['admin/area/delete/(:any)'] = 'admin_area/delete/$1';
    $route['admin/area/area_csv'] = 'admin_area/area_csv/$1'; //$1 = page number
$route['admin/area/(:any)'] = 'admin_area/index/$1'; //$1 = page number
    
$route['admin/claim'] = 'admin_claim/index';
$route['admin/claim/view/(:any)'] = 'admin_claim/view/$1'; //$1 = page number
$route['admin/claim/(:any)'] = 'admin_claim/index/$1'; //$1 = page number

$route['admin/target'] = 'admin_target/index';
    $route['admin/target/target_csv'] = 'admin_target/target_csv/$1'; //$1 = page number
$route['admin/target/(:any)'] = 'admin_target/index/$1'; //$1 = page number

$route['admin/trainee'] = 'admin_trainee/index';
    $route['admin/trainee/trainee_csv'] = 'admin_trainee/trainee_csv/$1'; //$1 = page number
$route['admin/trainee/(:any)'] = 'admin_trainee/index/$1'; //$1 = page number
    
$route['admin/servicecenter'] = 'admin_servicecenter/index';
$route['admin/servicecenter/add'] = 'admin_servicecenter/add';
$route['admin/servicecenter/update/(:any)'] = 'admin_servicecenter/update/$1';
$route['admin/servicecenter/delete/(:any)'] = 'admin_servicecenter/delete/$1';
    $route['admin/servicecenter/center_csv'] = 'admin_servicecenter/center_csv/$1'; //$1 = page number
$route['admin/servicecenter/(:any)'] = 'admin_servicecenter/index/$1'; //$1 = page number
    
    
$route['admin/dsr'] = 'admin_dsr/index';
$route['admin/dsr/view/(:any)'] = 'admin_dsr/view/$1'; //$1 = page number
    $route['admin/dsr/dsr_csv'] = 'admin_dsr/dsr_csv/$1'; //$1 = page number
$route['admin/dsr/(:any)'] = 'admin_dsr/index/$1'; //$1 = page number
    
$route['admin/location'] = 'admin_location/index';
$route['admin/location/view/(:any)'] = 'admin_location/view/$1'; //$1 = page number
    $route['admin/location/location_csv'] = 'admin_location/location_csv/$1'; //$1 = page number
$route['admin/location/(:any)'] = 'admin_location/index/$1'; //$1 = page number
    
    
$route['admin/notifications'] = 'admin_notifications/index';
    $route['admin/notifications/add'] = 'admin_notifications/add';
$route['admin/notifications/view/(:any)'] = 'admin_notifications/view/$1'; //$1 = page number
    $route['admin/notifications/notification_csv'] = 'admin_notifications/notification_csv/$1'; //$1 = page number
$route['admin/notifications/(:any)'] = 'admin_notifications/index/$1'; //$1 = page number
    
    
    $route['admin/dashboard'] = 'admin_dashboard/index';
    
    $route['admin/dashboard/(:any)'] = 'admin_dashboard/index/$1'; //$1 = page number


/* End of file routes.php */
/* Location: ./application/config/routes.php */