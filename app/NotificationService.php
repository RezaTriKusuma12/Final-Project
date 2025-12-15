<?php
class NotificationService {
    private $koneksi;

    public function __construct($db)
    {
        $this->koneksi = $db;
    }

    public function logNotification($user_id, $title, $message, $status = 'sent')
    {
        $stmt = $this->koneksi->prepare(
            "INSERT INTO notifications (user_id, title, message, status) 
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("isss", $user_id, $title, $message, $status);
        return $stmt->execute();
    }
}
