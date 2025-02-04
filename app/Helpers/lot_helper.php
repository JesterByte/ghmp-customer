<?php

if (!function_exists('format_lot_id')) {
    function format_lot_id($lotId) {
        if (preg_match('/(\d+)([A-Z])(\d+)-(\d+)/', $lotId, $matches)) {
            return "Phase {$matches[1]} Lawn {$matches[2]} Row {$matches[3]} - Lot {$matches[4]}";
        }
        return $lotId;
    }
}
