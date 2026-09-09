# POS System - Walkthrough Guide

## 1. Getting Started

### Prerequisites

- PHP 8.3+ with intl extension
- Composer
- Node.js (for asset compilation if needed)

### Installation

```bash
cd pos
php composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Default Login

| Field | Value |
|---|---|
| URL | http://localhost:8000/admin/login |
| Email | admin@pos.local |
| Password | password |

---

## 2. Navigation Overview

After login you see the Filament admin panel with these sidebar sections:

| Nav Group | Page | Icon | Description |
|---|---|---|---|
| **POS** | Point of Sale | Shopping Cart | Main sales interface |
| **Management** | Dashboard | Home | Stats overview |
| | Items | Cube | Item catalog CRUD |
| | Invoices | Document | Invoice list & view |
| | Reports | Chart Bar | Sales analytics |
| | Settings | Cog | Tax rate config |

---

## 3. Using the POS (Point of Sale)

This is the main screen for daily operations.

### Step 1: Search for Items

- Type in the **search bar** (top of form) - searches by item name, code, barcode, or category
- Matching items appear as clickable cards below the search bar
- Each card shows: name, code, category, pre-tax price, and tax-inclusive price

### Step 2: Add Items to Cart

- **Click any item card** to add it to the cart
- If the same item is clicked again, quantity increments by 1
- The search bar clears automatically after adding

### Step 3: Manage Cart

- **Quantity +/- buttons**: Adjust quantity per line item
- **X button**: Remove an item from the cart
- **Clear Cart button**: Empty the entire cart and reset the form

### Step 4: Enter Customer Info

- **Customer Name** (required): Name of the customer
- **Customer Contact** (optional): Phone number or email

### Step 5: Select Payment Method

- **Cash**: Shows "Amount Paid" field. Enter the amount received. Change is calculated automatically.
- **Card**: No amount paid field shown. Invoice records the card payment.
- **Other**: For any other payment method.

### Step 6: Create Invoice

- Review the **Order Summary** panel on the right:
  - Subtotal (pre-tax)
  - Tax total (16%)
  - Grand total
  - Amount paid and change (for cash payments)
- Click **Create Invoice** button
- On success, you are redirected to the invoice detail view

---

## 4. Managing Items

Navigate to **Items** in the sidebar.

### Adding a New Item

1. Click **+ New Item** button
2. Fill in:
   - **Name** (required)
   - **Code** (required, unique) - e.g. "WM-001"
   - **Barcode** (optional, unique) - for scanner support
   - **Category** - dropdown (General, Electronics, Groceries, Clothing, Furniture, Stationery)
   - **Unit** - defaults to "piece"
   - **Pre-tax Price** (required) - the base price before tax
   - **Description** (optional)
   - **Active** toggle - inactive items can't be added to new invoices
3. Click **Create**

### Editing an Item

- Click the **Edit** (pencil) icon on any row
- Modify fields and click **Save**

### Filtering Items

- Use the **Category** dropdown filter
- Use the **Active Status** toggle filter
- Type in the table search bar to search by name, code, or barcode

---

## 5. Viewing Invoices

Navigate to **Invoices** in the sidebar.

### Invoice List

The table shows: invoice number, customer, date, total, status, payment method, and creator.

### Filtering Invoices

| Filter | How to Use |
|---|---|
| Status | Dropdown: Paid, Void, Refunded |
| Payment Method | Dropdown: Cash, Card, Other |
| Date Range | Pick "From" and "To" dates |
| Contains Item | Type an item name to find invoices containing that item |

### Viewing Invoice Details

- Click the **View** (eye) icon on any invoice row
- Shows complete invoice with:
  - Invoice number, date, status, payment method
  - Customer name and contact
  - All line items (item, code, qty, unit price, tax, total)
  - Subtotal, tax total, grand total
  - Amount paid and change (if cash)
  - Void/refund details (if applicable)

### Printing Invoices

From the invoice detail view, two buttons appear at the top:

- **Print Receipt** - Opens thermal receipt format (80mm) in a new tab, auto-triggers print dialog
- **Print A4** - Opens A4 format in a new tab, auto-triggers print dialog

---

## 6. Voiding & Refunding Invoices

From the **Invoices** list table:

### Void an Invoice

1. Click the **Void** button (only visible on "Paid" invoices)
2. Enter the **reason for voiding** in the modal
3. Confirm - the invoice status changes to "Void"
4. The void reason is recorded and shown on the invoice detail

### Refund an Invoice

1. Click the **Refund** button (only visible on "Paid" invoices)
2. Enter the **refund amount** (defaults to the grand total)
3. Confirm - the invoice status changes to "Refunded"
4. The refund amount is recorded and shown on the invoice detail

---

## 7. Sales Reports

Navigate to **Reports** in the sidebar.

### Generating a Report

1. Select a **date range** (From / To)
2. Click **Generate Report**

### Report Sections

| Section | Shows |
|---|---|
| Summary Cards | Total invoices, total revenue, total tax collected, voided count |
| Daily Sales | Date, number of invoices, revenue per day |
| Top Selling Items | Items ranked by revenue (with quantity sold) |
| Top Customers | Customers ranked by total spending (with invoice count) |

---

## 8. System Settings

Navigate to **Settings** in the sidebar.

### Tax Rate

- Default: **16%**
- Change the value and click **Save Settings**
- This affects:
  - Tax-inclusive price display on items
  - Tax calculation on new invoices
  - Tax amount on invoice line items

---

## 9. Data Model Reference

```
items
  id, name, code (unique), barcode (unique), category,
  description, unit, pre_tax_price, is_active, timestamps

invoices
  id, invoice_number (unique), customer_name, customer_contact,
  subtotal, tax_total, grand_total, status (paid/void/refunded),
  payment_method (cash/card/other), amount_paid, change_amount,
  void_reason, refund_amount, created_by (FK->users), timestamps

invoice_items
  id, invoice_id (FK), item_id (FK), item_name, item_code,
  quantity, unit_price, tax_amount, line_total, timestamps

settings
  id, key (unique), value, timestamps
```

---

## 10. Key Design Decisions

| Decision | Rationale |
|---|---|
| Invoices are immutable (no edit) | Financial integrity - changes require void + new invoice |
| Invoice number uses DB lock | Prevents duplicate numbers under concurrent access |
| Item name/code snapshotted on invoice | Preserves historical accuracy even if item is later edited |
| Tax rate stored in settings table | Configurable without code changes |
| SQLite database | Simple deployment, no external DB server needed |
| Filament v5 admin panel | Rapid CRUD, built-in auth, polished UI |
