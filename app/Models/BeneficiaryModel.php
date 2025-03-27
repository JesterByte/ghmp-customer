<?php

namespace App\Models;

use CodeIgniter\Model;

class BeneficiaryModel extends Model
{
    protected $table = "beneficiaries";
    protected $primaryKey = "id";

    protected $allowedFields = ["customer_id", "first_name", "middle_name", "last_name", "suffix_name", "contact_number", "email_address", "password_hashed", "relationship_to_customer", "status", "created_at", "updated_at"];

    protected $useTimestamps = true;

    public function mainOwnerStatus($beneficiaryEmail)
    {
        $result = $this->select("beneficiaries.status, customers.*")
            ->join("customers", "beneficiaries.customer_id = customers.id")
            ->where("beneficiaries.email_address", $beneficiaryEmail)
            ->get()
            ->getRowArray();

        return $result ? $result["status"] : null;
    }

    public function countBeneficiariesByCustomerId($customerId)
    {
        return $this->where("customer_id", $customerId)
            ->where("status", "Inactive")
            ->countAllResults();
    }
}
