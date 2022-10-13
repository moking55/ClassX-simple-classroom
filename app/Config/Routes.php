<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/info', 'Home::info');

// Login and Register Routes
$routes->get('/register', 'UserCenter::userRegister');
$routes->post('/register', 'BackendCenter::doRegister');
$routes->get('/login', 'UserCenter::userLogin');
$routes->post('/login', 'BackendCenter::doLogin');

$routes->get('/assignments', 'UserCenter::assignments');
$routes->get('/meeting', 'ClassRoom::meetingRoom');

// Classroom
$routes->group('/c', function ($routes){
    $routes->get('(:num)', 'ClassRoom::index/$1');
    $routes->get('(:num)/assign', 'ClassRoom::addAssignment/$1');
});

// Users
$routes->group('/u', function ($routes){
    $routes->get('logout', 'UserCenter::userLogout');
    $routes->get('settings', 'UserCenter::userSettings');
});

// Backends
$routes->group('/be', function ($routes){
    $routes->get('getannounces', 'ClassRoom::getAnnouce');
    $routes->post('createannounces', 'ClassRoom::createAnnouce');
    $routes->post('createclass', 'ClassRoom::createClass');
    $routes->post('joinclass', 'BackendCenter::doJoinClass');
    $routes->post('updateprofilesetting', 'BackendCenter::updateProfile');
    
    $routes->post('createmeet', 'BackendCenter::createMeeting');
    $routes->get('endmeet', 'BackendCenter::endMeeting');
    

    $routes->post('assignwork', 'ClassRoom::createAssignment');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
