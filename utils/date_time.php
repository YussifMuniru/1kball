<?php


function get_time_in_right_zone(string $timezone){

// Set the default timezone to UTC
date_default_timezone_set('UTC');

// Create a DateTime object with the current time in UTC
$date = new DateTime('now', new DateTimeZone('UTC'));

// Create a DateTimeZone object for the Hong Kong timezone (HKT, UTC+8)
$hongKongTimezone = new DateTimeZone($timezone);

// Convert the DateTime object to the Hong Kong timezone
$date->setTimezone($hongKongTimezone);

return ['full_date'=> $date->format('Y-m-d-l H:i:s'), 'shortend_date' => $date->format('l H:i')];

}

?>