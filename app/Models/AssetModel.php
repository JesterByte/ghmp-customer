<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model {
    public function getAssetsById($userId) {
        // Connect to the database
        // $db = \Config\Database::connect();
    
        // First query for lot_reservations
        $builder1 = $this->db->table("lot_reservations AS lr")
                      ->select("lr.lot_id AS asset_id, lr.reservee_id, lr.lot_type AS asset_type, lr.payment_option, lr.reservation_status, 'lot' AS asset, cs.payment_amount")
                      ->join("cash_sales AS cs", "lr.lot_id = cs.lot_id")
                      ->where("reservee_id", $userId);
    
        // Second query for estate_reservations
        $builder2 = $this->db->table("estate_reservations AS er")
                      ->select("er.estate_id AS asset_id, er.reservee_id, er.estate_type AS asset_type, er.payment_option, er.reservation_status, 'estate' AS asset, ecs.payment_amount")
                      ->join("estate_cash_sales AS ecs", "ecs.estate_id = er.estate_id")
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
    
}