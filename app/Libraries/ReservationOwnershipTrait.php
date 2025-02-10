<?php

namespace App\Libraries;

trait ReservationOwnershipTrait {
    public function setLotReservation($lotId) {
        return $this->db->table("lot_reservations")
            ->where("lot_id", $lotId)
            ->update(["reservation_status" => "Completed"]);
    }

    public function setEstateReservation($estateId) {
        return $this->db->table("estate_reservations")
            ->where("estate_id", $estateId)
            ->update(["reservation_status" => "Completed"]);
    }

    public function getReserveeId($lotId) {
        return $this->db->table("lot_reservations")
            ->select("reservee_id")
            ->where("lot_id", $lotId)
            ->get()
            ->getRowArray();  // Fetch associative array
    }

    public function getReserveeIdEstate($estateId) {
        return $this->db->table("estate_reservations")
            ->select("reservee_id")
            ->where("estate_id", $estateId)
            ->get()
            ->getRowArray();
    }

    public function setLotOwnership($lotId, $reserveeId) {
        return $this->db->table("lots")
            ->where("lot_id", $lotId)
            ->update([
                "owner_id" => $reserveeId,
                "status" => "Sold"
            ]);
    }

    public function setEstateOwnership($estateId, $reserveeId) {
        return $this->db->table("estates")
            ->where("estate_id", $estateId)
            ->update([
                "owner_id" => $reserveeId,
                "status" => "Sold"
            ]);
    }
}


// use PDO;

// trait ReservationOwnershipTrait {
//     public function setLotReservation($lotId) {
//         $stmt = $this->db->prepare("UPDATE lot_reservations SET reservation_status = :reservation_status WHERE lot_id = :lot_id");
//         $stmt->bindParam(":lot_id", $lotId);
//         $reservationStatus = "Completed";
//         $stmt->bindParam(":reservation_status", $reservationStatus);

//         return $stmt->execute();
//     }
//     public function setEstateReservation($estateId) {
//         $stmt = $this->db->prepare("UPDATE estate_reservations SET reservation_status = :reservation_status WHERE estate_id = :estate_id");
//         $stmt->bindParam(":estate_id", $estateId);
//         $reservationStatus = "Completed";
//         $stmt->bindParam(":reservation_status", $reservationStatus);

//         return $stmt->execute();
//     }

//     public function getReserveeId($lotId) {
//         $stmt = $this->db->prepare("SELECT reservee_id FROM lot_reservations WHERE lot_id = :lot_id");
//         $stmt->execute([":lot_id" => $lotId]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }
//     public function getReserveeIdEstate($estateId) {
//         $stmt = $this->db->prepare("SELECT reservee_id FROM estate_reservations WHERE estate_id = :estate_id");
//         $stmt->execute([":estate_id" => $estateId]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }

//     public function setLotOwnership($lotId, $reserveeId) {
//         $stmt = $this->db->prepare("UPDATE lots SET owner_id = :owner_id, status = :status WHERE lot_id = :lot_id");
//         $stmt->bindParam(":lot_id", $lotId);
//         $status = "Sold";
//         $stmt->bindParam(":status", $status);
//         $stmt->bindParam(":owner_id", $reserveeId);

//         return $stmt->execute();
//     }
//     public function setEstateOwnership($estateId, $reserveeId) {
//         $stmt = $this->db->prepare("UPDATE estates SET owner_id = :owner_id, status = :status WHERE estate_id = :estate_id");
//         $stmt->bindParam(":estate_id", $estateId);
//         $status = "Sold";
//         $stmt->bindParam(":status", $status);
//         $stmt->bindParam(":owner_id", $reserveeId);

//         return $stmt->execute();
//     }

// }