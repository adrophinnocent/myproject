# Environment Setup Guide

This guide ensures your `.env` file is correctly configured for production.

## Essential Keys
*   **APP_KEY:** Never share this. It encrypts your sessions.
*   **DB_CONNECTION:** Use `sqlite` for local and `pgsql` or `mysql` for live servers.
*   **MAIL_MAILER:** Set to `smtp` for live emails. Use a Gmail App Password for the password field.

## Security Settings
*   **APP_DEBUG:** Must be `false` on your live website to hide technical errors from users.
*   **APP_URL:** Ensure this matches your real domain (e.g., `https://twinasafaris.com`).
