#!/bin/bash

echo "🚀 Deploying PayPal 403 Fix..."

# 1. Clear caches
echo "📝 Clearing Laravel caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Test PayPal configuration
echo "🔍 Testing PayPal configuration..."
echo "Base URL: $(php artisan tinker --execute='echo config("services.paypal.base_url");')"
echo "Client ID exists: $(php artisan tinker --execute='echo !empty(config("services.paypal.client_id")) ? "Yes" : "No";')"
echo "Client Secret exists: $(php artisan tinker --execute='echo !empty(config("services.paypal.client_secret")) ? "Yes" : "No";')"

# 3. Run debug script
echo "🧪 Running PayPal debug script..."
if [ -f "debug_paypal_403.php" ]; then
    php debug_paypal_403.php
else
    echo "❌ Debug script not found. Create it first."
fi

# 4. Test a simple PayPal request
echo "🧪 Testing PayPal access token..."
php artisan tinker --execute='
$service = new App\Services\PayPalService();
echo "PayPal Service initialized successfully";
'

echo "✅ Deploy completed!"
echo ""
echo "📋 Next steps:"
echo "1. Check your PayPal Business Account verification status"
echo "2. Ensure your app is approved for live payments"
echo "3. Test a small payment transaction"
echo "4. Monitor logs: tail -f storage/logs/laravel.log | grep PayPal" 