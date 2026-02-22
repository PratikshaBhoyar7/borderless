# ✅ API Token-Based Authentication

## Overview

The Borderless Patient Analytics API now uses **Bearer Token authentication** for secure API access. This replaces session-based authentication and provides better security for external clients and integrations.

---

## 🔑 Token-Based Authentication System

### What is a Token?

An API token is a unique string that identifies your application. Instead of sending credentials on every request, you authenticate once to get a token, then use that token for subsequent requests.

**Token Example**:
```
smrrPZ5dNCoqdbKfa8Z6RbLUGBoZCBwggBXZBaRHO7oJCHAQEa0kBpSNqyxt
```

---

## 🚀 How to Use

### Step 1: Get a Token (Login)

**Endpoint**: `POST /api/auth/login`

**Request**:
```bash
curl -X POST 'http://localhost:8000/api/auth/login' \
  -H 'Content-Type: application/json' \
  -d '{
    "email": "admin@admin.com",
    "password": "password"
  }'
```

**Response** (200 OK):
```json
{
  "success": true,
  "message": "Authenticated successfully",
  "token": "smrrPZ5dNCoqdbKfa8Z6RbLUGBoZCBwggBXZBaRHO7oJCHAQEa0kBpSNqyxt",
  "token_type": "Bearer",
  "expires_in": 86400,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@admin.com",
    "role": "user"
  }
}
```

**Save the token** for subsequent requests!

---

### Step 2: Use the Token (Authorization Header)

**Format**: `Authorization: Bearer {token}`

**Example**:
```bash
curl -X GET 'http://localhost:8000/api/auth/me' \
  -H 'Authorization: Bearer smrrPZ5dNCoqdbKfa8Z6RbLUGBoZCBwggBXZBaRHO7oJCHAQEa0kBpSNqyxt' \
  -H 'Content-Type: application/json'
```

**Response** (200 OK):
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@admin.com",
    "role": "user"
  }
}
```

---

## 📚 Complete API Endpoints

### Authentication Endpoints

#### 1. Login - Get Token
```
POST /api/auth/login
```

**Request Body**:
```json
{
  "email": "admin@admin.com",
  "password": "password"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Authenticated successfully",
  "token": "YOUR_TOKEN_HERE",
  "token_type": "Bearer",
  "expires_in": 86400,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@admin.com",
    "role": "user"
  }
}
```

**Status Codes**:
- `200` - Success
- `401` - Invalid credentials
- `422` - Validation error

---

#### 2. Check Status (No Token Required)
```
GET /api/auth/check
```

**Response** (Authenticated):
```json
{
  "authenticated": true,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@admin.com",
    "role": "user"
  }
}
```

**Response** (Not Authenticated):
```json
{
  "authenticated": false,
  "message": "Not authenticated"
}
```

---

#### 3. Get Current User (Token Required)
```
GET /api/auth/me
Authorization: Bearer {token}
```

**Response**:
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@admin.com",
    "role": "user"
  }
}
```

---

#### 4. Logout / Revoke Token (Token Required)
```
POST /api/auth/logout
Authorization: Bearer {token}
```

**Response**:
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

#### 5. Refresh Token (Token Required)
```
POST /api/auth/refresh
Authorization: Bearer {token}
```

**Response**:
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "token": "NEW_TOKEN_HERE",
  "token_type": "Bearer",
  "expires_in": 86400,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@admin.com",
    "role": "user"
  }
}
```

---

## 🔒 Authorization Header Format

All protected endpoints require the `Authorization` header with Bearer token:

```
Authorization: Bearer {your_token_here}
```

### Examples in Different Languages

**cURL**:
```bash
curl -H "Authorization: Bearer your_token_here" \
  https://api.example.com/api/auth/me
```

**JavaScript/Fetch**:
```javascript
fetch('/api/auth/me', {
  headers: {
    'Authorization': 'Bearer your_token_here',
    'Content-Type': 'application/json'
  }
})
```

**Python**:
```python
import requests

headers = {
    'Authorization': 'Bearer your_token_here',
    'Content-Type': 'application/json'
}
response = requests.get('/api/auth/me', headers=headers)
```

**PHP**:
```php
$headers = [
    'Authorization: Bearer your_token_here',
    'Content-Type: application/json'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
```

---

## 📋 All Analytics Endpoints (With Token)

All analytics endpoints now require the Bearer token:

```bash
# Get stats
curl -X GET 'http://localhost:8000/admin/analytics/api/stats' \
  -H 'Authorization: Bearer {token}' \
  -H 'Content-Type: application/json'

# Get demographics
curl -X GET 'http://localhost:8000/admin/analytics/api/demographics' \
  -H 'Authorization: Bearer {token}'

# Get health metrics
curl -X GET 'http://localhost:8000/admin/analytics/api/health-metrics' \
  -H 'Authorization: Bearer {token}'

# Get patients (paginated)
curl -X GET 'http://localhost:8000/admin/analytics/api/patients?page=1&per_page=10' \
  -H 'Authorization: Bearer {token}'
```

---

## 🧪 Testing with Postman

### Step 1: Login Request

1. Create new **POST** request to `http://localhost:8000/api/auth/login`
2. Set Body to JSON:
   ```json
   {
     "email": "admin@admin.com",
     "password": "password"
   }
   ```
3. Send and copy the `token` from response

### Step 2: Use Token in Other Requests

1. Create new request to any protected endpoint
2. Go to **Authorization** tab
3. Select **Bearer Token** from dropdown
4. Paste your token
5. Send request

**Or manually add header**:
- Header: `Authorization`
- Value: `Bearer your_token_here`

---

## ⏱️ Token Expiration

- **Default**: 24 hours (86400 seconds)
- **When Expired**: Get 401 Unauthorized error
- **Solution**: Use refresh endpoint to get new token

### Refresh Token Flow

```bash
# When token expires, refresh it
curl -X POST 'http://localhost:8000/api/auth/refresh' \
  -H 'Authorization: Bearer old_token_here' \
  -H 'Content-Type: application/json'

# Response with new token
{
  "token": "new_token_here",
  "token_type": "Bearer",
  "expires_in": 86400
}
```

---

## 🔐 Security Features

### Token Storage
- **Hashed** in database using SHA-256
- **Unique** per user
- **Revoked** on logout

### Token Transmission
- **Always** use HTTPS in production
- **Never** expose token in URLs
- **Always** use Authorization header

### Best Practices

✅ **DO**:
- Store token securely (encrypted storage)
- Use Authorization header
- Refresh token before expiration
- Revoke token on logout
- Use HTTPS

❌ **DON'T**:
- Send credentials in every request
- Store token in plain text
- Use token in URL parameters
- Share token with others
- Use HTTP (always use HTTPS)

---

## 📊 Error Responses

### 401 Unauthorized

**Missing Token**:
```json
{
  "message": "Unauthorized"
}
```

**Invalid Token**:
```json
{
  "message": "Unauthorized"
}
```

**Expired Token**:
```json
{
  "message": "Unauthorized"
}
```

**Solution**: Login again to get a new token

### 422 Validation Error

**Missing Email or Password**:
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### 500 Server Error

```json
{
  "success": false,
  "message": "Authentication error",
  "error": "Error message"
}
```

---

## 🔄 Complete Authentication Flow

```
1. Client Requests Login
   POST /api/auth/login
   { "email": "admin@admin.com", "password": "password" }

2. Server Validates Credentials
   ✓ Email found
   ✓ Password correct

3. Server Generates Token
   token = "unique_random_string_60_chars"
   hashed = sha256(token)

4. Server Stores Hashed Token
   UPDATE users SET api_token = hashed WHERE id = 1

5. Server Returns Token to Client
   {
     "success": true,
     "token": "unique_random_string_60_chars",
     "expires_in": 86400
   }

6. Client Makes Protected Request
   GET /api/auth/me
   Authorization: Bearer unique_random_string_60_chars

7. Server Verifies Token
   received_token = extract from Authorization header
   hashed_received = sha256(received_token)
   stored_hashed = get from database

8. Server Compares Hashes
   if (hashed_received === stored_hashed)
     ✓ Token valid
     return user data
   else
     ✗ Token invalid
     return 401 Unauthorized

9. Client Receives Data
   {
     "success": true,
     "user": { ... }
   }
```

---

## 💡 Use Cases

### Web Dashboard
```javascript
// Login
const loginRes = await fetch('/api/auth/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'admin@admin.com',
    password: 'password'
  })
});

const { token } = await loginRes.json();
localStorage.setItem('api_token', token);

// Use token for API calls
const statsRes = await fetch('/admin/analytics/api/stats', {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('api_token')}`
  }
});
```

### Mobile App
```swift
// Save token after login
let token = loginResponse.token
UserDefaults.standard.set(token, forKey: "api_token")

// Use token in requests
let headers = [
    "Authorization": "Bearer \(token)",
    "Content-Type": "application/json"
]

let request = URLRequest(url: url)
request.allHTTPHeaderFields = headers
```

### Third-Party Integration
```python
# Get token
response = requests.post(
    'http://localhost:8000/api/auth/login',
    json={'email': 'admin@admin.com', 'password': 'password'}
)
token = response.json()['token']

# Use token for all subsequent requests
headers = {
    'Authorization': f'Bearer {token}',
    'Content-Type': 'application/json'
}

stats = requests.get(
    'http://localhost:8000/admin/analytics/api/stats',
    headers=headers
).json()
```

---

## ✅ Testing Checklist

Before using in production, verify:

- [ ] Can login and get token
- [ ] Token is 60 characters long
- [ ] Token works in Authorization header
- [ ] Invalid token returns 401
- [ ] Expired token returns 401
- [ ] Refresh endpoint generates new token
- [ ] Logout revokes token
- [ ] Protected endpoints require token
- [ ] All filters work with token
- [ ] Analytics endpoints respond

---

## 🔗 Related Documentation

- `COMPLETE_API_DOCUMENTATION.md` - Full endpoint reference
- `POSTMAN_TESTING_GUIDE.md` - Postman setup guide
- `POSTMAN_READY.md` - Quick Postman guide

---

## 📞 Support

### Common Issues

**Q: Token not working?**
- A: Check Authorization header format: `Bearer {token}`
- Verify token is not expired (24 hours)
- Make sure to use the exact token from login response

**Q: Getting 401 Unauthorized?**
- A: Token may be expired, login again to get new token
- Check Authorization header is present
- Verify token format is correct

**Q: How to store token safely?**
- A: Use secure storage (localStorage for web, Keychain for iOS, Keystore for Android)
- Never send token in URL
- Always use HTTPS
- Never log token in console

---

**Status**: ✅ **TOKEN-BASED AUTHENTICATION ACTIVE**

**Last Updated**: December 29, 2025

**Version**: 1.0 - Token-Based Authentication System
