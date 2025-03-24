<?php

namespace App\Models;

use CodeIgniter\Model;

class OwnershipTransferModel extends Model {
    protected $table = "ownership_transfer_requests";
    protected $primaryKey = "id";

    protected $allowedFields = ["user_id", "new_owner_email", "otp_code", "otp_expires_at", "status", "created_at"];

    protected $useTimestamps = true;
}