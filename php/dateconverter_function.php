<?php

function formatDateToWords($birthdate) {
        
    $date = new DateTime($birthdate);

    // Get day, month, and year as integers
    $day = $date->format('d');
    $month = $date->format('m');
    $year = $date->format('Y');

    // Array of month names
    $monthNames = array(
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    );

    $monthName = $monthNames[$month - 1];

    if ($day > 10 && $day < 14) {
        $ordinal = $day . 'th';
    } else {
        switch ($day % 10) {
            case 1:
                $ordinal = $day . 'st';
                break;
            case 2:
                $ordinal = $day . 'nd';
                break;
            case 3:
                $ordinal = $day . 'rd';
                break;
            default:
                $ordinal = $day . 'th';
                break;
        }
    }

    $formattedDate = $monthName . ' ' . $ordinal . ', ' . $year;

    return $formattedDate;
}

function formatDateandTimeToWords($datetime) {
    $date = new DateTime($datetime);
    
    // Get day, month, and year as integers
    $day = $date->format('d');
    $month = $date->format('m');
    $year = $date->format('Y');
    $hour = $date->format('h'); // Use 'h' for 12-hour format
    $minute = $date->format('i');
    $ampm = $date->format('A'); // 'AM' or 'PM'

    // Array of month names
    $monthNames = array(
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    );

    $monthName = $monthNames[$month - 1];
    
    if ($day > 10 && $day < 14) {
        $ordinal = $day . 'th';
    } else {
        switch ($day % 10) {
            case 1:
                $ordinal = $day . 'st';
                break;
            case 2:
                $ordinal = $day . 'nd';
                break;
            case 3:
                $ordinal = $day . 'rd';
                break;
            default:
                $ordinal = $day . 'th';
                break;
        }
    }

    $formattedDateandTime = $monthName . ' ' . $ordinal . ', ' . $year . ' at ' . $hour . ':' . $minute . ' ' . $ampm;
    return $formattedDateandTime;
} 