# Webhook APP_KEY Fix Documentation

## Problem Summary

The `/admin/webhooks` page was throwing a 500 error with "The MAC is invalid" after every deployment. This was caused by the deployment process regenerating the APP_KEY on each deployment, which broke encrypted data in the database.

## Root Cause

1. **Encrypted Data**: The `WebhookSetting` model has an `auth_credentials` field with `'encrypted:json'` casting
2. **APP_KEY Regeneration**: The deployment script ran `php artisan key:generate --force` on every deployment
3. **Decryption Failure**: When the view tried to access `$settings->auth_credentials['token']`, Laravel couldn't decrypt it with the new APP_KEY
4. **MAC Invalid Error**: This resulted in the "The MAC is invalid" decryption exception

## The Fix

### 1. Updated Deployment Script

The deployment workflow (`.github/workflows/deploy.yml`) has been updated to:

```bash
# OLD (BROKEN):
php artisan key:generate --force

# NEW (FIXED):
if ! grep -q "APP_KEY=base64:" $RELEASE_PATH/.env || grep -q "APP_KEY=$" $RELEASE_PATH/.env; then
  echo "🔧 APP_KEY missing or empty, generating new key..."
  php artisan key:generate --force
else
  echo "✅ APP_KEY already exists, preserving it"
fi
```

This ensures the APP_KEY is only generated if it's missing or empty, preserving existing encrypted data.

### 2. What You Need to Do

#### Step 1: Ensure APP_KEY is in GitHub Secrets

1. Get your current APP_KEY from the server:
   ```bash
   ssh root@165.227.66.174
   grep APP_KEY /var/www/referral-app/current/.env
   ```

2. Copy the APP_KEY value (including `base64:` prefix)

3. Update your GitHub repository secrets:
   - Go to your GitHub repository
   - Settings → Secrets and variables → Actions
   - Edit the `ENV_FILE` secret
   - Make sure it includes the APP_KEY line:
     ```
     APP_KEY=base64:your-actual-key-here
     ```

#### Step 2: Test the Fix

1. **Commit and push** the updated deployment workflow
2. **Deploy** your application (the deployment should preserve the APP_KEY)
3. **Test** the `/admin/webhooks` page - it should work without errors
4. **Configure** your webhook settings through the UI
5. **Deploy again** to verify the webhook settings persist

## Verification

After the fix is deployed:

1. ✅ The `/admin/webhooks` page loads without errors
2. ✅ Webhook settings persist between deployments
3. ✅ No more "MAC is invalid" errors
4. ✅ Encrypted data remains accessible

## Files Modified

- `.github/workflows/deploy.yml` - Updated to preserve APP_KEY
- `scripts/fix-deployment-app-key.sh` - Diagnostic script explaining the issue

## Technical Details

### WebhookSetting Model Encryption

The `WebhookSetting` model has these encrypted fields:
```php
protected $casts = [
    'auth_credentials' => 'encrypted:json',
    // ... other fields
];
```

### How Laravel Encryption Works

- Laravel uses the APP_KEY to encrypt/decrypt data
- When APP_KEY changes, previously encrypted data becomes unreadable
- This causes "The MAC is invalid" errors when trying to decrypt

### The Solution Logic

1. **Check if APP_KEY exists** in the .env file
2. **Only generate new key** if APP_KEY is missing or empty
3. **Preserve existing APP_KEY** to maintain encrypted data integrity

## Prevention

To prevent this issue in the future:

1. **Never run** `php artisan key:generate --force` in production deployments
2. **Always preserve** the APP_KEY between deployments
3. **Use GitHub secrets** to manage the APP_KEY consistently
4. **Test webhook functionality** after each deployment

## Troubleshooting

If you still get "MAC is invalid" errors:

1. **Check APP_KEY consistency**:
   ```bash
   grep APP_KEY /var/www/referral-app/current/.env
   ```

2. **Clear webhook settings** (if needed):
   ```bash
   php artisan tinker --execute="App\Models\WebhookSetting::truncate();"
   ```

3. **Reconfigure webhooks** through the admin interface

## Success Criteria

✅ **Fixed**: APP_KEY is preserved between deployments
✅ **Fixed**: Webhook settings persist across deployments  
✅ **Fixed**: No more "MAC is invalid" errors
✅ **Fixed**: `/admin/webhooks` page loads successfully
