<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminNotificationModel extends Model {
    protected $table = "admin_notifications";
    protected $primaryKey = "id";

    protected $allowedFields = ["admin_id", "message", "link", "is_read", "created_at"];

    protected $useTimestamps = false;
}