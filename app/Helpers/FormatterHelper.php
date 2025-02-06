<?php 

namespace App\Helpers;

class FormatterHelper
{
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
    public static function extractEstateIdParts($estateId)
    {
        if (preg_match('/^([E])([A-C])-([\d]+)$/', $estateId, $matches)) {
            return [
                'estate' => $matches[1],   // Always "E"
                'type' => $matches[2],     // Estate Type A/B/C
                'number' => $matches[3]    // Estate Number
            ];
        }
        return null; // Invalid format
    }

    // Determine if the ID is for a Lot or Estate
    public static function determineIdType($id)
    {
        // Check for Lot ID format
        if (preg_match('/^(\d)([A-Z])(\d+)-(\d+)$/', $id)) {
            return 'lot';
        }
        // Check for Estate ID format
        if (preg_match('/^([E])([A-C])-([\d]+)$/', $id)) {
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
}
