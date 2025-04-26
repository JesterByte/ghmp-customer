<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    public function getAssetsById($userId)
    {
        // Lot reservations
        $builder1 = $this->db->table("lot_reservations AS lr")
            ->select("
                lr.created_at,
                lr.id AS reservation_id,
                lr.lot_id AS asset_id, 
                lr.reservee_id, 
                lr.lot_type AS asset_type, 
                lr.payment_option, 
                lr.reservation_status, 
                lr.cancellation_reason,
                'lot' AS asset, 
                COALESCE(sm.down_payment, i.down_payment, 0) AS down_payment,
                COALESCE(sm.down_payment_status, i.down_payment_status, 0) AS down_payment_status,
                COALESCE(cs.payment_amount, sm.monthly_payment, i.monthly_payment, 0) AS payment_amount,
                CASE
                    WHEN cs.payment_amount IS NOT NULL THEN 'cash_sale'
                    WHEN sm.monthly_payment IS NOT NULL THEN 'six_months'
                    WHEN i.monthly_payment IS NOT NULL THEN 'installments'
                    ELSE 'none'
                END AS payment_type,
                rr.status AS restructure_status,
                rr.discounted_price
            ")
            ->where("lr.reservee_id", $userId)
            ->join("cash_sales AS cs", "cs.lot_id = lr.lot_id", "left")
            ->join("six_months AS sm", "sm.lot_id = lr.lot_id", "left")
            ->join("installments AS i", "i.lot_id = lr.lot_id", "left")
            ->join("restructure_requests AS rr", "rr.reservation_id = lr.id AND rr.asset_id = lr.lot_id AND rr.status = 'Approved'", "left");
    
        // Estate reservations
        $builder2 = $this->db->table("estate_reservations AS er")
            ->select("
                er.created_at,
                er.id AS reservation_id,
                er.estate_id AS asset_id, 
                er.reservee_id, 
                er.estate_type AS asset_type, 
                er.payment_option, 
                er.reservation_status, 
                er.cancellation_reason,
                'estate' AS asset, 
                COALESCE(sm.down_payment, i.down_payment, 0) AS down_payment,
                COALESCE(sm.down_payment_status, i.down_payment_status, 0) AS down_payment_status,
                COALESCE(cs.payment_amount, sm.monthly_payment, i.monthly_payment, 0) AS payment_amount,
                CASE
                    WHEN cs.payment_amount IS NOT NULL THEN 'estate_cash_sale'
                    WHEN sm.monthly_payment IS NOT NULL THEN 'estate_six_months'
                    WHEN i.monthly_payment IS NOT NULL THEN 'estate_installments'
                    ELSE 'none'
                END AS payment_type,
                rr.status AS restructure_status,
                rr.discounted_price
            ")
            ->where("er.reservee_id", $userId)
            ->join("estate_cash_sales AS cs", "cs.estate_id = er.estate_id", "left")
            ->join("estate_six_months AS sm", "sm.estate_id = er.estate_id", "left")
            ->join("estate_installments AS i", "i.estate_id = er.estate_id", "left")
            ->join("restructure_requests AS rr", "rr.reservation_id = er.id AND rr.asset_id = er.estate_id AND rr.status = 'Approved'", "left");
    
        // Combine and execute
        $sql1 = $builder1->getCompiledSelect();
        $sql2 = $builder2->getCompiledSelect();
    
        $finalQuery = "$sql1 UNION $sql2";
        $query = $this->db->query($finalQuery);
    
        return $query->getResultArray();
    }    

    public function getOwnedAssets($userId)
    {
        $builder1 = $this->db->table("lots")
            ->select("
            'lot' AS asset_type,
            lot_id AS asset_id,
            latitude_start,
            latitude_end,  
            longitude_start,
            longitude_end,
            status,
            NULL AS occupancy,
            NULL AS capacity")
            ->where("owner_id", $userId)
            ->where("owner_id IS NOT NULL", null, false)
            ->where("status", "Sold");

        $builder2 = $this->db->table("estates")
            ->select("
            'estate' AS asset_type,
            estate_id AS asset_id,
            latitude_start,
            latitude_end,  
            longitude_start,
            longitude_end,
            status,
            occupancy,
            capacity")
            ->where("owner_id", $userId)
            ->groupStart()
            ->where("status", "Sold")
            ->orWhere("status", "Sold and Occupied")
            ->groupEnd()
            ->where("owner_id IS NOT NULL", null, false);

        $sql1 = $builder1->getCompiledSelect();
        $sql2 = $builder2->getCompiledSelect();

        $finalQuery = "$sql1 UNION $sql2";

        $query = $this->db->query($finalQuery);

        $result = $query->getResultArray();

        return !empty($result) ? $result : [];
    }
}
