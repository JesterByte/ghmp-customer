<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table = "contacts";
    protected $primaryKey = "id";

    protected $allowedFields = ["name", "email", "message", "status", "created_at", "updated_at"];

    protected $useTimestamps = true;
}
