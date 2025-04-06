<?php

namespace App\Models;

use CodeIgniter\Model;

class PricingModel extends Model
{
    // protected $db;

    // public function __construct() {
    //     $this->db = \Config\Database::connect();
    // }

    public function getPhasePricing($phaseNumber, $lotType)
    {

        $builder = $this->db->table("phase_pricing");
        $builder->where("phase", $phaseNumber);
        $builder->where("lot_type", $lotType);

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function getEstatePricing($estateType)
    {
        $builder = $this->db->table("estate_pricing");
        $builder->where("estate", $estateType);

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function getLowestLotPrice()
    {
        $builder = $this->db->table("phase_pricing");
        $builder->select('MIN(total_purchase_price) as lowest_price');

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result['lowest_price'];
    }

    public function getLowestEstatePrice()
    {
        $builder = $this->db->table("estate_pricing");
        $builder->select("MIN(total_purchase_price) as lowest_price");

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result["lowest_price"];
    }

    public function getPricingByPhaseAndLotType($phase, $lotType)
    {
        if (empty($phase) || empty($lotType)) {
            return null;
        }

        $builder = $this->db->table("phase_pricing");
        $builder->where("phase", $phase);
        $builder->where("lot_type", $lotType);
        $builder->select("*");  // Changed to select all columns

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function getPricingByEstateType($estateType)
    {
        if (empty($estateType)) {
            return null;
        }

        $builder = $this->db->table("estate_pricing");
        $builder->where("estate", $estateType);
        $builder->select("*");

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function getPricingByCategory($category, $burialType)
    {
        if (empty($category)) {
            return null;
        }

        $builder = $this->db->table("burial_pricing");
        $builder->where("category", $category);
        $builder->where("burial_type", $burialType);
        $builder->select("*");

        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result;
    }

    // public function getRates() {
    //     $builder = $this->db->table("")
    // }
}
