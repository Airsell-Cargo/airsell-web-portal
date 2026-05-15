<?php
/**
 * MAWB Records Dashboard
 * 
 * Displays Master Air Waybill records from the database with proper
 * security measures and accessibility standards.
 */

include 'db_connect.php';

// Initialize variables
$records = [];
$error_message = null;
$record_count = 0;

try {
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM mawb_records ORDER BY issue_date DESC");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $record_count = $result->num_rows;
    
    // Fetch all records into array
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    // Log error securely (don't expose to user)
    error_log("Dashboard Error: " . $e->getMessage());
    $error_message = "An error occurred while retrieving records. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAWB Records Dashboard</title>
    <style>
        .mawb-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .mawb-header {
            margin-bottom: 20px;
        }

        .mawb-header h2 {
            color: #333;
            margin: 0 0 10px 0;
        }

        .record-count {
            color: #666;
            font-size: 14px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .no-records {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 12px 20px;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            text-align: center;
        }

        .table-wrapper {
            overflow-x: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            white-space: nowrap;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .mawb-container {
                padding: 10px;
            }

            th, td {
                padding: 8px 10px;
                font-size: 14px;
            }

            .table-wrapper {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="mawb-container">
        <div class="mawb-header">
            <h2>MAWB Records</h2>
            <?php if ($record_count > 0): ?>
                <p class="record-count">Total Records: <?= htmlspecialchars($record_count) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($records)): ?>
            <div class="no-records">
                No records found.
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table role="grid" aria-label="MAWB Records">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">MAWB Number</th>
                            <th scope="col">Shipper</th>
                            <th scope="col">Consignee</th>
                            <th scope="col">Origin</th>
                            <th scope="col">Destination</th>
                            <th scope="col">Flight</th>
                            <th scope="col">Date</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Pieces</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["mawb_number"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["shipper_details"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["consignee_details"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["origin_airport"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["destination_airport"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["flight_number"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["issue_date"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["weight"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row["pieces"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php
                                        $status = $row["status"] ?? 'unknown';
                                        $status_class = 'status-' . strtolower(str_replace(' ', '-', $status));
                                    ?>
                                    <span class="status <?= htmlspecialchars($status_class, ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>