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
|	http://codeigniter.com/user_guide/general/routing.html
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


$route['auth/login']['post']           = 'auth/login';
$route['auth/logout']['post']          = 'auth/logout';
$route['pegawai']['get']          	       = 'pegawai';
$route['pegawai/detail/(:num)']['get']    = 'pegawai/detail/$1';
$route['pegawai/create']['post']   	   = 'pegawai/create';
$route['pegawai/update/(:num)']['put']    = 'pegawai/update/$1';
$route['pegawai/delete/(:num)']['delete'] = 'pegawai/delete/$1';
$route['mahasiswa']['get']          	       = 'mahasiswa';
$route['mahasiswa/detail/(:num)']['get']    = 'mahasiswa/detail/$1';
$route['mahasiswa/create']['post']   	   = 'mahasiswa/create';
$route['mahasiswa/update/(:num)']['put']    = 'mahasiswa/update/$1';
$route['mahasiswa/delete/(:num)']['delete'] = 'mahasiswa/delete/$1';
$route['mahasiswa/poin/(:num)']['get'] = 'mahasiswa/poin/$1';
$route['user']['get']          	       = 'user';
$route['user/detail/(:num)']['get']    = 'user/detail/$1';
$route['user/create']['post']   	   = 'user/create';
$route['user/update/(:num)']['put']    = 'user/update/$1';
$route['user/delete/(:num)']['delete'] = 'user/delete/$1';
$route['kelas']['get']          	       = 'kelas';
$route['kelas/detail/(:num)']['get']    = 'kelas/detail/$1';
$route['kelas/create']['post']   	   = 'kelas/create';
$route['kelas/update/(:num)']['put']    = 'kelas/update/$1';
$route['kelas/delete/(:num)']['delete'] = 'kelas/delete/$1';




$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
