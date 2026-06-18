# Twina Safaris: Platform Architecture

This document describes the technical structure of the booking platform.

## 1. Technical Stack
*   **Framework:** Laravel 13 (PHP 8.3)
*   **Styling:** Tailwind CSS (Modern luxury utility-first CSS)
*   **Reactivity:** Alpine.js (Lightweight UI interaction)
*   **Database:** SQLite (Current) / PostgreSQL (Live recommended)

## 2. Core Modules
*   **Tour Engine:** Unified `Tour` model for Safaris, Trekking, and Day Trips.
*   **Booking System:** Handles leads, reference numbers, and status tracking.
*   **Notification Engine:** Live dashboard alerts and multi-recipient email system.
*   **Translation Engine:** JSON-based localization (FR, DE, ES, IT, ZH, NL).

## 3. Data Security
*   CSRF protection on all forms.
*   Password hashing (Bcrypt).
*   Admin authentication middleware.
