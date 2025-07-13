# Admin API Documentation

This API provides admin-only endpoints for managing leads and commissions in the referral system.

## Authentication

The API uses Laravel Sanctum for authentication. Admin users must first obtain an API token.

### Generate API Token

**POST** `/api/auth/token`

Generate an API token for admin users.

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password"
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
        "email": "admin@example.com",
        "role": "admin"
    }
}
```

### Using the Token

Include the token in the Authorization header for all subsequent requests:

```
Authorization: Bearer 1|abc123...
```

### Other Auth Endpoints

- **GET** `/api/auth/me` - Get current user info
- **POST** `/api/auth/revoke` - Revoke current token

## Leads API

Base URL: `/api/admin/leads`

### List Leads

**GET** `/api/admin/leads`

**Query Parameters:**
- `status` - Filter by status (new, contacted, qualified, etc.)
- `referrer_id` - Filter by referrer user ID
- `search` - Search in name, email, or company
- `sort_by` - Sort field (default: created_at)
- `sort_direction` - Sort direction (asc/desc, default: desc)
- `per_page` - Results per page (default: 15)
- `page` - Page number

**Example:**
```
GET /api/admin/leads?status=new&search=acme&per_page=10
```

**Response:**
```json
{
    "data": [
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
            "pipeline_stage": "lead",
            "appointment_date": null,
            "proposal_sent_date": null,
            "close_date": null,
            "monthly_retainer": null,
            "created_at": "2025-07-12T22:00:00.000000Z",
            "updated_at": "2025-07-12T22:00:00.000000Z",
            "referrer": {
                "id": 2,
                "name": "Partner User",
                "email": "partner@example.com"
            },
            "commissions": [],
            "total_commissions": 0
        }
    ],
    "links": {...},
    "meta": {...}
}
```

### Get Single Lead

**GET** `/api/admin/leads/{id}`

**Response:** Single lead object with relationships loaded.

### Create Lead

**POST** `/api/admin/leads`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "company": "Acme Corp",
    "status": "new",
    "notes": "Interested in roofing services",
    "referrer_id": 2,
    "source": "website",
    "pipeline_stage": "lead",
    "monthly_retainer": 2500.00
}
```

**Required Fields:**
- `name` (string, max 255)
- `email` (valid email, max 255)
- `company` (string, max 255)

**Optional Fields:**
- `phone` (string, max 20)
- `status` (enum: new, contacted, qualified, appointment_scheduled, proposal_sent, closed, lost)
- `notes` (text)
- `referrer_id` (exists in users table)
- `source` (string, max 255)
- `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content` (string, max 255)
- `pipeline_stage` (enum: lead, qualified, appointment, proposal, negotiation, closed_won, closed_lost)
- `appointment_date`, `proposal_sent_date`, `close_date` (date)
- `monthly_retainer` (numeric, min 0)

### Update Lead

**PUT** `/api/admin/leads/{id}`

Same request body as create, but all fields are optional (use `sometimes` validation).

### Delete Lead

**DELETE** `/api/admin/leads/{id}`

**Response:**
```json
{
    "message": "Lead deleted successfully"
}
```

## Commissions API

Base URL: `/api/admin/commissions`

### List Commissions

**GET** `/api/admin/commissions`

**Query Parameters:**
- `status` - Filter by status (pending, approved, paid, cancelled)
- `user_id` - Filter by user ID
- `type` - Filter by type (referral, fast_close_bonus, monthly_recurring)
- `lead_id` - Filter by lead ID
- `search` - Search in user name/email or lead name/company
- `sort_by` - Sort field (default: created_at)
- `sort_direction` - Sort direction (asc/desc, default: desc)
- `per_page` - Results per page (default: 15)
- `page` - Page number

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "user_id": 2,
            "lead_id": 1,
            "amount": 250.00,
            "type": "referral",
            "status": "pending",
            "notes": "Initial referral commission",
            "created_at": "2025-07-12T22:00:00.000000Z",
            "updated_at": "2025-07-12T22:00:00.000000Z",
            "user": {
                "id": 2,
                "name": "Partner User",
                "email": "partner@example.com"
            },
            "lead": {
                "id": 1,
                "name": "John Doe",
                "company": "Acme Corp"
            }
        }
    ],
    "links": {...},
    "meta": {...}
}
```

### Get Single Commission

**GET** `/api/admin/commissions/{id}`

### Create Commission

**POST** `/api/admin/commissions`

**Request Body:**
```json
{
    "user_id": 2,
    "lead_id": 1,
    "amount": 250.00,
    "type": "referral",
    "status": "pending",
    "notes": "Initial referral commission"
}
```

**Required Fields:**
- `user_id` (exists in users table)
- `amount` (numeric, min 0)
- `type` (enum: referral, fast_close_bonus, monthly_recurring)

**Optional Fields:**
- `lead_id` (exists in leads table)
- `status` (enum: pending, approved, paid, cancelled, default: pending)
- `notes` (text)

### Update Commission

**PUT** `/api/admin/commissions/{id}`

Same request body as create, but all fields are optional.

### Delete Commission

**DELETE** `/api/admin/commissions/{id}`

## Error Responses

### Validation Errors (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "amount": ["The amount must be a number."]
    }
}
```

### Authentication Errors (401)
```json
{
    "message": "Unauthenticated."
}
```

### Authorization Errors (403)
```json
{
    "message": "Access denied. Admin privileges required."
}
```

### Not Found Errors (404)
```json
{
    "message": "No query results for model [App\\Models\\Lead] 999"
}
```

## Rate Limiting

API requests are subject to Laravel's default rate limiting (60 requests per minute per IP).

## Example Usage with cURL

### 1. Get API Token
```bash
curl -X POST http://your-domain.com/api/auth/token \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### 2. List Leads
```bash
curl -X GET http://your-domain.com/api/admin/leads \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 3. Create Lead
```bash
curl -X POST http://your-domain.com/api/admin/leads \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "company": "Acme Corp",
    "phone": "+1234567890"
  }'
```

### 4. Update Lead Status
```bash
curl -X PUT http://your-domain.com/api/admin/leads/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"status": "qualified", "pipeline_stage": "qualified"}'
```

### 5. Create Commission
```bash
curl -X POST http://your-domain.com/api/admin/commissions \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 2,
    "lead_id": 1,
    "amount": 250.00,
    "type": "referral"
  }'

## Webhook Management

Base URL: `/api/admin/webhooks`

### Get Webhook Settings

**GET** `/api/admin/webhooks/settings`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "url": "https://your-app.com/webhooks/volume-up",
        "auth_type": "bearer",
        "enabled_events": ["lead.created", "lead.updated", "commission.approved"],
        "is_active": true,
        "max_retry_attempts": 3,
        "retry_delays": [60, 300, 900],
        "last_successful_delivery": "2025-07-12T22:00:00.000000Z",
        "last_failed_delivery": null,
        "consecutive_failures": 0,
        "available_events": {
            "lead.created": "Lead Created",
            "lead.updated": "Lead Updated",
            "lead.status_changed": "Lead Status Changed",
            "commission.created": "Commission Created",
            "commission.updated": "Commission Updated",
            "commission.approved": "Commission Approved",
            "commission.paid": "Commission Paid"
        },
        "auth_types": {
            "none": "No Authentication",
            "basic": "Basic Authentication",
            "bearer": "Bearer Token",
            "custom": "Custom Headers"
        }
    }
}
```

### Update Webhook Settings

**PUT** `/api/admin/webhooks/settings`

**Request Body:**
```json
{
    "url": "https://your-app.com/webhooks/volume-up",
    "auth_type": "bearer",
    "auth_credentials": {
        "token": "your-bearer-token"
    },
    "enabled_events": ["lead.created", "lead.updated", "commission.approved"],
    "is_active": true,
    "max_retry_attempts": 3,
    "retry_delays": [60, 300, 900],
    "secret_key": "your-webhook-secret"
}
```

### Test Webhook

**POST** `/api/admin/webhooks/test`

**Response:**
```json
{
    "success": true,
    "message": "Test webhook sent successfully",
    "data": {
        "success": true,
        "status": "success",
        "http_status": 200,
        "response_time": "245ms",
        "error_message": null,
        "webhook_id": "550e8400-e29b-41d4-a716-446655440000"
    }
}
```

### Get Webhook Logs

**GET** `/api/admin/webhooks/logs`

**Query Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Results per page (max 100)
- `event_type` (string): Filter by event type
- `status` (string): Filter by status (pending, success, failed, retrying)
- `webhook_setting_id` (integer): Filter by webhook setting ID

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "webhook_setting_id": 1,
            "event_type": "lead.created",
            "webhook_id": "550e8400-e29b-41d4-a716-446655440000",
            "url": "https://your-app.com/webhooks/volume-up",
            "payload": {
                "event": "lead.created",
                "timestamp": "2025-07-12T22:00:00.000000Z",
                "data": {
                    "lead": {...},
                    "referrer": {...}
                }
            },
            "http_status": 200,
            "status": "success",
            "attempt_number": 1,
            "max_attempts": 3,
            "response_time": 0.245,
            "created_at": "2025-07-12T22:00:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 100
    }
}
```

### Get Webhook Statistics

**GET** `/api/admin/webhooks/stats`

**Query Parameters:**
- `days` (integer): Number of days to include in stats (default: 30)

**Response:**
```json
{
    "success": true,
    "data": {
        "total_deliveries": 150,
        "successful_deliveries": 145,
        "failed_deliveries": 5,
        "pending_deliveries": 0,
        "retrying_deliveries": 0,
        "success_rate": 96.67,
        "average_response_time": 0.234,
        "last_successful_delivery": "2025-07-12T22:00:00.000000Z",
        "last_failed_delivery": "2025-07-10T15:30:00.000000Z",
        "consecutive_failures": 0
    }
}
```

### Retry Specific Webhook

**POST** `/api/admin/webhooks/logs/{id}/retry`

**Response:**
```json
{
    "success": true,
    "message": "Webhook retry initiated",
    "data": {
        "id": 1,
        "status": "retrying",
        "attempt_number": 2,
        "next_retry_at": "2025-07-12T22:05:00.000000Z"
    }
}
```

### Retry All Failed Webhooks

**POST** `/api/admin/webhooks/retry-all-failed`

**Response:**
```json
{
    "success": true,
    "message": "Queued 3 webhook(s) for retry",
    "data": {
        "retried_count": 3
    }
}
```

### Cleanup Old Webhook Logs

**DELETE** `/api/admin/webhooks/logs/cleanup`

**Request Body:**
```json
{
    "days": 90
}
```

**Response:**
```json
{
    "success": true,
    "message": "Deleted 250 webhook log(s) older than 90 days",
    "data": {
        "deleted_count": 250
    }
}
```

## Webhook Payload Examples

### Lead Created
```json
{
    "event": "lead.created",
    "timestamp": "2025-07-12T22:00:00.000000Z",
    "data": {
        "lead": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "company": "Acme Corp",
            "status": "lead",
            "created_at": "2025-07-12T22:00:00.000000Z"
        },
        "referrer": {
            "id": 2,
            "name": "Partner User",
            "email": "partner@example.com",
            "referral_code": "PARTNER123"
        }
    },
    "webhook_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

### Commission Approved
```json
{
    "event": "commission.approved",
    "timestamp": "2025-07-12T22:00:00.000000Z",
    "data": {
        "commission": {
            "id": 1,
            "amount": 250.00,
            "type": "rev_share",
            "status": "approved",
            "created_at": "2025-07-12T22:00:00.000000Z"
        },
        "user": {
            "id": 2,
            "name": "Partner User",
            "email": "partner@example.com",
            "referral_code": "PARTNER123"
        },
        "lead": {
            "id": 1,
            "name": "John Doe",
            "company": "Acme Corp"
        },
        "previous_status": "pending"
    },
    "webhook_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

## Webhook Security

### HMAC Signature Verification

If you configure a secret key, webhooks will include an HMAC signature in the `X-Webhook-Signature` header:

```
X-Webhook-Signature: sha256=a8b7c6d5e4f3g2h1...
```

To verify the signature in your webhook handler:

```php
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $your_secret_key);

if (!hash_equals($signature, $expectedSignature)) {
    http_response_code(401);
    exit('Invalid signature');
}
```

### Webhook Headers

All webhooks include these headers:
- `Content-Type: application/json`
- `User-Agent: VolumeUp-Webhook/1.0`
- `X-Webhook-ID: {unique-delivery-id}`
- `X-Webhook-Event: {event-type}`
- `X-Webhook-Timestamp: {iso-timestamp}`
- `X-Webhook-Signature: sha256={hmac-signature}` (if secret configured)
