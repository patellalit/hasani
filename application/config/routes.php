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
$route['API/V1/login'] = 'Authentication_API/validate_credentials';

//Users
$route['API/V1/users'] = 'Users_API/user_list';
$route['API/V1/register'] = 'Users_API/user_add';
$route['API/V1/user/update/(:any)'] = 'Users_API/user_update/$1';
$route['API/V1/user/delete/(:any)'] = 'Users_API/user_delete/$1';

//Customers
$route['API/V1/outlets'] = 'Customer_API/customer_list';
$route['API/V1/outlets/add'] = 'Customer_API/customer_add';
$route['API/V1/outlets/update/(:any)'] = 'Customer_API/customer_update/$1';
$route['API/V1/outlets/delete/(:any)'] = 'Customer_API/customer_delete/$1';

//DSR
$route['API/V1/dsr'] = 'DSR_API/dsr_list';
$route['API/V1/dsr/add'] = 'DSR_API/dsr_add';
$route['API/V1/dsr/update/(:any)'] = 'DSR_API/dsr_update/$1';
$route['API/V1/dsr/delete/(:any)'] = 'DSR_API/dsr_delete/$1';

$route['API/V1/dsr/area'] = 'DSR_API/customer_area_list';
$route['API/V1/dsr/area/outlets'] = 'DSR_API/customer_list_by_area';

$route['API/V1/products'] = 'Product_API/product_list';

//Target
$route['API/V1/target'] = 'Target_API/target_list';
$route['API/V1/target/add'] = 'Target_API/target_add';
$route['API/V1/target/update/(:any)'] = 'Target_API/target_update/$1';
$route['API/V1/target/delete/(:any)'] = 'Target_API/target_delete/$1';

/*admin*/
$route['admin'] = 'user/index';
$route['admin/signup'] = 'user/signup';
$route['admin/create_member'] = 'user/create_member';
$route['admin/login'] = 'user/index';
$route['admin/logout'] = 'user/logout';
$route['admin/login/validate_credentials'] = 'user/validate_credentials';

$route['admin/users'] = 'admin_users/index';
$route['admin/users/add'] = 'admin_users/add';
$route['admin/users/update'] = 'admin_users/update';
$route['admin/users/update/(:any)'] = 'admin_users/update/$1';
$route['admin/users/delete/(:any)'] = 'admin_users/delete/$1';
$route['admin/users/(:any)'] = 'admin_users/index/$1'; //$1 = page number

$route['admin/customers'] = 'admin_customers/index';
$route['admin/customers/add'] = 'admin_customers/add';
$route['admin/customers/update'] = 'admin_customers/update';
$route['admin/customers/update/(:any)'] = 'admin_customers/update/$1';
$route['admin/customers/delete/(:any)'] = 'admin_customers/delete/$1';
$route['admin/customers/csv'] = 'admin_customers/csv';
$route['admin/customers/(:any)'] = 'admin_customers/index/$1'; //$1 = page number



/* End of file routes.php */
/* Location: ./application/config/routes.php */

                            
                            
