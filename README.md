# Airsell Web Portal 📦✈️
**General Cargo & Logistics Management System**

The Airsell Web Portal is a digital manifestation and tracking system designed to streamline freight forwarding operations in Hargeisa and across East Africa.

## 🚀 Core Features
* **Real-time Tracking:** IATA Resolution 600a standardized shipment monitoring with ONE Record integration.
* **Operational Dashboard:** Internal tools for manifest management.
* **Service Portals:** Dedicated sections for General Cargo, Livestock, and Sea Freight.
* **Mobile-First Design:** Fully responsive for field operations at the airport or port.
* **Secure API Integration:** Environment-based configuration with XSS protection.

## 🛠 Tech Stack
* **Frontend:** HTML5, CSS3 (Flexbox/Grid), JavaScript.
* **Backend:** PHP 7.4+ with secure credential management.
* **Tracking API:** ONE Record standard for cargo events.
* **Branding:** Standardized Red (#d32f2f), Yellow (#f9da43), and Green (#007a33) palette.
* **Compliance:** ISO 8601 for date/time and IATA standards for logistics data.

## 📂 Project Structure
```
airsell-web-portal/
├── index.html              # Main landing page
├── tracker.php             # Shipment tracking timeline
├── .env.example            # Environment configuration template
├── .gitignore              # Git exclusion rules
├── css/                    # Stylesheets for brand identity
├── js/                     # Tracking logic and UI interactivity
└── assets/                 # Company logos and operational icons
```

## 🔧 Setup & Installation

### Prerequisites
- PHP 7.4 or higher
- Web server (Apache/Nginx)
- CARGO_API_KEY from ONE Record provider

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Airsell-Cargo/airsell-web-portal.git
   cd airsell-web-portal
   ```

2. **Configure environment variables**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and add your API credentials:
   ```bash
   CARGO_API_KEY=your_actual_api_key_here
   ```
   ⚠️ **IMPORTANT:** Never commit `.env` to version control.

3. **Verify `.gitignore` is configured**
   - Ensure `.env` is excluded from git tracking
   - The `.gitignore` file automatically prevents sensitive files from being committed

4. **Deploy to web server**
   ```bash
   chmod 755 .
   chmod 644 *.php *.html
   chmod 755 css/ js/ assets/
   ```
   - Set appropriate file permissions for directories and files
   - Ensure `error_log` is configured to write to a secure location

5. **Configure PHP error logging** (production)
   Edit your web server's PHP configuration:
   ```ini
   display_errors = Off
   error_log = /var/log/php-errors.log
   log_errors = On
   ```

## 📖 Usage

### Tracking a Shipment
Access the tracker with a shipment ID:
```
https://yourdomain.com/tracker.php?piece_id=ABC123XYZ
```

**Parameters:**
- `piece_id` (required): The shipment tracking number (e.g., "ABC123XYZ")

**Response:**
- Timeline of tracking events with dates, locations, and status
- Error handling for invalid IDs or API failures
- Fully accessible for screen readers and keyboard navigation

**Example:**
```
https://airsell-cargo.example.com/tracker.php?piece_id=AIR-2024-001234
```

### API Integration
The tracker uses the `getTrackingTimeline()` function to fetch events from the ONE Record API:
```php
$events = getTrackingTimeline($pieceId, getenv('CARGO_API_KEY'));
```

**Response Format:**
Each event contains:
- `cargo:eventDate` - ISO 8601 timestamp
- `cargo:eventCode` - Event code (e.g., "PKG", "FOH", "DLV")
- `cargo:recordedAtLocation.cargo:locationCode` - Location IATA code

## 🔒 Security Features
✅ **Environment-based API key management** - No hardcoded credentials
✅ **XSS protection** - All output escaped with `htmlspecialchars()`
✅ **Input validation** - Query parameters validated before use
✅ **Error logging** - Sensitive info logged, not exposed to users
✅ **HTTPS recommended** - For all production deployments
✅ **Git protection** - `.env` and sensitive files excluded from version control

## ♿ Accessibility
✅ **ARIA labels** - Screen reader support for timeline structure
✅ **Semantic HTML** - `<time>` elements for date/time data
✅ **Keyboard navigation** - Full Tab and Enter key support
✅ **Focus indicators** - Clear visual feedback for keyboard users
✅ **Color-independent** - Status indicators don't rely on color alone

## 🧪 Testing Checklist

### Functional Testing
- [ ] Valid tracking ID displays timeline correctly
- [ ] Missing `piece_id` parameter shows error message
- [ ] Invalid ID returns "No tracking events" message
- [ ] API errors are handled gracefully without exposing keys

### Security Testing
- [ ] XSS payload in query string: `piece_id=<script>alert('test')</script>`
- [ ] Verify `.env` file is not tracked in git
- [ ] Confirm no API keys are logged or displayed to users
- [ ] Check HTTPS is enforced on production

### Accessibility Testing
- [ ] Screen reader reads timeline events correctly (NVDA/JAWS)
- [ ] Keyboard navigation works (Tab through timeline items)
- [ ] Focus indicators are visible
- [ ] Color-blind users can distinguish status indicators

### Mobile Testing
- [ ] Timeline displays correctly on mobile (< 480px)
- [ ] Touch interactions work properly
- [ ] No horizontal scroll on mobile devices

## 📝 Development Guidelines

### Code Standards
- Use PHP 7.4+ syntax and features
- Follow PSR-12 coding standards
- Always escape output: `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`
- Validate and sanitize all user input
- Log errors to file, never display to users

### Adding New Features
1. Create a new branch: 
   ```bash
   git checkout -b feature/your-feature-name
   ```
2. Test thoroughly with the checklist above
3. Submit a pull request with detailed descriptions
4. Request review from team members before merging

### Code Review Checklist
- [ ] No hardcoded secrets or credentials
- [ ] All user input is validated
- [ ] All output is properly escaped
- [ ] Error handling is present
- [ ] Tests pass (if applicable)
- [ ] Documentation is updated

## 📊 Performance Considerations
- **Caching:** Consider implementing Redis/Memcached for tracking events
- **Pagination:** Implement for shipments with many events
- **API Rate Limiting:** Monitor and implement backoff strategies
- **HTTPS Only:** Use HTTP/2 for improved performance
- **Database:** Index tracking ID lookups for faster queries

## 🐛 Bug Reports & Feature Requests
Report issues via GitHub Issues with:
- Detailed description of the problem
- Steps to reproduce
- Expected vs. actual behavior
- Browser/environment information
- Screenshots/logs if applicable

**Template:**
```
## Description
Brief description of the issue.

## Steps to Reproduce
1. Go to...
2. Click on...
3. Observe...

## Expected Behavior
What should happen.

## Actual Behavior
What actually happens.

## Environment
- Browser: Chrome 120.0
- OS: Windows 11
- PHP Version: 8.1
```

## 🚀 Deployment Guide

### Development
```bash
git clone ...
cp .env.example .env
# Edit .env with development API key
php -S localhost:8000
```

### Staging/Production
```bash
# On server
git pull origin main
# Verify .env exists with correct API key
# Verify file permissions are set correctly
# Test tracker functionality
```

### Monitoring
- Monitor `error_log` for PHP errors
- Track API response times
- Set up alerts for failed API requests
- Monitor disk space for logs

## 📧 Contact & Support
For operational inquiries or technical access:
- **Administrator:** Hamse Dahir
- **Email:** airsellcargo@gmail.com
- **Repository:** [Airsell-Cargo/airsell-web-portal](https://github.com/Airsell-Cargo/airsell-web-portal)

## 📄 License
[Specify your license: MIT, Apache 2.0, Proprietary, etc.]

---
**Last Updated:** June 3, 2026
**Status:** ✅ Production Ready
**Version:** 2.0 (Security & Accessibility Enhanced)
