# POS System - Project TODO & Progress Tracker

## Project Info

| Field | Value |
|---|---|
| Stack | Laravel 13 + Filament v5 + Livewire 4 + SQLite |
| Tax Rate | 16% (configurable) |
| Invoice Format | INV-YYYY-NNNN (e.g. INV-2026-0001) |
| Auth | Filament login panel |

---

## Progress Summary

| Category | Done | Total | Status |
|---|---|---|---|
| Database & Models | 4/4 | 4 | DONE |
| Filament Resources | 2/2 | 2 | DONE |
| Custom Pages | 3/3 | 3 | DONE |
| Print Templates | 2/2 | 2 | DONE |
| Routes & Controllers | 1/1 | 1 | DONE |
| Seeders | 2/2 | 2 | DONE |
| Dashboard Widget | 1/1 | 1 | DONE |
| **Overall** | **15/15** | | **COMPLETE** |

---

## Detailed Checklist

### 1. Infrastructure

- [x] Laravel 13 project scaffolded
- [x] Filament v5 installed and configured
- [x] intl PHP extension enabled
- [x] SQLite database configured
- [x] AdminPanelProvider registered
- [x] Filament assets published

### 2. Database Migrations

- [x] `items` table (name, code, barcode, category, description, unit, pre_tax_price, is_active)
- [x] `invoices` table (invoice_number, customer info, totals, status, payment, void/refund fields, created_by FK)
- [x] `invoice_items` table (invoice_id FK, item_id FK, snapshots of name/code/price/qty/tax/total)
- [x] `settings` table (key-value store for tax_rate etc.)
- [x] Default users, cache, jobs tables (Laravel defaults)

### 3. Eloquent Models

- [x] `Item` - active scope, search scope, computed price_including_tax & tax_amount
- [x] `Invoice` - items/creator relationships, generateInvoiceNumber() with DB lock, status_color, search scope
- [x] `InvoiceItem` - belongs to Invoice & Item
- [x] `Setting` - getValue/setValue static helpers

### 4. Filament Resources

- [x] `ItemResource` - Full CRUD (create/edit/list), form with sections (info, pricing, description), table with columns/filters (category, active status), search by name/code/barcode
- [x] `InvoiceResource` - List + View pages, infolist with sections (details, customer, line items, totals, payment, void/refund), table with filters (status, payment method, date range, contains item), void/refund actions

### 5. Custom Filament Pages

- [x] `POS` page - Item search, cart management (add/update/remove/clear), customer info, payment method, amount paid, change calculation, invoice creation via DB transaction
- [x] `Settings` page - Tax rate configuration
- [x] `Reports` page - Date-range filter, summary cards (invoices, revenue, tax, voids), daily sales table, top items table, top customers table

### 6. Print Templates

- [x] Thermal receipt (80mm width, monospace, auto-print via JS)
- [x] A4 invoice (full-page layout with header, sections, tables, totals)

### 7. Routes & Controllers

- [x] `/` redirects to POS page
- [x] `InvoicePrintController` with `printThermal` and `printA4` methods
- [x] Print routes registered

### 8. Seeders

- [x] `DatabaseSeeder` - Creates tax_rate setting, admin user (admin@pos.local / password), calls ItemSeeder
- [x] `ItemSeeder` - 12 sample items (electronics, stationery, furniture) with codes, barcodes, prices

### 9. Dashboard

- [x] `StatsOverview` widget - Today's invoices, today's revenue, month revenue, active items count

---

## Known Issues / Bugs Fixed

| Date | Issue | Fix |
|---|---|---|
| 2026-08-18 | `Filament\Enums\NavigationGroup` does not exist in v5 | Removed import, use `getNavigationGroup()` method |
| 2026-08-18 | `$navigationGroup` type mismatch (`?string` vs `string\|UnitEnum\|null`) | Replaced property with `getNavigationGroup()` method |
| 2026-08-18 | `$navigationIcon` type mismatch (`?string` vs `string\|BackedEnum\|null`) | Replaced property with `getNavigationIcon()` method |
| 2026-08-18 | `form()` signature changed to `Schema` in Filament v5 | Updated all `form()` method signatures |
| 2026-08-18 | `infolist()` signature changed to `Schema` in Filament v5 | Updated `infolist()` method signature |
| 2026-08-18 | `$view` property is non-static in Filament v5 Page | Removed `static` keyword from `$view` declarations |
| 2026-08-18 | Heroicon names used spaces instead of hyphens | Fixed to hyphens (`heroicon-o-printer`) |
| 2026-08-19 | `heroicon-oprinter` missing dash in InvoiceResource table action | Fixed to `heroicon-o-printer` |
| 2026-08-19 | POS page `statePath('data')` caused form state desync from public properties | Removed `defaultForm()` so form values map to public Livewire properties directly |
| 2026-08-19 | Print templates hardcoded `Tax (16%)` ignoring configured rate | Controller now passes dynamic `$taxRate` from settings to thermal and A4 views |
| 2026-08-19 | Feature test expected 200 from `/` but route redirects (302) | Fixed assertion to expect 302 |

---

## Optional Enhancements (Not in v1 scope)

- [ ] Thermal receipt auto-print on invoice creation
- [ ] Barcode scanner input support (auto-focus search on scan)
- [ ] Multi-currency support
- [ ] Inventory/stock management
- [ ] Payment gateway integration
- [ ] Multi-branch / multi-warehouse
- [ ] Advanced reporting with charts
- [ ] Export reports to CSV/Excel
- [ ] Customer management (CRUD for customers)
- [ ] User roles beyond admin/cashier
- [ ] Dark mode toggle
- [ ] Keyboard shortcuts for POS (F2=search, F5=clear, etc.)
