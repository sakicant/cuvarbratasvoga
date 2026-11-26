# cuvarbratasvoga.com

Website for **Čuvar brata svoga (ČBS)** with its own sign-up system (no Google Forms).
Plain HTML/CSS/JS + a small PHP backend. Needs a normal PHP host (Apache, PHP 8.0+). GitHub Pages can't run it.

## What's in it

| Path | What it is |
|---|---|
| `index.php` | Home: gathering, invitation, prices, **sign-up form**, payments by country |
| `povijest.php` | History: documentary, mission, timeline |
| `mediji.php` | All talks from YouTube, filter by year, search |
| `privatnost.php` | Privacy notice linked from the sign-up form |
| `lang/` | **All website text, one file per language** (see Languages below) |
| `inc/layout.php` | Shared header/footer, **header logo setting** at the top |
| `assets/brand/` | **Logo set** for all materials. Open `assets/brand/index.html` for a preview + downloads |
| `api/register.php` | Receives sign-ups, saves them, sends confirmation e-mail |
| `admin/` | Admin panel: login, applicants, deposit status, notes, Excel export |
| `data/` | SQLite database lives here (blocked from the web by `.htaccess`) |

## Languages

7 languages: Croatian (`hr`, source), Bosnian (`bs`), Serbian Latin (`sr`), Montenegrin (`cnr`), Macedonian (`mk`), Slovenian (`sl`), English (`en`).

- Visitors get their browser's language automatically (regional languages first, English only if none match); the switcher in the header changes it and remembers the choice.
- Links: `index.php?lang=mk`, `mediji.php?lang=sl`, … (Croatian has no `?lang`).
- To change a text: edit it in `lang/xx.php` (only the part right of `=>`). Croatian `lang/hr.php` is the source; a text missing in another language shows in Croatian.
- After editing, run `php lang/check.php` – it reports missing texts or broken placeholders/HTML.
- Confirmation e-mails go out in the language the applicant used; the admin panel shows it. The admin panel itself is Croatian.
- Talk titles stay in the language they were given in.

## Logo

- `assets/brand/png/` – logo in 7 colours (original brown, black, white, ochre `gold`, pine green `pine`, BiH blue, BiH yellow), 256–2048 px, transparent.
- Website palette "Pine and ochre": pine `#1b2a22`, ochre `#cc9a2e`, light `#efe9dc`, bark `#6b4a2f` (top of `assets/css/style.css`).
- `assets/brand/square/` – 1080×1080 profile/post images. `assets/brand/favicon/` – browser and phone icons.
- `assets/brand/cbs-logo-set.zip` – everything in one download.
- Footer always uses the white logo. Header logo: change `CBS_HEADER_LOGO` at the top of `inc/layout.php`.
- Made from the 900 px logo on the YouTube channel. For large print (banners, T-shirts) get a vector file (SVG/AI/PDF) from the logo's designer.

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

1. `assets/js/config.js`: dates for the countdown. Place/theme texts: `event.*` in each `lang/xx.php`.
2. `api/config.php` → `event`: year, theme, start date (what e-mails and age calculation use). Set `registration_open` to `true` to show prices, the sign-up form and payments on the site (and accept sign-ups); `false` hides them. Preview with `?prijave=1`.
3. `lang/hr.php` (then the other languages): invitation text, prices, schedule. The schedule section goes where the `RASPORED` comment is in `index.php`.
4. Old years stay in the database; switch year in the admin panel's dropdown.

## Adding talks

In `assets/js/talks.js` add: `{ id: "YOUTUBE_ID", year: 2027, title: "Naslov", speaker: "Ime Prezime", duration: "35:10" },`

## Vercel preview (static copy)

Vercel can't run PHP, so it shows a static copy from `preview/` (all pages × 7 languages; sign-up and admin hidden).
After changing texts or design, rebuild it before pushing:

```
php tools/build-preview.php
```

`vercel.json` sends the Vercel address to `/preview/`. The real site (with sign-up) runs on the PHP server.

## Local preview

```
php -S localhost:8000
```

(Requires the `pdo_sqlite` and `mbstring` PHP extensions.) Set `'enabled' => false` under `mail` in your local `api/config.php`.
