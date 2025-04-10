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

    public function getLastTwoPayments($userId) 
    {
        // Lot Payments Query
        $lotPayments = $this->db->table('lot_reservations lr')
            ->select('COALESCE(cs.payment_date, smp.payment_date, ip.payment_date) as date,
                     CONCAT("Payment for Lot ", l.lot_id) as description,
                     COALESCE(cs.payment_amount, smp.payment_amount, ip.payment_amount) as amount,
                     "Paid" as status')
            ->join('lots l', 'l.id = lr.lot_id')
            ->join('cash_sales cs', 'cs.reservation_id = lr.id', 'left')
            ->join('six_months sm', 'sm.reservation_id = lr.id', 'left')
            ->join('six_months_payments smp', 'smp.six_months_id = sm.id', 'left')
            ->join('installments i', 'i.reservation_id = lr.id', 'left')
            ->join('installment_payments ip', 'ip.installment_id = i.id', 'left')
            ->where('lr.reservee_id', $userId)
            ->where('lr.reservation_status', 'Confirmed')
            ->where('COALESCE(cs.payment_date, smp.payment_date, ip.payment_date) IS NOT NULL');

        // Estate Payments Query
        $estatePayments = $this->db->table('estate_reservations er')
            ->select('COALESCE(cs.payment_date, smp.payment_date, ip.payment_date) as date,
                     CONCAT("Payment for Estate ", e.estate_id) as description,
                     COALESCE(cs.payment_amount, smp.payment_amount, ip.payment_amount) as amount,
                     "Paid" as status')
            ->join('estates e', 'e.id = er.estate_id')
            ->join('estate_cash_sales cs', 'cs.reservation_id = er.id', 'left')
            ->join('estate_six_months sm', 'sm.reservation_id = er.id', 'left')
            ->join('estate_six_months_payments smp', 'smp.six_months_id = sm.id', 'left')
            ->join('estate_installments i', 'i.reservation_id = er.id', 'left')
            ->join('estate_installment_payments ip', 'ip.installment_id = i.id', 'left')
            ->where('er.reservee_id', $userId)
            ->where('er.reservation_status', 'Confirmed')
            ->where('COALESCE(cs.payment_date, smp.payment_date, ip.payment_date) IS NOT NULL');

        // Combine queries and get latest 2 payments
        return $lotPayments->union($estatePayments)
            ->orderBy('date', 'DESC')
            ->limit(2)
            ->get()
            ->getResult();
    }

    public function getPaymentHistory($userId) 
    {
        $months = [];
        $amounts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $startDate = date('Y-m-01', strtotime("-$i month"));
            $endDate = date('Y-m-t', strtotime("-$i month"));
            
            // Lot Payments
            $lotPayments = $this->db->table('lot_reservations lr')
                ->select('COALESCE(SUM(cs.payment_amount), 0) + COALESCE(SUM(smp.payment_amount), 0) + COALESCE(SUM(ip.payment_amount), 0) as total')
                ->join('cash_sales cs', 'cs.reservation_id = lr.id AND cs.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                ->join('six_months sm', 'sm.reservation_id = lr.id', 'left')
                ->join('six_months_payments smp', 'smp.six_months_id = sm.id AND smp.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                ->join('installments i', 'i.reservation_id = lr.id', 'left')
                ->join('installment_payments ip', 'ip.installment_id = i.id AND ip.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                ->where('lr.reservee_id', $userId)
                ->where('lr.reservation_status', 'Confirmed')
                ->get()
                ->getRow()
                ->total;

            // Estate Payments
            $estatePayments = $this->db->table('estate_reservations er')
                ->select('COALESCE(SUM(cs.payment_amount), 0) + COALESCE(SUM(smp.payment_amount), 0) + COALESCE(SUM(ip.payment_amount), 0) as total')
                ->join('estate_cash_sales cs', 'cs.reservation_id = er.id AND cs.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                ->join('estate_six_months sm', 'sm.reservation_id = er.id', 'left')
                ->join('estate_six_months_payments smp', 'smp.six_months_id = sm.id AND smp.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                ->join('estate_installments i', 'i.reservation_id = er.id', 'left')
                ->join('estate_installment_payments ip', 'ip.installment_id = i.id AND ip.payment_date BETWEEN "' . $startDate . '" AND "' . $endDate . '"', 'left')
                ->where('er.reservee_id', $userId)
                ->where('er.reservation_status', 'Confirmed')
                ->get()
                ->getRow()
                ->total;

            $months[] = date('M', strtotime($startDate));
            $amounts[] = $lotPayments + $estatePayments;
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
            ->where('lr.reservation_status', 'Confirmed')
            ->countAllResults();

        $estateCount = $this->db->table('estate_reservations er')
            ->where('er.reservee_id', $userId)
            ->where('er.reservation_status', 'Confirmed')
            ->countAllResults();

        return [
            'counts' => [$lotCount, $estateCount]
        ];
    }
}
