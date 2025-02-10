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

$routes->get("/dashboard", "AdminController::index");
$routes->get("/my_lots_and_estates", "MyLotsAndEstatesController::index");
$routes->get("/select_payment_option/(:any)/(:any)", "MyLotsAndEstatesController::selectPaymentOption/$1/$2");

$routes->get("/reserve_lot", "ReserveLotController::index");
$routes->get("/reserve_estate", "ReserveEstateController::index");

$routes->get("/payment_management", "PaymentManagementController::index");

$routes->post("reserve/submitReservation", "ReserveLotController::submitReservation");
$routes->post("reserve/submitReservationEstate", "ReserveEstateController::submitReservation");
$routes->post("/payment_option_submit", "MyLotsAndEstatesController::paymentOptionSubmit");

$routes->get("/api/available_lots", "ReserveLotController::getAvailableLots");
$routes->get("/api/available_estates", "ReserveEstateController::getAvailableEstates");
$routes->get("/api/cash_sales", "PaymentManagementController::getCashSales");
$routes->get("/api/six_months", "PaymentManagementController::getSixMonths");

$routes->post("/api/pay_cash_sale", "PaymentManagementController::payCashSale");
$routes->post("/api/pay_six_months", "PaymentManagementController::paySixMonths");
