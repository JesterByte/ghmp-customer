<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AssetModel;
use App\Models\EstateReservationModel;
use App\Models\LotReservationModel;
use App\Models\PricingModel;
use App\Models\AdminNotificationModel;

class MyLotsAndEstatesController extends BaseController
{

    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $assetModel = new AssetModel();
        $table = $assetModel->getAssetsById($session->get("user_id"));

        foreach ($table as $key => $row) {
            $row["encrypted_reservation_id"] = bin2hex($this->encrypter->encrypt($row["reservation_id"]));
            $row["encrypted_asset_id"] = bin2hex($this->encrypter->encrypt($row["asset_id"]));
            $row["encrypted_asset_type"] = bin2hex($this->encrypter->encrypt($row["asset_type"]));

            if ($row["reservation_status"] == "Cancelled" || $row["reservation_status"] == "Completed" || $row["reservation_status"] == "Pending" || (isset($row["down_payment_status"]) && $row["down_payment_status"] == "Pending")) {
                $row["payment_link"] = "#";
            } else {
                if ($row["payment_option"] == "6 Months") {
                    $isSixMonths = true;
                } else {
                    $isSixMonths = false;
                }

                $row["payment_link"] = $this->createPaymongoLink($row["payment_amount"], $row["asset_id"], $row["payment_option"], false, $isSixMonths);
            }

            if (isset($row["down_payment_status"]) && $row["down_payment_status"] == "Pending") {
                if ($row["payment_option"] == "6 Months") {
                    $isSixMonths = true;
                } else {
                    $isSixMonths = false;
                }

                $row["down_payment_link"] = $this->createPaymongoLink($row["down_payment"], $row["asset_id"], $row["payment_option"], true, $isSixMonths);
            } else {
                $row["down_payment_link"] = "#";
            }

            $table[$key] = $row;
        }

        $data = [
            "pageTitle" => "My Lots & Estates",
            "table" => $table,
            "session" => $session
        ];
        return view("admin/my_lots_and_estates", $data);
    }

    public function selectPaymentOption($reservationId, $assetId, $assetType)
    {
        $session = session();

        $decryptedReservationId = $this->encrypter->decrypt(hex2bin($reservationId));
        $decryptedAssetId = $this->encrypter->decrypt(hex2bin($assetId));
        $decryptedAssetType = $this->encrypter->decrypt(hex2bin($assetType));

        $reservationType = FormatterHelper::determineIdType($decryptedAssetId);

        $phase = "";
        $lotType = "";
        $estateType = "";

        $pricingModel = new PricingModel();
        switch ($reservationType) {
            case "lot":
                $lotIdParts = FormatterHelper::extractLotIdParts($decryptedAssetId);
                $phase = "Phase " . $lotIdParts["phase"];
                $lotType = $decryptedAssetType;
                $pricing = $pricingModel->getPhasePricing($phase, $lotType);
                break;
            case "estate":
                $estateType = "Estate " . $decryptedAssetType;
                $pricing = $pricingModel->getEstatePricing($estateType);
                break;
        }

        $data = [
            "pageTitle" => "Select Payment Option",
            "pricing" => $pricing,
            "reservationId" => $decryptedReservationId,
            "encryptedReservationId" => $reservationId,
            "assetId" => $decryptedAssetId,
            "encryptedAssetId" => $assetId,
            "assetType" => $assetType,
            "encryptedAssetType" => $assetType,
            "phase" => $phase,
            "lotType" => $lotType,
            "estateType" => $estateType,

            "session" => $session
        ];
        return view("admin/payment_option", $data);
    }

    public function paymentOptionSubmit()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }

        $session = session();
        $adminNotificationModel = new AdminNotificationModel();


        $reservationId = $this->decryptAssetId($this->request->getPost("reservation_id"));
        $assetId = $this->decryptAssetId($this->request->getPost("asset_id"));
        $reservationType = $this->decryptAssetId($this->request->getPost("reservation_type"));
        $paymentOption = FormatterHelper::formatPaymentOption($this->request->getPost("payment_option"));

        $formattedAssetId = FormatterHelper::formatAssetId($assetId);

        switch ($paymentOption) {
            case "Cash Sale":
                $link = "cash-sale";
                break;
            case "6 Months":
                $link = "six-months";
                break;
            case (strpos($paymentOption, "Installment") !== false):
                $link = "installments";
                break;
        }

        $formattedAssetId = FormatterHelper::formatLotId($assetId);

        $lotTypes = ["Supreme", "Special", "Standard"];
        $estateTypes = ["A", "B", "C"];

        // Process for Lot Reservation
        if (in_array($reservationType, $lotTypes)) {
            $this->processLotReservation($reservationId, $assetId, $reservationType, $paymentOption, $session);

            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-check-lg text-success"></i>',
                "title" => "Operation Completed",
                "message" => "Payment option has been set successfully!",
            ]);

            // Insert notification for the admin about the new reservation
            $notificationMessage = "{$session->get("user_full_name")} has chosen $paymentOption as their payment option for Asset: {$formattedAssetId}.";
            $notificationData = [
                'admin_id' => null,  // Null for general admin notification
                'message' => $notificationMessage,
                'link' => 'lot-reservations-' . $link,  // Link to the reservations page
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $adminNotificationModel->insert($notificationData);

            $lotEmailBody = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
                    <h2 style="color: #333; text-align: center;">New Lot Reservation - Payment Option Chosen</h2>
                    <p style="font-size: 16px;">Dear Admin,</p>
                    <p style="font-size: 16px;"><strong>' . htmlspecialchars($session->get("user_full_name")) . '</strong> has chosen a payment option for their lot reservation.</p>
                    
                    <div style="background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <p><strong>Reservation Details:</strong></p>
                        <p><strong>Lot:</strong> ' . htmlspecialchars($formattedAssetId) . '</p>
                        <p><strong>Payment Option:</strong> <span style="color: #28a745; font-weight: bold;">' . htmlspecialchars($paymentOption) . '</span></p>
                    </div>

                    <p style="margin-top: 20px; font-size: 16px;">Please review the details in the admin panel:</p>
                    <p style="text-align: center;">
                        <a target="_blank" href="' . $this->admin_url('lot-reservations-' . $link) . '" style="background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                            View Reservation
                        </a>
                    </p>

                    <hr style="border: 0; height: 1px; background: #ddd; margin-top: 20px;">
                    <p style="text-align: center; font-size: 12px; color: #777;">This is an automated email. Please do not reply.</p>
                </div>
            ';


            // Email Content
            $this->email->setTo($this->adminEmail);
            $this->email->setSubject('New Lot Reservation - Payment Chosen');
            $this->email->setMessage($lotEmailBody);

            // Send Email
            if ($this->email->send()) {
                log_message('info', 'Reservation email sent to admin.');
            } else {
                log_message('error', 'Failed to send reservation email: ' . $this->email->printDebugger(['headers']));
            }

            return redirect()->to(base_url("my_lots_and_estates"));
        }

        // Process for Estate Reservation
        if (in_array($reservationType, $estateTypes)) {
            $this->processEstateReservation($reservationId, $assetId, $paymentOption, $session);

            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-check-lg text-success"></i>',
                "title" => "Operation Completed",
                "message" => "Payment option has been set successfully!",
            ]);

            // Insert notification for the admin about the new reservation
            $notificationMessage = "{$session->get("user_full_name")} has chosen $paymentOption as their payment option for Asset: {$formattedAssetId}.";
            $notificationData = [
                'admin_id' => null,  // Null for general admin notification
                'message' => $notificationMessage,
                'link' => 'estate-reservations-' . $link,  // Link to the reservations page
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $adminNotificationModel->insert($notificationData);

            $estateEmailBody = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
                <h2 style="color: #333; text-align: center;">New Estate Reservation - Payment Option Chosen</h2>
                <p style="font-size: 16px;">Dear Admin,</p>
                <p style="font-size: 16px;"><strong>' . htmlspecialchars($session->get("user_full_name")) . '</strong> has chosen a payment option for their estate reservation.</p>
                
                <div style="background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    <p><strong>Reservation Details:</strong></p>
                    <p><strong>Estate:</strong> ' . htmlspecialchars($formattedAssetId) . '</p>
                    <p><strong>Payment Option:</strong> <span style="color: #28a745; font-weight: bold;">' . htmlspecialchars($paymentOption) . '</span></p>
                </div>

                <p style="margin-top: 20px; font-size: 16px;">Please review the details in the admin panel:</p>
                <p style="text-align: center;">
                    <a target="_blank" href="' . $this->admin_url('estate-reservations-' . $link) . '" style="background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                        View Reservation
                    </a>
                </p>

                <hr style="border: 0; height: 1px; background: #ddd; margin-top: 20px;">
                <p style="text-align: center; font-size: 12px; color: #777;">This is an automated email. Please do not reply.</p>
            </div>
        ';


            // Email Content
            $this->email->setTo($this->adminEmail);
            $this->email->setSubject('New Estate Reservation - Payment Chosen');
            $this->email->setMessage($estateEmailBody);

            // Send Email
            if ($this->email->send()) {
                log_message('info', 'Reservation email sent to admin.');
            } else {
                log_message('error', 'Failed to send reservation email: ' . $this->email->printDebugger(['headers']));
            }

            return redirect()->to(base_url("my_lots_and_estates"));
        }
    }

    private function decryptAssetId($encryptedId)
    {
        return $this->encrypter->decrypt(hex2bin($encryptedId));
    }

    private function processLotReservation($reservationId, $assetId, $reservationType, $paymentOption, $session)
    {
        $lotIdParts = FormatterHelper::extractLotIdParts($assetId);
        $phase = "Phase " . $lotIdParts["phase"];

        $pricingModel = new PricingModel();
        $pricing = $pricingModel->getPhasePricing($phase, $reservationType);

        $lotReservationModel = new LotReservationModel();
        $lotReservationModel->updateLotPaymentOption($reservationId, $assetId, $session->get("user_id"), $paymentOption, "Confirmed");
        $downPaymentDueDate = $this->generateDownPaymentDueDate();

        $this->applyPaymentOption($lotReservationModel, $reservationId, $assetId, $paymentOption, $pricing, $downPaymentDueDate);
    }

    private function processEstateReservation($reservationId, $assetId, $paymentOption, $session)
    {
        $estateIdParts = FormatterHelper::extractEstateIdParts($assetId);
        $estateType = "Estate " . $estateIdParts['type'];

        $pricingModel = new PricingModel();
        $pricing = $pricingModel->getEstatePricing($estateType);

        $estateReservationModel = new EstateReservationModel();
        $estateReservationModel->updateEstatePaymentOption($reservationId, $assetId, $session->get("user_id"), $paymentOption, "Confirmed");
        $downPaymentDueDate = $this->generateDownPaymentDueDate();
        $this->applyPaymentOption($estateReservationModel, $reservationId, $assetId, $paymentOption, $pricing, $downPaymentDueDate);
    }

    private function applyPaymentOption($reservationModel, $reservationId, $assetId, $paymentOption, $pricing, $downPaymentDueDate)
    {
        switch ($paymentOption) {
            case "Cash Sale":
                $this->applyCashSale($reservationModel, $reservationId, $assetId, $pricing);
                break;
            case "6 Months":
                $this->applySixMonths($reservationModel, $reservationId, $assetId, $pricing, $downPaymentDueDate);
                break;
            case "Installment: 1 Year":
                $this->applyInstallment($reservationModel, $reservationId, $assetId, $pricing, 1, $downPaymentDueDate);
                break;
            case "Installment: 2 Years":
                $this->applyInstallment($reservationModel, $reservationId, $assetId, $pricing, 2, $downPaymentDueDate);
                break;
            case "Installment: 3 Years":
                $this->applyInstallment($reservationModel, $reservationId, $assetId, $pricing, 3, $downPaymentDueDate);
                break;
            case "Installment: 4 Years":
                $this->applyInstallment($reservationModel, $reservationId, $assetId, $pricing, 4, $downPaymentDueDate);
                break;
            case "Installment: 5 Years":
                $this->applyInstallment($reservationModel, $reservationId, $assetId, $pricing, 5, $downPaymentDueDate);
                break;
        }
    }

    private function applyCashSale($reservationModel, $reservationId, $assetId, $pricing)
    {
        $cashSaleId = $reservationModel->setCashSalePayment($reservationId, $assetId, $pricing["cash_sale"]);
        $dueDate = $this->generateCashSaleDueDate();
        $reservationModel->setCashSaleDueDate($cashSaleId, $assetId, $dueDate);
    }

    private function applySixMonths($reservationModel, $reservationId, $assetId, $pricing, $downPaymentDueDate)
    {
        $downPayment = $pricing["six_months_down_payment"];
        $totalAmount = $this->getSixMonthsFinalBalance($pricing["six_months_monthly_amortization"]);
        $paymentAmount = $pricing["six_months_monthly_amortization"];
        $reservationModel->setSixMonthsPayment($reservationId, $assetId, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount);
    }

    private function applyInstallment($reservationModel, $reservationId, $assetId, $pricing, $termYears, $downPaymentDueDate)
    {
        $years = ["1" => "one", "2" => "two", "3" => "three", "4" => "four", "5" => "five"];

        if ($termYears == "1") {
            $termYearsKey = $years[$termYears] . "_year";
        } else if ($termYears > "1") {
            $termYearsKey = $years[$termYears] . "_years";
        }

        $termYears = $years[$termYears];

        $downPayment = $pricing["down_payment"];
        $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_" . $termYearsKey], $termYears);
        // $totalAmount = $pricing["balance"];
        $paymentAmount = $pricing["monthly_amortization_" . $termYearsKey];
        $interestRate = $pricing[$termYearsKey . "_interest_rate"];

        $reservationModel->setInstallmentPayment($reservationId, $assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $interestRate);
    }

    private function generateCashSaleDueDate()
    {
        $date = new \DateTime();

        $date->modify("+7 days");
        return $date->format("Y-m-d");
    }

    // private function generateSixMonthsDueDate()
    // {
    //     $date = new \DateTime();

    //     $date->modify("+6 months");
    //     return $date->format("Y-m-d");
    // }

    private function generateDownPaymentDueDate()
    {
        $date = new \DateTime();

        $date->modify("+30 days");
        return $date->format("Y-m-d");
    }

    private function getFinalBalance($monthlyPayment, $termYears)
    {
        return (float) $monthlyPayment * ((int) $termYears * 12);
    }

    private function getSixMonthsFinalBalance($monthlyPayment)
    {
        return (float) $monthlyPayment * 6;
    }
}
