<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['posts/index'] = 'posts/index';
$route['posts/create'] = 'posts/create';
$route['posts/edit/(:any)'] = 'posts/edit/$1';
$route['posts/update'] = 'posts/update';
$route['posts/(:any)'] = 'posts/view/$1';
$route['posts'] = 'posts/index';

$route['default_controller'] = 'pages/view';

$route['categories'] = 'categories/index';
$route['categories/create'] = 'categories/create';
$route['categories/posts/(:any)'] = 'categories/posts/$1';

//jqgrid
$route['jqgrid'] = 'JqgridController/grid';
$route['c3js'] = 'Graph_c3js/chart';
$route['new'] = 'Graph_c3js/chart_new';

$route['data'] = 'JqgridController/getData';

// JqgridController

$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
$route['(:any)'] = 'pages/view/$1';
