# Product Requirements Document (PRD)
## Sistem Informasi Data Pangan

---

## 1. Product Overview

### 1.1 Product Name
**SISTEM INFORMASI DATA PANGAN**
Food Commodity Price Information System

### 1.2 Purpose
A web-based information system designed to track, manage, and monitor food commodity prices across different dates. The system enables administrators to manage commodity data and users to view price trends and historical data.

### 1.3 Target Organization
**SATUAN INTELIJEN KORBRIMOB POLRI**
(Indonesian Police Intelligence Unit - Mobile Brigade Corps)

### 1.4 Target Users
- **Admin (DansatIntel)**: Full access to manage commodities and users
- **Regular Users (anggota, Dansat level)**: View data and access reports
- **Stakeholders**: Law enforcement personnel monitoring food commodity prices

---

## 2. Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend Language | PHP | 8.0.30 |
| Database | MySQL/MariaDB | 10.4.32 |
| Frontend | HTML5, Tailwind CSS (CDN), Vanilla JavaScript | - |
| CSS Framework | Tailwind CSS | v3 (CDN) |
| Web Server | Apache | - |
| Version Control | Git | - |

---

## 3. Core Features & Functionalities

### 3.1 Authentication & Authorization

#### Login System
- Username and password validation against database
- Session-based authentication
- Session data stored:
  - `id_petugas`: User ID
  - `username`: Login username
  - `leveluser`: User level (Dansat, anggota)
  - `role`: User role (admin, user)

#### Role-Based Access Control
- **Admin users** can access:
  - Dashboard
  - Commodity management (CRUD)
  - User management (CRUD)
- **Regular users** can access:
  - Dashboard
  - View history
  - Transaction features (UI exists but not implemented)

### 3.2 Dashboard (Main Interface)

#### Features:
1. **Header with Logo**
   - System title display
   - Organization logo

2. **Data Panel Dropdown** (Admin only)
   - Switch between "Konsumen" and "Produsen" views

3. **Date Range Filter**
   - Filter commodity prices by custom date range
   - Default: Yesterday (tanggal_awal) and Today (tanggal_akhir)
   - Reset button to return to default dates

4. **Price Comparison Table**
   - Columns:
     - **Komoditas**: Commodity name
     - **Hari Ini (Rp)**: Latest price (today)
     - **Kemarin (Rp)**: Previous price (yesterday)
     - **Perubahan**: Price change with percentage
   - Visual indicators:
     - **Red ▲**: Price increase
     - **Green ▼**: Price decrease
     - **Gray ━**: No change
   - Formatted rupiah display with thousand separators

5. **Navigation Menu**
   - Conditional display based on user role
   - Admin options:
     - Kelola Data Bahan Pangan
     - Kelola User
   - User options:
     - Transaksi Pembayaran (placeholder)
     - History Pembayaran (placeholder)

6. **Logout Button**

### 3.3 Admin Panel - Commodity Management

#### CRUD Operations for Commodities

**Create (Tambah Data):**
- Form fields:
  - Komoditas (text): Commodity name
  - Tanggal (date): Date of price record
  - Harga (number): Price in Rupiah (integer, min: 0)
  - Kategori (dropdown/text):
    - Select from existing categories
    - OR create new category
- JavaScript validation for form completion
- Category toggle functionality (select existing or create new)

**Read (Display Data):**
- Table listing all commodities
- Columns: ID, Komoditas, Tanggal, Harga, Kategori, Aksi
- Data sorting: ORDER BY tanggal DESC, komoditas ASC (newest first)
- Sequential numbering starting from 1
- Formatted date display (dd/mm/yyyy)
- Formatted rupiah display

**Update (Edit Data):**
- Click "Edit" button to load commodity data into form
- Same form used for both add and edit
- Preserves data in form fields
- Cancel button to abort edit and return to list
- Hidden input for ID and action type

**Delete (Hapus Data):**
- Confirmation dialog before deletion
- Removes record from database
- Success/error message after operation

**Status Notifications:**
- Success messages (green):
  - Data berhasil ditambahkan
  - Data berhasil diupdate
  - Data berhasil dihapus
- Error messages (red):
  - Harga tidak valid
  - Gagal menambahkan data
  - Gagal mengupdate data

### 3.4 Admin Panel - User Management

#### CRUD Operations for Users

**Create (Tambah User):**
- Form fields:
  - Username (text): Login username
  - Password (password): Numeric password
  - Level User (text): User level (e.g., Dansat, anggota)
  - Role (dropdown): admin or user

**Read (Display Users):**
- User table with all credentials
- Columns: ID, Username, Password, Level User, Role, Aksi
- Sequential numbering
- Role badges with color coding:
  - Admin: Red badge
  - User: Blue badge

**Update (Edit User):**
- Edit user details
- Password field is optional (if empty, doesn't change)
- Preserves existing password if not updated
- Cancel button to abort edit

**Delete (Hapus User):**
- Remove user with confirmation
- Confirmation dialog before deletion

**Status Notifications:**
- Success/error messages for each operation

### 3.5 Processing Logic (Backend)

#### Login Processing (`proses_login.php`)
- Validates credentials against `tb_login` table
- Creates session variables if match found
- Redirects to dashboard on success
- Returns error message on failure

#### Commodity Processing (`proses_bahanpangan.php`)
- Handles INSERT, UPDATE, DELETE operations
- Input sanitization using `mysqli_real_escape_string()`
- Price validation (must be non-negative)
- Redirect with status messages

#### User Processing (`proses_user.php`)
- Handles INSERT, UPDATE, DELETE for users
- Password update is optional during edit
- Redirect with status messages

---

## 4. Database Schema

### 4.1 Table: `tb_bahanpangan` (Food Commodities)

```sql
CREATE TABLE tb_bahanpangan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  komoditas VARCHAR(100),  -- commodity name
  tanggal DATE,            -- date of price record
  harga INT,               -- price in rupiah
  kategori VARCHAR(50)     -- category
);
```

**Sample Categories:**
- Beras (Rice): 3 quality levels
  - Beras Kualitas Bawah
  - Beras Kualitas Medium
  - Beras Kualitas Premium
- Daging (Meat):
  - Daging Ayam Ras Segar
  - Daging Sapi
- Telur (Eggs):
  - Telur Ayam Ras Segar
- Bawang (Garlic/Onion):
  - Bawang Merah
  - Bawang Putih
- Cabai (Chili):
  - Cabai Merah Besar
  - Cabai Merah Keriting
  - Cabai Rawit Hijau
  - Cabai Rawit Merah
- Minyak Goreng (Cooking Oil):
  - Minyak Goreng Curah
  - Minyak Goreng Kemasan Bermerk
- Gula Pasir (Sugar)

### 4.2 Table: `tb_login` (User Credentials)

```sql
CREATE TABLE tb_login (
  id_petugas INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),    -- login username
  password INT(50),        -- numeric password (plain text)
  leveluser VARCHAR(50),   -- user level
  role VARCHAR(20)         -- role (admin/user)
);
```

**Default Users:**

| Username | Password | Level User | Role |
|----------|----------|------------|------|
| DansatIntel | 1609 | Dansat | admin |
| KaurOps | 1234 | anggota | user |

---

## 5. User Interface Design

### 5.1 Design System

#### Color Palette
- **Primary Colors:**
  - Red 900: `#9c1616` (main brand color)
  - Gray 100: `#f4f0f0` (background, inputs)
- **Secondary Colors:**
  - Black: `#171111` (text)
  - Gray: `#876464` (secondary text)
  - White: `#FFFFFF` (cards, forms)
- **Borders:**
  - Gray: `#e5dcdc`

#### Typography
- **Font Family**: 'Public Sans', 'Noto Sans', sans-serif
- **Headings**: Bold, sizes from text-lg to text-4xl
- **Body**: Regular weight, responsive sizing

#### Components
- **Buttons**:
  - Primary: Red 900 background, white text, rounded-xl
  - Secondary: Gray 100 background, black text, rounded-xl
- **Forms**:
  - Border: 1px solid `#e5dcdc`
  - Focus: No outline, no ring
  - Rounded: rounded-xl
  - Padding: p-[15px]
- **Tables**:
  - Header: White background, black text
  - Rows: Border-separated
  - Borders: `#e5dcdc`

### 5.2 Page Layouts

#### Login Page (`auth/login.blade.php`)
- Centered card layout
- Gradient background (primary-blue to accent-red)
- Security icon
- Modern input fields and buttons

#### Main Application Layout (`layouts/app.blade.php`)
- Top navigation header
- Main content area with centered, max-width container

#### Dashboard Page (`dashboard.blade.php`)
- Uses the main application layout
- Displays a title
- Contains date filters and a table for price comparison

#### Admin Pages (`admin/**/*.blade.php`)
- Use the main application layout
- Display a title and description
- Contain forms and tables for data management

### 5.3 UI Components Library

#### Input Fields
```html
<input
    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
```

#### Buttons
```html
<!-- Primary Button -->
<button
    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#9c1616] text-white text-sm font-bold leading-normal tracking-[0.015em]">
    <span class="truncate">Simpan</span>
</button>

<!-- Secondary Button -->
<button
    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#f4f0f0] text-[#171111] text-sm font-bold leading-normal tracking-[0.015em]">
    <span class="truncate">Batal</span>
</button>
```

#### Tables
```html
<div class="flex overflow-hidden rounded-xl border border-[#e5dcdc] bg-white">
    <table class="flex-1">
        <thead>
            <tr class="bg-white">
                <th class="px-4 py-3 text-left text-[#171111] text-sm font-medium leading-normal">
                    Header
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t border-t-[#e5dcdc]">
                <td class="h-[72px] px-4 py-2 text-[#171111] text-sm font-normal leading-normal">
                    Data
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 6. User Flows

### 6.1 Login & Dashboard Access Flow
```
START
  ↓
User visits index.php
  ↓
Check if already logged in (auth_check.php)
  ├─ YES → Redirect to dashboard.php
  └─ NO → Display login form
       ↓
User enters username & password
  ↓
Submit form to proses_login.php
  ↓
Validate credentials against tb_login
  ├─ INVALID → Redirect to index.php?pesan=error
  └─ VALID → Create session variables
       ↓
       Redirect to dashboard.php
       ↓
       Display dashboard with filtered price data
       ↓
END
```

### 6.2 Admin - Commodity Management Flow
```
START (from dashboard)
  ↓
Click "Kelola Data Bahan Pangan"
  ↓
Check admin authorization (admin_auth.php)
  ├─ NOT ADMIN → Redirect to dashboard
  └─ ADMIN → Open kelola_bahanpangan.php
       ↓
       Display commodity list & form
       ↓
User selects action:
  ├─ ADD NEW:
  │   └─ Fill form → Submit → proses_bahanpangan.php (action=tambah)
  │       → INSERT into tb_bahanpangan
  │       → Redirect with status=tambah_success
  │
  ├─ EDIT:
  │   └─ Click Edit → Load data to form → Modify → Submit
  │       → proses_bahanpangan.php (action=edit)
  │       → UPDATE tb_bahanpangan WHERE id=X
  │       → Redirect with status=edit_success
  │
  └─ DELETE:
      └─ Click Hapus → Confirm → proses_bahanpangan.php (action=hapus)
          → DELETE FROM tb_bahanpangan WHERE id=X
          → Redirect with status=hapus_success
          ↓
Display updated list with status message
  ↓
END
```

### 6.3 Admin - User Management Flow
```
START (from dashboard)
  ↓
Click "Kelola User"
  ↓
Check admin authorization (admin_auth.php)
  ├─ NOT ADMIN → Redirect to dashboard
  └─ ADMIN → Open kelola_user.php
       ↓
       Display user list & form
       ↓
User selects action:
  ├─ ADD NEW:
  │   └─ Fill form (username, password, leveluser, role)
  │       → Submit → proses_user.php (action=tambah)
  │       → INSERT into tb_login
  │       → Redirect with status message
  │
  ├─ EDIT:
  │   └─ Click Edit → Load data to form
  │       → Modify (password optional)
  │       → Submit → proses_user.php (action=edit)
  │       → UPDATE tb_login WHERE id_petugas=X
  │       → Redirect with status message
  │
  └─ DELETE:
      └─ Click Hapus → Confirm → proses_user.php (action=hapus)
          → DELETE FROM tb_login WHERE id_petugas=X
          → Redirect with status message
          ↓
Display updated list with status message
  ↓
END
```

### 6.4 Price Viewing & Filtering Flow
```
START (from dashboard)
  ↓
User on dashboard.php
  ↓
Default: Display data for yesterday & today
  ↓
User optionally changes date range:
  ├─ Select Tanggal Awal (start date)
  ├─ Select Tanggal Akhir (end date)
  └─ Click "Filter" button
      ↓
      Submit GET request to dashboard.php with parameters
      ↓
      Query tb_bahanpangan WHERE tanggal BETWEEN dates
      ↓
      Group data by komoditas
      ↓
      Calculate price changes:
      │  - First record (awal) vs Last record (akhir)
      │  - Determine color & symbol (▲▼━)
      │  - Calculate percentage change
      ↓
      Display table with:
      │  - Commodity name
      │  - Latest price (akhir)
      │  - Previous price (awal)
      │  - Change (with color & symbol)
      ↓
User can click "Reset" to return to default dates
  ↓
END
```

### 6.5 Logout Flow
```
START (from any logged-in page)
  ↓
User clicks "Logout" button
  ↓
Navigate to fungsi/logout.php
  ↓
Destroy session (session_destroy())
  ↓
Redirect to index.php
  ↓
Display login page
  ↓
END
```

---

## 7. API Endpoints (Server Routes)

| Endpoint | Method | Purpose | Parameters | Response |
|----------|--------|---------|------------|----------|
| `/index.php` | GET | Display login form | - | HTML login page |
| `/fungsi/proses_login.php` | POST | Process login | `username`, `password` | Redirect to dashboard or error |
| `/dashboard.php` | GET | Display dashboard | `tanggal_awal`, `tanggal_akhir` (optional) | HTML dashboard with filtered data |
| `/admin/kelola_bahanpangan.php` | GET | Show commodity management | `edit` (optional) | HTML admin panel |
| `/admin/proses_bahanpangan.php` | POST/GET | Process commodity CRUD | `action`, `id`, `komoditas`, `tanggal`, `harga`, `kategori` | Redirect with status |
| `/admin/kelola_user.php` | GET | Show user management | `edit` (optional) | HTML admin panel |
| `/admin/proses_user.php` | POST/GET | Process user CRUD | `action`, `id_petugas`, `username`, `password`, `leveluser`, `role` | Redirect with status |
| `/fungsi/logout.php` | GET | Logout user | - | Redirect to index.php |

---

## 8. Business Rules

### 8.1 Authentication Rules
1. Users must log in to access any page except index.php
2. Session expires when browser is closed (session-based)
3. Only admin role can access admin panel pages
4. Invalid login shows error message: "pesan" parameter in URL

### 8.2 Data Validation Rules

#### Commodity Data
1. **Komoditas** (Commodity):
   - Required field
   - Text input
   - No specific length limit

2. **Tanggal** (Date):
   - Required field
   - Must be valid date format (Y-m-d)
   - Default to current date when adding

3. **Harga** (Price):
   - Required field
   - Must be integer (whole number)
   - Must be non-negative (>= 0)
   - Display format: Rp X.XXX (thousand separator)

4. **Kategori** (Category):
   - Required field
   - Can select existing category or create new
   - New category validation: must not be empty

#### User Data
1. **Username**:
   - Required field
   - Varchar(50)
   - Must be unique

2. **Password**:
   - Required when creating new user
   - Optional when editing (blank = keep current)
   - Must be numeric
   - Stored as plain text (security concern)

3. **Level User**:
   - Required field
   - Text input (e.g., Dansat, anggota)

4. **Role**:
   - Required field
   - Must be either "admin" or "user"

### 8.3 Business Logic

#### Price Comparison Calculation
```
For each commodity:
  1. Get all records in date range
  2. First record = harga_awal (previous)
  3. Last record = harga_akhir (latest)
  4. Calculate: perubahan = harga_akhir - harga_awal
  5. Calculate: persentase = (perubahan / harga_awal) × 100
  6. Determine display:
     - IF perubahan > 0: Red text, ▲ symbol (price increase)
     - IF perubahan < 0: Green text, ▼ symbol (price decrease)
     - IF perubahan = 0: Gray text, ━ symbol (no change)
```

#### Date Filter Default
```
IF no date parameters in URL:
  tanggal_akhir = TODAY
  tanggal_awal = YESTERDAY (TODAY - 1 day)
ELSE:
  Use provided dates from GET parameters
```

### 8.4 Authorization Rules

| Page | Admin Access | User Access |
|------|--------------|-------------|
| `index.php` | ✅ (redirects to dashboard if logged in) | ✅ |
| `dashboard.php` | ✅ | ✅ |
| `kelola_bahanpangan.php` | ✅ | ❌ |
| `kelola_user.php` | ✅ | ❌ |
| `proses_bahanpangan.php` | ✅ | ❌ |
| `proses_user.php` | ✅ | ❌ |

---

## 9. Installation & Setup

### 9.1 Prerequisites
- PHP 8.0 or higher
- MySQL/MariaDB 10.4 or higher
- Apache web server
- Web browser (Chrome, Firefox, Edge)

### 9.2 Installation Steps

1. **Clone or Download Project**
   ```bash
   git clone <repository-url>
   # or download and extract ZIP
   ```

2. **Import Database**
   - Open phpMyAdmin or MySQL client
   - Create new database: `db_bahanpangan`
   - Import file: `db_bahanpangan.sql`

3. **Configure Database Connection**
   - Open: `fungsi/koneksi.php`
   - Update if needed:
     ```php
     $server = "localhost";
     $user = "admin";
     $pass = "admin";
     $database = "db_bahanpangan";
     ```

4. **Deploy to Web Server**
   - Copy project folder to web server root (e.g., `htdocs`, `www`)
   - Ensure Apache is running
   - Ensure MySQL is running

5. **Access Application**
   - Open browser
   - Navigate to: `http://localhost/[project-folder]/index.php`

6. **Login with Default Credentials**
   - Admin:
     - Username: `DansatIntel`
     - Password: `1609`
   - User:
     - Username: `KaurOps`
     - Password: `1234`

### 9.3 Database Reset
If needed, use `reset-db.php` to reset database (use with caution).

---

## 10. Known Issues & Limitations

### 10.1 Security Issues

1. **SQL Injection Vulnerability**
   - Direct SQL concatenation used in queries
   - Only partial mitigation with `mysqli_real_escape_string()`
   - **Recommendation**: Use prepared statements

2. **Password Security**
   - Passwords stored as plain text (not hashed)
   - Passwords are numeric only
   - Visible in user management table
   - **Recommendation**: Implement password hashing (bcrypt, argon2)

3. **Session Management**
   - Session variable assignment issues in `proses_login.php`:
     ```php
     // Line 22-23: Incorrect assignments
     $_SESSION['username'] = $password; // should be username
     $_SESSION['password'] = $data['idkaryawan']; // field doesn't exist
     ```
   - **Recommendation**: Fix session variable assignments

4. **No CSRF Protection**
   - Forms don't include CSRF tokens
   - **Recommendation**: Implement CSRF protection

5. **XSS Vulnerability**
   - User inputs displayed without sanitization
   - **Recommendation**: Use `htmlspecialchars()` on output

### 10.2 Functional Limitations

1. **Missing Features**
   - "Transaksi Pembayaran" page doesn't exist
   - "History Pembayaran" page doesn't exist
   - Menu items present but links are broken

2. **Validation Issues**
   - Minimal input validation
   - No username uniqueness check
   - No duplicate commodity check for same date

3. **No Pagination**
   - All data displayed in single table
   - May cause performance issues with large datasets

4. **No Search/Filter in Admin Tables**
   - Cannot search commodities or users
   - Only date filter on dashboard

5. **Limited Error Handling**
   - Basic error messages
   - No detailed logging
   - Database connection errors only show basic message

### 10.3 UI/UX Limitations

1. **No Responsive Testing**
   - Layout may break on very small screens
   - Table overflow on mobile not fully handled

2. **No Loading Indicators**
   - No feedback during form submission
   - No loading state for long operations

3. **Limited Accessibility**
   - No ARIA labels
   - No keyboard navigation support
   - No screen reader optimization

4. **Browser Compatibility**
   - Not tested on older browsers
   - Relies on modern CSS (Tailwind)

---

## 11. Future Enhancements

### 11.1 Priority 1 - Security Fixes
- [ ] Implement prepared statements for all queries
- [ ] Add password hashing (bcrypt)
- [ ] Fix session variable assignments
- [ ] Add CSRF protection
- [ ] Sanitize all user inputs and outputs

### 11.2 Priority 2 - Core Features
- [ ] Implement "Transaksi Pembayaran" feature
- [ ] Implement "History Pembayaran" feature
- [ ] Add pagination for large datasets
- [ ] Add search/filter in admin tables
- [ ] Add username uniqueness validation
- [ ] Add duplicate commodity detection

### 11.3 Priority 3 - UX Improvements
- [ ] Add loading indicators
- [ ] Implement toast notifications
- [ ] Add data export (Excel, PDF)
- [ ] Add data visualization (charts, graphs)
- [ ] Improve mobile responsiveness
- [ ] Add keyboard shortcuts

### 11.4 Priority 4 - Advanced Features
- [ ] User activity logging
- [ ] Email notifications
- [ ] Backup and restore functionality
- [ ] Multi-language support
- [ ] Advanced reporting
- [ ] API for mobile apps

---

## 12. Development Guidelines

### 12.1 Code Style (from CLAUDE.md)

1. **Keep code very simple for beginners**
   - Avoid complex patterns
   - Use straightforward logic
   - Minimal abstraction

2. **Use Indonesian comments on important code**
   - All comments in lowercase
   - Place above important logic blocks
   - Explain "why" not "what"

3. **No over-engineering**
   - Don't add unnecessary features
   - Don't create complex architecture
   - KISS principle (Keep It Simple, Stupid)

4. **Follow current structure**
   - Match existing file organization
   - Use same naming conventions
   - Consistent code formatting

5. **No assumptions**
   - Ask before implementing unclear requirements
   - Clarify ambiguous specifications
   - Document decisions

6. **No improvisation unless requested**
   - Stick to requirements
   - Don't add features without approval
   - Maintain scope

### 12.2 File Organization

```
project-root/
├── index.php              # Entry point (login)
├── dashboard.php          # Main dashboard
├── reset-db.php           # DB reset utility
├── db_bahanpangan.sql     # Database schema
├── 1.png                  # Logo
├── 2.jpg                  # Secondary image
├── CLAUDE.md              # Coding guidelines
├── .gitignore             # Git ignore rules
├── PRD.md                 # This document
│
├── fungsi/                # Core functions
│   ├── koneksi.php        # DB connection
│   ├── proses_login.php   # Login processor
│   ├── auth_check.php     # Index auth check
│   ├── dashboard_auth.php # Dashboard auth
│   └── logout.php         # Logout handler
│
└── admin/                 # Admin panel
    ├── admin_auth.php     # Admin authorization
    ├── kelola_bahanpangan.php  # Commodity CRUD UI
    ├── kelola_user.php    # User CRUD UI
    ├── proses_bahanpangan.php  # Commodity processor
    └── proses_user.php    # User processor
```

### 12.3 Naming Conventions

- **Files**: lowercase with underscores (`kelola_user.php`)
- **Variables**: camelCase (`$tanggalAwal`)
- **Database tables**: `tb_` prefix (`tb_bahanpangan`)
- **Database fields**: lowercase (`id_petugas`)
- **Constants**: UPPERCASE (`DATABASE`)
- **Functions**: camelCase (`toggleKategori()`)

---

## 13. Support & Contact

### 13.1 Default Login Credentials

**Administrator:**
- Username: `DansatIntel`
- Password: `1609`
- Access: Full system access

**Regular User:**
- Username: `KaurOps`
- Password: `1234`
- Access: Dashboard view only

### 13.2 System Information

- **System Name**: SISTEM INFORMASI DATA PANGAN
- **Organization**: SATUAN INTELIJEN KORBRIMOB POLRI
- **Copyright**: © 2024 Data Pangan Indonesia

---

## 14. Glossary

| Term | Indonesian | Definition |
|------|-----------|------------|
| Komoditas | Commodity | Food items being tracked (rice, meat, etc.) |
| Bahan Pangan | Food Commodity | Raw food materials |
| Harga | Price | Cost of commodity in Rupiah |
| Tanggal | Date | Date of price record |
| Kategori | Category | Grouping of similar commodities |
| Dansat | Dansat Level | High-level user (Commander level) |
| Anggota | Member | Regular member level |
| Kelola | Manage | CRUD operations |
| Perubahan | Change | Price difference |
| Hari Ini | Today | Current day |
| Kemarin | Yesterday | Previous day |

---

## 15. Appendix

### 15.1 Sample Data Structure

#### Sample Commodity Record
```json
{
  "id": 1,
  "komoditas": "Beras Kualitas Premium",
  "tanggal": "2025-10-03",
  "harga": 15000,
  "kategori": "Beras"
}
```

#### Sample User Record
```json
{
  "id_petugas": 1,
  "username": "DansatIntel",
  "password": "1609",
  "leveluser": "Dansat",
  "role": "admin"
}
```

### 15.2 Color Reference

```css
/* Primary Colors */
--red-800: #991B1B;
--red-900: #7F1D1D;
--red-700: #B91C1C;

/* Secondary Colors */
--gray-800: #1F2937;
--gray-100: #F3F4F6;
--gray-200: #E5E7EB;

/* Status Colors */
--green-600: #059669;
--red-600: #DC2626;
--blue-600: #2563EB;
```

### 15.3 Icon Reference

Emojis used in UI:
- 📦 - Commodity Management
- 👥 - User Management
- 📊 - Transaction
- 📋 - History
- 🚪 - Logout
- 🔍 - Filter
- 👤 - Username
- 🔒 - Password

### 15.4 Database Connection Settings

```php
$server = "localhost";
$user = "admin";
$pass = "admin";
$database = "db_bahanpangan";
$koneksi = mysqli_connect($server, $user, $pass, $database);
mysqli_set_charset($koneksi, 'utf8mb4');
```

---

**Document Version**: 1.0
**Last Updated**: October 2024
**Created By**: System Analysis
**Status**: Production Ready (with known security issues)

---

## Notes for Replication

When replicating this system in a different project structure:

1. **Maintain the core business logic**: Price comparison calculation, date filtering, CRUD operations
2. **Keep the UI/UX design**: Color scheme, layout patterns, user flows
3. **Preserve the data structure**: Same database schema for compatibility
4. **Follow coding guidelines**: Simple code for beginners, Indonesian comments
5. **Address security issues**: Implement fixes mentioned in Section 10.1
6. **Test thoroughly**: Especially authentication and authorization flows
7. **Update configuration**: Database credentials, file paths, etc.
8. **Consider missing features**: Decide whether to implement placeholder features
9. **Maintain documentation**: Keep this PRD updated with any changes
10. **Follow CLAUDE.md**: Adhere to project-specific coding standards

This PRD serves as a complete blueprint for understanding, maintaining, and replicating the Sistem Informasi Data Pangan application.
