<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Helpers\FormatterHelper;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $encrypter;

    public function __construct()
    {
        $this->encrypter = \Config\Services::encrypter();
        date_default_timezone_set("Asia/Manila");
    }

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ["navigation"];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    public function createPaymongoLink($paymentAmount, $assetId, $paymentOption, $isDownPayment = false)
    {
        $db = \Config\Database::connect();

        $assetType = FormatterHelper::determineIdType($assetId);

        switch ($assetType) {
            case "lot":
                $formatteddAssetId = FormatterHelper::formatLotId($assetId);
                $reservationTable = "lot_reservations";
                $installmentTable = "installments";
                $column = "lot_id";
                break;
            case "estate":
                $formatteddAssetId = FormatterHelper::formatEstateId($assetId);
                $reservationTable = "estate_reservations";
                $installmentTable = "estate_installments";
                $column = "estate_id";
                break;
            default:
                return false; // Invalid asset type
        }

        $paymentAmount = $paymentAmount * 100; // Convert PHP to centavos

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/links",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => $paymentAmount,
                        'description' => 'Payment for ' . $formatteddAssetId,
                        'remarks' => $paymentOption . ' Reservation',
                        "redirect" => [
                            "success" => base_url("my_lots_and_estates"),
                            "failed" => base_url("my_lots_and_estates"),
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic " . base64_encode("sk_test_ZTA2SopEKfLJHZPZ7Tc4XKCK"),
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) return false;

        $data = json_decode($response, true);
        $checkoutUrl = $data["data"]["attributes"]["checkout_url"] ?? null;
        $referenceNumber = $data["data"]["attributes"]["reference_number"] ?? null;

        if ($checkoutUrl && $referenceNumber) {
            if (str_contains($paymentOption, "Installment")) {
                switch ($isDownPayment) {
                    case true:
                        $referenceNumberColumn = "down_reference_number";
                        break;
                    case false:
                        $referenceNumberColumn = "reference_number";
                        break;
                }

                $db->table($installmentTable)
                    ->where($column, $assetId)
                    ->set([$referenceNumberColumn => $referenceNumber])
                    ->update();

                // Store the reference number in the reservation table
                $db->table($reservationTable)
                    ->where($column, $assetId)
                    ->set(["reference_number" => $referenceNumber])
                    ->update();
            } else {
                // Store the reference number in the reservation table
                $db->table($reservationTable)
                    ->where($column, $assetId)
                    ->set(["reference_number" => $referenceNumber])
                    ->update();
            }
        }

        return $checkoutUrl;
    }

    public function createPaymongoLinkBurial($paymentAmount, $assetId, $burialType, $isDownPayment = false)
    {
        $db = \Config\Database::connect();

        $assetType = FormatterHelper::determineIdType($assetId);

        switch ($assetType) {
            case "lot":
                $formatteddAssetId = FormatterHelper::formatLotId($assetId);
                // $reservationTable = "lot_reservations";
                // $installmentTable = "installments";
                // $column = "lot_id";
                break;
            case "estate":
                $formatteddAssetId = FormatterHelper::formatEstateId($assetId);
                // $reservationTable = "estate_reservations";
                // $installmentTable = "estate_installments";
                // $column = "estate_id";
                break;
            default:
                return false; // Invalid asset type
        }

        $paymentAmount = $paymentAmount * 100; // Convert PHP to centavos

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/links",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => $paymentAmount,
                        'description' => 'Payment for Burial in ' . $formatteddAssetId,
                        'remarks' => $burialType . ' Burial',
                        "redirect" => [
                            "success" => base_url("my_memorial_services"),
                            "failed" => base_url("my_memorial_services"),
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic " . base64_encode("sk_test_ZTA2SopEKfLJHZPZ7Tc4XKCK"),
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) return false;

        $data = json_decode($response, true);
        $checkoutUrl = $data["data"]["attributes"]["checkout_url"] ?? null;
        $referenceNumber = $data["data"]["attributes"]["reference_number"] ?? null;

        if ($checkoutUrl && $referenceNumber) {
            // if (str_contains($paymentOption, "Installment")) {
            //     switch ($isDownPayment) {
            //         case true:
            //             $referenceNumberColumn = "down_reference_number";
            //             break;
            //         case false:
            //             $referenceNumberColumn = "reference_number";
            //             break;
            //     }

            //     $db->table($installmentTable)
            //     ->where($column, $assetId)
            //     ->set([$referenceNumberColumn => $referenceNumber])
            //     ->update();
            // } else {
            //     // Store the reference number in the reservation table
            //     $db->table($reservationTable)
            //     ->where($column, $assetId)
            //     ->set(["reference_number" => $referenceNumber])
            //     ->update();
            // }

            $db->table("burial_reservations")
                ->where("asset_id", $assetId)
                ->where("burial_type", $burialType)
                ->set(["reference_number" => $referenceNumber])
                ->update();
        }

        return $checkoutUrl;
    }
}
