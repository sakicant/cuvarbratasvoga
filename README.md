# cuvarbratasvoga.com

Website for **Čuvar brata svoga (ČBS)** with its own sign-up system (no Google Forms).
Plain HTML/CSS/JS + a small PHP backend. Needs a normal PHP host (Apache, PHP 8.0+). GitHub Pages can't run it.

## What's in it

| Path | What it is |
|---|---|
| `index.html` | Home: current gathering, invitation, schedule, prices, **sign-up form**, payments by country |
| `povijest.html` | History: documentary, mission, timeline |
| `mediji.html` | All talks from YouTube, filter by year, search |
| `privatnost.html` | Privacy notice linked from the sign-up form |
| `api/register.php` | Receives sign-ups, saves them, sends confirmation e-mail |
| `admin/` | Admin panel: login, applicants, deposit status, notes, Excel export |
| `data/` | SQLite database lives here (blocked from the web by `.htaccess`) |

## Admin access

- **Main admin** (no country): sees every country, adds/removes admins, resets passwords.
- **Country admin** (HR, BA, RS, ME, MK, SI): sees and edits **only** applicants from their country.
  Gets an e-mail for each new sign-up from their country (if an e-mail is set).
- Sign-ups with country "Ostalo" are visible to the main admin only.

## Install on the server (one time)

1. Upload everything to `public_html` (except `.git`).
2. Copy `api/config.sample.php` to `api/config.php` and fill in:
   - `secret` and `setup_key`: long random strings
   - `mail.from`: an address on your domain, e.g. `prijave@cuvarbratasvoga.com`
   - optional: move the database outside `public_html` (see comment in the file), or use MySQL
3. Open `https://cuvarbratasvoga.com/admin/`, enter the `setup_key`, create the main admin.
4. In **Admini**, add one admin per country. Each gets a one-time password to pass on; they change it under **Lozinka**.
5. Submit one test sign-up and check that the confirmation e-mail arrives (check spam too).

## Each year

1. `assets/js/config.js`: number, dates, place, theme (what the website shows).
2. `api/config.php` → `event`: year, number, theme, start date (what e-mails and age calculation use). Set `registration_open`.
3. `index.html`: invitation text, schedule, prices.
4. Old years stay in the database; switch year in the admin panel's dropdown.

## Adding talks

In `assets/js/talks.js` add: `{ id: "YOUTUBE_ID", year: 2027, title: "Naslov", speaker: "Ime Prezime", duration: "35:10" },`

## Local preview

```
php -S localhost:8000
```

(Requires the `pdo_sqlite` and `mbstring` PHP extensions.) Set `'enabled' => false` under `mail` in your local `api/config.php`.
