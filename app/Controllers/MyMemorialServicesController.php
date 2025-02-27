<?php

namespace App\Controllers;

use App\Models\BurialReservationsModel;

class MyMemorialServicesController extends BaseController {
    public function index(): string {
        $burialReservationsModel = new BurialReservationsModel();
        $burialReservations = $burialReservationsModel->getBurialReservations(session()->get("user_id"));

        $data = [
            "pageTitle" => "My Memorial Services",
            "burialReservations" => $burialReservations
        ];
        return view("admin/my_memorial_services", $data);
        // return view('brochure/home');
    }


}
