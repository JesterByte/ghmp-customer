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
            $row["encrypted_asset_id"] = bin2hex($this->encrypter->encrypt( $row["asset_id"]));
            $row["encrypted_asset_type"] = bin2hex($this->encrypter->encrypt($row["asset_type"]));
            $table[$key] = $row;
        }

        $data = [
            "pageTitle" => "My Lots & Estates",
            "table" => $table 
        ];
        return view("admin/lots_and_estates", $data);
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
                $estateIdParts = FormatterHelper::extractEstateIdParts($decryptedAssetId);
                $estateLetter = $estateIdParts['type'];
                $estateType = "Estate " . $estateLetter;
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

    public function paymentOptionSubmit() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $session = session();

            $assetId = $this->request->getPost("asset_id");
            $reservationType = $this->request->getPost("reservation_type");

            $paymentOption = $this->request->getPost("payment_option");
            $paymentOption = FormatterHelper::formatPaymentOption($paymentOption);

            $assetId = $this->encrypter->decrypt(hex2bin($assetId));
            $reservationType = $this->encrypter->decrypt(hex2bin($reservationType));

            $lotTypes = ["Supreme", "Special", "Standard"];
            $estateTypes = ["A", "B", "C"];

            if (in_array($reservationType, $lotTypes)) {
                $lotReservationModel = new LotReservationModel();
                $lotReservationModel->updateLotPaymentOption($assetId, $session->get("user_id"), $paymentOption);

            } else if (in_array($reservationType, $estateTypes)) {
                $estateReservationModel = new EstateReservationModel();
                $estateReservationModel->updateEstatePaymentOption($assetId, $session->get("user_id"), $paymentOption);
            }

            return redirect()->to(base_url("my_lots_and_estates"));
        }
    }
}