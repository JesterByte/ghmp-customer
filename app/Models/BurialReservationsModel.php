<?php

namespace App\Models;

use CodeIgniter\Model;

class BurialReservationsModel extends Model {
    protected $table = "burial_reservations";
    protected $primaryKey = "id";
    protected $allowedFields = [
        "asset_id", "reservee_id", "relationship", "first_name", "middle_name", "last_name", "suffix", "date_of_birth", "date_of_death", "obituary", "date_time", "status"
    ];

    public function setBurialReservation($assetId, $reserveeId, $relationship, $firstName, $middleName, $lastName, $suffix, $birthDate, $deathDate, $obituary, $dateTime) {
        $data = [
            "asset_id" => $assetId,
            "reservee_id" => $reserveeId,
            "relationship" => $relationship,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "suffix" => $suffix,
            "date_of_birth" => $birthDate,
            "date_of_death" => $deathDate,
            "obituary" => $obituary,
            "date_time" => $dateTime
        ];

        return $this->insert($data);
    }

    public function getBurialReservations($userId) {
        return $this->where('reservee_id', $userId)->findAll();
    }
    
}