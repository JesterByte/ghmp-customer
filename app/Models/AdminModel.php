<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";

    protected $allowedFields = ["email"];

    protected $useTimestamps = true;

    public function getOwnerPropertiesCount($userId)
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
}
