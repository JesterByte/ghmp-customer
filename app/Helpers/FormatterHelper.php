<?php 

namespace App\Helpers;

class FormatterHelper
{
    public static $checkIcon = '<i class="bi bi-check-lg text-success"></i>';
    public static $xIcon = '<i class="bi bi-x-lg text-danger"></i>';
    public static $warningIcon = '<i class="bi bi-exclamation-lg text-warning"></i>';

    // Extract parts of a Lot ID
    public static function extractLotIdParts($lotId)
    {
        if (preg_match('/^(\d)([A-Z])(\d+)-(\d+)$/', $lotId, $matches)) {
            return [
                'phase' => $matches[1],
                'lawn' => $matches[2],
                'row' => $matches[3],
                'lot' => $matches[4]
            ];
        }
        return null; // Invalid format
    }

    // Extract parts of an Estate ID
    public static function extractEstateIdParts($estateId) {
        // Define the regular expression pattern
        $pattern = '/^E-([A-C])(\d+)$/';
    
        // Check if the ID matches the pattern
        if (preg_match($pattern, $estateId, $matches)) {
            // If matched, extract and return the details
            $type = $matches[1];  // Type (A, B, or C)
            $estateNumber = $matches[2];  // Estate Number (positive integer)
    
            return [
                'estate' => 'E',
                'type' => $type,
                'estate_number' => $estateNumber
            ];
        } else {
            // If the ID doesn't match the expected pattern
            return null;
        }
    }

    // Determine if the ID is for a Lot or Estate
    public static function determineIdType($id)
    {
        // Check for Lot ID format
        if (preg_match('/^(\d)([A-Z])(\d+)-(\d+)$/', $id)) {
            return 'lot';
        }
        // Check for Estate ID format
        if (preg_match('/^E-([A-C])(\d+)$/', $id)) {
            return 'estate';
        }
        // Return null if neither format matches
        return null;
    }

    public static function formatPrice($amount) {
        return "₱" . number_format($amount, 2);  // Format with 2 decimal places and commas
    }

    public static function formatRate($rate, $precision = 0) {
        return number_format($rate * 100, $precision) . "%";  // Format with 2 decimal places and commas
    }

    public static function convertToInteger($amount) {
        return number_format($amount, 0);
    }

    public static function sevenDaysFromNow() {
        $now = new \DateTime();
        $now->modify("+7 days");

        return $now->format("F j, Y");
    }

    public static function sixMonthsFromNow() {
        $now = new \DateTime();
        $now->modify("+6 Months");

        return $now->format("F j, Y");
    }

    public static function formatPaymentOption($paymentOption) {
        // For "cash_sale" or "six_months", return the string as-is
        if ($paymentOption == "cash_sale") {
            return "Cash Sale";
        } elseif ($paymentOption == "six_months") {
            return "6 Months";
        }
    
        // For installments, format it as "Installment: X Year(s)"
        if (is_numeric($paymentOption) && $paymentOption >= 1 && $paymentOption <= 5) {
            $years = (int)$paymentOption;
            return "Installment: $years Year" . ($years > 1 ? "s" : ""); // Singular for 1 year, plural for others
        }
    
        // If the payment option doesn't match any known type
        return "Unknown Payment Option";
    }

    public static function formatLotId($lotId) {
        if (preg_match('/(\d+)([A-Z])(\d+)-(\d+)/', $lotId, $matches)) {
            return "Phase {$matches[1]} Lawn {$matches[2]} Row {$matches[3]} - Lot {$matches[4]}";
        }
        return $lotId;
    }

    public static function formatEstateId($input) {
        // Split the input string by '-'
        list($estate, $lot) = explode('-', $input);
    
        // Replace 'E' with 'Estate' for the estate part
        $estate = ($estate == 'E') ? 'Estate' : $estate;
    
        // Format the lot by adding a space between the letter and number
        $lot = preg_replace('/([A-Za-z])(\d)/', '$1 #$2', $lot);
    
        // Combine the formatted estate and lot
        return $estate . ' ' . $lot;
    }

    public static function formatDate($date) {
        return date("F j, Y h:i:s A", strtotime($date));
    }
}
