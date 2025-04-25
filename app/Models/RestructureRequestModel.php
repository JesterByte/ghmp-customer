<?php

namespace App\Models;

use CodeIgniter\Model;

class RestructureRequestModel extends Model
{
    protected $table = 'restructure_requests'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'reservation_id', 'customer_id', 'asset_id', 'reason', 'status', 'created_at', 'updated_at']; // Define the allowed fields
}
