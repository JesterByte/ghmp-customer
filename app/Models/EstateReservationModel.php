<?php

namespace App\Models;

use CodeIgniter\Model;

class EstateReservationModel extends Model
{
    protected $table = 'estate_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'estate_id', 'reservee_id', 'estate_type', 'payment_option', 'reservation_status']; // Define the allowed fields

    // Method to update the payment option for estate reservation
    public function updateEstatePaymentOption($estateId, $reserveeId, $paymentOption, $reservationStatus)
    {
        // Step 1: Find the latest reservation based on `created_at`
        $latestReservation = $this->db->table($this->table) // Replace 'estate_reservations' with the actual table name
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

    public function setCashSalePayment($estateId, $paymentAmount)
    {
        $data = [
            "estate_id" => $estateId,
            "payment_amount" => $paymentAmount
        ];

        return $this->db->table("estate_cash_sales")->insert($data);
    }

    public function setCashSaleDueDate($estateId, $dueDate)
    {
        $data = [
            "estate_id" => $estateId,
            "due_date" => $dueDate
        ];

        return $this->db->table("estate_cash_sale_due_dates")->insert($data);
    }

    public function setSixMonthsPayment($estateId, $paymentAmount)
    {
        $data = [
            "estate_id" => $estateId,
            "payment_amount" => $paymentAmount
        ];

        return $this->db->table("estate_six_months")->insert($data);
    }

    public function setSixMonthsDueDate($estateId, $dueDate)
    {
        $data = [
            "estate_id" => $estateId,
            "due_date" => $dueDate
        ];

        return $this->db->table("estate_six_months_due_dates")->insert($data);
    }

    public function setInstallmentPayment($estateId, $termYears, $downPayment, $downPaymentDueDate, $totalAmount, $paymentAmount, $interestRate)
    {
        $data = [
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
