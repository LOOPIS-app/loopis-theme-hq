# Stripe Setup (Membership)

## Flow (short)
1. User signs up and confirms email on `/wp-activate.php`.
2. Account is active with role `member_pending`.
3. User logs in and goes to `/shop/?option=membership-stripe`.
4. Button creates Stripe Checkout Session via `POST /wp-json/loopis/v1/create-membership-checkout`.
5. Stripe redirects back to:
   - success: `/shop/?option=membership-stripe&checkout=success`
   - cancel: `/shop/?option=membership-stripe&checkout=cancelled`
6. Webhook `checkout.session.completed` calls `/wp-json/loopis/v1/stripe-membership-webhook`.
7. User is upgraded from `member_pending` to `member`, payment is stored.

## What you must set in Stripe Dashboard

### 1. API keys
- Go to Developers -> API keys.
- Use your Secret key as environment variable:

```env
LOOPIS_STRIPE_SECRET_KEY=sk_test_...   # or sk_live_...
```

### 2. Membership webhook endpoint
- Go to Developers -> Webhooks -> Add endpoint.
- Endpoint URL:
  - `https://YOUR_DOMAIN/wp-json/loopis/v1/stripe-membership-webhook`
- Event to send:
  - `checkout.session.completed`
- Copy signing secret (`whsec_...`) and set:

```env
LOOPIS_STRIPE_WEBHOOK_SECRET_MEMBERSHIP=whsec_...
```

### 3. Mode consistency
- Test mode: use `sk_test_...` + test webhook secret and set `WP_TEST=true`.
- Live mode: use `sk_live_...` + live webhook secret and set `WP_TEST=false`.

### 4. Verify membership price IDs
Price IDs are used in code via `loopis_get_membership_stripe_product_ids()`.
If your Stripe product uses different IDs, update them in:
- `includes/functions/payment/stripe-membership.php`

## Quick verification
1. Sign up a new user and confirm email.
2. Log in and open `/shop/?option=membership-stripe`.
3. Pay with Stripe.
4. Confirm redirect to `...membership-stripe&checkout=success`.
5. Confirm user role changes to `member`.

## Optional log checks
Look for these in `wp-content/debug.log`:
- `LOOPIS: Stripe membership webhook received`
- `LOOPIS: activate_account success using Stripe`
