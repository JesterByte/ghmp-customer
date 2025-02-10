<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\ReservationOwnershipTrait;

class SixMonthsModel extends Model {
    use ReservationOwnershipTrait;

    public function getAssetsById($userId) {
        // Connect to the database
        // $db = \Config\Database::connect();
    
        // First query for lot_reservations
        $builder1 = $this->db->table("lot_reservations")
                      ->select("lot_id AS asset_id, reservee_id, lot_type AS asset_type, payment_option, reservation_status, 'lot' AS asset")
                      ->where("reservee_id", $userId);
    
        // Second query for estate_reservations
        $builder2 = $this->db->table("estate_reservations")
                      ->select("estate_id AS asset_id, reservee_id, estate_type AS asset_type, payment_option, reservation_status, 'estate' AS asset")
                      ->where("reservee_id", $userId);
    
        // Get the compiled SELECT SQL queries
        $sql1 = $builder1->getCompiledSelect();
        $sql2 = $builder2->getCompiledSelect();
    
        // Combine the queries using UNION
        $finalQuery = "$sql1 UNION $sql2";
    
        // Execute the final query
        $query = $this->db->query($finalQuery);
    
        // Fetch the results as an array
        $result = $query->getResultArray();
    
        return $result;
    }

    public function getSixMonths() {
        $cashSalesQuery = $this->db->table("six_months AS sm")
            ->select("'lot' AS asset_type, sm.lot_id AS asset_id, sm.payment_amount, smdd.due_date")
            ->join("six_months_due_dates AS smdd", "smdd.lot_id = sm.lot_id", "left")
            ->where("payment_status", "Pending")
            ->getCompiledSelect();
    
        $estateSalesQuery = $this->db->table("estate_six_months AS esm")
            ->select("'estate' AS asset_type, esm.estate_id AS asset_id, esm.payment_amount, esmdd.due_date")
            ->join("estate_six_months_due_dates AS esmdd", "esmdd.estate_id = esm.estate_id", "left")
            ->where("payment_status", "Pending")
            ->getCompiledSelect();
    
        $query = $this->db->query("$cashSalesQuery UNION $estateSalesQuery");
    
        return $query->getResult();
    }
    
    public function setSixMonthsPayment($assetIdType, $tableName, $assetId, $assetIdKey, $paymentAmount, $receiptPath) {
        $builder = $this->db->table($tableName);
        $updated = $builder->where($assetIdKey, $assetId)
                ->update([
                    "payment_amount" => $paymentAmount,
                    "payment_status" => "Paid",
                    "receipt_path" => $receiptPath,
                    "payment_date" => date("Y-m-d H:i:s")
                ]);

        switch ($assetIdType) {
            case "lot":
                $this->setLotReservation($assetId);
                $lotReservations = $this->getReserveeId($assetId);
                $this->setLotOwnership($assetId, $lotReservations["reservee_id"]);
                break;
            case "estate":
                $this->setEstateReservation($assetId);
                $estateReservations = $this->getReserveeIdEstate($assetId);
                $this->setEstateOwnership($assetId, $estateReservations["reservee_id"]);
                break;
        }

        if ($updated) {
            return ["success" => true, "message" => "Payment Successful!"];
        } else {
            return ["success" => false, "message" => "Failed to update payment."];
        }

    }
    
}