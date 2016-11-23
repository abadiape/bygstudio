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
$route['addClipCategory'] = 'clientes/addClipCategory';
$route['addNewCategoryDiv'] = 'clientes/addNewCategoryDiv';
$route['addvisit'] = 'versiones/addvisit';
$route['agregar'] = 'clientes/agregar';
$route['agregar/(:any)'] = 'clientes/agregar';
$route['buscar'] = 'clientes/buscar';
$route['cambio'] = 'login/cambio';
$route['changeCategoryOrder'] = 'clientes/changeCategoryOrder';
$route['changepass'] = 'login/changepass';
$route['clientes'] = 'clientes/index';
$route['clipDelete'] = 'clientes/clipDelete';
$route['comments'] = 'clientes/comments';
$route['comments/(:any)'] = 'clientes/comments';
$route['crear'] = 'clientes/crear';
$route['creardocs'] = 'clientes/creardocs';
$route['cambiar'] = 'clientes/modificar';
$route['delcont'] = 'clientes/delcont';
$route['delcont/(:any)'] = 'clientes/delcont';
$route['delcustomer'] = 'clientes/delcustomer';
$route['delproject'] = 'clientes/delproject';
$route['delvideo'] = 'clientes/delvideo';
$route['editcon'] = 'clientes/editcon';
$route['editdata'] = 'clientes/editdata';
$route['getClipLoadSpace'] = 'clientes/getClipLoadSpace';
$route['loadclips'] = 'clientes/loadclips';
$route['loadclips/(:any)'] = 'clientes/loadclips';
$route['logout'] = 'clientes/logout';
$route['newcontact'] = 'clientes/newcontact';
$route['newcontact/(:any)'] = 'clientes/newcontact';
$route['notes'] = 'clientes/notes';
$route['notes/(:any)'] = 'clientes/notes';
$route['pwdmail'] = 'login/pwdmail';
$route['savecomments'] = 'clientes/savecomments';
$route['setCategoryFeature'] = 'clientes/setCategoryFeature';
$route['setClipFeature'] = 'clientes/setClipFeature';
$route['sendcomments'] = 'versiones/sendcomments';
$route['sendmail'] = 'clientes/sendmail';
$route['search'] = 'clientes/search';
$route['savechanges'] = 'clientes/savechanges';
$route['show'] = 'clientes/showprojects';
$route['show/(:any)'] = 'clientes/showprojects';
$route['showClipLoadDiv'] = 'clientes/showClipLoadDiv';
$route['subirdocs'] = 'clientes/subirdocs';
$route['subirdocs/(:any)'] = 'clientes/subirdocs';
$route['upload'] = 'clientes/upload';
$route['updateclips'] = 'clientes/updateclips';
$route['uploadclip'] = 'clientes/uploadclip';
$route['uploadclip/(:any)'] = 'clientes/uploadclip';
$route['uploadstill'] = 'clientes/uploadstill';
$route['upload/(:any)'] = 'clientes/upload';
$route['versiones/download/(:any)'] = 'versiones/download';
$route['versiones/login'] = 'versiones/login';
$route['versiones/login/(:any)'] = 'versiones/login';
$route['versiones/(:any)'] = 'versiones/index';
$route['versions'] = 'clientes/versions';
$route['versions/test'] = 'clientes/test';
$route['versions/(:any)'] = 'clientes/versions';
$route['versiones/video'] = 'versiones/index';
$route['versiones/video/(:any)'] = 'versiones/index';
$route['default_controller'] = 'login';
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */