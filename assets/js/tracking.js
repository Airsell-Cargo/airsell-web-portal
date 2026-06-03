/**
 * Airsell Cargo - Tracking Functionality
 * Handles shipment tracking via AWB (Air Waybill) number
 */

document.addEventListener('DOMContentLoaded', function() {
  const trackButton = document.querySelector('.btn-track');
  const trackInput = document.querySelector('.track-input-group input');

  if (trackButton && trackInput) {
    // Track on button click
    trackButton.addEventListener('click', handleTracking);

    // Track on Enter key press
    trackInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        handleTracking();
      }
    });
  }
});

/**
 * Handle tracking request
 */
function handleTracking() {
  const trackInput = document.querySelector('.track-input-group input');
  const awb = trackInput.value.trim();

  if (!awb) {
    showAlert('Please enter an Air Waybill (AWB) number', 'warning');
    trackInput.focus();
    return;
  }

  if (!isValidAWB(awb)) {
    showAlert('Invalid AWB format. Please enter a valid Air Waybill number.', 'error');
    return;
  }

  // Show loading state
  const trackButton = document.querySelector('.btn-track');
  const originalText = trackButton.textContent;
  trackButton.textContent = 'TRACKING...';
  trackButton.disabled = true;

  // Make tracking request
  fetch(`tracker.php?awb=${encodeURIComponent(awb)}`)
    .then(response => {
      if (response.ok) {
        window.location.href = response.url || `tracker.php?awb=${encodeURIComponent(awb)}`;
      } else {
        throw new Error('Tracking failed');
      }
    })
    .catch(error => {
      showAlert('Error fetching tracking information. Please try again.', 'error');
      trackButton.textContent = originalText;
      trackButton.disabled = false;
    });
}

/**
 * Validate AWB format (11-digit number, optional hyphens)
 */
function isValidAWB(awb) {
  // AWB format: 11 digits, may include hyphens or spaces
  const awbRegex = /^\d{1,3}[-\s]?\d{1,4}[-\s]?\d{1,4}[-\s]?\d{1}$/;
  return awbRegex.test(awb.replace(/[\s-]/g, ''));
}

/**
 * Display alert message
 */
function showAlert(message, type = 'info') {
  // Create alert container if it doesn't exist
  let alertContainer = document.querySelector('.alert-container');
  if (!alertContainer) {
    alertContainer = document.createElement('div');
    alertContainer.className = 'alert-container';
    document.body.insertBefore(alertContainer, document.body.firstChild);
  }

  // Create alert element
  const alert = document.createElement('div');
  alert.className = `alert alert-${type}`;
  alert.innerHTML = `
    <span>${message}</span>
    <button class="alert-close">&times;</button>
  `;

  alertContainer.appendChild(alert);

  // Auto-remove after 5 seconds
  setTimeout(() => {
    alert.remove();
  }, 5000);

  // Manual close
  alert.querySelector('.alert-close').addEventListener('click', function() {
    alert.remove();
  });
}
