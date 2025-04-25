<?php

namespace App\Models;

use CodeIgniter\Model;

class LotModel extends Model
{
    protected $table = 'lots'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'lot_id', 'lot_type', 'owner_id', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status', 'occupancy', 'capacity']; // Define the allowed fields

    // Define any custom query to fetch available lots
    // public function getAvailableLots() {
    //     return $this->where('status', "Available")->findAll();
    // }

    public function getAvailableLots()
    {
        return $this->select('lots.*, pp.total_purchase_price AS price')
            ->join('phase_pricing pp', 'CONCAT("Phase ", SUBSTRING(lots.lot_id, 1, 1)) = pp.phase AND pp.lot_type = lots.lot_type')
            ->where('lots.status', 'Available')
            ->findAll();
    }

    public function getChosenLots($userId)
    {
        return $this->select("lots.*")
            ->join("lot_reservations lr", "lr.lot_id = lots.lot_id")
            ->where("lr.reservee_id", $userId)
            ->whereIn("lots.status", ["Reserved", "Sold", "Sold and Occupied"])
            ->whereNotIn("lr.reservation_status", ["Cancelled"]) // Exclude only cancelled
            ->groupBy("lots.lot_id")
            ->orderBy("lr.created_at", "DESC")
            ->findAll();
    }
}
