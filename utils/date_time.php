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
function convert_time_to_other_timezone($time,string $timezone){

// Set the default timezone to UTC
date_default_timezone_set($timezone);

// Create a DateTime object with the current time in UTC
$date = new DateTime($time, new DateTimeZone($timezone));

// Create a DateTimeZone object for the Hong Kong timezone (HKT, UTC+8)
$hongKongTimezone = new DateTimeZone("UTC");

// Convert the DateTime object to the Hong Kong timezone
$date->setTimezone($hongKongTimezone);

return ['full_date'=> $date->format('Y-m-d-l H:i:s'), 'shortend_date' => $date->format('l H:i')];

}

function get_date_time_in($timezone = 'UTC'){
// Set the default timezone to UTC
date_default_timezone_set($timezone); return new DateTime();

}

?>