<?php

namespace App\Models;

use CodeIgniter\Model;

class EstateReservationModel extends Model
{
    protected $table = 'estate_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'estate_id', 'reservee_id', 'estate_type', 'payment_option', 'reservation_status', 'reference_number', 'created_at', 'updated_at']; // Define the allowed fields
    protected $useTimestamps = true;

    // Method to update the payment option for estate reservation
    public function updateEstatePaymentOption($reservationId, $estateId, $reserveeId, $paymentOption, $reservationStatus)
    {
        // Step 1: Find the latest reservation based on `created_at`
        $latestReservation = $this->db->table($this->table) // Replace 'estate_reservations' with the actual table name
            ->where("id", $reservationId)
            ->where('estate_id', $estateId)
            ->where('reservee_id', $reserveeId)
            ->where('reservation_status', 'Confirmed')
            ->orderBy('created_at', 'DESC') // Order by `created_at` in descending order
            ->limit(1) // Limit to the latest record
            ->get()
            ->getRow();

        // Step 2: If a reservation is found, update it
        if ($latestReservation) {
            return $this->db->table($this->table)
                ->where('id', $latestReservation->id) // Use the ID of the latest reservation
                ->update([
                    'payment_option' => $paymentOption,
                    'reservation_status' => $reservationStatus
                ]);
        }

        // Step 3: If no reservation is found, return false or handle accordingly
        return false;
    }

    // public function setCashSalePayment($reservationId, $estateId, $paymentAmount)
    // {
    //     $data = [
    //         "reservation_id" => $reservationId,
    //         "estate_id" => $estateId,
    //         "payment_amount" => $paymentAmount
    //     ];

    //     return $this->db->table("estate_cash_sales")->insert($data);
    // }

    public function setCashSalePayment($reservationId, $estateId, $paymentAmount)
    {
        $data = [
            "reservation_id" => $reservationId,
            "estate_id" => $estateId,
            "payment_amount" => $paymentAmount
        ];

        $this->db->table("estate_cash_sales")->insert($data);

        $insertedId = $this->db->insertID();

        return $insertedId;
    }

    public function setCashSaleDueDate($cashSaleId, $estateId, $dueDate)
    {
        $data = [
            "cash_sale_id" => $cashSaleId,
            "estate_id" => $estateId,
            "due_date" => $dueDate
        ];

        return $this->db->table("estate_cash_sale_due_dates")->insert($data);
    }

    // public function setSixMonthsPayment($reservationId, $estateId, $paymentAmount)
    // {
    //     $data = [
    //         "reservation_id" => $reservationId,
    //         "estate_id" => $estateId,
    //         "payment_amount" => $paymentAmount
    //     ];

    //     return $this->db->table("estate_six_months")->insert($data);
    // }

    public function setSixMonthsPayment($reservationId, $estateId, $paymentAmount)
    {
        $data = [
            "reservation_id" => $reservationId,
            "estate_id" => $estateId,
            "payment_amount" => $paymentAmount
        ];

        $this->db->table("estate_six_months")->insert($data);
    
        $insertedId = $this->db->insertID();

        return $insertedId;
    }

    public function setSixMonthsDueDate($sixMonthsId, $estateId, $dueDate)
    {
        $data = [
            "six_months_id" => $sixMonthsId,
            "estate_id" => $estateId,
            "due_date" => $dueDate
        ];

        return $this->db->table("estate_six_months_due_dates")->insert($data);
    }

    public function setInstallmentPayment($reservationId, $estateId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $interestRate)
    {
        $data = [
            "reservation_id" => $reservationId,
            "estate_id" => $estateId,
            "term_years" => $termYears,
            "down_payment" => $downPayment,
            "down_payment_due_date" => $downPaymentDueDate,
            "total_amount" => $totalAmount,
            "monthly_payment" => $paymentAmount,
            "interest_rate" => $interestRate
        ];

        return $this->db->table("estate_installments")->insert($data);
    }
}
