<?php

namespace App\Controllers;

class MyLotsAndEstatesController extends BaseController {
    public function index() {
        $data = ["pageTitle" => "My Lots & Estates"];
        return view("admin/lots_and_estates", $data);
    }
}