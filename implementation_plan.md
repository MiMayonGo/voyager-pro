# Implementation Plan — Reports Page Overhaul

[Overview]
Restructure the Reports page into 4 enhanced tabs (Financial, Customers, Packages, Operations) with new charts, metrics, and a single "Export Full Report" CSV button.

[Types]
No new database types needed.

[Files]
- **Modified: `app/Http/Controllers/ReportController.php`** — Add all new data queries for Financial, Customers, Packages, Operations tabs + CSV export method.
- **Modified: `resources/views/reports/index.blade.php`** — Complete rewrite with 4 tabs, new charts, new tables, export button.
- **Modified: `routes/web.php`** — Add route for CSV export.

[Functions]
- **`ReportController::index()`** — Add queries for:
  - Payment status breakdown (paid/pending/failed/refunded counts + amounts)
  - Refunds summary (cancelled bookings count, refunded payments)
  - Repeat vs first-time customer counts
  - Worst performing packages (lowest bookings)
  - Seasonal trend data (bookings by month × package)
  - Payment success rate
  - Average booking-to-payment time
  - Cancellation rate

- **`ReportController::export()`** — New method. Generates a CSV file with all system data: bookings, payments, packages, users, reviews.

[Classes]
No new classes.

[Dependencies]
No new packages. CSV export uses Laravel's built-in streaming response.

[Testing]
Manual verification of all 4 tabs and CSV export.

[Implementation Order]
1. Rewrite ReportController with all new data + export method
2. Rewrite reports/index.blade.php with 4 tabs + export button
3. Add export route to web.php
