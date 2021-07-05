<?php

//$routes->get('(:any)', 'Pages::view/$1');



$routes->get('/', 'HomeController::landpage');
$routes->get('uid', 'TestController::uuidtest');
$routes->get('home', 'HomeController::index');
$routes->get('/land', 'HomeController::landpage');
$routes->get('leitor', 'QrCodeController::reader', ['filter' => 'ssl']);

$path = getenv("app.qrcode.label.url.path");
$routes->get("$path/(:segment)", 'QrCodeController::showScan/$1');

$routes->get('home/shortid', 'HomeController::shortidtest');

$routes->group('field', ['filter' => 'ssl+auth:admin'], function ($routes) {
    $routes->get('/', 'AdminFieldController::index');
    $routes->get('logout', 'AdminFieldController::logout');
    $routes->get('schedule/open/(:segment)', 'ScheduleController::openSchedule/$1');
    $routes->post('schedule/save', 'ScheduleController::saveSchedule/$1');
});

$routes->group('admin', ['filter' => 'ssl+auth:admin'], function ($routes) {
    $routes->get('/', 'AdminController::index');

    $routes->get('login', 'AuthController::getAdmLogin');
    $routes->post('login', 'AuthController::postAdmLogin');
    $routes->get('logout', 'AuthController::getAdmLogout');

    $routes->get('chat/(:segment)', 'ChatController::chatAttendant/$1');
    
    $routes->get('dashboard', 'AdminController::dashboard');

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

    $routes->get('printLabels', 'QrCodeController::printLabels');
    $routes->get('reprintLabels', 'QrCodeController::reprintLabels');
});

$routes->group('admin/api', function ($routes) {
    $routes->get('insertobject/(:any)', 'QrObjectController::insertAttendeeObject/$1');
    $routes->get('deleteobject/(:num)', 'QrObjectController::deleteAttendeeObject/$1');
});




$routes->group('/attendee', ['filter' => 'ssl'], function ($routes) {

    $routes->get('login/(:segment)', 'AuthController::AttendeeLogin/$1', );
    $routes->get('logout', 'AuthController::getAtendeeLogout', );

    $routes->group('chat', ['filter' => 'ssl'], function ($routes) {
        $routes->get('guest', 'ChatController::chatAttendeeGuest',);
        $routes->get('identified', 'ChatController::chatAttendeeIdentified',);
        $routes->get('front/(:segment)', 'ChatController::chatAttendee/$1');
    });

    $routes->group('schedule', ['filter' => 'ssl'], function ($routes) {
        $routes->get('identified', 'ScheduleController::getIdentifiedAttendeeScheduler',);
        $routes->post('identified', 'ScheduleController::postIdentifiedAttendeeScheduler',);
    });

});

$routes->group('chat/api', ['filter' => 'ssl'], function ($routes) {
    // $routes->get('random/user', 'ChatController::RandomChatUser');
    // $routes->get('random/attendee', 'ChatController::RandomChatAttendee');
    // $routes->get('random/attendant', 'ChatController::RandomChatAttendant');
    // $routes->get('user/register/(:alpha)', 'ChatController::registerChatUser/$1');
    // $routes->get('user/list', 'ChatController::listChatUsers');
    // $routes->get('user/(:segment)', 'ChatController::getChatUser/$1');
    // $routes->get('sessions/user/(:segment)', 'ChatController::getChatSessionsByUser/$1');
    // $routes->get('sessions/all', 'ChatController::getAllChatSessions');
    // $routes->get('sessions/detailed/all', 'ChatController::getAllChatSessionsDetailed');
    // $routes->get('sessions/detailed/attendant/(:segment)', 'ChatController::getChatSessionsDetailedByAttendant/$1');
    // $routes->get('sessions/closed', 'ChatController::getClosedChatSessions');
    // $routes->get('sessions/open', 'ChatController::getOpenChatSessions');
    // $routes->get('sessions/new', 'ChatController::getNewChatSessions');
    // $routes->get('session/create/(:segment)', 'ChatController::createChatSession/$1');
    // $routes->get('session/(:segment)', 'ChatController::getChatSessionById/$1');
    // $routes->get('session/open/(:segment)/(:segment)', 'ChatController::openChatSession/$1/$2');
    $routes->post('sync', 'ChatController::sync');
});
