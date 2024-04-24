<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MainController::index');
$routes->get('/login', 'MainController::login');
$routes->get('/register', 'MainController::register');
$routes->get('/logout', 'MainController::logout');
$routes->get('/board/(:any)/(:num)', 'BoardController::board/$1/$2');
$routes->get('/read/(:any)/(:num)/(:num)', 'BoardController::read/$1/$2/$3');
$routes->get('/board/(:any)/write', 'BoardController::write/$1');
$routes->get('/modify/(:any)/(:num)/(:num)', 'BoardController::modify/$1/$2/$3');
$routes->get('/deleteBoard/(:any)/(:num)', 'BoardController::deleteBoard/$1/$2');

$routes->post('/doLogin', 'MainController::doLogin');
$routes->post('/doRegister', 'MainController::doRegister');
$routes->post('/register/id', 'MainController::duplicateId');
$routes->post('/register/nick', 'MainController::duplicateNick');
$routes->post('/registerComment/(:any)/(:num)/(:num)', 'BoardController::registerComment/$1/$2/$3');
$routes->post('/addUp/(:any)/(:num)/(:num)', 'BoardController::addUp/$1/$2/$3');
$routes->post('/board/(:any)/write/registerWrite', 'BoardController::registerWrite/$1');
$routes->post('/board/(:any)/modify/registerModify/(:num)/(:num)', 'BoardController::registerModify/$1/$2/$3');
$routes->post('/upload', 'S3Controller::uploadImage');