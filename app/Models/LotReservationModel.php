<?php

namespace App\Models;

use CodeIgniter\Model;

class LotReservationModel extends Model
{
    protected $table = 'lot_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'lot_id', 'reservee_id', 'lot_type', 'payment_option', 'reservation_status', 'reference_number', 'created_at', 'updated_at']; // Define the allowed fields
    protected $useTimestamps = true;

    public function updateLotPaymentOption($reservationId, $lotId, $reserveeId, $paymentOption, $reservationStatus)
    {
        // Step 1: Find the latest reservation based on `created_at`
        $latestReservation = $this->db->table($this->table)
            ->where("id", $reservationId)
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

    // public function setCashSalePayment($reservationId, $lotId, $paymentAmount) {
    //     $data = [
    //         "reservation_id" => $reservationId,
    //         "lot_id" => $lotId,
    //         "payment_amount" => $paymentAmount
    //     ];

    //     return $this->db->table("cash_sales")->insert($data);
    // }

    public function setCashSalePayment($reservationId, $lotId, $paymentAmount)
    {
        $data = [
            "reservation_id" => $reservationId,
            "lot_id" => $lotId,
            "payment_amount" => $paymentAmount
        ];

        $this->db->table("cash_sales")->insert($data);

        $insertedId = $this->db->insertID();

        return $insertedId;
    }


    public function setCashSaleDueDate($cashSaleId, $lotId, $dueDate)
    {
        $data = [
            "cash_sale_id" => $cashSaleId,
            "lot_id" => $lotId,
            "due_date" => $dueDate
        ];

        return $this->db->table("cash_sale_due_dates")->insert($data);
    }

    // public function setSixMonthsPayment($reservationId, $lotId, $paymentAmount)
    // {
    //     $data = [
    //         "reservation_id" => $reservationId,
    //         "lot_id" => $lotId,
    //         "payment_amount" => $paymentAmount
    //     ];

    //     return $this->db->table("six_months")->insert($data);
    // }

    // public function setSixMonthsPayment($reservationId, $lotId, $paymentAmount)
    // {
    //     $data = [
    //         "reservation_id" => $reservationId,
    //         "lot_id" => $lotId,
    //         "payment_amount" => $paymentAmount
    //     ];

    //     $this->db->table("six_months")->insert($data);

    //     $insertedId = $this->db->insertID();

    //     return $insertedId; 
    // }

    // public function setSixMonthsDueDate($sixMonthsId, $lotId, $dueDate)
    // {
    //     $data = [
    //         "six_months_id" => $sixMonthsId,
    //         "lot_id" => $lotId,
    //         "due_date" => $dueDate
    //     ];

    //     return $this->db->table("six_months_due_dates")->insert($data);
    // }

    public function setSixMonthsPayment($reservationId, $lotId, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount)
    {
        $data = [
            "reservation_id" => $reservationId,
            "lot_id" => $lotId,
            "down_payment" => $downPayment,
            "down_payment_due_date" => $downPaymentDueDate,
            "total_amount" => $totalAmount,
            "monthly_payment" => $paymentAmount
        ];

        return $this->db->table("six_months")->insert($data);
    }

    public function setInstallmentPayment($reservationId, $lotId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $interestRate)
    {
        $data = [
            "reservation_id" => $reservationId,
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
