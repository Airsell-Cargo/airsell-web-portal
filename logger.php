<form action="process_event.php" method="POST" class="event-form">
    <h3>Log Logistics Event</h3>
    
    <label>Logistics Object ID (from your portal):</label>
    <input type="text" name="object_id" placeholder="e.g., piece_12345" required>

    <label>Select Status Update:</label>
    <select name="event_code">
        <option value="RCS">Received (RCS)</option>
        <option value="DEP">Departed (DEP)</option>
        <option value="ARR">Arrived (ARR)</option>
        <option value="DLV">Delivered (DLV)</option>
    </select>

    <label>Current Location:</label>
    <select name="location">
        <option value="HGA">Hargeisa (HGA)</option>
        <option value="BER">Berbera (BER)</option>
        <option value="GLK">Gaalkacyo (GLK)</option>
    </select>

    <button type="submit">Update Shipment</button>
</form>
