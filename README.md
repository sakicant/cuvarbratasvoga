# cuvarbratasvoga.com

New website for **Čuvar brata svoga (ČBS)**. Plain HTML/CSS/JS: no build step, works on any static host (GitHub Pages, Netlify, cPanel).

## Pages

| File | What it is |
|---|---|
| `index.html` | Home: current gathering, invitation, schedule, prices, sign-up, payments by country, contact |
| `povijest.html` | History: documentary film, mission, timeline of every gathering |
| `mediji.html` | All talks from YouTube, filter by year, search by title/speaker |

## Updating each year

1. **`assets/js/config.js`**: number, theme, dates, place, Google Form link, payment accounts.
   After the `end` date passes, the site switches to "gathering finished" mode by itself (sign-up buttons hide).
   To preview the "sign-up open" mode, add `?prijave=1` to the URL.
2. **`index.html`**: invitation text, schedule table, prices (text sections).
3. **`assets/img/plakat-2026.jpg`**: replace with the new poster (keep the name, or update the 3 references).
4. **`assets/docs/`**: new invitation letters and schedule PDF.

## Adding talks

Open `assets/js/talks.js` and add a line:

```js
{ id: "YOUTUBE_ID", year: 2027, title: "Naslov", speaker: "Ime Prezime", duration: "35:10" },
```

`YOUTUBE_ID` is the part after `watch?v=` in the YouTube link. Add `type: "promo"` for trailers/announcements.
Add the year's theme to `CBS_YEARS` at the top of the same file.

## Local preview

```
python -m http.server 8000
```

Then open http://localhost:8000
