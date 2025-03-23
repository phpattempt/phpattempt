# PHPAttempt 🛡️

A minimalistic PHP package that encourages error-first programming approach, helping you write more reliable and maintainable code.

[![PHP Version](https://img.shields.io/packagist/php-v/phpattempt/phpattempt.svg)](https://packagist.org/packages/phpattempt/phpattempt)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/phpattempt/phpattempt.svg)](https://packagist.org/packages/phpattempt/phpattempt)

## Installation

```bash
composer require phpattempt/phpattempt
```

## Usage

The attempt() function takes a callable and returns an array containing the result and any potential error:

```php
[$data, $error] = attempt(fn () => someRiskyOperation());

if ($error) {
    // Handle error
    log($error->getMessage());
    return;
}

// Process $data safely
echo $data;
```

## Why Use PHPAttempt?
- 🎯 Error-First Mindset : Forces developers to consider error cases first
- 🧩 Clean Code : Reduces try-catch block nesting and improves readability
- 🔒 Type-Safe : Fully typed return values for better IDE support
- 🪶 Lightweight : Zero dependencies, just one function
- ⚡ Framework Agnostic : Works with any PHP project

## Requirements
- PHP 8.2 or higher

## Basic Examples

```php
// Simple calculation
[$result, $error] = attempt(fn () => 1 + 1);
// $result = 2, $error = null

// Handling potential errors
[$user, $error] = attempt(function () {
    if (!validateInput()) {
        throw new InvalidArgumentException('Invalid input');
    }
    return createUser();
});

if ($error) {
    return response()->json(['error' => $error->getMessage()], 400);
}
```

## Usage with Laravel example

```php
// Controller method
public function store(Request $request)
{
    [$user, $error] = attempt(function () use ($request) {
        return User::create($request->validated());
    });

    if ($error) {
        return back()->withErrors(['message' => $error->getMessage()]);
    }

    return redirect()->route('users.show', $user);
}

// Service layer
public function processPayment(Order $order)
{
    [$transaction, $error] = attempt(function () use ($order) {
        $payment = StripePayment::create([
            'amount' => $order->total,
            'currency' => 'usd',
        ]);

        return $order->transactions()->create([
            'payment_id' => $payment->id,
            'status' => 'completed'
        ]);
    });

    if ($error) {
        Log::error('Payment failed: ' . $error->getMessage());
        throw new PaymentFailedException($error->getMessage());
    }

    return $transaction;
}
```

## Nested Attempts

You can safely nest attempt() calls:

```php
[$result, $error] = attempt(function () {
    [$user, $userError] = attempt(fn () => User::findOrFail($id));
    
    if ($userError) {
        throw new UserNotFoundException($userError->getMessage());
    }
    
    [$order, $orderError] = attempt(fn () => $user->orders()->latest()->firstOrFail());
    
    if ($orderError) {
        throw new OrderNotFoundException($orderError->getMessage());
    }
    
    return ['user' => $user, 'last_order' => $order];
});
```

## License
The MIT License (MIT). Please see License File for more information.

## Credits

[webpnk.dev](https://webpnk.dev)