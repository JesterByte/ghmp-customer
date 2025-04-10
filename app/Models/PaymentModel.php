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
    
            SELECT 'Cash Sale' AS payment_option, cs.estate_id AS asset_id, cs.payment_amount, cs.payment_status, cs.receipt_path, cs.payment_date
            FROM estate_cash_sales cs
            JOIN estate_reservations er ON cs.reservation_id = er.id
            WHERE cs.payment_status = 'Paid' AND er.reservee_id = ?
    
            UNION ALL
    
            SELECT '6 Months' AS payment_option, sm.lot_id AS asset_id, smp.payment_amount, sm.payment_status, smp.receipt_path, smp.payment_date
            FROM six_months sm
            JOIN lot_reservations lr ON sm.reservation_id = lr.id
            JOIN six_months_payments smp ON sm.id = smp.six_months_id
            WHERE lr.reservee_id = ?
            GROUP BY sm.lot_id
    
            UNION ALL
    
            SELECT '6 Months' AS payment_option, sm.estate_id AS asset_id, smp.payment_amount, sm.payment_status, smp.receipt_path, smp.payment_date
            FROM estate_six_months sm
            JOIN estate_reservations er ON sm.reservation_id = er.id
            JOIN estate_six_months_payments smp ON sm.id = smp.six_months_id
            WHERE er.reservee_id = ?
            GROUP BY sm.estate_id
    
            UNION ALL
    
            SELECT 'Installment' AS payment_option, i.lot_id AS asset_id, ip.payment_amount, ip.payment_status, ip.receipt_path, ip.payment_date
            FROM installments i
            JOIN lot_reservations lr ON i.reservation_id = lr.id
            JOIN installment_payments ip ON i.id = ip.installment_id
            WHERE lr.reservee_id = ?
            GROUP BY i.lot_id
    
            UNION ALL
    
            SELECT 'Installment' AS payment_option, i.estate_id AS asset_id, ip.payment_amount, ip.payment_status, ip.receipt_path, ip.payment_date
            FROM estate_installments i
            JOIN estate_reservations er ON i.reservation_id = er.id
            JOIN estate_installment_payments ip ON i.id = ip.installment_id
            WHERE er.reservee_id = ?
            GROUP BY i.estate_id";

        return $this->db->query($query, [$userId, $userId, $userId, $userId, $userId, $userId])->getResultArray();
    }
}
