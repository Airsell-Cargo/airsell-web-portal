<?php
/**
 * Airsell Cargo Tracking Timeline
 * Displays shipment tracking events with improved security and accessibility
 */

// Security: Get piece ID from query parameter, never hardcode
$pieceId = isset($_GET['piece_id']) ? trim($_GET['piece_id']) : null;
$apiKey = getenv('CARGO_API_KEY'); // Use environment variable, never hardcode

$events = [];
$errorMessage = null;

// Validate input
if (!$pieceId) {
    $errorMessage = 'Tracking ID is required.';
} elseif (!$apiKey) {
    error_log('CARGO_API_KEY environment variable not configured');
    $errorMessage = 'System configuration error. Please contact support.';
} else {
    // Fetch tracking data with error handling
    try {
        $events = getTrackingTimeline($pieceId, $apiKey);
        
        // Validate response
        if (!is_array($events)) {
            $events = [];
            $errorMessage = 'Invalid tracking data received.';
        }
    } catch (Exception $e) {
        error_log('Tracking API error: ' . $e->getMessage());
        $errorMessage = 'Unable to load tracking data. Please try again later.';
    }
}
?>

<div class="timeline" role="region" aria-label="Shipment tracking timeline">
    <?php if ($errorMessage): ?>
        <div class="timeline-error" role="alert">
            <p><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    <?php elseif (empty($events)): ?>
        <div class="timeline-empty" role="status">
            <p>No tracking events found for this shipment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <?php 
                // Safely extract and validate event data
                $eventDate = isset($event['cargo:eventDate']) ? $event['cargo:eventDate'] : null;
                $eventCode = isset($event['cargo:eventCode']) ? $event['cargo:eventCode'] : 'Unknown';
                $locationCode = isset($event['cargo:recordedAtLocation']['cargo:locationCode']) 
                    ? $event['cargo:recordedAtLocation']['cargo:locationCode'] 
                    : 'Unknown';
                
                // Format date safely
                $formattedDate = $eventDate 
                    ? date('M d, H:i', strtotime($eventDate)) 
                    : 'Date unavailable';
            ?>
            <div class="timeline-item">
                <div class="timeline-marker" aria-hidden="true"></div>
                <div class="timeline-date">
                    <time datetime="<?php echo htmlspecialchars($eventDate, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8'); ?>
                    </time>
                </div>
                <div class="timeline-content">
                    <strong><?php echo htmlspecialchars($eventCode, ENT_QUOTES, 'UTF-8'); ?></strong> 
                    <span class="location-badge">
                        <?php echo htmlspecialchars($locationCode, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <p class="status-note">
                        <span class="status-indicator" aria-label="Status updated"></span>
                        Updated in ONE Record System
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
/* Timeline container */
.timeline {
    border-left: 3px solid #f9da43;
    padding: 10px 0;
    margin-left: 20px;
    position: relative;
}

/* Individual timeline item */
.timeline-item {
    margin-bottom: 20px;
    padding-left: 20px;
    position: relative;
}

/* Timeline marker (visual indicator) */
.timeline-marker {
    content: '';
    position: absolute;
    left: -9px;
    top: 5px;
    width: 15px;
    height: 15px;
    background: #007a33; /* Brand Green */
    border-radius: 50%;
    border: 2px solid white;
}

/* Date styling */
.timeline-date {
    font-size: 0.8em;
    color: #666;
    margin-bottom: 4px;
}

/* Content styling */
.timeline-content strong {
    color: #d32f2f; /* Brand Red */
    display: block;
    margin-bottom: 4px;
}

/* Location badge */
.location-badge {
    display: inline-block;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.85em;
    color: #333;
}

/* Status indicator */
.status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #007a33;
    border-radius: 50%;
    margin-right: 6px;
    vertical-align: middle;
}

.status-note {
    margin-top: 4px;
    font-size: 0.9em;
    color: #555;
}

/* Error and empty states */
.timeline-error,
.timeline-empty {
    padding: 15px;
    border-radius: 4px;
    text-align: center;
}

.timeline-error {
    background-color: #ffebee;
    border: 1px solid #ef5350;
    color: #c62828;
}

.timeline-empty {
    background-color: #f5f5f5;
    border: 1px solid #bdbdbd;
    color: #666;
}

/* Accessibility: Focus visible for keyboard navigation */
.timeline-item:focus-visible {
    outline: 2px solid #007a33;
    outline-offset: 2px;
}
</style>
