#  SECURITY_PRIVACY - Security & Privacy Design Doc

Security measures and privacy compliance for DreamSpace.

---

##  Inhoudsopgave

1. [Security Overview](#security-overview)
2. [Authentication System](#authentication-system)
3. [Authorization & Access Control](#authorization--access-control)
4. [Data Protection](#data-protection)
5. [Privacy Compliance](#privacy-compliance)
6. [Security Best Practices](#security-best-practices)
7. [Incident Response](#incident-response)

---

##  Security Overview

### Security Principles
```
1. CIA Triad
   ├─ Confidentiality: Only authorized users see data
   ├─ Integrity: Data cannot be modified by unauthorized users
   └─ Availability: System is reliable and accessible

2. Defense in Depth
   ├─ Multiple layers of security
   ├─ If one layer fails, others protect
   └─ No single point of failure

3. Principle of Least Privilege
   ├─ Users get minimum permissions needed
   ├─ Admin accounts for sensitive tasks only
   └─ No unnecessary access granted
```

---

##  Authentication System

### Password Hashing

#### Implementation
```
Algorithm: Bcrypt
Salt Rounds: 10
Cost Factor: 2^10 = 1024 iterations
Hash Length: 60 characters

Example:
  Plain: "MyPassword123!"
  Hash:  "$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6"
```

#### Why Bcrypt?
```
✓ Slow by design (adaptive cost factor)
✓ Resistant to rainbow tables
✓ Protective against GPU attacks
✓ No key needed (unlike HMAC)
✓ Industry standard for password hashing
```

#### Password Requirements
```
Minimum length: 8 characters
Must contain:
  ✓ Uppercase letter (A-Z)
  ✓ Lowercase letter (a-z)
  ✓ Number (0-9)
  ✓ Special character (!@#$%^&*)
  ✓ No spaces allowed
  
Examples:
  ✓ Valid: Password123!
  ✗ Invalid: password123 (no uppercase)
  ✗ Invalid: Pass123 (special char missing)
```

### JWT Token Authentication

#### Token Structure
```
Header.Payload.Signature

Header (Base64):
{
  "alg": "HS256",
  "typ": "JWT"
}

Payload (Base64):
{
  "userid": 1,
  "username": "ShadowSlayer",
  "role": "Speler",
  "iat": 1709471400,
  "exp": 1709472300
}

Signature (HMAC-SHA256):
HMAC-SHA256(
  Secret_Key,
  Base64URL(header) + "." + Base64URL(payload)
)
```

#### Token Lifecycle
```
┌─────────────┐
│ User Login  │
└──────┬──────┘
       │
       ▼
┌──────────────────────────────────┐
│ Server generates JWT             │
│ - Expiry: 15 minutes             │
│ - Signature: Secret key          │
│ - Payload: userid, role          │
└──────┬─────────────────────────────┘
       │
       │─→ Access Token (short-lived)
       │
       │   ┌─────────────────────────┐
       │   │ Also generate:          │
       │   │ Refresh Token (7 days)  │
       │   │ Store in HTTP-only      │
       │   │ cookie (secure)         │
       │   └─────────────────────────┘
       │
       ▼
Client stores Access Token:
  - localStorage (vulnerable to XSS)
  - sessionStorage (cleared on close)
  
OR

Server stores Refresh Token:
  - HTTP-only cookie (secure, httpOnly flag)
  - Can't be accessed by JavaScript
  - Automatically sent with requests
```

#### Token Validation Flow
```
Client sends request:
  Headers: { Authorization: "Bearer {ACCESS_TOKEN}" }
                              ↓
Server receives request:
  1. Extract token from header
  2. Verify signature with secret key
  3. Check expiration (iat + exp)
  4. Extract user data (userid, role)
  5. Attach to request object
  6. Pass to next middleware
                              ↓
If valid:  Process request with user context
If invalid:  Return 401 Unauthorized
If expired: → Use refresh token to get new access token
```

#### Refresh Token Strategy
```
Access Token: 15 minutes
└─ Short-lived
└─ JWT in localStorage
└─ Sent with each request

Refresh Token: 7 days
└─ Long-lived
└─ Stored in HTTP-only cookie
└─ Used to get new access token
└─ Can be revoked per user

Flow:
  User logs in
    ↓ Receive: Access + Refresh tokens
    ↓ Access token expires after 15 min
    ↓ (/api/auth/refresh with refresh token)
    ↓ Receive new access token
    ↓ User stays logged in
    ↓ Refresh token expires after 7 days
    ↓ User must log in again
```

---

##  Authorization & Access Control

### Role-Based Access Control (RBAC)

#### Roles Defined
```
┌─────────────────────┐
│      SPELER         │ (Regular Player)
├─────────────────────┤
│ Can:                │
│ ✓ View items        │
│ ✓ Manage inventory  │
│ ✓ Trade items       │
│ ✓ View notifications│
│ ✓ Edit own profile  │
│ Cannot:             │
│ ✗ Create items      │
│ ✗ Delete users      │
│ ✗ View statistics   │
└─────────────────────┘

┌─────────────────────┐
│     BEHEERDER       │ (Administrator)
├─────────────────────┤
│ Can:                │
│ ✓ Do everything     │
│ ✓ Create items      │
│ ✓ Edit all items    │
│ ✓ Delete items      │
│ ✓ Manage users      │
│ ✓ View statistics   │
│ ✓ Assign items      │
│ ✓ Export reports    │
│ Cannot:             │
│ ✗ Access databases  │
│ ✗ System config     │
└─────────────────────┘
```

#### Authorization Middleware
```javascript
// Middleware for admin-only routes
function authorize(requiredRole) {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({ error: 'Not authenticated' });
    }
    
    if (req.user.role !== requiredRole) {
      return res.status(403).json({ error: 'Insufficient permissions' });
    }
    
    next();
  };
}

// Usage
app.post('/api/items', authenticate, authorize('Beheerder'), createItem);
```

#### Per-Resource Authorization
```javascript
// User can only view/edit own profile
app.get('/api/users/:userid', authenticate, (req, res) => {
  if (req.params.userid != req.user.userid && req.user.role !== 'Beheerder') {
    return res.status(403).json({ error: 'Cannot view other profiles' });
  }
  
  // Allow access
  getUserProfile(req.params.userid);
});
```

---

##  Data Protection

### Data at Rest (Stored)

#### Password Storage
```
✓ Bcrypt hashed (10 rounds)
✓ Never stored in plaintext
✓ Random salt per password
✓ Salted hash combination prevents rainbow tables
```

#### Sensitive Data Handling
```
Type              Storage                    Encryption
─────────────────────────────────────────────────────────
Passwords         Database (hashed)          Bcrypt
User tokens       Database & client          JWT (signed)
Credit cards      Don't store!               Third-party only
Email addresses   Database (plaintext)       SSL/TLS in transit
Inventory data    Database (plaintext)       Role-based access
```

### Data in Transit (Network)

#### HTTPS/TLS Encryption
```
Communication:
  Client ←→ Server (HTTPS)
  
TLS Version: 1.2 or higher
Cipher Suite: Modern (AES-256-GCM)
Certificate: Valid, properly signed

Without HTTPS:
  Client sends: { username: "user", password: "pass123" }
  Over network: Plain text! 
  
With HTTPS:
  Client sends: { username: "user", password: "pass123" }
  Over network: Encrypted binary data 
```

### Data in Memory (Runtime)

#### "Defense in Depth"
```
1. Never log passwords/tokens
    console.log(password)
   ✓ console.log('Password accepted')

2. Clear sensitive variables after use
   const password = req.body.password;
   // ... use password ...
   password = null; // Clear from memory

3. Use HTTPS only (no HTTP fallback)
   if (process.env.NODE_ENV === 'production') {
     app.use((req, res, next) => {
       if (!req.secure) return res.redirect('https://...');
       next();
     });
   }

4. HTTP security headers
   Strict-Transport-Security  (force HTTPS)
   X-Content-Type-Options     (prevent MIME sniffing)
   X-Frame-Options            (prevent clickjacking)
   Content-Security-Policy    (XSS prevention)
```

### SQL Injection Prevention

####  Vulnerable Code
```javascript
// NEVER do this!
const username = req.body.username;
const query = `SELECT * FROM GEBRUIKER WHERE username = '${username}'`;
db.query(query);

// Attacker input: ' OR '1'='1
// Query becomes: SELECT * FROM GEBRUIKER WHERE username = '' OR '1'='1'
// Returns ALL users! 
```

####  Secure Code
```javascript
// Use parameterized queries
const username = req.body.username;
const query = 'SELECT * FROM GEBRUIKER WHERE username = ?';
db.query(query, [username]);

// Attacker input: ' OR '1'='1
// Query treats input as literal string, not SQL code ✓
```

### XSS Prevention

#### Vulnerable
```javascript
// User input: <img src=x onerror="alert('hacked')">
const userComment = req.body.comment;
res.send(`<div>${userComment}</div>`);
// Executes JavaScript! 
```

#### Secure
```javascript
// Use template escaping in React/Vue
const userComment = req.body.comment;
// React automatically escapes by default
<div>{userComment}</div> // Safe

// Or manually escape
const escaped = userComment
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;')
  .replace(/'/g, '&#x27;');
res.send(`<div>${escaped}</div>`) // Safe
```

### CSRF Prevention

#### What is CSRF?
```
Attacker tricks user into clicking link while logged in:
  User logged in on: evil.com
  Evil site has: <img src="dreamspace.com/api/trades/delete/123">
  Without CSRF token: Trade GET deleted by accident!
```

#### CSRF Token Protection
```javascript
// Server generates unique token per session
app.get('/login', (req, res) => {
  const csrfToken = generateRandomToken();
  req.session.csrfToken = csrfToken;
  res.send(`<form method="POST" action="/api/trades">
    <input type="hidden" name="_csrf" value="${csrfToken}">
    <input type="submit">
  </form>`);
});

// Server validates token on state-changing requests
app.post('/api/trades', (req, res) => {
  if (req.body._csrf !== req.session.csrfToken) {
    return res.status(403).json({ error: 'Invalid CSRF token' });
  }
  // Process request
});
```

---

##  Privacy Compliance

### Data Collection

#### What We Collect
```
Necessary:
  ✓ Username (login identification)
  ✓ Email (account recovery, notifications)
  ✓ Password hash (authentication)
  ✓ Role (authorization)
  ✓ Inventory (core functionality)
  ✓ Trade history (user data)

NOT Collected:
  ✗ IP addresses (no tracking)
  ✗ Location data (no GPS)
  ✗ Browsing history (no analytics)
  ✗ Device info (no fingerprinting)
```

#### Data Minimization
```
Sluipers we DON'T implement:
   Cookie tracking
   Analytics scripts
   Third-party ad networks
   Session recording
   User behavior tracking
  
Result: Users' privacy respected ✓
```

### User Rights (GDPR Compliant)

#### Right to Access
```
Users can request: "Show me all my data"

API Endpoint:
  GET /api/users/me/export

Returns ZIP with:
  - Personal data (username, email, account info)
  - Inventory items
  - Trade history
  - Notification history
  - All associated data
```

#### Right to Deletion ("Right to be Forgotten")
```
Users can request: "Delete my account"

API Endpoint:
  DELETE /api/users/me/delete-account

What happens:
  1. All personal data deleted
  2. Inventory deleted
  3. Pending trades cancelled
  4. Account marked inactive (not actually deleted yet for audit trail)
  5. Confirmation email sent
  6. User logged out
  
Data retained for legal reasons (7 years):
  - Trade completion records (anonymized)
  - Audit logs (no personal info)
```

#### Right to Rectification
```
Users can correct: "My email is wrong"

API Endpoint:
  PUT /api/users/me/profile

Can change:
  ✓ Email address
  ✓ Username (if not taken)
  ✗ Password is separate endpoint
  
Cannot change:
  ✗ Created date (for audit)
  ✗ Role (admin-only)
```

### Privacy Policy

#### Key Sections
```
1. Data We Collect
   - Explain what & why

2. How We Use It
   - For authentication
   - For inventory management
   - For trading functionality
   - For admin oversight

3. How Long We Keep It
   - Active accounts: as long as active
   - Deleted accounts: 7 years (legal requirement, anonymized)

4. Who We Share With
   - No third parties
   - No advertisers
   - Only us!

5. Your Rights
   - Access
   - Correction
   - Deletion
   - How to exercise them

6. Security
   - How we protect data
   - Encryption
   - Access controls
```

---

##  Security Best Practices

### Development

 **DO:**
```
☐ Validate ALL user input (client & server)
☐ Use parameterized queries for database
☐ Keep dependencies updated
☐ Use environment variables for secrets
☐ Enable HTTPS in production
☐ Use strong random tokens (crypto.randomBytes)
☐ Log security events
☐ Code review all changes
☐ Test security scenarios
☐ Handle errors safely (don't expose stack traces)
```

 **DON'T:**
```
☐ Don't log passwords/tokens
☐ Don't hardcode secrets in code
☐ Don't trust client-side validation alone
☐ Don't expose internal error messages
☐ Don't use old algorithms (MD5, SHA1)
☐ Don't enable debug mode in production
☐ Don't trust user input (always sanitize)
☐ Don't disable security features
☐ Don't use HTTP in production
☐ Don't delay security updates
```

### Deployment

```
1. Environment Variables (.env)
   JS_SECRET=long_random_string_generated_securely
   DB_PASSWORD=strong_password_12345!@#
   NODE_ENV=production
   HTTPS=true

2. Database
   ☐ Backup daily
   ☐ Encryption at rest
   ☐ Strong access controls
   ☐ No public access

3. Application
   ☐ Enable all security headers
   ☐ SSL/TLS certificate (Let's Encrypt)
   ☐ Firewall rules
   ☐ Monitor logs

4. Access Control
   ☐ SSH key authentication (no passwords)
   ☐ Minimal admin accounts
   ☐ Audit all admin actions
   ☐ Disable unnecessary services
```

---

##  Incident Response

### Security Incident Plan

#### If Password Breach
```
1. Immediately:
   ☐ Notify affected users
   ☐ Force password reset
   ☐ Review logs for unauthorized access

2. Within 24 hours:
   ☐ Analysis: How did it happen?
   ☐ Patch: Fix the vulnerability
   ☐ Notify authorities if required

3. Follow-up:
   ☐ Implement prevention measures
   ☐ Update security practices
   ☐ Review logs for other breaches
```

#### If Data Leak
```
1. Immediately:
   ☐ Isolate affected systems
   ☐ Preserve evidence
   ☐ Stop leakage

2. Assess:
   ☐ What data was exposed?
   ☐ Who was affected?
   ☐ How long was it accessible?

3. Notify:
   ☐ Users (within 72 hours, legally required)
   ☐ Authorities (if required by law)
   ☐ Customers/partners

4. Fix:
   ☐ Patch vulnerability
   ☐ Deploy fix
   ☐ Monitor for abuse
```

#### If System Under Attack (DDoS)
```
1. Activate:
   ☐ Inform team
   ☐ Enable DDoS protection
   ☐ Notify hosting provider

2. Mitigate:
   ☐ Rate limiting
   ☐ IP blocking
   ☐ Traffic filtering

3. Recovery:
   ☐ Scale infrastructure
   ☐ Restore services
   ☐ Post-mortem analysis
```

---

##  Security Checklist

**Before Going to Production:**

```
Authentication
  ☐ Passwords are hashed (Bcrypt, 10+ rounds)
  ☐ JWT tokens have proper expiration
  ☐ Refresh tokens secure (HTTP-only cookies)
  ☐ Login rate limiting implemented
  
Authorization
  ☐ RBAC implemented (Speler, Beheerder)
  ☐ Admin endpoints protected
  ☐ User can only access own data
  ☐ Proper error messages (no info leaks)
  
Data Protection
  ☐ HTTPS enforced in production
  ☐ SQL injection prevention (parameterized queries)
  ☐ XSS prevention (input encoding)
  ☐ CSRF tokens on state-changing requests
  
Infrastructure
  ☐ Environment variables for secrets
  ☐ Database backups automated
  ☐ Logs are monitored
  ☐ Security headers configured
  
Testing
  ☐ Security test cases included
  ☐ Vulnerability scan completed
  ☐ Dependency audit (npm audit)
  ☐ Penetration testing done
```

---

**Version:** 1.0  
**Last Updated:** March 3, 2026
