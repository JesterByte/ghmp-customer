<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model {
    public function getAssetsById($userId) {
        // Connect to the database
        // $db = \Config\Database::connect();
    
        // First query for lot_reservations
        $builder1 = $this->db->table("lot_reservations")
        ->select("
        lot_reservations.lot_id AS asset_id, 
        lot_reservations.reservee_id, 
        lot_reservations.lot_type AS asset_type, 
        lot_reservations.payment_option, 
        lot_reservations.reservation_status, 
        'lot' AS asset, 
        COALESCE(installments.down_payment, 0) AS down_payment,
        installments.down_payment_status AS down_payment_status,
        COALESCE(cash_sales.payment_amount, six_months.payment_amount, installments.monthly_payment, 0) AS payment_amount,
        CASE
            WHEN cash_sales.payment_amount IS NOT NULL THEN 'cash_sale'
            WHEN six_months.payment_amount IS NOT NULL THEN 'six_months'
            WHEN installments.monthly_payment IS NOT NULL THEN 'installments'
            ELSE 'none'
        END AS payment_type")
        ->where("lot_reservations.reservee_id", $userId)
        ->join("cash_sales", "cash_sales.lot_id = lot_reservations.lot_id", "left")
        ->join("six_months", "six_months.lot_id = lot_reservations.lot_id", "left")
        ->join("installments", "installments.lot_id = lot_reservations.lot_id", "left");

        // Second query for estate_reservations (joins estate_cash_sales)
        $builder2 = $this->db->table("estate_reservations")
            ->select("
            estate_reservations.estate_id AS asset_id, 
            estate_reservations.reservee_id, 
            estate_reservations.estate_type AS asset_type, 
            estate_reservations.payment_option, 
            estate_reservations.reservation_status, 
            'estate' AS asset, 
            COALESCE(estate_installments.down_payment, 0) AS down_payment,
            estate_installments.down_payment_status AS down_payment_status,
            COALESCE(estate_cash_sales.payment_amount, estate_six_months.payment_amount, estate_installments.monthly_payment, 0) AS payment_amount,
            CASE
                WHEN estate_cash_sales.payment_amount IS NOT NULL THEN 'estate_cash_sale'
                WHEN estate_six_months.payment_amount IS NOT NULL THEN 'estate_six_months'
                WHEN estate_installments.monthly_payment IS NOT NULL THEN 'estate_installments'
                ELSE 'none'
            END AS payment_type")
            ->where("estate_reservations.reservee_id", $userId)
            ->join("estate_cash_sales", "estate_cash_sales.estate_id = estate_reservations.estate_id", "left")
            ->join("estate_six_months", "estate_six_months.estate_id = estate_reservations.estate_id", "left")
            ->join("estate_installments", "estate_installments.estate_id = estate_reservations.estate_id", "left");
    
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

    public function getOwnedAssets($userId) {
        $builder1 = $this->db->table("lots")
        ->select("
        lot_id AS asset_id,
        latitude_start,
        latitude_end,  
        longitude_start,
        longitude_end")
        ->where("owner_id", $userId)
        ->where("owner_id IS NOT NULL");

        $builder2 = $this->db->table("estates")
        ->select("
        estate_id AS asset_id,
        latitude_start,
        latitude_end,  
        longitude_start,
        longitude_end")
        ->where("owner_id", $userId)
        ->where("owner_id IS NOT NULL");

        $sql1 = $builder1->getCompiledSelect();
        $sql2 = $builder2->getCompiledSelect();

        $finalQuery = "$sql1 UNION $sql2";

        $query = $this->db->query($finalQuery);

        $result = $query->getResultArray();

        return !empty($result) ? $result : [];
    }
    
}