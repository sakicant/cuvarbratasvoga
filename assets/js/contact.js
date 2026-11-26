/* Kontakt obrazac — šalje poruku na api/contact.php; poslužitelj je prosljeđuje koordinatoru za odabranu državu. */
(function () {
  "use strict";
  var form = document.getElementById("contact-form");
  if (!form) return;
  var done = document.getElementById("contact-done");
  var errBox = form.querySelector(".form-error");
  var btn = form.querySelector(".btn-submit");
  var TR = window.CBS_TR || { T: function (k) { return k; }, lang: "hr" };
  var T = TR.T;

  function box(name) {
    var el = form.querySelector('[name="' + name + '"]');
    return el ? el.closest(".field") : null;
  }
  function clear() {
    errBox.hidden = true;
    form.querySelectorAll(".field.invalid").forEach(function (f) { f.classList.remove("invalid"); var m = f.querySelector(".msg"); if (m) m.remove(); });
  }
  function fieldErr(name, msg) {
    var b = box(name); if (!b) return;
    b.classList.add("invalid");
    var m = document.createElement("span"); m.className = "msg"; m.textContent = msg; b.appendChild(m);
  }
  function err(msg) { errBox.textContent = msg; errBox.hidden = false; }

  form.addEventListener("submit", function (ev) {
    ev.preventDefault();
    clear();
    var d = {
      name: form.elements["name"].value.trim(),
      email: form.elements["email"].value.trim(),
      country: form.elements["country"].value,
      message: form.elements["message"].value.trim(),
      website: form.elements["website"].value,
      consent: form.elements["consent"].checked,
      lang: TR.lang
    };
    var e = {};
    if (d.name.length < 2) e.name = T("js.err.name");
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(d.email)) e.email = T("js.err.email");
    if (!d.country) e.country = T("js.err.country");
    if (d.message.length < 10) e.message = T("js.err.message");
    if (!d.consent) e.consent = T("js.err.consent");
    var keys = Object.keys(e);
    if (keys.length) {
      keys.forEach(function (k) { fieldErr(k, e[k]); });
      err(T("js.err.check"));
      var first = box(keys[0]); if (first) first.scrollIntoView({ behavior: "smooth", block: "center" });
      return;
    }
    btn.disabled = true;
    var label = btn.textContent;
    btn.textContent = T("js.sending");
    fetch("api/contact.php", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(d) })
      .then(function (r) { return r.json().catch(function () { return { ok: false, error: T("js.err.unavailable") }; }); })
      .then(function (res) {
        if (res.ok) {
          form.hidden = true; done.hidden = false; done.focus();
          done.scrollIntoView({ behavior: "smooth", block: "center" });
          return;
        }
        if (res.fields) Object.keys(res.fields).forEach(function (k) { fieldErr(k, res.fields[k]); });
        err(res.error || T("js.err.generic"));
      })
      .catch(function () { err(T("js.err.network")); })
      .then(function () { btn.disabled = false; btn.textContent = label; });
  });

  form.addEventListener("input", function (e) {
    var b = e.target.closest(".field.invalid");
    if (b) { b.classList.remove("invalid"); var m = b.querySelector(".msg"); if (m) m.remove(); }
  });

  document.getElementById("contact-again").addEventListener("click", function () {
    form.elements["message"].value = "";
    done.hidden = true; form.hidden = false;
    form.elements["message"].focus();
  });
})();
