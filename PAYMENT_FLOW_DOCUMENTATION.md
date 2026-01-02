# 💳 Payment Flow Documentation - TopWriterX

## 📋 **Overview**
TopWriterX có 2 loại thanh toán hoàn toàn riêng biệt:
1. **Trial Payment**: $1 USD với auto-refund (để verify user)
2. **Credits Payment**: $600 USD không refund (mua 30 credits)

---

## 🎯 **1. TRIAL PAYMENT FLOW**

### **Purpose**: Verify user registration với minimal fee
- **Amount**: $1.00 USD
- **Auto-refund**: Có (ngay sau khi capture)
- **Redirect**: `trial-success` → Dashboard

### **Flow Architecture**:
```
try-writing form → trial-payment-options → PayPal/Coinbase → trial-success
```

### **Key Files & Routes**:

#### **Routes** (`routes/web.php`):
```php
// Trial payment routes
Route::get('/paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');
Route::get('/paypal-success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/trial-success', function () {
    return view('trial.success');
})->name('trial.success');
```

#### **Controller** (`app/Http/Controllers/PayPalController.php`):
```php
public function checkout() {
    // Tạo PayPal payment $1.00
    $response = $this->paypalService->createPayment(1.00);
}

public function success() {
    // Capture payment
    // Update TrialRegistration
    // AUTO-REFUND $1.00 immediately
    // Redirect to trial.success
}
```

#### **Service** (`app/Services/PayPalService.php`):
```php
public function createPayment($amount) {
    // Uses global return_url: /paypal-success
    // For trial payments only
}
```

#### **Auto-Refund** (`app/Services/RefundService.php`):
```php
public function processTrialRefund($trial, $captureId, $amount) {
    // Automatically refund $1.00 after capture
    // Update trial registration with refund details
}
```

---

## 💰 **2. CREDITS PAYMENT FLOW**

### **Purpose**: Mua 30 credits cho premium content
- **Amount**: $600.00 USD  
- **Auto-refund**: KHÔNG
- **Redirect**: `credits-success` → Dashboard

### **Flow Architecture**:
```
Dashboard → credits/checkout → PayPal/Coinbase → credits/success → Dashboard
```

### **Key Files & Routes**:

#### **Routes** (`routes/web.php`):
```php
// Credits payment routes (require auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/credits/paypal/checkout', [CreditsController::class, 'processPayPal']);
    Route::get('/credits/paypal/success', [CreditsController::class, 'paypalSuccess']);
    Route::get('/credits/success', [CreditsController::class, 'success']);
});
```

#### **Controller** (`app/Http/Controllers/CreditsController.php`):
```php
public function processPayPal() {
    // Tạo PayPal payment với CUSTOM return URL
    $paymentData = $this->paypalService->createPayment([
        'intent' => 'CAPTURE',
        'purchase_units' => [/* $600 */],
        'application_context' => [
            'return_url' => route('credits.paypal.success'), // RIÊNG BIỆT!
            'cancel_url' => route('credits.paypal.cancel')
        ]
    ]);
}

public function paypalSuccess() {
    // Capture payment
    // Update Transaction
    // Add 30 credits to user
    // NO REFUND
    // Redirect to credits.success
}
```

---

## 🔧 **3. CRITICAL DIFFERENCES**

### **Return URLs**:
- **Trial**: `/paypal-success` → `PayPalController::success()`
- **Credits**: `/credits/paypal/success` → `CreditsController::paypalSuccess()`

### **Payment Creation**:
- **Trial**: `createPayment(1.00)` - uses global config
- **Credits**: `createPayment([...])` - custom payload with specific return URL

### **Post-Payment Actions**:
- **Trial**: Auto-refund + redirect to trial-success
- **Credits**: Add credits + redirect to credits-success

---

## 🚨 **4. COMMON MISTAKES & HOW TO AVOID**

### **❌ Mistake 1**: Credits payment going to trial-success
**Cause**: Using wrong return URL in PayPal payload
**Fix**: Always use custom payload for credits:
```php
// ❌ Wrong - uses global return URL
$paymentData = $this->paypalService->createPayment(600.00);

// ✅ Correct - uses specific return URL
$paymentData = $this->paypalService->createPayment([
    'application_context' => [
        'return_url' => route('credits.paypal.success')
    ]
]);
```

### **❌ Mistake 2**: Refunding credits payment
**Cause**: Using same success handler for both payment types
**Fix**: Separate controllers with different logic:
- `PayPalController::success()` - trial only, has refund
- `CreditsController::paypalSuccess()` - credits only, NO refund

### **❌ Mistake 3**: Wrong database updates
**Cause**: Updating wrong table or missing Schema checks
**Fix**: 
- Trial: Update `trial_registrations` table
- Credits: Update `transactions` table + user credits

---

## 🔄 **5. TESTING FLOWS**

### **Test Commands**:
```bash
# Test trial payment
php artisan test:auto-refund

# Test credits payment
php artisan test:credits-payment paypal
php artisan test:credits-payment coinbase

# Full automation test
python test_automation.py
```

### **Manual Testing**:
1. **Trial**: try-writing → payment → verify refund in logs
2. **Credits**: dashboard → credits/checkout → verify 30 credits added

---

## 📝 **6. HOW TO MODIFY**

### **Change Trial Amount**:
1. Update `PayPalController::checkout()`: `createPayment(NEW_AMOUNT)`
2. Update `trial/payment_options.blade.php`: Display text
3. Update `RefundService`: Refund amount

### **Change Credits Amount/Price**:
1. Update `CreditsController::processPayPal()`: Purchase units amount
2. Update `credits/checkout.blade.php`: Display price
3. Update success handler: Credits to add

### **Add New Payment Method**:
1. Create new controller (e.g., `StripeController`)
2. Add routes with specific return URLs
3. Follow same pattern: separate success handlers
4. Update checkout pages with new option

### **Change Return URLs**:
1. **Trial**: Update `config/services.php` → `paypal.return_url`
2. **Credits**: Update controller payload → `application_context.return_url`

---

## 🎯 **7. PRODUCTION CHECKLIST**

### **Before Deploy**:
- [ ] Test both payment flows separately
- [ ] Verify return URLs point to correct controllers
- [ ] Check refund only happens for trial payments
- [ ] Verify credits are added correctly
- [ ] Test with real PayPal sandbox account

### **Monitoring**:
- [ ] Check logs for payment completion
- [ ] Monitor refund success rates
- [ ] Track credits addition accuracy
- [ ] Watch for redirect issues

---

## 🔍 **8. TROUBLESHOOTING**

### **Credits going to trial-success**:
1. Check `CreditsController::processPayPal()` return URL
2. Verify route exists: `credits.paypal.success`
3. Test PayPal payload in logs

### **Refund not working**:
1. Check `RefundService::processTrialRefund()`
2. Verify capture ID is valid
3. Check PayPal API credentials

### **Credits not added**:
1. Check `CreditsController::paypalSuccess()`
2. Verify user credits update
3. Check transaction record creation

---

**🎯 REMEMBER**: Trial và Credits là 2 hệ thống hoàn toàn riêng biệt với controllers, routes, và logic khác nhau! 