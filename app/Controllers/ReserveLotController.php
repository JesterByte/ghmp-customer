<?php

namespace App\Controllers;

class ReserveLotController extends BaseController {
    public function index() {
        $data = ["pageTitle" => "Reserve a Lot"];
        return view("admin/reserve_lot", $data);
    }
}