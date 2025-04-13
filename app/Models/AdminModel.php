<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";

    protected $allowedFields = ["email"];

    protected $useTimestamps = true;

    public function getOwnerAssetsCount($userId)
    {
        $lotsCount = $this->db->table('lots')
            ->where('owner_id', $userId)
            ->countAllResults();

        $estatesCount = $this->db->table('estates')
            ->where('owner_id', $userId)
            ->countAllResults();

        return $lotsCount + $estatesCount;
    }

    public function getScheduledMemorialServices($userId) {
        return $this->db->table("burial_reservations")
            ->where("reservee_id", $userId)
            ->whereNotIn("status", ["Completed", "Cancelled"])
            ->countAllResults();
    }

    public function getNextPaymentDueDate($userId) 
    {
        // Lot Reservations Query
        $lotQuery = $this->db->table('lot_reservations lr')
            ->select('COALESCE(csdd.due_date, sm.down_payment_due_date, sm.next_due_date, 
                              i.down_payment_due_date, i.next_due_date) as due_date')
            ->where('lr.reservee_id', $userId)
            ->where('lr.reservation_status', 'Confirmed')  // Added condition
            ->join('cash_sales cs', 'lr.id = cs.reservation_id', 'left')
            ->join('cash_sale_due_dates csdd', 'cs.id = csdd.cash_sale_id', 'left')
            ->join('six_months sm', 'sm.reservation_id = lr.id', 'left')
            ->join('installments i', 'i.reservation_id = lr.id', 'left')
            ->where('COALESCE(csdd.due_date, sm.down_payment_due_date, sm.next_due_date, 
                             i.down_payment_due_date, i.next_due_date) >=', date('Y-m-d'))
            ->where('COALESCE(csdd.due_date, sm.down_payment_due_date, sm.next_due_date, 
                             i.down_payment_due_date, i.next_due_date) IS NOT NULL');

        // Estate Reservations Query
        $estateQuery = $this->db->table('estate_reservations er')
            ->select('COALESCE(csdd.due_date, sm.down_payment_due_date, sm.next_due_date, 
                              i.down_payment_due_date, i.next_due_date) as due_date')
            ->where('er.reservee_id', $userId)
            ->where('er.reservation_status', 'Confirmed')  // Added condition
            ->join('estate_cash_sales cs', 'er.id = cs.reservation_id', 'left')
            ->join('estate_cash_sale_due_dates csdd', 'cs.id = csdd.cash_sale_id', 'left')
            ->join('estate_six_months sm', 'sm.reservation_id = er.id', 'left')
            ->join('estate_installments i', 'i.reservation_id = er.id', 'left')
            ->where('COALESCE(csdd.due_date, sm.down_payment_due_date, sm.next_due_date, 
                             i.down_payment_due_date, i.next_due_date) >=', date('Y-m-d'))
            ->where('COALESCE(csdd.due_date, sm.down_payment_due_date, sm.next_due_date, 
                             i.down_payment_due_date, i.next_due_date) IS NOT NULL');

        // Combine and get earliest due date
        $result = $lotQuery->union($estateQuery)
            ->orderBy('due_date', 'ASC')
            ->limit(1)
            ->get()
            ->getRow();

        return $result ? $result->due_date : null;
    }

    public function getLastTwoPayments($userId): array 
    {
        $query = "SELECT * FROM (
            SELECT 
                'Cash Sale' AS payment_option,
                'lot' AS asset_type,
                cs.lot_id AS asset_id, 
                cs.payment_amount AS amount, 
                cs.payment_status AS status, 
                cs.receipt_path, 
                cs.payment_date AS date,
                lr.reference_number,
                CONCAT('Payment for Lot ', l.lot_id) as description
            FROM cash_sales cs
            JOIN lot_reservations lr ON cs.reservation_id = lr.id
            JOIN lots l ON l.id = lr.lot_id
            WHERE lr.reservee_id = ? AND cs.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Cash Sale' AS payment_option,
                'estate' AS asset_type,
                cs.estate_id AS asset_id, 
                cs.payment_amount AS amount, 
                cs.payment_status AS status, 
                cs.receipt_path, 
                cs.payment_date AS date,
                er.reference_number,
                CONCAT('Payment for Estate ', e.estate_id) as description
            FROM estate_cash_sales cs
            JOIN estate_reservations er ON cs.reservation_id = er.id
            JOIN estates e ON e.id = er.estate_id
            WHERE er.reservee_id = ? AND cs.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                '6 Months' AS payment_option,
                'lot' AS asset_type,
                sm.lot_id AS asset_id, 
                smp.payment_amount AS amount, 
                smp.payment_status AS status, 
                smp.receipt_path, 
                smp.payment_date AS date,
                sm.reference_number,
                CONCAT('Payment for Lot ', l.lot_id) as description
            FROM six_months sm
            JOIN lot_reservations lr ON sm.reservation_id = lr.id
            JOIN lots l ON l.id = lr.lot_id
            JOIN six_months_payments smp ON sm.id = smp.six_months_id
            WHERE lr.reservee_id = ? AND smp.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                '6 Months' AS payment_option,
                'estate' AS asset_type,
                sm.estate_id AS asset_id, 
                smp.payment_amount AS amount, 
                smp.payment_status AS status, 
                smp.receipt_path, 
                smp.payment_date AS date,
                sm.reference_number,
                CONCAT('Payment for Estate ', e.estate_id) as description
            FROM estate_six_months sm
            JOIN estate_reservations er ON sm.reservation_id = er.id
            JOIN estates e ON e.id = er.estate_id
            JOIN estate_six_months_payments smp ON sm.id = smp.six_months_id
            WHERE er.reservee_id = ? AND smp.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Installment' AS payment_option,
                'lot' AS asset_type,
                i.lot_id AS asset_id, 
                ip.payment_amount AS amount, 
                ip.payment_status AS status, 
                ip.receipt_path, 
                ip.payment_date AS date,
                i.reference_number,
                CONCAT('Payment for Lot ', l.lot_id) as description
            FROM installments i
            JOIN lot_reservations lr ON i.reservation_id = lr.id
            JOIN lots l ON l.id = lr.lot_id
            JOIN installment_payments ip ON i.id = ip.installment_id
            WHERE lr.reservee_id = ? AND ip.payment_date IS NOT NULL

            UNION ALL

            SELECT 
                'Installment' AS payment_option,
                'estate' AS asset_type,
                i.estate_id AS asset_id, 
                ip.payment_amount AS amount, 
                ip.payment_status AS status, 
                ip.receipt_path, 
                ip.payment_date AS date,
                i.reference_number,
                CONCAT('Payment for Estate ', e.estate_id) as description
            FROM estate_installments i
            JOIN estate_reservations er ON i.reservation_id = er.id
            JOIN estates e ON e.id = er.estate_id
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
                payment_amount AS amount,
                payment_status AS status,
                receipt_path,
                payment_date AS date,
                reference_number,
                'Payment for Burial Service' as description
            FROM burial_reservations
            WHERE reservee_id = ? AND payment_date IS NOT NULL
        ) AS combined_payments 
        ORDER BY date DESC 
        LIMIT 2";

        try {
            $result = $this->db->query($query, array_fill(0, 7, $userId))->getResultArray();
            
            // If no results found, return empty array
            if (empty($result)) {
                return [];
            }

            // Format each payment record
            return array_map(function($payment) {
                return [
                    'payment_option' => $payment['payment_option'],
                    'asset_type' => $payment['asset_type'],
                    'asset_id' => $payment['asset_id'],
                    'amount' => floatval($payment['amount']),
                    'status' => $payment['status'],
                    'receipt_path' => $payment['receipt_path'],
                    'date' => $payment['date'],
                    'reference_number' => $payment['reference_number'],
                    'description' => $payment['description']
                ];
            }, $result);
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to get payments: ' . $e->getMessage());
            return [];
        }
    }

    public function getPaymentHistory($userId): array 
    {
        $months = [];
        $amounts = [];
    
        for ($i = 5; $i >= 0; $i--) {
            $startDate = date('Y-m-01', strtotime("-$i month"));
            $endDate = date('Y-m-t', strtotime("-$i month"));
    
            try {
                // Lot Payments
                $lotPayments = $this->db->table('lot_reservations lr')
                    ->select('COALESCE(SUM(cs.payment_amount), 0) + COALESCE(SUM(smp.payment_amount), 0) + COALESCE(SUM(ip.payment_amount), 0) as total')
                    ->join('cash_sales cs', 'cs.reservation_id = lr.id AND cs.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                    ->join('six_months sm', 'sm.reservation_id = lr.id', 'left')
                    ->join('six_months_payments smp', 'smp.six_months_id = sm.id AND smp.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                    ->join('installments i', 'i.reservation_id = lr.id', 'left')
                    ->join('installment_payments ip', 'ip.installment_id = i.id AND ip.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                    ->where('lr.reservee_id', $userId)
                    ->whereNotIn('lr.reservation_status', ['Pending', 'Cancelled'])
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    
                // Estate Payments
                $estatePayments = $this->db->table('estate_reservations er')
                    ->select('COALESCE(SUM(cs.payment_amount), 0) + COALESCE(SUM(smp.payment_amount), 0) + COALESCE(SUM(ip.payment_amount), 0) as total')
                    ->join('estate_cash_sales cs', 'cs.reservation_id = er.id AND cs.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                    ->join('estate_six_months sm', 'sm.reservation_id = er.id', 'left')
                    ->join('estate_six_months_payments smp', 'smp.six_months_id = sm.id AND smp.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                    ->join('estate_installments i', 'i.reservation_id = er.id', 'left')
                    ->join('estate_installment_payments ip', 'ip.installment_id = i.id AND ip.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                    ->where('er.reservee_id', $userId)
                    ->whereNotIn('er.reservation_status', ['Pending', 'Cancelled'])
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    
                // Burial Payments
                $burialPayments = $this->db->table('burial_reservations')
                    ->select('COALESCE(SUM(payment_amount), 0) as total')
                    ->where('reservee_id', $userId)
                    ->where('payment_date >=', $startDate)
                    ->where('payment_date <=', $endDate)
                    ->whereNotIn('status', ['Pending', 'Cancelled'])
                    ->get()
                    ->getRow()
                    ->total ?? 0;
    
                $months[] = date('M', strtotime($startDate));
                $amounts[] = floatval($lotPayments + $estatePayments + $burialPayments);
            } catch (\Exception $e) {
                log_message('error', 'Failed to get payment history: ' . $e->getMessage());
                continue;
            }
        }
    
        return [
            'months' => $months,
            'amounts' => $amounts
        ];
    }

    public function getAssetDistribution($userId)
    {
        $lotCount = $this->db->table('lot_reservations lr')
            ->where('lr.reservee_id', $userId)
            ->whereNotIn('lr.reservation_status', ['Pending', 'Cancelled'])
            ->countAllResults();

        $estateCount = $this->db->table('estate_reservations er')
            ->where('er.reservee_id', $userId)
            ->whereNotIn('er.reservation_status', ['Pending', 'Cancelled'])
            ->countAllResults();

        return [
            'counts' => [$lotCount, $estateCount]
        ];
    }
}
