<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Dashboard
$routes->get('/',          'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index');

// Employees
$routes->get('/employees',                          'Employees::index');
$routes->get('/employees/create',                   'Employees::create');
$routes->post('/employees/store',                    'Employees::store');
$routes->get('/employees/view/(:num)',               'Employees::view/$1');
$routes->get('/employees/edit/(:num)',               'Employees::edit/$1');
$routes->post('/employees/update/(:num)',             'Employees::update/$1');
$routes->get('/employees/toggle-status/(:num)',      'Employees::toggleStatus/$1');

// ID Photos Viewer
$routes->get('/employees/id-photo/(:num)/(:segment)', 'Employees::idPhoto/$1/$2');

// Emergency Contacts
$routes->post('/employees/(:num)/emergency/store',              'Employees::storeEmergency/$1');
$routes->post('/employees/(:num)/emergency/update/(:num)',      'Employees::updateEmergency/$1/$2');
$routes->get('/employees/(:num)/emergency/delete/(:num)',      'Employees::deleteEmergency/$1/$2');

// Children
$routes->post('/employees/(:num)/child/store',                  'Employees::storeChild/$1');
$routes->post('/employees/(:num)/child/update/(:num)',          'Employees::updateChild/$1/$2');
$routes->get('/employees/(:num)/child/delete/(:num)',          'Employees::deleteChild/$1/$2');

// Education
$routes->post('/employees/(:num)/education/store',              'Employees::storeEducation/$1');
$routes->post('/employees/(:num)/education/update/(:num)',      'Employees::updateEducation/$1/$2');
$routes->get('/employees/(:num)/education/delete/(:num)',      'Employees::deleteEducation/$1/$2');

// Employment History
$routes->post('/employees/(:num)/history/store',                'Employees::storeHistory/$1');
$routes->post('/employees/(:num)/history/update/(:num)',        'Employees::updateHistory/$1/$2');
$routes->get('/employees/(:num)/history/delete/(:num)',        'Employees::deleteHistory/$1/$2');

// Character References
$routes->post('/employees/(:num)/reference/store',              'Employees::storeReference/$1');
$routes->post('/employees/(:num)/reference/update/(:num)',      'Employees::updateReference/$1/$2');
$routes->get('/employees/(:num)/reference/delete/(:num)',      'Employees::deleteReference/$1/$2');

// Other IDs
$routes->post('/employees/(:num)/other-id/store',           'Employees::storeOtherId/$1');
$routes->post('/employees/(:num)/other-id/update/(:num)',   'Employees::updateOtherId/$1/$2');
$routes->get('/employees/(:num)/other-id/delete/(:num)',    'Employees::deleteOtherId/$1/$2');

// PRC Licenses
$routes->post('/employees/(:num)/prc/store',                'Employees::storePrc/$1');
$routes->post('/employees/(:num)/prc/update/(:num)',        'Employees::updatePrc/$1/$2');
$routes->get('/employees/(:num)/prc/delete/(:num)',         'Employees::deletePrc/$1/$2');

// Projects Module
$routes->get('/projects',                    'Projects::index');
$routes->get('/projects/create',             'Projects::create');
$routes->post('/projects/store',             'Projects::store');
$routes->get('/projects/view/(:num)',        'Projects::view/$1');
$routes->get('/projects/edit/(:num)',        'Projects::edit/$1');
$routes->post('/projects/update/(:num)',     'Projects::update/$1');

// Settings Module
$routes->get('/settings',                              'Settings::index');
$routes->get('/settings/departments',                  'Settings::departments');
$routes->post('/settings/departments/store',           'Settings::storeDepartment');
$routes->get('/settings/departments/edit/(:num)',      'Settings::departments/$1');
$routes->post('/settings/departments/update/(:num)',   'Settings::updateDepartment/$1');
$routes->get('/settings/departments/delete/(:num)',    'Settings::deleteDepartment/$1');
$routes->get('/settings/positions',                    'Settings::positions');
$routes->post('/settings/positions/store',             'Settings::storePosition');
$routes->get('/settings/positions/edit/(:num)',        'Settings::positions/$1');
$routes->post('/settings/positions/update/(:num)',     'Settings::updatePosition/$1');
$routes->get('/settings/positions/delete/(:num)',      'Settings::deletePosition/$1');
