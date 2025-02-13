<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model {
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
    
}