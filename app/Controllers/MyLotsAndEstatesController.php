<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AssetModel;
use App\Models\EstateReservationModel;
use App\Models\LotReservationModel;
use App\Models\PricingModel;

class MyLotsAndEstatesController extends BaseController {

    public function index() {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $assetModel = new AssetModel();
        $table = $assetModel->getAssetsById($session->get("user_id"));

        foreach ($table as $key => $row) {
            $row["encrypted_asset_id"] = bin2hex($this->encrypter->encrypt($row["asset_id"]));
            $row["encrypted_asset_type"] = bin2hex($this->encrypter->encrypt($row["asset_type"]));

            if ($row["reservation_status"] == "Completed" || $row["reservation_status"] == "Pending" || (isset($row["down_payment_status"]) && $row["down_payment_status"] == "Pending")) {
                $row["payment_link"] = "#";
            } else {
                $row["payment_link"] = $this->createPaymongoLink($row["payment_amount"], $row["asset_id"], $row["payment_option"]);
            }

            if (isset($row["down_payment_status"]) && $row["down_payment_status"] == "Pending") {
                $row["down_payment_link"] = $this->createPaymongoLink($row["down_payment"], $row["asset_id"], $row["payment_option"], true);
            } else {
                $row["down_payment_link"] = "#";
            }

            $table[$key] = $row;
        }

        $data = [
            "pageTitle" => "My Lots & Estates",
            "table" => $table 
        ];
        return view("admin/my_lots_and_estates", $data);
    }

    public function selectPaymentOption($assetId, $assetType) {
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
            "assetId" => $decryptedAssetId,
            "encryptedAssetId" => $assetId,
            "assetType" => $assetType,
            "encryptedAssetType" => $assetType,
            "phase" => $phase,
            "lotType" => $lotType,
            "estateType" => $estateType
        ];
        return view("admin/payment_option", $data);
    }

    // public function paymentOptionSubmit() {
    //     if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //         $session = session();

    //         $assetId = $this->request->getPost("asset_id");
    //         $reservationType = $this->request->getPost("reservation_type");


    //         $paymentOption = $this->request->getPost("payment_option");

    //         $paymentOption = FormatterHelper::formatPaymentOption($paymentOption);

    //         $assetId = $this->encrypter->decrypt(hex2bin($assetId));
    //         $reservationType = $this->encrypter->decrypt(hex2bin($reservationType));

    //         $lotTypes = ["Supreme", "Special", "Standard"];
    //         $estateTypes = ["A", "B", "C"];

    //         if (in_array($reservationType, $lotTypes)) {
    //             $lotIdParts = FormatterHelper::extractLotIdParts($assetId);
    //             $phase = "Phase " . $lotIdParts["phase"];
                
    //             $pricingModel = new PricingModel();
    //             $pricing = $pricingModel->getPhasePricing($phase, $reservationType);

    //             $lotReservationModel = new LotReservationModel();
    //             $lotReservationModel->updateLotPaymentOption($assetId, $session->get("user_id"), $paymentOption);
    //             $downPaymentDueDate = $this->generateDownPaymentDueDate();
    //             switch ($paymentOption) {
    //                 case "Cash Sale":
    //                     $lotReservationModel->setCashSalePayment($assetId, $pricing["cash_sale"]);
    //                     $dueDate = $this->generateCashSaleDueDate();
    //                     $lotReservationModel->setCashSaleDueDate($assetId, $dueDate);
    //                     break;
    //                 case "6 Months":
    //                     $lotReservationModel->setSixMonthsPayment($assetId, $pricing["cash_sale"]);
    //                     $dueDate = $this->generateSixMonthsDueDate();
    //                     $lotReservationModel->setSixMonthsDueDate($assetId, $dueDate);
    //                     break; 
    //                 case "Installment: 1 Year":
    //                     $termYears = 1;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_one_year"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_one_year"];
    //                     $lotReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["one_year_interest_rate"]);
    //                     break;
    //                 case "Installment: 2 Years":
    //                     $termYears = 2;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_two_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_two_years"];
    //                     $lotReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["two_years_interest_rate"]);
    //                     break;
    //                 case "Installment: 3 Years":
    //                     $termYears = 3;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_four_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_three_years"];
    //                     $lotReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["three_years_interest_rate"]);
    //                     break;
    //                 case "Installment: 4 Years":
    //                     $termYears = 4;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_four_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_four_years"];
    //                     $lotReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["four_years_interest_rate"]);
    //                     break;
    //                 case "Installment: 5 Years":
    //                     $termYears = 5;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_five_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_five_years"];
    //                     $lotReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["five_years_interest_rate"]);
    //                     break;               
    //             }

    //         } else if (in_array($reservationType, $estateTypes)) {
    //             $estateIdParts = FormatterHelper::extractEstateIdParts($assetId);
    //             $estateType = "Estate " . $estateIdParts['type'];
                
    //             $pricingModel = new PricingModel();
    //             $pricing = $pricingModel->getEstatePricing($estateType);

    //             $estateReservationModel = new EstateReservationModel();
    //             $estateReservationModel->updateEstatePaymentOption($assetId, $session->get("user_id"), $paymentOption);
    //             $downPaymentDueDate = $this->generateDownPaymentDueDate();
    //             switch ($paymentOption) {
    //                 case "Cash Sale":
    //                     $estateReservationModel->setCashSalePayment($assetId, $pricing["cash_sale"]);
    //                     $dueDate = $this->generateCashSaleDueDate();
    //                     $estateReservationModel->setCashSaleDueDate($assetId, $dueDate);
    //                     break;
    //                 case "6 Months":
    //                     $estateReservationModel->setSixMonthsPayment($assetId, $pricing["cash_sale"]);
    //                     $dueDate = $this->generateSixMonthsDueDate();
    //                     $estateReservationModel->setSixMonthsDueDate($assetId, $dueDate);
    //                     break; 
    //                 case "Installment: 1 Year":
    //                     $termYears = 1;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_one_year"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_one_year"];
    //                     $estateReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["one_year_interest_rate"]);
    //                     break;
    //                 case "Installment: 2 Years":
    //                     $termYears = 2;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_two_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_two_years"];
    //                     $estateReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["two_years_interest_rate"]);
    //                     break;
    //                 case "Installment: 3 Years":
    //                     $termYears = 3;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_four_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_three_years"];
    //                     $estateReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["three_years_interest_rate"]);
    //                     break;
    //                 case "Installment: 4 Years":
    //                     $termYears = 4;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_four_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_four_years"];
    //                     $estateReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["four_years_interest_rate"]);
    //                     break;
    //                 case "Installment: 5 Years":
    //                     $termYears = 5;
    //                     $downPayment = $pricing["down_payment"];
    //                     $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_five_years"], $termYears);
    //                     $paymentAmount = $pricing["monthly_amortization_five_years"];
    //                     $estateReservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $pricing["five_years_interest_rate"]);
    //                     break;               
    //             }
    //         }

    //         return redirect()->to(base_url("my_lots_and_estates"));
    //     }
    // }

    public function paymentOptionSubmit() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }
    
        $session = session();
        $assetId = $this->decryptAssetId($this->request->getPost("asset_id"));
        $reservationType = $this->decryptAssetId($this->request->getPost("reservation_type"));
        $paymentOption = FormatterHelper::formatPaymentOption($this->request->getPost("payment_option"));
    
        $lotTypes = ["Supreme", "Special", "Standard"];
        $estateTypes = ["A", "B", "C"];
    
        // Process for Lot Reservation
        if (in_array($reservationType, $lotTypes)) {
            $this->processLotReservation($assetId, $reservationType, $paymentOption, $session);

            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-check-lg text-success"></i>',
                "title" => "Operation Completed",
                "message" => "Payment option has been set successfully!",
            ]);

            return redirect()->to(base_url("my_lots_and_estates"));
        }
    
        // Process for Estate Reservation
        if (in_array($reservationType, $estateTypes)) {
            $this->processEstateReservation($assetId, $paymentOption, $session);

            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-check-lg text-success"></i>',
                "title" => "Operation Completed",
                "message" => "Payment option has been set successfully!",
            ]);
            
            return redirect()->to(base_url("my_lots_and_estates"));
        }
    }
    
    private function decryptAssetId($encryptedId) {
        return $this->encrypter->decrypt(hex2bin($encryptedId));
    }
    
    private function processLotReservation($assetId, $reservationType, $paymentOption, $session) {
        $lotIdParts = FormatterHelper::extractLotIdParts($assetId);
        $phase = "Phase " . $lotIdParts["phase"];
        
        $pricingModel = new PricingModel();
        $pricing = $pricingModel->getPhasePricing($phase, $reservationType);
    
        $lotReservationModel = new LotReservationModel();
        $lotReservationModel->updateLotPaymentOption($assetId, $session->get("user_id"), $paymentOption, "Confirmed");
        $downPaymentDueDate = $this->generateDownPaymentDueDate();
    
        $this->applyPaymentOption($lotReservationModel, $assetId, $paymentOption, $pricing, $downPaymentDueDate);
    }
    
    private function processEstateReservation($assetId, $paymentOption, $session) {
        $estateIdParts = FormatterHelper::extractEstateIdParts($assetId);
        $estateType = "Estate " . $estateIdParts['type'];
        
        $pricingModel = new PricingModel();
        $pricing = $pricingModel->getEstatePricing($estateType);
    
        $estateReservationModel = new EstateReservationModel();
        $estateReservationModel->updateEstatePaymentOption($assetId, $session->get("user_id"), $paymentOption, "Confirmed");
        $downPaymentDueDate = $this->generateDownPaymentDueDate();
    
        $this->applyPaymentOption($estateReservationModel, $assetId, $paymentOption, $pricing, $downPaymentDueDate);
    }
    
    private function applyPaymentOption($reservationModel, $assetId, $paymentOption, $pricing, $downPaymentDueDate) {
        switch ($paymentOption) {
            case "Cash Sale":
                $this->applyCashSale($reservationModel, $assetId, $pricing);
                break;
            case "6 Months":
                $this->applySixMonths($reservationModel, $assetId, $pricing);
                break;
            case "Installment: 1 Year":
                $this->applyInstallment($reservationModel, $assetId, $pricing, 1, $downPaymentDueDate);
                break;
            case "Installment: 2 Years":
                $this->applyInstallment($reservationModel, $assetId, $pricing, 2, $downPaymentDueDate);
                break;
            case "Installment: 3 Years":
                $this->applyInstallment($reservationModel, $assetId, $pricing, 3, $downPaymentDueDate);
                break;
            case "Installment: 4 Years":
                $this->applyInstallment($reservationModel, $assetId, $pricing, 4, $downPaymentDueDate);
                break;
            case "Installment: 5 Years":
                $this->applyInstallment($reservationModel, $assetId, $pricing, 5, $downPaymentDueDate);
                break;
        }
    }
    
    private function applyCashSale($reservationModel, $assetId, $pricing) {
        $reservationModel->setCashSalePayment($assetId, $pricing["cash_sale"]);
        $dueDate = $this->generateCashSaleDueDate();
        $reservationModel->setCashSaleDueDate($assetId, $dueDate);
    }
    
    private function applySixMonths($reservationModel, $assetId, $pricing) {
        $reservationModel->setSixMonthsPayment($assetId, $pricing["cash_sale"]);
        $dueDate = $this->generateSixMonthsDueDate();
        $reservationModel->setSixMonthsDueDate($assetId, $dueDate);
    }
    
    private function applyInstallment($reservationModel, $assetId, $pricing, $termYears, $downPaymentDueDate) {
        $years = ["1" => "one", "2" => "two", "3" => "three", "4" => "four", "5" => "five"];

        if ($termYears == "1") {
            $termYearsKey = $years[$termYears] . "_year";
        } else if ($termYears > "1") {
            $termYearsKey = $years[$termYears] . "_years";
        }

        $termYears = $years[$termYears];

        $downPayment = $pricing["down_payment"];
        $totalAmount = $this->getFinalBalance($pricing["monthly_amortization_" . $termYearsKey], $termYears);
        $paymentAmount = $pricing["monthly_amortization_" . $termYearsKey];
        $interestRate = $pricing[$termYearsKey . "_interest_rate"];
    
        $reservationModel->setInstallmentPayment($assetId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $interestRate);
    }

    private function generateCashSaleDueDate() {
        $date = new \DateTime();

        $date->modify("+7 days");
        return $date->format("Y-m-d");
    }

    private function generateSixMonthsDueDate() {
        $date = new \DateTime();

        $date->modify("+6 months");
        return $date->format("Y-m-d");
    }

    private function generateDownPaymentDueDate() {
        $date = new \DateTime();

        $date->modify("+30 days");
        return $date->format("Y-m-d");
    }

    private function getFinalBalance($monthlyPayment, $termYears) {
        return $monthlyPayment * ((int) $termYears * 12);
    } 
}