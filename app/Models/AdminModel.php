<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";

    protected $allowedFields = ["email"];

    protected $useTimestamps = true;
}
