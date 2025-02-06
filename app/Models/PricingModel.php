<?php

namespace App\Models;

use CodeIgniter\Model;

class PricingModel extends Model {
    // protected $db;

    // public function __construct() {
    //     $this->db = \Config\Database::connect();
    // }
    
    public function getPhasePricing($phaseNumber, $lotType) {
    
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

    public function getEstatePricing($estateType) {
        $builder = $this->db->table("estate_pricing");
        $builder->where("estate", $estateType);
        
        $query = $builder->get();
        $result = $query->getRowArray();

        if (!$result) {
            return null;
        }

        return $result;    
    }
    
}