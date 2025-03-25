<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model {
    protected $table = "notifications";
    protected $primaryKey = "id";

    protected $allowedFields = ["customer_id", "message", "link", "is_read", "created_at"];

    protected $useTimestamps = false;

    public function getUnreadNotifications($customerId) {
        $sql = "SELECT * FROM {$this->table} WHERE customer_id = ? AND is_read = 0 ORDER BY created_at DESC";
        return $this->query($sql, [$customerId])->getResultArray();
    }

    public function markAsRead($notificationId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE id = ?";
        return $this->query($sql, [$notificationId]);
    }

    public function markAllAsRead($customerId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE customer_id = ?";
        return $this->query($sql, [$customerId]);
    }
}