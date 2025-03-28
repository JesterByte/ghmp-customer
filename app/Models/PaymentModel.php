<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    public function getAllPayments($userId)
    {
        $query = "
            SELECT 'Cash Sale' AS payment_option, cs.lot_id AS asset_id, cs.payment_amount, cs.payment_status, cs.receipt_path, cs.payment_date
            FROM cash_sales cs
            JOIN lot_reservations lr ON cs.reservation_id = lr.id
            WHERE cs.payment_status = 'Paid' AND lr.reservee_id = ?
    
            UNION ALL
    
            SELECT 'Cash Sale' AS payment_option, ecs.estate_id AS asset_id, ecs.payment_amount, ecs.payment_status, ecs.receipt_path, ecs.payment_date
            FROM estate_cash_sales ecs
            JOIN estate_reservations er ON ecs.reservation_id = er.id
            WHERE ecs.payment_status = 'Paid' AND er.reservee_id = ?
    
            UNION ALL
    
            SELECT '6 Months' AS payment_option, sm.lot_id AS asset_id, sm.payment_amount, sm.payment_status, sm.receipt_path, sm.payment_date
            FROM six_months sm
            JOIN lot_reservations lr ON sm.reservation_id = lr.id
            WHERE sm.payment_status = 'Paid' AND lr.reservee_id = ?
    
            UNION ALL
    
            SELECT '6 Months' AS payment_option, esm.estate_id AS asset_id, esm.payment_amount, esm.payment_status, esm.receipt_path, esm.payment_date
            FROM estate_six_months esm
            JOIN estate_reservations er ON esm.reservation_id = er.id
            WHERE esm.payment_status = 'Paid' AND er.reservee_id = ?
    
            UNION ALL
    
            SELECT 'Installment' AS payment_option, i.lot_id AS asset_id, ip.payment_amount, ip.payment_status, ip.receipt_path, ip.payment_date
            FROM installments i
            JOIN lot_reservations lr ON i.reservation_id = lr.id
            JOIN installment_payments ip ON i.id = ip.installment_id
            WHERE lr.reservee_id = ?
            GROUP BY i.lot_id
    
            UNION ALL
    
            SELECT 'Installment' AS payment_option, ei.estate_id AS asset_id, eip.payment_amount, eip.payment_status, eip.receipt_path, eip.payment_date
            FROM estate_installments ei
            JOIN estate_reservations er ON ei.reservation_id = er.id
            JOIN estate_installment_payments eip ON ei.id = eip.installment_id
            WHERE er.reservee_id = ?
            GROUP BY ei.estate_id";

        return $this->db->query($query, [$userId, $userId, $userId, $userId, $userId, $userId])->getResultArray();
    }
}
