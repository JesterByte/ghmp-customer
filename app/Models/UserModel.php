<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = "customers";
    protected $primaryKey = "id";

    protected $allowedFields = ["first_name", "middle_name", "last_name", "suffix_name", "contact_number", "email_address", "password_hashed", "created_at", "updated_at"];

    protected $useTimestamps = true;
}