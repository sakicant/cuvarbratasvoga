(function () {
  "use strict";
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var EV = window.CBS_EVENT || {};
  /* prijevodi (iz lang/*.php, ubacuje ih PHP u window.CBS_T) */
  var DICT = window.CBS_T || {}, LANG = window.CBS_LANG || "hr";
  function T(k, vars) {
    var s = DICT[k];
    if (s == null) return k;
    if (typeof s === "object") s = s.other || "";
    if (vars) Object.keys(vars).forEach(function (v) { s = s.split("{" + v + "}").join(vars[v]); });
    return s;
  }
  function TP(n, k) {
    var f = DICT[k] || {}, cat = "other";
    try { cat = new Intl.PluralRules(LANG === "cnr" ? "sr" : LANG).select(n); } catch (e) {}
    return n + " " + (f[cat] || f.other || "");
  }
  window.CBS_TR = { T: T, TP: TP, lang: LANG };
  /* naziv teme godine na jeziku stranice (iz povijesti), inače izvorni */
  function themeOf(y) { return DICT["hist.y" + y + "_t"] || ((window.CBS_YEARS || {})[y] || {}).theme || ""; }
  var PLAY = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>';

  /* ---------- mobilni izbornik ---------- */
  var menuBtn = $(".menu-btn"), nav = $(".nav"), header = $(".site-header");
  if (menuBtn && nav) {
    menuBtn.addEventListener("click", function () {
      var open = nav.classList.toggle("open");
      menuBtn.setAttribute("aria-expanded", open);
      if (header) header.classList.toggle("menu-open", open);
    });
    $$("a", nav).forEach(function (a) { a.addEventListener("click", function () {
      nav.classList.remove("open"); menuBtn.setAttribute("aria-expanded", "false");
      if (header) header.classList.remove("menu-open");
    }); });
  }

  /* ---------- prozirno zaglavlje: dobiva pozadinu kad se skrola ---------- */
  if (header) {
    var onScroll = function () { header.classList.toggle("scrolled", window.scrollY > 10); };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* ---------- stanje okupljanja: otvorene prijave / završeno ---------- */
  var now = new Date();
  var forceLive = /[?&]prijave=1/.test(location.search);
  var ended = EV.end && now > new Date(EV.end) && !forceLive;
  document.documentElement.setAttribute("data-event", ended ? "past" : "live");
  /* data-reg (prijave otvorene/zatvorene) postavlja PHP iz api/config.php; ?prijave=1 = pregled otvorenih prijava */
  if (forceLive) document.documentElement.setAttribute("data-reg", "open");

  $$("[data-ev]").forEach(function (el) {
    var v = EV[el.getAttribute("data-ev")];
    if (v != null) el.textContent = v;
  });
  $$("[data-ev-href]").forEach(function (el) {
    var k = el.getAttribute("data-ev-href"), v = EV[k];
    if (!v) return;
    el.href = k === "email" ? "mailto:" + v : v;
  });

  /* odbrojavanje */
  var cd = $(".countdown");
  if (cd && !ended && EV.start) {
    var target = new Date(EV.start);
    cd.setAttribute("role", "timer");
    cd.innerHTML = [T("js.days"), T("js.hours"), T("js.minutes"), T("js.seconds")].map(function (l) { return "<div><b>0</b><span>" + l + "</span></div>"; }).join("");
    var nums = $$("b", cd);
    var pad = function (n) { return (n < 10 ? "0" : "") + n; };
    var timer;
    var tick = function () {
      var ms = target - new Date();
      if (ms <= 0) { cd.hidden = true; clearInterval(timer); return; }
      var s = Math.floor(ms / 1000);
      nums[0].textContent = Math.floor(s / 86400);
      nums[1].textContent = pad(Math.floor(s / 3600) % 24);
      nums[2].textContent = pad(Math.floor(s / 60) % 60);
      nums[3].textContent = pad(s % 60);
    };
    tick(); timer = setInterval(tick, 1000);
  } else if (cd) { cd.hidden = true; }

  /* ---------- uplate po državama ---------- */
  var payRoot = $("#payments");
  if (payRoot && window.CBS_PAYMENTS) {
    var tabs = document.createElement("div");
    tabs.className = "tabs"; tabs.setAttribute("role", "tablist"); tabs.setAttribute("aria-label", T("js.country"));
    var panel = document.createElement("div");
    panel.className = "tab-panel"; panel.setAttribute("role", "tabpanel"); panel.id = "pay-panel";
    var show = function (i) {
      $$(".tab", tabs).forEach(function (t, j) { t.setAttribute("aria-selected", i === j); t.tabIndex = i === j ? 0 : -1; });
      var p = window.CBS_PAYMENTS[i];
      panel.setAttribute("aria-label", T("country." + p.code));
      panel.innerHTML = "<dl>" + p.rows.map(function (r) {
        var val = r[1];
        if (r[0] === "multi") val = T("js.pay.multi_val", { email: '<a href="mailto:' + r[1] + '">' + r[1] + "</a>" });
        if (r[2] === "copy") val = '<span class="iban">' + r[1] + '</span><button class="copy" type="button" data-copy="' + r[1] + '">' + T("js.copy") + "</button>";
        return "<dt>" + T("js.pay." + r[0]) + "</dt><dd>" + val + "</dd>";
      }).join("") + "</dl>";
    };
    window.CBS_PAYMENTS.forEach(function (p, i) {
      var b = document.createElement("button");
      b.type = "button"; b.className = "tab"; b.setAttribute("role", "tab"); b.setAttribute("aria-controls", "pay-panel");
      b.textContent = T("country." + p.code);
      b.addEventListener("click", function () { show(i); });
      b.addEventListener("keydown", function (e) {
        var n = window.CBS_PAYMENTS.length;
        if (e.key === "ArrowRight" || e.key === "ArrowLeft") {
          var k = (i + (e.key === "ArrowRight" ? 1 : n - 1)) % n; show(k); tabs.children[k].focus();
        }
      });
      tabs.appendChild(b);
    });
    payRoot.appendChild(tabs); payRoot.appendChild(panel); show(0);
    panel.addEventListener("click", function (e) {
      var b = e.target.closest(".copy"); if (!b) return;
      var txt = b.getAttribute("data-copy");
      var done = function () { b.textContent = T("js.copied"); setTimeout(function () { b.textContent = T("js.copy"); }, 1800); };
      if (navigator.clipboard) navigator.clipboard.writeText(txt).then(done, function () {});
    });
  }

  /* ---------- video modal ---------- */
  var modal = null;
  function openVideo(id, title, sub) {
    if (!modal) {
      modal = document.createElement("div");
      modal.className = "modal"; modal.setAttribute("role", "dialog"); modal.setAttribute("aria-modal", "true");
      modal.innerHTML = '<div class="modal-inner"><div class="modal-frame"></div><div class="modal-cap"><div><h3></h3><p></p></div><button class="modal-close" type="button">' + T("js.close") + '</button></div></div>';
      document.body.appendChild(modal);
      modal.addEventListener("click", function (e) { if (e.target === modal || e.target.closest(".modal-close")) closeVideo(); });
      document.addEventListener("keydown", function (e) { if (e.key === "Escape" && modal.classList.contains("open")) closeVideo(); });
    }
    modal._return = document.activeElement;
    $(".modal-frame", modal).innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0" title="' + title.replace(/"/g, "&quot;") + '" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>';
    $("h3", modal).textContent = title;
    $("p", modal).innerHTML = sub + ' · <a href="https://www.youtube.com/watch?v=' + id + '" target="_blank" rel="noopener">' + T("js.open_yt") + "</a>";
    modal.classList.add("open");
    document.body.style.overflow = "hidden";
    $(".modal-close", modal).focus();
  }
  function closeVideo() {
    modal.classList.remove("open");
    $(".modal-frame", modal).innerHTML = "";
    document.body.style.overflow = "";
    if (modal._return) modal._return.focus();
  }

  function card(t) {
    var th = themeOf(t.year);
    var meta = "ČBS " + t.year + (th && t.type !== "promo" ? " · " + th : "");
    var el = document.createElement("article");
    el.className = "vcard";
    el.innerHTML =
      '<button type="button" aria-label="' + T("js.watch") + " " + t.title.replace(/"/g, "&quot;") + '">' +
      '<div class="thumb"><img loading="lazy" src="https://i.ytimg.com/vi/' + t.id + '/hqdefault.jpg" alt="">' +
      '<span class="dur">' + (t.duration || "") + '</span><span class="play">' + PLAY + "</span></div></button>" +
      '<div class="vbody"><span class="vmeta"></span><h3></h3><span class="vspeaker"></span></div>';
    $(".vmeta", el).textContent = meta;
    $("h3", el).textContent = t.title;
    $(".vspeaker", el).textContent = t.speaker || "";
    $("button", el).addEventListener("click", function () { openVideo(t.id, t.title, [t.speaker, "ČBS " + t.year].filter(Boolean).join(" · ")); });
    return el;
  }

  /* ---------- stranica Mediji ---------- */
  var media = $("#media");
  if (media && window.CBS_TALKS) {
    var talks = window.CBS_TALKS;
    var yearsOf = function (list) { return Object.keys(list.reduce(function (a, t) { a[t.year] = 1; return a; }, {})).map(Number).sort(function (a, b) { return b - a; }); };
    var years = yearsOf(talks);
    var chipYears = yearsOf(talks.filter(function (t) { return t.type !== "promo"; }));
    var chips = $("#year-chips"), q = $("#q"), promo = $("#show-promo"), count = $("#result-count");
    var state = { year: "all", q: "", promo: false };

    var hashYear = parseInt((location.hash || "").replace("#", ""), 10);
    if (years.indexOf(hashYear) > -1) state.year = hashYear;

    function chip(label, val) {
      var b = document.createElement("button");
      b.type = "button"; b.className = "ychip"; b.textContent = label;
      b.addEventListener("click", function () {
        state.year = val; render();
        history.replaceState(null, "", location.pathname + location.search + (val === "all" ? "" : "#" + val));
        window.scrollTo({ top: media.offsetTop - 150, behavior: "smooth" });
      });
      b._val = val; chips.appendChild(b);
    }
    chip(T("js.all_years"), "all");
    chipYears.forEach(function (y) { chip(String(y), y); });

    var norm = function (s) { return (s || "").toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "").replace(/đ/g, "d"); };

    function render() {
      $$(".ychip", chips).forEach(function (b) { b.setAttribute("aria-pressed", b._val === state.year); });
      var nq = norm(state.q);
      var list = talks.filter(function (t) {
        if (!state.promo && t.type === "promo") return false;
        if (state.year !== "all" && t.year !== state.year) return false;
        if (nq && norm(t.title + " " + t.speaker + " " + t.year + " " + themeOf(t.year) + " " + ((window.CBS_YEARS[t.year] || {}).theme || "")).indexOf(nq) === -1) return false;
        return true;
      });
      media.innerHTML = "";
      count.textContent = TP(list.length, "js.videos");
      if (!list.length) { media.innerHTML = '<p class="empty">' + T("js.no_results") + "</p>"; return; }
      years.forEach(function (y) {
        var items = list.filter(function (t) { return t.year === y; });
        if (!items.length) return;
        var info = window.CBS_YEARS[y] || {};
        var sec = document.createElement("section");
        sec.className = "year-block"; sec.id = "g" + y;
        sec.innerHTML = '<div class="year-head"><h2>' + y + "</h2>" +
          (themeOf(y) ? '<span class="theme">' + themeOf(y) + "</span>" : "") +
          (info.place ? '<span class="place">' + info.place + "</span>" : "") +
          '<span class="count">' + TP(items.length, "js.videos") + "</span></div>";
        var grid = document.createElement("div"); grid.className = "video-grid";
        items.forEach(function (t) { grid.appendChild(card(t)); });
        sec.appendChild(grid); media.appendChild(sec);
      });
    }
    q.addEventListener("input", function () { state.q = q.value; render(); });
    if (promo) promo.addEventListener("change", function () { state.promo = promo.checked; render(); });
    render();
  }

  /* izdvojeni videi (početna) */
  $$("[data-latest]").forEach(function (box) {
    var n = parseInt(box.getAttribute("data-latest"), 10) || 3;
    (window.CBS_TALKS || []).filter(function (t) { return t.type !== "promo"; }).slice(0, n).forEach(function (t) { box.appendChild(card(t)); });
  });

  $$("[data-ids]").forEach(function (box) {
    box.getAttribute("data-ids").split(",").forEach(function (id) {
      var t = (window.CBS_TALKS || []).filter(function (x) { return x.id === id.trim(); })[0];
      if (t) box.appendChild(card(t));
    });
  });

  /* lite YouTube (povijest) */
  $$(".lite[data-id]").forEach(function (box) {
    var id = box.getAttribute("data-id");
    $("button", box).addEventListener("click", function () {
      box.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0" title="' + (box.getAttribute("data-title") || "Video") + '" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>';
    });
  });
  /* gumbi koji otvaraju video u modalu */
  $$("[data-video]").forEach(function (b) {
    b.addEventListener("click", function (e) { e.preventDefault(); openVideo(b.getAttribute("data-video"), b.getAttribute("data-title") || "", b.getAttribute("data-sub") || ""); });
  });

  /* lagano pojavljivanje */
  if ("IntersectionObserver" in window) {
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add("in"); io.unobserve(e.target); } }); }, { rootMargin: "0px 0px -8% 0px" });
    $$(".reveal").forEach(function (el) { io.observe(el); });
  } else { $$(".reveal").forEach(function (el) { el.classList.add("in"); }); }

  var yr = $("#year-now"); if (yr) yr.textContent = new Date().getFullYear();
})();
