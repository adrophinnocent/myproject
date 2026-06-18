# PayPal & Payment Gateway Setup

Currently, Twina Safaris accepts manual bookings. To enable automatic payments, follow this guide.

## 1. PayPal Integration
*   **Sandbox Mode:** Always test with a PayPal Developer account first.
*   **Live Credentials:** Add your `PAYPAL_CLIENT_ID` and `PAYPAL_SECRET` to the `.env` file.
*   **Webhook:** Set up the webhook to automatically update booking status to "Paid" once the traveler pays.

## 2. Bank Transfer
*   The "Bank Transfer" option is enabled by default. Ensure your bank details are provided in the automatic Booking Confirmation email.
