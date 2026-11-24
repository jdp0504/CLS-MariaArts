# Maria Art – Customer Loyalty System (CLS)

A web-based customer loyalty system for Maria Art. Customers earn points on purchases and referrals and redeem them for rewards. Cashiers record purchases and redemptions, and admins manage members, rewards, email notifications and reports.

Built with **Laravel 13**, **PHP 8.3+**, Blade, Tailwind CSS and Vite.

| Role | What they can do |
|---|---|
| Customer | Register (with optional referral code), view points and rewards, edit profile |
| Cashier | Find a customer by phone, add purchase points (RM1 = 1 point), redeem rewards, reset passwords |
| Admin | Archive or reactivate members, manage rewards, send email notifications, view reports |

---

## 1. Requirements

Install these first:

| Software | Version | Check with |
|---|---|---|
| PHP | **8.3 or newer** | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js | **20.19+ or 22.12+** | `node -v` |
| Git | any | `git --version` |

PHP needs these extensions enabled: `pdo_sqlite` (or `pdo_mysql` for MySQL), `mbstring`, `bcmath`, `intl`, `zip`, `fileinfo`, `openssl`.

> **Using XAMPP?** Current XAMPP ships PHP 8.2, which is **too old** for this project. Install a newer PHP instead, for example with [Laravel Herd](https://herd.laravel.com) (Windows/macOS) or from [php.net](https://www.php.net/downloads). You can still use XAMPP's **MySQL** as the database (see option B below).

---

## 2. Install

### Step 1: Get the code

```bash
git clone https://github.com/jdp0504/CLS-MariaArts.git
cd CLS-MariaArts
```

### Step 2: Install dependencies

```bash
composer install
npm install
```

### Step 3: Create your settings file

macOS / Linux:
```bash
cp .env.example .env
php artisan key:generate
```

Windows (Command Prompt):
```bat
copy .env.example .env
php artisan key:generate
```

### Step 4: Set up the database

Choose **one** option.

**Option A: SQLite (easiest, no database server needed)**

`.env` already uses SQLite. Just create the empty database file:

- macOS / Linux: `touch database/database.sqlite`
- Windows: `type nul > database\database.sqlite`

**Option B: MySQL (XAMPP, MySQL Workbench, etc.)**

1. Start MySQL and create an empty database called `cls_mariaarts` (for example in phpMyAdmin).
2. In `.env`, replace the `DB_` lines with:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cls_mariaarts
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Create the tables and demo data

```bash
php artisan migrate --seed
php artisan storage:link
```

### Step 6: Build the front end and start the server

```bash
npm run build
php artisan serve
```

Open **http://127.0.0.1:8000** in your browser.

---

## 3. Demo accounts

| Role | Login tab | Username | Password |
|---|---|---|---|
| Admin | Staff | `admin` | `admin123` |
| Cashier | Staff | `cashier1` / `cashier2` | `cashier123` |
| Customer | Customer | `customer1` (Alice, 150 pts, referral code `ALICE01`) | `pass123` |
| Customer | Customer | `customer2` | `pass123` |
| Customer (archived – cannot log in) | Customer | `customer3` | `pass123` |

To reset everything back to the demo data:

```bash
php artisan migrate:fresh --seed
```

---

## 4. Email notifications (optional)

By default, `.env` has `MAIL_MAILER=log`, so emails are **not sent**. They are written to `storage/logs/laravel.log` instead. Notifications are still saved in the database.

To send real emails from your own computer using Gmail:

1. Turn on 2-Step Verification for your Google account, then create an **App Password** (Google Account → Security → App passwords).
2. In `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yourname@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_FROM_ADDRESS=yourname@gmail.com
MAIL_FROM_NAME="Maria Arts"
```

3. Run `php artisan config:clear` and restart `php artisan serve`.

Make sure the customer you send to has a real email address (edit it under **My Profile**).

---

## 5. Run the automated tests

```bash
php artisan test
```

The tests use a temporary in-memory database, so they never change your data. They cover login and lockout, registration and referrals, cashier points and redemption, admin membership, rewards, notifications and reports, and customer profile.

---

## 6. Deploying online (optional)

The live version runs on:

- **[Render](https://render.com)** (free web service): runs the app from `Dockerfile` using `render.yaml` and `start.sh`
- **[TiDB Cloud Starter](https://tidbcloud.com)** (free MySQL-compatible database): keeps data between restarts
- **[Brevo](https://www.brevo.com)** (free SMTP relay, port 2525): sends emails, because Render's free plan blocks the usual email ports

To deploy your own copy:

1. Fork this repository on GitHub.
2. In TiDB Cloud, create a Starter instance in the region closest to your Render service, then run `CREATE DATABASE cls_mariaarts;` in its SQL Editor.
3. In Render, choose **New → Blueprint** and select your fork.
4. In the Render service's **Environment**, set:
   - `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` from TiDB's **Connect** screen
   - `MAIL_MAILER=smtp`, `MAIL_HOST=smtp-relay.brevo.com`, `MAIL_PORT=2525`
   - `MAIL_USERNAME` and `MAIL_PASSWORD` from Brevo's SMTP login and key
   - `MAIL_FROM_ADDRESS`: a sender email you verified in Brevo
5. Deploy. On the first start, `start.sh` creates the tables and the demo accounts. After that, it keeps existing data.

> Never commit passwords or keys to GitHub. Put them only in `.env` (local, already git-ignored) or in Render's Environment settings.

---

## 7. Troubleshooting

| Problem | Fix |
|---|---|
| `Your PHP version does not satisfy that requirement` | Install PHP 8.3 or newer (see Requirements). |
| `could not find driver` | Enable `pdo_sqlite` or `pdo_mysql` in your `php.ini`. |
| `Database file at path ... database.sqlite does not exist` | Do Step 4, option A. |
| `No application encryption key has been specified` | Run `php artisan key:generate`. |
| `Vite manifest not found` | Run `npm run build`. |
| Page looks unstyled | Run `npm run build` again, then refresh. |
| Changed `.env` but nothing happened | Run `php artisan config:clear` and restart `php artisan serve`. |
| Login says "Invalid username or password" for staff | Choose the **Staff** tab for admin and cashier accounts. |
