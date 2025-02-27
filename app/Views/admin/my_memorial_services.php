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
                <th class="text-center">Asset</th>
                <th class="text-center">Interred</th>
                <th class="text-center">Date & Time</th>
                <th class="text-center">Service Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            use App\Helpers\FormatterHelper;

            if (isset($burialReservations)) {
                foreach ($burialReservations as $burialReservation) {
                    $middleName = !empty($burialReservation["middle_name"]) ? $burialReservation["middle_name"] : "";
                    $suffix = !empty($burialReservation["suffix"]) ? ", " . $burialReservation["suffix"] : "";
                    $interred = $burialReservation["first_name"] . " " . $middleName . " " . $burialReservation["last_name"] . $suffix;
                    $dateTime = FormatterHelper::formatDate($burialReservation["date_time"]);

                    echo "<tr>";
                    echo "<td class='text-center'>" . $burialReservation['asset_id'] . "</td>";
                    echo "<td class='text-center'>" . $interred . "</td>";
                    echo "<td class='text-center'>" . $dateTime . "</td>";
                    echo "<td class='text-center'>" . $burialReservation["status"] . "</td>";
                    echo "<td class='text-center'>
                        
                    </td>";
                    echo "</tr>";
                }
            }

            ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>
