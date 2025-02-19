<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model {
    public function getAssetsById($userId) {
        // Connect to the database
        // $db = \Config\Database::connect();
    
        // First query for lot_reservations
        $builder1 = $this->db->table("lot_reservations")
        ->select("lot_reservations.lot_id AS asset_id, lot_reservations.reservee_id, lot_reservations.lot_type AS asset_type, lot_reservations.payment_option, lot_reservations.reservation_status, 'lot' AS asset, COALESCE(cash_sales.payment_amount, 0) AS payment_amount")
        ->where("lot_reservations.reservee_id", $userId)
        ->join("cash_sales", "cash_sales.lot_id = lot_reservations.lot_id", "left");

        // Second query for estate_reservations (joins estate_cash_sales)
        $builder2 = $this->db->table("estate_reservations")
            ->select("estate_reservations.estate_id AS asset_id, estate_reservations.reservee_id, estate_reservations.estate_type AS asset_type, estate_reservations.payment_option, estate_reservations.reservation_status, 'estate' AS asset, COALESCE(estate_cash_sales.payment_amount, 0) AS payment_amount")
            ->where("estate_reservations.reservee_id", $userId)
            ->join("estate_cash_sales", "estate_cash_sales.estate_id = estate_reservations.estate_id", "left");
    
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
    
}