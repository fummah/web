<?php
function getWorkingDays($startDate, $endDate, $holidays) {
  $startWorkingHour = 8;
  $endWorkingHour = 16;
  $startDate = strtotime($startDate);
  $endDate = strtotime($endDate);
  $workingDays = 0;
  $currentDate = $startDate;
  while ($currentDate <= $endDate+86400) {
      $dayOfWeek = date('N', $currentDate);
      if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
          $currentDateFormatted = date('Y-m-d', $currentDate);
          if (!in_array($currentDateFormatted, $holidays)) {           
              $startTime = strtotime(date('Y-m-d', $currentDate) . " $startWorkingHour:00:00");
              $endTime = strtotime(date('Y-m-d', $currentDate) . " $endWorkingHour:00:00");
              $startTime = $startTime<$startDate? $startDate : $startTime;
              if((int)date('H', $startTime)<$endWorkingHour){
              $endTime = $endTime>$endDate? $endDate : $endTime;
              if($startTime<$endTime){
                //$workingHours = floor(($endTime - $startTime) / 3600); 
                $workingHours = ($endTime - $startTime) / 3600; 
                $workingDays += $workingHours;
              }              
           }
          }          
      }      
      $currentDate = strtotime('+1 day', $currentDate);
  }
  return $workingDays;
}

// Example usage:
$startDate = '2024-02-22 02:00:00';
$endDate = date("Y-m-d H:i:s"); // Current date and time
$holidays = ['2023-12-31', '2024-01-01']; // Example holidays
$remainingHours = getWorkingDays($startDate, $endDate, $holidays);
echo "Remaining working hours: $remainingHours ----> $startDate ---- $endDate";
