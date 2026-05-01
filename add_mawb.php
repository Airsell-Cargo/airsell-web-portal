<h2>Add New MAWB Record</h2>
<form method="post" action="save_mawb.php">
  MAWB Number: <input type="text" name="mawb_number"><br>
  Shipper: <input type="text" name="shipper_details"><br>
  Consignee: <input type="text" name="consignee_details"><br>
  Origin: <input type="text" name="origin_airport"><br>
  Destination: <input type="text" name="destination_airport"><br>
  Flight: <input type="text" name="flight_number"><br>
  Date: <input type="date" name="issue_date"><br>
  Weight: <input type="number" step="0.01" name="weight"><br>
  Pieces: <input type="number" name="pieces"><br>
  Status: <input type="text" name="status"><br>
  <input type="submit" value="Save">
</form>
