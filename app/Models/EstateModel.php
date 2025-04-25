<?php

namespace App\Models;

use CodeIgniter\Model;

class EstateModel extends Model
{
    protected $table = 'estates'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'estate_id', 'owner_id', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status', 'occupancy', 'capacity']; // Define the allowed fields

    // Define any custom query to fetch available lots
    public function getAvailableEstates()
    {
        return $this->select("estates.*, ep.total_purchase_price AS price")
            ->join("estate_pricing ep", "CONCAT('Estate ', SUBSTRING(estates.estate_id, 3, 1)) = ep.estate")
            ->where('estates.status', 'Available')
            ->findAll();
    }

    public function getChosenEstates($userId)
    {
        return $this->select("estates.*")
            ->join("estate_reservations er", "er.estate_id = estates.estate_id")
            ->where("er.reservee_id", $userId)
            ->whereIn("estates.status", ["Reserved", "Sold", "Sold and Occupied"])
            ->whereNotIn("er.reservation_status", ["Cancelled"]) // Exclude only cancelled
            ->groupBy("estates.estate_id")
            ->orderBy("er.created_at", "DESC")
            ->findAll();
    }

    public function getEstatePricing($estateType)
    {
        $builer = $this->db->table("estate_pricing");
        $builer->select("sqm, number_of_lots");
        $builer->where("estate", $estateType);

        $query = $builer->get();

        return $query->getRow();
    }
}
