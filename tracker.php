<?php
$events = getTrackingTimeline("your_piece_id", "your_api_key");
?>

<div class="timeline">
    <?php foreach ($events as $event): ?>
        <div class="timeline-item">
            <div class="timeline-date">
                <?php echo date('M d, H:i', strtotime($event['cargo:eventDate'])); ?>
            </div>
            <div class="timeline-content">
                <strong><?php echo $event['cargo:eventCode']; ?></strong> 
                - <?php echo $event['cargo:recordedAtLocation']['cargo:locationCode']; ?>
                <p>Status: Updated in ONE Record System</p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
.timeline { border-left: 3px solid #f9da43; /* Using your brand Yellow */ padding: 10px 0; margin-left: 20px; }
.timeline-item { margin-bottom: 20px; padding-left: 20px; position: relative; }
.timeline-item::before { content: ''; position: absolute; left: -9px; top: 5px; width: 15px; height: 15px; background: #007a33; /* Your brand Green */ border-radius: 50%; }
.timeline-date { font-size: 0.8em; color: #666; }
.timeline-content strong { color: #d32f2f; /* Your brand Red */ }
</style>
