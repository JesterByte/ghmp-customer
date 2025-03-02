<?php

namespace App\Models;

use CodeIgniter\Model;

class BurialReservationsModel extends Model {
    protected $table = "burial_reservations";
    protected $primaryKey = "id";
    protected $allowedFields = [
        "reservee_id", "asset_id", "burial_type", "relationship", "first_name", "middle_name", "last_name", "suffix", "date_of_birth", "date_of_death", "obituary", "date_time", "status", "payment_amount", "payment_status", "reference_number"  
    ];

    public function setBurialReservation($assetId, $burialType, $reserveeId, $relationship, $firstName, $middleName, $lastName, $suffix, $birthDate, $deathDate, $obituary, $paymentAmount, $dateTime) {
        $data = [
            "asset_id" => $assetId,
            "burial_type" => $burialType,
            "reservee_id" => $reserveeId,
            "relationship" => $relationship,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "suffix" => $suffix,
            "date_of_birth" => $birthDate,
            "date_of_death" => $deathDate,
            "obituary" => $obituary,
            "payment_amount" => $paymentAmount,
            "date_time" => $dateTime
        ];

        return $this->insert($data);
    }

    public function getBurialReservations($userId) {
        return $this->where('reservee_id', $userId)->findAll();
    }

    public function getBurialPricing($category, $burialType) {
        $builder = $this->db->table("burial_pricing");

        $query = $builder->where([
            "category" => $category,
            "burial_type" => $burialType
        ])->get();

        return $query->getRowArray();
    }

}