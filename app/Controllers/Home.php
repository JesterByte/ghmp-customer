<?php

namespace App\Controllers;
use App\Models\MapModel;

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Home"];
        return view("brochure/home", $data);
        // return view('brochure/home');
    }

    public function locator()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Lots & Estates"];
        return view("brochure/locator", $data);
    }

    public function about()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "About"];
        return view("brochure/about", $data);
    }

    public function contact()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Contact"];
        return view("brochure/contact", $data);
    }

    public function fetchLots()
    {
        $mapModel = new MapModel();
        $lots = $mapModel->getLots();

        header("Content-Type: application/json");
        echo json_encode($lots);
    }
}
