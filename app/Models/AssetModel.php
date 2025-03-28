<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    public function getAssetsById($userId)
    {
        // Connect to the database
        // $db = \Config\Database::connect();

        // First query for lot_reservations
        $builder1 = $this->db->table("lot_reservations AS lr")
            ->select("
        lr.id AS reservation_id,
        lr.lot_id AS asset_id, 
        lr.reservee_id, 
        lr.lot_type AS asset_type, 
        lr.payment_option, 
        lr.reservation_status, 
        'lot' AS asset, 
        COALESCE(i.down_payment, 0) AS down_payment,
        i.down_payment_status AS down_payment_status,
        COALESCE(cs.payment_amount, sm.payment_amount, i.monthly_payment, 0) AS payment_amount,
        CASE
            WHEN cs.payment_amount IS NOT NULL THEN 'cash_sale'
            WHEN sm.payment_amount IS NOT NULL THEN 'six_months'
            WHEN i.monthly_payment IS NOT NULL THEN 'installments'
            ELSE 'none'
        END AS payment_type")
            ->where("lr.reservee_id", $userId)
            ->join("cash_sales AS cs", "cs.lot_id = lr.lot_id", "left")
            ->join("six_months AS sm", "sm.lot_id = lr.lot_id", "left")
            ->join("installments AS i", "i.lot_id = lr.lot_id", "left");

        // Second query for estate_reservations (joins estate_cash_sales)
        $builder2 = $this->db->table("estate_reservations AS er")
            ->select("
            er.id AS reservation_id,
            er.estate_id AS asset_id, 
            er.reservee_id, 
            er.estate_type AS asset_type, 
            er.payment_option, 
            er.reservation_status, 
            'estate' AS asset, 
            COALESCE(ei.down_payment, 0) AS down_payment,
            ei.down_payment_status AS down_payment_status,
            COALESCE(ecs.payment_amount, esm.payment_amount, ei.monthly_payment, 0) AS payment_amount,
            CASE
                WHEN ecs.payment_amount IS NOT NULL THEN 'estate_cash_sale'
                WHEN esm.payment_amount IS NOT NULL THEN 'estate_six_months'
                WHEN ei.monthly_payment IS NOT NULL THEN 'estate_installments'
                ELSE 'none'
            END AS payment_type")
            ->where("er.reservee_id", $userId)
            ->join("estate_cash_sales AS ecs", "ecs.estate_id = er.estate_id", "left")
            ->join("estate_six_months AS esm", "esm.estate_id = er.estate_id", "left")
            ->join("estate_installments AS ei", "ei.estate_id = er.estate_id", "left");

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

    public function getOwnedAssets($userId)
    {
        $builder1 = $this->db->table("lots")
            ->select("
            lot_id AS asset_id,
            latitude_start,
            latitude_end,  
            longitude_start,
            longitude_end,
            NULL AS occupancy,
            NULL AS capacity")
            ->where("owner_id", $userId)
            ->where("owner_id IS NOT NULL", null, false)
            ->where("status", "Sold")
            ->where("owner_id IS NOT NULL", null, false)
            ->where("NOT EXISTS (SELECT 1 FROM burial_reservations WHERE asset_id = lot_id AND status != 'Cancelled')", null, false);

        $builder2 = $this->db->table("estates")
            ->select("
            estate_id AS asset_id,
            latitude_start,
            latitude_end,  
            longitude_start,
            longitude_end,
            occupancy,
            capacity")
            ->where("owner_id", $userId)
            ->where("status", "Sold")
            ->where("owner_id IS NOT NULL", null, false)
            ->where("occupancy < capacity", null, false)
            ->where("NOT EXISTS (SELECT 1 FROM burial_reservations WHERE asset_id = estate_id AND status != 'Cancelled')", null, false);

        $sql1 = $builder1->getCompiledSelect();
        $sql2 = $builder2->getCompiledSelect();

        $finalQuery = "$sql1 UNION $sql2";

        $query = $this->db->query($finalQuery);

        $result = $query->getResultArray();

        return !empty($result) ? $result : [];
    }


    // public function getOwnedAssets($userId)
    // {
    //     $builder1 = $this->db->table("lots")
    //         ->select("
    //     lot_id AS asset_id,
    //     latitude_start,
    //     latitude_end,  
    //     longitude_start,
    //     longitude_end,
    //     NULL AS occupancy,
    //     NULL AS capacity")
    //         ->where("owner_id", $userId)
    //         ->where("owner_id IS NOT NULL", null, false);

    //     $builder2 = $this->db->table("estates")
    //         ->select("
    //     estate_id AS asset_id,
    //     latitude_start,
    //     latitude_end,  
    //     longitude_start,
    //     longitude_end,
    //     occupancy,
    //     capacity")
    //         ->where("owner_id", $userId)
    //         ->where("owner_id IS NOT NULL", null, false)
    //         ->where("occupancy < capacity", null, false);

    //     $sql1 = $builder1->getCompiledSelect();
    //     $sql2 = $builder2->getCompiledSelect();

    //     $finalQuery = "$sql1 UNION $sql2";

    //     $query = $this->db->query($finalQuery);

    //     $result = $query->getResultArray();

    //     return !empty($result) ? $result : [];
    // }
}
