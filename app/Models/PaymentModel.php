<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model {
    public function getAllPayments() {
        $query = "
            SELECT 'Cash Sale' AS payment_option, lot_id AS asset_id, payment_amount, payment_status, receipt_path, payment_date
            FROM cash_sales
            WHERE payment_status = 'Paid'
            UNION ALL
            SELECT 'Cash Sale' AS payment_option, estate_id AS asset_id, payment_amount, payment_status, receipt_path, payment_date
            FROM estate_cash_sales
            WHERE payment_status = 'Paid'
            UNION ALL
            SELECT '6 Months' AS payment_option, lot_id AS asset_id, payment_amount, payment_status, receipt_path, payment_date
            FROM six_months
            WHERE payment_status = 'Paid'
            UNION ALL
            SELECT '6 Months' AS payment_option, estate_id AS asset_id, payment_amount, payment_status, receipt_path, payment_date
            FROM estate_six_months
            WHERE payment_status = 'Paid'
            UNION ALL
            SELECT 'Installment' AS payment_option, i.lot_id AS asset_id, ip.payment_amount, ip.payment_status, ip.receipt_path, ip.payment_date
            FROM installments i
            JOIN installment_payments ip ON i.id = ip.installment_id
            GROUP BY i.lot_id
            UNION ALL
            SELECT 'Installment' AS payment_option, ei.estate_id AS asset_id, eip.payment_amount, eip.payment_status, eip.receipt_path, eip.payment_date
            FROM estate_installments ei
            JOIN estate_installment_payments eip ON ei.id = eip.installment_id
            GROUP BY ei.estate_id
        ";
    
        return $this->db->query($query)->getResultArray();
    }
    
}