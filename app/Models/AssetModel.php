<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model {
    public function getAssetsById($userId) {
        $db = \Config\Database::connect();

        $builder = $db->table("lot_reservations")->select("lot_id AS asset_id, reservee_id, lot_type, payment_option, 'Lot' AS asset_type")->where("reservee_id", $userId);


        
    }
}