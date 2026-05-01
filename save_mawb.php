<?php
include 'db_connect.php';

$sql = "INSERT INTO mawb_records 
(mawb_number, shipper_details, consignee_details, origin_airport, destination_airport, flight_number, issue_date, weight, pieces, status)
VALUES ('".$_POST['mawb_number']."','".$_POST['shipper_details']."','".$_POST['consignee_details']."','".$_POST['origin_airport']."','".$_POST['destination_airport']."','".$_POST['flight_number']."','".$_POST['issue_date']."','".$_POST['weight']."','".$_POST['pieces']."','".$_POST['status']."')";

if ($conn->query($sql) === TRUE) {
    echo "New MAWB record added successfully. <a href='dashboard.php'>View Records</a>";
} else {
    echo "Error: " . $conn->error;
}
?>
