<?php

namespace App\Models;

use CodeIgniter\Model;

class LotReservationModel extends Model {
    protected $table = 'lot_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'lot_id', 'reservee_id', 'lot_type', 'payment_option', 'reservation_status']; // Define the allowed fields

    public function updateLotPaymentOption($lotId, $reserveeId, $paymentOption, $reservationStatus) {
        // Step 1: Find the latest reservation based on `created_at`
        $latestReservation = $this->db->table($this->table)
            ->where("lot_id", $lotId)
            ->where("reservee_id", $reserveeId)
            ->where("reservation_status", "Confirmed")
            ->orderBy("created_at", "DESC") // Order by `created_at` in descending order
            ->limit(1) // Limit to the latest record
            ->get()
            ->getRow();
    
        // Step 2: If a reservation is found, update it
        if ($latestReservation) {
            return $this->db->table($this->table)
                ->where("id", $latestReservation->id) // Use the ID of the latest reservation
                ->update([
                    "payment_option" => $paymentOption,
                    "reservation_status" => $reservationStatus
                ]);
        }
    
        // Step 3: If no reservation is found, return false or handle accordingly
        return false;
    }

    public function setCashSalePayment($lotId, $paymentAmount) {
        $data = [
            "lot_id" => $lotId,
            "payment_amount" => $paymentAmount
        ];

        return $this->db->table("cash_sales")->insert($data);
    }

    public function setCashSaleDueDate($lotId, $dueDate) {
        $data = [
            "lot_id" => $lotId,
            "due_date" => $dueDate
        ];

        return $this->db->table("cash_sale_due_dates")->insert($data);
    }

    public function setSixMonthsPayment($lotId, $paymentAmount) {
        $data = [
            "lot_id" => $lotId,
            "payment_amount" => $paymentAmount
        ];

        return $this->db->table("six_months")->insert($data);
    }

    public function setSixMonthsDueDate($lotId, $dueDate) {
        $data = [
            "lot_id" => $lotId,
            "due_date" => $dueDate
        ];

        return $this->db->table("six_months_due_dates")->insert($data);
    }

    public function setInstallmentPayment($lotId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $interestRate) {
        $data = [
            "lot_id" => $lotId,
            "term_years" => $termYears,
            "down_payment" => $downPayment,
            "down_payment_due_date" => $downPaymentDueDate,
            "total_amount" => $totalAmount,
            "monthly_payment" => $paymentAmount,
            "interest_rate" => $interestRate
        ];

        return $this->db->table("installments")->insert($data);
    }
}
