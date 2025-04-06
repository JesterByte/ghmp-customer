<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AdminModel;
use App\Models\AdminNotificationModel;
use App\Models\ContactModel;
use App\Models\MapModel;
use App\Models\PricingModel;
use CodeIgniter\Format\Format;

use function PHPSTORM_META\map;

class Home extends BaseController
{
    protected $pricingModel;
    protected $contactModel;
    protected $adminNotificationModel;
    protected $emailer;
    protected $adminModel;
    protected $session;

    public function __construct()
    {
        $this->pricingModel = new PricingModel();
        $this->contactModel = new ContactModel();
        $this->adminNotificationModel = new AdminNotificationModel();
        $this->session = session();
        $this->emailer = \Config\Services::email();
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $lowestLotPrice =  FormatterHelper::formatPrice($this->pricingModel->getLowestLotPrice());
        $lowestEstatePrice = FormatterHelper::formatPrice($this->pricingModel->getLowestEstatePrice());

        $data = [
            "pageTitle" => "Home",
            "lowestLotPrice" => $lowestLotPrice,
            "lowestEstatePrice" => $lowestEstatePrice
        ];
        return view("brochure/home", $data);
        // return view('brochure/home');
    }

    public function locator()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Lots & Estates"];
        return view("brochure/locator", $data);
    }

    public function pricing()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Pricing"];
        return view("brochure/pricing", $data);
    }

    public function getLotPricing()
    {
        $phase = "Phase {$this->request->getPost("phase")}";
        $lotType = $this->request->getPost("lotType");

        $lotPricing = $this->pricingModel->getPricingByPhaseAndLotType($phase, $lotType);

        return $this->response->setJSON($lotPricing);
    }

    public function getEstatePricing()
    {
        $estateType = "Estate {$this->request->getPost("estateType")}";

        $estatePricing = $this->pricingModel->getPricingByEstateType($estateType);

        return $this->response->setJSON($estatePricing);
    }

    public function getBurialPricing()
    {
        $category = $this->request->getPost("category");
        $burialType = $this->request->getPost("burial_type");

        $burialPricing = $this->pricingModel->getPricingByCategory($category, $burialType);

        return $this->response->setJSON($burialPricing);
    }

    public function about()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "About"];
        return view("brochure/about", $data);
    }

    public function contact()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Contact"];
        return view("brochure/contact", $data);
    }

    public function contactSubmit()
    {
        $name = FormatterHelper::cleanName(strip_tags($this->request->getPost("name")));
        $email = FormatterHelper::cleanEmail(strip_tags($this->request->getPost("email")));
        $message = trim(strip_tags($this->request->getPost("message")));

        $data = [
            "name" => $name,
            "email" => $email,
            "message" => $message
        ];

        if ($this->contactModel->insert($data)) {
            $adminEmail = $this->adminModel->select("email")
                ->where("id", 1)
                ->get()
                ->getRow()
                ->email;

            $this->emailer->setTo($adminEmail);
            $this->emailer->setSubject('New Inquiry from Website');

            $emailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                <h2 style='text-align: center; color: #333;'>New Website Inquiry</h2>
                <p style='color: #555;'>You have received a new inquiry from the website. Here are the details:</p>
                <table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd;'><strong>Date & Time:</strong></td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>" . date('Y-m-d h:i A') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd;'><strong>Name:</strong></td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$name}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd;'><strong>Email:</strong></td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$data['email']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd;'><strong>Message:</strong></td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>" . nl2br($data['message']) . "</td>
                    </tr>
                </table>
                <p style='margin-top: 20px; color: #888; font-size: 12px;'>This is an automated notification from Green Haven Memorial Park website. Please do not reply.</p>
            </div>";

            $this->emailer->setMessage($emailBody);

            if ($this->emailer->send()) {
                log_message('info', 'Admin notification email sent successfully');
            } else {
                log_message('error', 'Failed to send admin notification email: ' . $this->emailer->printDebugger(['headers']));
            }

            $icon = FormatterHelper::$checkIcon;
            $message = "We have successfully received your inquiry!";
            $title = "Inquiry Successful";
        } else {
            $icon = FormatterHelper::$xIcon;
            $message = "Sorry, we have failed to process your inquiry. Please try again later.";
            $title = "Inquiry Failed";
        }

        $this->session->setFlashdata("flash_message", [
            "icon" => $icon,
            "message" => $message,
            "title" => $title
        ]);

        return redirect()->to(base_url("contact"));
    }

    public function fetchLots()
    {
        $mapModel = new MapModel();
        $lots = $mapModel->getLots();

        header("Content-Type: application/json");
        echo json_encode($lots);
    }
}
