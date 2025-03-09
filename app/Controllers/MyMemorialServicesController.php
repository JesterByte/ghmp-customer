<?php

namespace App\Controllers;

use App\Models\BurialReservationsModel;

class MyMemorialServicesController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $burialReservationsModel = new BurialReservationsModel();
        $burialReservations = $burialReservationsModel->getBurialReservations(session()->get("user_id"));

        foreach ($burialReservations as $key => $row) {
            if ($row["status"] == "Approved" && $row["payment_status"] == "Pending") {
                $burialReservations[$key]["payment_link"] = $this->createPaymongoLinkBurial($row["payment_amount"], $row["asset_id"], $row["burial_type"]);
            }
        }

        $data = [
            "pageTitle" => "My Memorial Services",
            "burialReservations" => $burialReservations
        ];
        return view("admin/my_memorial_services", $data);
        // return view('brochure/home');
    }
}
