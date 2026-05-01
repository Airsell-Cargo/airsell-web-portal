<?php
include 'db_connect.php';

$sql = "SELECT * FROM mawb_records ORDER BY issue_date DESC";
$result = $conn->query($sql);

echo "<h2>MAWB Records</h2>";
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
              <th>ID</th><th>MAWB Number</th><th>Shipper</th><th>Consignee</th>
              <th>Origin</th><th>Destination</th><th>Flight</th>
              <th>Date</th><th>Weight</th><th>Pieces</th><th>Status</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["id"]."</td>
                <td>".$row["mawb_number"]."</td>
                <td>".$row["shipper_details"]."</td>
                <td>".$row["consignee_details"]."</td>
                <td>".$row["origin_airport"]."</td>
                <td>".$row["destination_airport"]."</td>
                <td>".$row["flight_number"]."</td>
                <td>".$row["issue_date"]."</td>
                <td>".$row["weight"]."</td>
                <td>".$row["pieces"]."</td>
                <td>".$row["status"]."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}
?>
