# Webhook System Documentation

The Volume Up Referral App includes a comprehensive webhook system that allows external applications to receive real-time notifications about important events in the referral system.

## Overview

The webhook system automatically sends HTTP POST requests to configured endpoints when specific events occur, such as:
- New leads being created
- Lead status changes
- Commissions being created, approved, or paid
- And more...

## Features

- **Multiple Event Types**: Support for various lead and commission events
- **Flexible Authentication**: Bearer tokens, basic auth, or custom headers
- **Automatic Retries**: Configurable retry logic with exponential backoff
- **Security**: HMAC signature verification for webhook authenticity
- **Logging**: Comprehensive logging of all webhook deliveries
- **Statistics**: Detailed analytics on webhook performance
- **Admin API**: Full REST API for webhook management

## Quick Start

### 1. Configure Webhook Settings

Use the admin API to configure your webhook endpoint:

```bash
curl -X PUT http://your-domain.com/api/admin/webhooks/settings \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://your-app.com/webhooks/volume-up",
    "auth_type": "bearer",
    "auth_credentials": {
      "token": "your-bearer-token"
    },
    "enabled_events": ["lead.created", "commission.approved"],
    "is_active": true,
    "secret_key": "your-webhook-secret"
  }'
```

### 2. Handle Webhook Events

Create an endpoint in your application to receive webhooks:

```php
<?php
// webhook-handler.php

// Verify webhook signature (recommended)
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$secret = 'your-webhook-secret';
$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($signature, $expectedSignature)) {
    http_response_code(401);
    exit('Invalid signature');
}

// Parse webhook data
$data = json_decode($payload, true);
$event = $data['event'];
$eventData = $data['data'];

// Handle different event types
switch ($event) {
    case 'lead.created':
        handleNewLead($eventData['lead'], $eventData['referrer']);
        break;
        
    case 'commission.approved':
        handleCommissionApproved($eventData['commission'], $eventData['user']);
        break;
        
    default:
        error_log("Unknown webhook event: $event");
}

// Return 200 OK to acknowledge receipt
http_response_code(200);
echo 'OK';

function handleNewLead($lead, $referrer) {
    // Your lead processing logic here
    error_log("New lead: {$lead['name']} from {$referrer['name']}");
}

function handleCommissionApproved($commission, $user) {
    // Your commission processing logic here
    error_log("Commission approved: ${$commission['amount']} for {$user['name']}");
}
```

### 3. Test Your Webhook

Use the test endpoint to verify your webhook handler:

```bash
curl -X POST http://your-domain.com/api/admin/webhooks/test \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

## Available Events

| Event | Description | Triggered When |
|-------|-------------|----------------|
| `lead.created` | New lead submitted | A new lead is created in the system |
| `lead.updated` | Lead information changed | Any lead field is updated |
| `lead.status_changed` | Lead status changed | Lead moves through the pipeline |
| `commission.created` | New commission generated | A commission is created for a referrer |
| `commission.updated` | Commission modified | Commission details are changed |
| `commission.approved` | Commission approved | Admin approves a pending commission |
| `commission.paid` | Commission paid | Commission is marked as paid |

## Webhook Payload Structure

All webhooks follow this standard structure:

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

## Authentication Types

### No Authentication
```json
{
  "auth_type": "none"
}
```

### Bearer Token
```json
{
  "auth_type": "bearer",
  "auth_credentials": {
    "token": "your-bearer-token"
  }
}
```

### Basic Authentication
```json
{
  "auth_type": "basic",
  "auth_credentials": {
    "username": "your-username",
    "password": "your-password"
  }
}
```

### Custom Headers
```json
{
  "auth_type": "custom",
  "auth_credentials": {
    "X-API-Key": "your-api-key",
    "X-Custom-Header": "custom-value"
  }
}
```

## Retry Logic

The webhook system includes intelligent retry logic:

1. **Initial Delivery**: Webhook is sent immediately
2. **First Retry**: After 1 minute if failed
3. **Second Retry**: After 5 minutes if failed
4. **Third Retry**: After 15 minutes if failed
5. **Final Failure**: Marked as permanently failed

You can customize retry delays in the webhook settings:

```json
{
  "max_retry_attempts": 3,
  "retry_delays": [60, 300, 900]
}
```

## Security

### HMAC Signature Verification

When you configure a secret key, all webhooks include an HMAC signature in the `X-Webhook-Signature` header:

```
X-Webhook-Signature: sha256=a8b7c6d5e4f3g2h1...
```

Always verify this signature to ensure webhook authenticity:

```php
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$secret = 'your-webhook-secret';
$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

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

## Monitoring and Debugging

### View Webhook Logs

```bash
curl -X GET "http://your-domain.com/api/admin/webhooks/logs?per_page=20" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### Get Webhook Statistics

```bash
curl -X GET "http://your-domain.com/api/admin/webhooks/stats?days=30" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### Retry Failed Webhooks

```bash
# Retry all failed webhooks
curl -X POST http://your-domain.com/api/admin/webhooks/retry-all-failed \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"

# Retry specific webhook
curl -X POST http://your-domain.com/api/admin/webhooks/logs/123/retry \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

## Command Line Tools

### Retry Failed Webhooks

```bash
php artisan webhooks:retry-failed
```

This command can be scheduled to run periodically:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('webhooks:retry-failed')->everyFiveMinutes();
}
```

## Best Practices

### 1. Idempotency
Make your webhook handlers idempotent. You might receive the same webhook multiple times due to retries.

```php
function handleNewLead($lead, $referrer) {
    // Check if already processed
    if (LeadProcessor::isProcessed($lead['id'])) {
        return;
    }
    
    // Process the lead
    LeadProcessor::process($lead);
    LeadProcessor::markAsProcessed($lead['id']);
}
```

### 2. Quick Response
Respond quickly (within 30 seconds) to avoid timeouts:

```php
// Process webhook immediately
http_response_code(200);
echo 'OK';
fastcgi_finish_request(); // If using PHP-FPM

// Do heavy processing after response
processWebhookData($data);
```

### 3. Error Handling
Handle errors gracefully and return appropriate HTTP status codes:

```php
try {
    processWebhook($data);
    http_response_code(200);
    echo 'OK';
} catch (TemporaryException $e) {
    // Return 5xx for temporary errors (will retry)
    http_response_code(503);
    echo 'Temporary error';
} catch (PermanentException $e) {
    // Return 4xx for permanent errors (won't retry)
    http_response_code(400);
    echo 'Permanent error';
}
```

### 4. Logging
Log all webhook events for debugging:

```php
error_log("Webhook received: " . json_encode([
    'event' => $data['event'],
    'webhook_id' => $data['webhook_id'],
    'timestamp' => $data['timestamp']
]));
```

## Testing

### Test Script
Run the included test script to verify the webhook system:

```bash
cd referral-app
php test_webhook_system.php
```

### Manual Testing
1. Set up a test endpoint (e.g., using webhook.site)
2. Configure webhook settings via API
3. Create test leads/commissions
4. Verify webhooks are received

## Troubleshooting

### Common Issues

**Webhooks not being sent:**
- Check if webhook settings are active
- Verify enabled events include the event you're testing
- Check webhook logs for error messages

**Authentication failures:**
- Verify auth credentials are correct
- Check if your endpoint expects different auth format

**Signature verification failures:**
- Ensure you're using the correct secret key
- Verify HMAC calculation matches the example code

**Timeouts:**
- Ensure your endpoint responds within 30 seconds
- Check network connectivity between servers

### Debug Mode
Enable debug logging in your webhook handler:

```php
error_log("Webhook payload: " . $payload);
error_log("Webhook signature: " . $signature);
error_log("Expected signature: " . $expectedSignature);
```

## API Reference

For complete API documentation, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md#webhook-management).

## Support

For webhook system support:
1. Check the webhook logs via API
2. Review error messages in application logs
3. Test with the provided test script
4. Verify your endpoint with webhook.site or similar tools
