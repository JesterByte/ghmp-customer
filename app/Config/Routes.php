<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get("/", "Home::index");
$routes->get("/home", "Home::index");
$routes->get("/locator", "Home::locator");
$routes->get("/about", "Home::about");
$routes->get("/contact", "Home::contact");
$routes->get("/signup", "SignupController::index");
$routes->get("/signin", "SigninController::index");
$routes->get("/signout", "SignoutController::signout");

$routes->post("/signin/submit", "SigninController::submit");

$routes->get("dashboard", "AdminController::index");
$routes->get("my_lots_and_estates", "MyLotsAndEstatesController::index");
$routes->get("reserve_lot", "ReserveLotController::index");