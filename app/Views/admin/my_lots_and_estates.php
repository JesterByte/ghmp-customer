<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<!-- <div class="d-flex justify-content-end">
    <div class="btn-group">
    <a href="#" class="btn btn-primary active" aria-current="page">Lot</a>
    <a href="#" class="btn btn-primary">Estate</a>
    </div>
</div> -->

<div class="table-responsive rounded shadow">
    <table id="table" class="table">
        <thead>
            <tr>
                <th class="text-center">Sorter</th>
                <th class="text-center">Asset</th>
                <th class="text-center">Asset Type</th>
                <th class="text-center">Payment Option</th>
                <th class="text-center">Reservation Status</th>
                <th class="text-center"><i class="bi bi-hand-index-thumb-fill"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php

            use App\Helpers\FormatterHelper;

            if (isset($table)) {
                foreach ($table as $row) {
                    $reservationType = FormatterHelper::determineIdType($row["asset_id"]);

                    if ($row["asset_type"] != "Pending" && $row["payment_option"] == "Pending" && $row["reservation_status"] == "Confirmed") {
                        $paymentOption = $row["payment_option"];
                    } else {
                        $paymentOption = $row["payment_option"];
                    }

                    if ($row["reservation_status"] == "Cancelled") {
                        if ($reservationType == "lot") {
                            $row["asset_type"] = "N//A";
                        }
                        $paymentOption = "N/A";
                    }

                    if ($row["reservation_status"] == "Confirmed" && $row["payment_option"] != "Pending") {
                        if ($row["down_payment_status"] == "Pending") {
                            $action = '<a target="_blank" href="' . $row["down_payment_link"] . '" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Click the button below to securely complete your payment through PayMongo." role="button"><i class="bi bi-credit-card-fill"></i> Pay Down ' . FormatterHelper::formatPrice($row["down_payment"]) . '</a>';
                        } else {
                            // Payment link + optional Restructure button
                            $action = '<a target="_blank" href="' . $row["payment_link"] . '" class="btn btn-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Click the button below to securely complete your payment through PayMongo." role="button"><i class="bi bi-credit-card-fill"></i> Pay ' . FormatterHelper::formatPrice($row["payment_amount"]) . '</a>';

                            if ($row["restructure_status"] === "Approved") {
                                $action = '<a target="_blank" href="' . $row["restructure_link"] . '" class="btn btn-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Click the button below to securely complete your payment through PayMongo." role="button"><i class="bi bi-credit-card-fill"></i> Pay ' . FormatterHelper::formatPrice($row["discounted_price"]) . ' (Restructured)</a>';
                            } else if (strpos($row["payment_option"], "Installment") !== false || $row["payment_option"] === "6 Months") {
                                $action .= '<br><button type="button" data-bs-reservation-id="'.$row["reservation_id"].'" data-bs-asset-id="'.$row["asset_id"].'" class="btn-request btn btn-warning mt-1" data-bs-toggle="modal" data-bs-target="#restructureRequestModal"><i class="bi bi-arrow-repeat"></i> Request Restructure</button>';
                            }
                        }
                    } else if ($row["reservation_status"] == "Completed") {
                        $action = '<button type="button" class="btn btn-success" disabled><i class="bi bi-check-circle-fill"></i> Paid</button>';
                    } else if ($row["reservation_status"] === "Confirmed" && $row["payment_option"] === "Pending") {
                        $action = "<a role='button' href='select_payment_option/{$row["encrypted_reservation_id"]}/{$row["encrypted_asset_id"]}/{$row["encrypted_asset_type"]}' class='btn btn-primary'>Choose Payment Option</a>";
                    } else if ($row["reservation_status"] === "Pending") {
                        $action = '<button type="button" class="btn-cancel btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelReservation" data-bs-asset-id="' . $row["asset_id"] . '"><i class="bi bi-x"></i> Cancel Reservation</button>';
                    } else {
                        $action = "";

                        // Add restructure button fallback (if needed)
                        if (strpos($row["payment_option"], "Installment") !== false) {
                            $action = '<a href="request_restructure/' . $row["encrypted_reservation_id"] . '" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="You may request to restructure your plan into a one-time payment."><i class="bi bi-arrow-repeat"></i> Request Restructure</a>';
                        }
                    }


                    switch ($row["reservation_status"]) {
                        case "Cancelled":
                            $reservationStatus = "{$row["reservation_status"]} ({$row["cancellation_reason"]})";
                            break;
                        case "Pending":
                            $reservationStatus = "Please wait for the approval of the Adminstrator";
                            break;
                        default:
                            $reservationStatus = $row["reservation_status"];
                            break;
                    }

                    echo "<tr>";
                    echo "<td class='text-center'>" . $row["created_at"] . "</td>";
                    echo "<td class='text-center'>" . $row['asset_id'] . "</td>";
                    echo "<td class='text-center'>" . $row["asset_type"] . "</td>";
                    echo "<td class='text-center'>" . $paymentOption . "</td>";
                    echo "<td class='text-center'>" . $reservationStatus . "</td>";
                    echo "<td class='text-center'>
                        $action
                    </td>";
                    echo "</tr>";
                }
            }

            ?>
        </tbody>
    </table>
</div>

<?= $this->include("modals/confirm_cancel_reservation") ?>
<?= $this->include("modals/confirm_restructure_request") ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const cancelButtons = document.querySelectorAll(".btn-cancel");
        const assetIdHidden = document.getElementById("assetId");

        cancelButtons.forEach(button => {
            button.addEventListener("click", function() {
                assetIdHidden.value = this.getAttribute("data-bs-asset-id");
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const requestButtons = document.querySelectorAll(".btn-request");
        const reservationIdHidden = document.getElementById("reservation_id");
        const assetIdHidden = document.getElementById("asset_id");

        requestButtons.forEach(button => {
            button.addEventListener("click", function() {
                reservationIdHidden.value = this.getAttribute("data-bs-reservation-id");
                assetIdHidden.value = this.getAttribute("data-bs-asset-idd");
            });
        })
    });
</script>
<?= $this->endSection(); ?>