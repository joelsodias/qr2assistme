<?php

//$routes->get('(:any)', 'Pages::view/$1');



$routes->get('/', 'HomeController::index');
$routes->get('uid', 'TestController::uuidtest');
$routes->get('home', 'HomeController::index');
$routes->get('/land', 'HomeController::landpage');
$routes->get('leitor', 'QrCodeController::reader', ['filter' => 'ssl']);
$routes->get('qr/(:segment)', 'QrCodeController::showScan/$1');

$routes->get('home/shortid', 'HomeController::shortidtest');


$routes->group('admin', ['filter' => 'ssl+auth:admin'], function ($routes) {
    $routes->get('/', 'AdminController::index');
    //$routes->group('admin', function ($routes) {
    $routes->get('login', 'AuthController::getAdmLogin');
    $routes->post('login', 'AuthController::postAdmLogin');
    $routes->get('logout', 'AuthController::getAdmLogout');

    $routes->get('chat/(:segment)', 'ChatController::chatAttendant/$1');
    
    $routes->get('dashboard', 'AdminController::dashboard');

    $routes->get('field', 'AdminController::viewFieldAdmin');

    $routes->get('qrobject', 'CRUDQrObjectController::index');
    $routes->get('qrobject/list', 'CRUDQrObjectController::getList');
    $routes->post('qrobject/list', 'CRUDQrObjectController::getList');
    $routes->post('qrobject/save', 'CRUDQrObjectController::postInsert');
    $routes->post('qrobject/update', 'CRUDQrObjectController::postUpdate');
    $routes->post('qrobject/delete', 'CRUDQrObjectController::postDelete');

    $routes->get('customer', 'CRUDCustomerController::index');
    $routes->get('customer/list', 'CRUDCustomerController::getList');
    $routes->post('customer/list', 'CRUDCustomerController::getList');
    $routes->post('customer/save', 'CRUDCustomerController::postInsert');
    $routes->post('customer/update', 'CRUDCustomerController::postUpdate');
    $routes->post('customer/delete', 'CRUDCustomerController::postDelete');

    $routes->get('qrlabels', 'QrCodeController::printLabels');
    $routes->get('etiquetas', 'QrCodeController::printLabels');
});

$routes->group('admin/api', function ($routes) {
    $routes->get('insertobject/(:any)', 'QrObjectController::insertAttendeeObject/$1');
    $routes->get('deleteobject/(:num)', 'QrObjectController::deleteAttendeeObject/$1');
});

$routes->group('chat', ['filter' => 'ssl'], function ($routes) {
    //$routes->group('chat', function ($routes) {
    $routes->get('guest', 'ChatController::chatAttendeeGuest',);
    $routes->get('login', 'AuthController::AttendeeChatLogin');
    $routes->get('front/(:segment)', 'ChatController::chatAttendee/$1');
});
$routes->group('chat/api', ['filter' => 'ssl'], function ($routes) {
    //$routes->group('chat/api', function ($routes) {
    $routes->get('random/user', 'ChatController::RandomChatUser');
    $routes->get('random/attendee', 'ChatController::RandomChatAttendee');
    $routes->get('random/attendant', 'ChatController::RandomChatAttendant');
    $routes->get('user/register/(:alpha)', 'ChatController::registerChatUser/$1');
    $routes->get('user/list', 'ChatController::listChatUsers');
    $routes->get('user/(:segment)', 'ChatController::getChatUser/$1');
    $routes->get('sessions/user/(:segment)', 'ChatController::getChatSessionsByUser/$1');
    $routes->get('sessions/all', 'ChatController::getAllChatSessions');
    $routes->get('sessions/detailed/all', 'ChatController::getAllChatSessionsDetailed');
    $routes->get('sessions/detailed/attendant/(:segment)', 'ChatController::getChatSessionsDetailedByAttendant/$1');
    $routes->get('sessions/closed', 'ChatController::getClosedChatSessions');
    $routes->get('sessions/open', 'ChatController::getOpenChatSessions');
    $routes->get('sessions/new', 'ChatController::getNewChatSessions');
    $routes->get('session/create/(:segment)', 'ChatController::createChatSession/$1');
    $routes->get('session/(:segment)', 'ChatController::getChatSessionById/$1');
    $routes->get('session/open/(:segment)/(:segment)', 'ChatController::openChatSession/$1/$2');
    $routes->post('sync', 'ChatController::sync');
});
