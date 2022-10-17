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
    $routes->get('(:num)/create_lession', 'ClassRoom::createLession/$1');
    $routes->get('(:num)/lession', 'ClassRoom::viewLession/$1');
    $routes->get('(:num)/settings', 'ClassRoom::editAssignment/$1');
    $routes->get('(:num)/check/(:num)', 'ClassRoom::checkAssignment/$1/$2');
});

// Users
$routes->group('/u', function ($routes){
    $routes->get('logout', 'UserCenter::userLogout');
    $routes->get('settings', 'UserCenter::userSettings');
    $routes->get('scores', 'ClassRoom::viewUserScore');
});

// Backends
$routes->group('/be', function ($routes){
    $routes->post('givescore', 'BackendCenter::givescore');
    $routes->get('getannounces', 'ClassRoom::getAnnouce');
    $routes->post('createannounces', 'ClassRoom::createAnnouce');
    $routes->post('createclass', 'ClassRoom::createClass');
    $routes->post('saveclasscover', 'ClassRoom::saveClassCover');
    
    $routes->get('removeClass', 'BackendCenter::doRemoveClass');
    
    $routes->post('create_less', 'BackendCenter::doCreateLession');

    $routes->post('upload_cover', 'BackendCenter::uploadCover');
    $routes->post('updatecover', 'BackendCenter::updateCover');
    $routes->post('saveeditclass', 'ClassRoom::saveEditedClass');

    $routes->post('joinclass', 'BackendCenter::doJoinClass');
    $routes->get('leaveclass', 'BackendCenter::doLeaveClass');
    $routes->post('updateprofilesetting', 'BackendCenter::updateProfile');
    
    $routes->post('createmeet', 'BackendCenter::createMeeting');
    $routes->get('endmeet', 'BackendCenter::endMeeting');
    
    $routes->get('getassignments', 'UserCenter::getUserAssignments');
    $routes->get('viewassignment', 'UserCenter::viewUserAssignment');
    $routes->post('submit_assignment', 'BackendCenter::submitAssignment');
    $routes->post('submit_attachment', 'BackendCenter::submitAttachment');
    
    $routes->post('upload_avatar', 'BackendCenter::uploadAvatar');
    $routes->post('upload_attachment', 'BackendCenter::uploadAttachment');
    $routes->post('save_attach', 'BackendCenter::saveAttachment');
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
