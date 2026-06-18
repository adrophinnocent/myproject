# Mailchimp Setup Guide for Twina Safaris

Mailchimp allows you to send beautiful marketing emails to your safari leads.

## 1. Get API Key
*   Log in to your Mailchimp account.
*   Go to **Account > Extras > API Keys**.
*   Create a new key and add it to your `.env` as `MAILCHIMP_API_KEY`.

## 2. Connect Audience
*   Find your **Audience ID** (List ID).
*   Add it to `.env` as `MAILCHIMP_LIST_ID`.

## 3. Automation
The Twina Safaris system can be set to automatically push every new newsletter subscriber directly into your Mailchimp list.
