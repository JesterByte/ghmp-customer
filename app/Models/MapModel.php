<?php

namespace App\Models;

use CodeIgniter\Model;

class MapModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect(); // Load database connection
    }

    public function getLots()
    {
        $query = $this->db->query("
            SELECT lot_id AS id, latitude_start, latitude_end, longitude_start, longitude_end, status, 'lot' AS type
            FROM lots
            UNION
            SELECT estate_id AS id, latitude_start, latitude_end, longitude_start, longitude_end, status, 'estate' AS type
            FROM estates
        ");

        return $query->getResultArray(); // Fetch as an associative array
    }
}
