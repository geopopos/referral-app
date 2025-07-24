# VolumeUp Referral System API Documentation

## Overview

This document provides comprehensive information about the VolumeUp Referral System API, including setup, authentication, and endpoint usage.

## API Documentation Access

The interactive Swagger documentation is available at:
```
http://your-domain.com/api/documentation
```

For local development:
```
http://localhost:8000/api/documentation
```

## Authentication

The API uses Laravel Sanctum for authentication with Bearer tokens.

### Generate API Token

**Endpoint:** `POST /api/auth/token`

**Description:** Generate an API token for admin users

**Request Body:**
```json
{
  "email": "admin@volumeup.agency",
  "password": "your-password"
}
```

**Response:**
```json
{
  "message": "Token generated successfully",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@volumeup.agency",
    "role": "admin"
  }
}
```

### Using the Token

Include the token in the Authorization header for all authenticated requests:
```
Authorization: Bearer 1|abc123...
```

### Other Authentication Endpoints

- `GET /api/auth/me` - Get current authenticated user
- `POST /api/auth/revoke` - Revoke current token

## Lead Management Endpoints

All lead endpoints require admin authentication.

### List Leads

**Endpoint:** `GET /api/admin/leads`

**Query Parameters:**
- `status` - Filter by lead status (new, contacted, qualified, appointment_scheduled, proposal_sent, closed, lost)
- `referrer_id` - Filter by referrer user ID
- `search` - Search in name, email, or company
- `sort_by` - Field to sort by (default: created_at)
- `sort_direction` - Sort direction (asc, desc, default: desc)
- `per_page` - Number of results per page (default: 15)
- `page` - Page number (default: 1)

**Example:**
```
GET /api/admin/leads?status=new&search=acme&per_page=10&page=1
```

### Create Lead

**Endpoint:** `POST /api/admin/leads`

**Required Fields:**
- `name` - Lead's full name
- `email` - Lead's email address
- `company` - Company name

**Optional Fields:**
- `phone` - Phone number
- `status` - Lead status
- `notes` - Additional notes
- `referrer_id` - ID of referring user
- `source` - Lead source
- UTM tracking fields: `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`
- Pipeline fields: `pipeline_stage`, `appointment_date`, `proposal_sent_date`, `close_date`
- `monthly_retainer` - Monthly retainer amount

### Get Lead

**Endpoint:** `GET /api/admin/leads/{id}`

Returns detailed information about a specific lead including referrer and commissions.

### Update Lead

**Endpoint:** `PUT /api/admin/leads/{id}`

Update any lead fields. Same structure as create endpoint but all fields are optional.

### Delete Lead

**Endpoint:** `DELETE /api/admin/leads/{id}`

Permanently delete a lead.

## Partners API

### Search User by Referral Code

**Endpoint:** `GET /api/admin/partners/search`

**Description:** Find a referral partner or admin user using their unique referral code. This endpoint is useful for attaching referrals to specific users via API.

**Authentication:** Admin required

**Query Parameters:**
- `referral_code` (required) - The referral code to search for

**Example:**
```
GET /api/admin/partners/search?referral_code=john123
```

**Success Response (200):**
```json
{
  "partner": {
    "id": 4,
    "name": "John Smith",
    "email": "john@example.com",
    "referral_code": "john123",
    "role": "partner",
    "created_at": "2025-07-12T22:31:28.000000Z",
    "stats": {
      "total_leads": 2,
      "total_commissions": 1697.00
    }
  }
}
```

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "User not found with the provided referral code"
}
```

**422 Validation Error:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "referral_code": [
      "The referral code field is required."
    ]
  }
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/admin/partners/search?referral_code=john123" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## Data Models

### Lead Model

```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "company": "Acme Corp",
  "status": "new",
  "notes": "Interested in roofing services",
  "referrer_id": 2,
  "source": "website",
  "utm_source": "google",
  "utm_medium": "cpc",
  "utm_campaign": "summer2024",
  "utm_term": "roofing services",
  "utm_content": "ad1",
  "pipeline_stage": "lead",
  "appointment_date": "2024-07-15",
  "proposal_sent_date": "2024-07-20",
  "close_date": "2024-07-25",
  "monthly_retainer": 2500.00,
  "created_at": "2024-07-12T22:00:00.000000Z",
  "updated_at": "2024-07-12T22:00:00.000000Z",
  "referrer": {
    "id": 2,
    "name": "Partner User",
    "email": "partner@example.com",
    "role": "partner"
  },
  "commissions": [
    {
      "id": 1,
      "user_id": 2,
      "lead_id": 1,
      "amount": 250.00,
      "type": "referral",
      "status": "pending",
      "notes": "Initial referral commission",
      "created_at": "2024-07-12T22:00:00.000000Z",
      "updated_at": "2024-07-12T22:00:00.000000Z"
    }
  ],
  "total_commissions": 250.00
}
```

### Lead Status Values

- `new` - New lead
- `contacted` - Lead has been contacted
- `qualified` - Lead is qualified
- `appointment_scheduled` - Appointment scheduled
- `proposal_sent` - Proposal sent
- `closed` - Deal closed
- `lost` - Lead lost

### Pipeline Stage Values

- `lead` - Initial lead
- `qualified` - Qualified lead
- `appointment` - Appointment scheduled
- `proposal` - Proposal stage
- `negotiation` - Negotiation stage
- `closed_won` - Successfully closed
- `closed_lost` - Lost opportunity

### Commission Types

- `referral` - Standard referral commission
- `fast_close_bonus` - Fast close bonus
- `monthly_recurring` - Monthly recurring commission

### Commission Status Values

- `pending` - Awaiting approval
- `approved` - Approved for payment
- `paid` - Commission paid
- `cancelled` - Commission cancelled

## Error Responses

### 401 Unauthenticated
```json
{
  "message": "Unauthenticated."
}
```

### 403 Access Denied
```json
{
  "message": "Access denied. Admin privileges required."
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Lead]."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

## Rate Limiting

The API implements standard Laravel rate limiting. Default limits apply unless otherwise specified.

## Pagination

List endpoints return paginated results with the following structure:

```json
{
  "data": [...],
  "current_page": 1,
  "last_page": 5,
  "per_page": 15,
  "total": 67,
  "from": 1,
  "to": 15,
  "path": "http://localhost:8000/api/admin/leads",
  "first_page_url": "http://localhost:8000/api/admin/leads?page=1",
  "last_page_url": "http://localhost:8000/api/admin/leads?page=5",
  "next_page_url": "http://localhost:8000/api/admin/leads?page=2",
  "prev_page_url": null
}
```

## Example Usage

### cURL Examples

**Generate Token:**
```bash
curl -X POST http://localhost:8000/api/auth/token \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@volumeup.agency","password":"password"}'
```

**List Leads:**
```bash
curl -X GET http://localhost:8000/api/admin/leads \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

**Create Lead:**
```bash
curl -X POST http://localhost:8000/api/admin/leads \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "company": "Acme Corp",
    "phone": "+1234567890",
    "status": "new",
    "notes": "Interested in roofing services"
  }'
```

### JavaScript/Fetch Examples

**Generate Token:**
```javascript
const response = await fetch('/api/auth/token', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'admin@volumeup.agency',
    password: 'password'
  })
});

const data = await response.json();
const token = data.token;
```

**List Leads:**
```javascript
const response = await fetch('/api/admin/leads?status=new&per_page=10', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
  }
});

const leads = await response.json();
```

## Development Setup

1. **Install Dependencies:**
   ```bash
   composer require darkaonline/l5-swagger
   ```

2. **Publish Configuration:**
   ```bash
   php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
   ```

3. **Generate Documentation:**
   ```bash
   php artisan l5-swagger:generate
   ```

4. **Access Documentation:**
   Visit `/api/documentation` in your browser

## Production Considerations

1. **Security:**
   - Ensure HTTPS is enabled in production
   - Use strong passwords for admin accounts
   - Regularly rotate API tokens
   - Implement proper CORS policies

2. **Performance:**
   - Enable API response caching
   - Use database indexing for frequently queried fields
   - Implement proper pagination limits

3. **Monitoring:**
   - Log API usage and errors
   - Monitor rate limiting
   - Track authentication failures

## Support

For API support and questions, contact:
- Email: support@volumeup.agency
- Documentation: Available at `/api/documentation`

## Version History

- **v1.0.0** - Initial API release with authentication and lead management
