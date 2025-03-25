<?php

namespace App\Controllers;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    public function fetchNotifications($customerId) {
        $notifications = $this->notificationModel->getUnreadNotifications($customerId);
        echo json_encode($notifications);
    }

    public function markAsRead($notificationId) {
        $this->notificationModel->markAsRead($notificationId);
        echo json_encode(["status" => "success"]);
    }

    public function markAllAsRead($customerId) {
        $this->notificationModel->markAllAsRead($customerId);
        echo json_encode(["status" => "success"]);
    }
}
