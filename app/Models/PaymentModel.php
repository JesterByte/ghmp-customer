<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    public function getAllPayments(int $userId): array
    {
        $query = "SELECT * FROM (
            SELECT 
                'Cash Sale' AS payment_option,
                'lot' AS asset_type,
                cs.lot_id AS asset_id, 
                cs.payment_amount, 
                cs.payment_status, 
                cs.receipt_path, 
                cs.payment_date,
                lr.reference_number
            FROM cash_sales cs
            JOIN lot_reservations lr ON cs.reservation_id = lr.id
            WHERE lr.reservee_id = ? AND cs.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Cash Sale' AS payment_option,
                'estate' AS asset_type,
                cs.estate_id AS asset_id, 
                cs.payment_amount, 
                cs.payment_status, 
                cs.receipt_path, 
                cs.payment_date,
                er.reference_number
            FROM estate_cash_sales cs
            JOIN estate_reservations er ON cs.reservation_id = er.id
            WHERE er.reservee_id = ? AND cs.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                '6 Months' AS payment_option,
                'lot' AS asset_type,
                sm.lot_id AS asset_id, 
                smp.payment_amount, 
                smp.payment_status, 
                smp.receipt_path, 
                smp.payment_date,
                sm.reference_number
            FROM six_months sm
            JOIN lot_reservations lr ON sm.reservation_id = lr.id
            JOIN six_months_payments smp ON sm.id = smp.six_months_id
            WHERE lr.reservee_id = ? AND smp.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                '6 Months' AS payment_option,
                'estate' AS asset_type,
                sm.estate_id AS asset_id, 
                smp.payment_amount, 
                smp.payment_status, 
                smp.receipt_path, 
                smp.payment_date,
                sm.reference_number
            FROM estate_six_months sm
            JOIN estate_reservations er ON sm.reservation_id = er.id
            JOIN estate_six_months_payments smp ON sm.id = smp.six_months_id
            WHERE er.reservee_id = ? AND smp.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Installment' AS payment_option,
                'lot' AS asset_type,
                i.lot_id AS asset_id, 
                ip.payment_amount, 
                ip.payment_status, 
                ip.receipt_path, 
                ip.payment_date,
                i.reference_number
            FROM installments i
            JOIN lot_reservations lr ON i.reservation_id = lr.id
            JOIN installment_payments ip ON i.id = ip.installment_id
            WHERE lr.reservee_id = ? AND ip.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Installment' AS payment_option,
                'estate' AS asset_type,
                i.estate_id AS asset_id, 
                ip.payment_amount, 
                ip.payment_status, 
                ip.receipt_path, 
                ip.payment_date,
                i.reference_number
            FROM estate_installments i
            JOIN estate_reservations er ON i.reservation_id = er.id
            JOIN estate_installment_payments ip ON i.id = ip.installment_id
            WHERE er.reservee_id = ? AND ip.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Burial' AS payment_option,
                CASE 
                    WHEN asset_id REGEXP '^[0-9]' THEN 'lot'
                    WHEN asset_id LIKE 'E%' THEN 'estate'
                END AS asset_type,
                asset_id,
                payment_amount,
                payment_status,
                receipt_path,
                payment_date,
                reference_number
            FROM burial_reservations
            WHERE reservee_id = ? AND payment_date IS NOT NULL
        ) AS combined_payments 
        ORDER BY payment_date DESC";

        try {
            return $this->db->query($query, array_fill(0, 7, $userId))->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Failed to get payments: ' . $e->getMessage());
            return [];
        }
    }
}
