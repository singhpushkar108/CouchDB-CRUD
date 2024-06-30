<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Router\Route;
/**
 * @var RouteCollection $routes
 */

//  $routes = new RouteCollection();
$routes = Services::routes();


if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}


$routes->setDefaultNamespace('App\Controllers');
$routes->setAutoRoute(true);



$myroutes = [];

// $routes->get('/', 'Home::index');

$myroutes['/fetch-all-data']= 'CouchApi::getAllData';
$myroutes['/insert-data']   = 'CouchApi::insertData';
$myroutes['/update-data']   = 'CouchApi::updateRecord';
$myroutes['/delete-data']   =  'CouchApi::deleterRecord';


// $routes->get('fetch-all-data', 'CouchApi::getAllData');
// $routes->post('insert-data', 'CouchApi::insertData');
// $routes->post('update-data/(:any)', 'CouchApi::updateData/$1');
// $routes->post('delete-data/(:any)', 'CouchApi::deleteData/$1');

$routes->map($myroutes);


if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
