# Security Policy

## 🔒 Security Overview
Airsell Web Portal implements industry-standard security practices to protect sensitive cargo tracking data and API integrations.

## Reporting Security Vulnerabilities

### Responsible Disclosure
If you discover a security vulnerability, please report it responsibly:

1. **DO NOT** create a public GitHub issue
2. Email: **airsellcargo@gmail.com** with subject: `[SECURITY] Vulnerability Report`
3. Include:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Suggested remediation (if available)

We will respond within 48 hours and work on a fix.

## Security Best Practices

### API Key Management ✅
- Store `CARGO_API_KEY` in environment variables, never in code
- Use `.env.example` as template, never commit `.env`
- Rotate API keys regularly (quarterly recommended)
- Use separate keys for development, staging, and production
- Monitor API usage for unusual activity
- Revoke old keys immediately after rotation

### Data Protection ✅
- Enable HTTPS on all deployments (enforced on production)
- Use TLS 1.2 or higher (TLS 1.3 preferred)
- Implement HSTS headers (see DEPLOYMENT.md)
- Never log API responses containing sensitive data
- Encrypt sensitive data at rest when stored
- Use secure cookies for session management (if applicable)

### Input Validation ✅
- Validate all query parameters (`piece_id`)
- Use allowlists for accepted characters/patterns
- Escape all output with `htmlspecialchars()`
- Use parameterized queries (if database queries are used)
- Implement rate limiting on tracking endpoints
- Reject overly long inputs

### Output Encoding ✅
All output must be properly escaped:
```php
// ✅ Correct - HTML escaped
<?php echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); ?>

// ❌ Wrong - Unescaped output
<?php echo $data; ?>
```

### Error Handling ✅
- Log errors to file, never display to users
- Don't expose API keys in error messages
- Don't expose database errors or server paths
- Provide generic error messages to users
- Monitor logs for suspicious patterns
- Implement alerting for multiple failed requests

### Database Security ✅ (When Used)
- Use parameterized queries / prepared statements
- Never concatenate user input into queries
- Use principle of least privilege for DB accounts
- Restrict database access by IP if possible
- Enable query logging for audit trail

## Known Vulnerabilities
None currently. Please report any findings responsibly.

## Security Update Schedule

### Regular Updates
- **Monthly:** Review dependencies and apply patches
- **Quarterly:** Rotate API keys
- **Semi-annually:** Security audit and assessment
- **Annually:** Penetration testing

### Monitoring
- Enable [GitHub Security Advisories](https://github.com/security/advisories)
- Monitor PHP security releases
- Subscribe to OWASP mailing lists
- Use Dependabot for automated dependency updates

## Compliance

### IATA Standards
- Resolution 600a compliance for cargo events
- Standardized event codes and timestamps
- Location IATA code validation

### Data Privacy
- Follow applicable data protection regulations (GDPR, etc.)
- Implement data retention policies
- Support user data export requests
- Document consent for data collection

### Security Standards
- HTTPS/TLS required on all production deployments
- No plaintext credential storage
- Secure session management
- Access control and authentication

## Security Testing

### Manual Testing
```bash
# Test XSS protection
curl "http://localhost:8000/tracker.php?piece_id=<script>alert('test')</script>"

# Test missing API key error handling
unset CARGO_API_KEY && php tracker.php

# Test with invalid tracking ID
curl "http://localhost:8000/tracker.php?piece_id=INVALID_ID"

# Test authentication (if applicable)
curl -H "Authorization: Bearer invalid_token" https://cargo.example.com/tracker.php
```

### Automated Testing
```bash
# OWASP ZAP scanning
zaproxy -cmd -quickurl https://cargo.example.com -quickout results.html

# PHP CodeSniffer for code standards
phpcs --standard=PSR12 tracker.php

# Dependency vulnerability scanning
composer audit  # If using Composer
```

### Continuous Security
- GitHub CodeQL analysis (included with GitHub Advanced Security)
- Dependabot alerts for dependencies
- SARIF format security reports

## Security Checklist for Production Deployments

### Before Going Live
- [ ] `.env` file is NOT committed to git (verify with `git log`)
- [ ] `.gitignore` excludes `.env` and other sensitive files
- [ ] HTTPS is enforced with valid SSL certificate
- [ ] `CARGO_API_KEY` is set in production environment
- [ ] Error logging is configured and permissions set
- [ ] PHP `display_errors = Off` in php.ini
- [ ] File permissions are set correctly (644 for files, 755 for directories)
- [ ] No debug information exposed in UI error messages
- [ ] Security headers are set (see DEPLOYMENT.md)
- [ ] HSTS is enabled (strict-transport-security)
- [ ] Content-Security-Policy header is configured
- [ ] X-Frame-Options prevents clickjacking
- [ ] Log files are not web-accessible
- [ ] Backup strategy is in place
- [ ] Monitoring and alerting is configured

### Ongoing
- [ ] Weekly: Review error logs for suspicious patterns
- [ ] Monthly: Check for available security patches
- [ ] Quarterly: Rotate API keys
- [ ] Quarterly: Review access logs
- [ ] Annually: Security assessment and penetration testing

## Incident Response

### If a Vulnerability is Discovered
1. **Immediately** isolate the affected system from production
2. **Assess** the scope and potential impact
3. **Notify** affected users and stakeholders
4. **Develop** and test a fix
5. **Deploy** the fix to production
6. **Verify** the vulnerability is remediated
7. **Document** the incident and lessons learned
8. **Review** and update security policies

### Incident Contact
**Email:** airsellcargo@gmail.com  
**Response Time:** Within 24 hours

## Third-Party Dependencies

### PHP Dependencies
Monitor for security updates:
- PHPUnit (if testing framework is used)
- Database drivers
- Any external libraries

### JavaScript Dependencies
- Keep browser compatibility libraries updated
- Monitor npm audit for vulnerabilities

## Compliance Documentation

### IATA Compliance
- Event timeline follows Resolution 600a standard
- Event codes match official IATA specifications
- Timestamp format is ISO 8601 compliant

### GDPR Compliance (if applicable)
- User consent for data collection
- Right to access their data
- Right to be forgotten
- Data export capabilities

## Security Contacts

| Role | Contact | Response Time |
|------|---------|----------------|
| Security Lead | airsellcargo@gmail.com | 24 hours |
| Administrator | Hamse Dahir | 48 hours |

## Additional Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [NIST Cybersecurity Framework](https://www.nist.gov/cyberframework)
- [CWE Top 25](https://cwe.mitre.org/top25/)

---
**Last Updated:** June 3, 2026  
**Status:** ✅ Active
