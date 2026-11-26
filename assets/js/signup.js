/* Obrazac za prijavu — šalje podatke na api/register.php */
(function () {
  "use strict";
  var form = document.getElementById("signup-form");
  if (!form) return;
  var done = document.getElementById("signup-done");
  var errBox = form.querySelector(".form-error");
  var btn = form.querySelector(".btn-submit");
  var API = "api/register.php";
  var TR = window.CBS_TR || { T: function (k) { return k; }, lang: "hr" };
  var T = TR.T;

  function fieldBox(name) {
    var el = form.querySelector('[name="' + name + '"]');
    return el ? (el.closest("fieldset") || el.closest(".field")) : null;
  }
  function clearErrors() {
    errBox.hidden = true;
    form.querySelectorAll(".field.invalid").forEach(function (f) { f.classList.remove("invalid"); var m = f.querySelector(".msg"); if (m) m.remove(); });
  }
  function showFieldError(name, msg) {
    var box = fieldBox(name);
    if (!box) return;
    box.classList.add("invalid");
    var m = document.createElement("span");
    m.className = "msg"; m.textContent = msg;
    box.appendChild(m);
  }
  function showError(msg) { errBox.textContent = msg; errBox.hidden = false; }

  function localCheck(data) {
    var e = {}, bad = function (k) { e[k] = T("js.err." + k); };
    if ((data.first_name || "").trim().length < 2) bad("first_name");
    if ((data.last_name || "").trim().length < 2) bad("last_name");
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email || "")) bad("email");
    if ((data.phone || "").replace(/\D/g, "").length < 6) bad("phone");
    if (!data.birth_date) bad("birth_date");
    if (!data.country) bad("country");
    if ((data.city || "").trim().length < 2) bad("city");
    if ((data.address || "").trim().length < 3) bad("address");
    if (!data.accommodation) bad("accommodation");
    if (!data.tshirt) bad("tshirt");
    if (!data.consent) bad("consent");
    return e;
  }

  function showDone(res, data) {
    form.hidden = true;
    done.hidden = false;
    done.querySelector(".done-ref").textContent = res.ref;
    done.querySelector(".done-mail").hidden = !res.mailed;
    var pay = (window.CBS_PAYMENTS || []).filter(function (p) { return p.code === data.country; })[0];
    var dl = done.querySelector(".done-pay-rows");
    dl.innerHTML = "";
    if (pay) {
      pay.rows.forEach(function (r) {
        if (r[0] === "multi" || r[0] === "purpose") return;
        var dt = document.createElement("dt"); dt.textContent = T("js.pay." + r[0]);
        var dd = document.createElement("dd"); dd.innerHTML = r[1];
        dl.appendChild(dt); dl.appendChild(dd);
      });
    } else {
      dl.innerHTML = "<dt>" + T("js.pay.contact") + "</dt><dd>" + T("js.pay.fallback") + "</dd>";
    }
    var dt2 = document.createElement("dt"); dt2.textContent = T("js.pay.purpose");
    // svrha uplate uvijek na jeziku banke te države (ne prevodi se)
    var pr = pay && pay.rows.filter(function (r) { return r[0] === "purpose"; })[0];
    var base = pr ? pr[1].replace(/\s*\(.*\)\s*$/, "").replace(/\.$/, "") : "ČBS " + ((window.CBS_EVENT || {}).year || "");
    var dd2 = document.createElement("dd"); dd2.textContent = base + " – " + res.ref + " – " + data.first_name + " " + data.last_name;
    dl.appendChild(dt2); dl.appendChild(dd2);
    done.focus();
    done.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  form.addEventListener("submit", function (ev) {
    ev.preventDefault();
    clearErrors();
    var fd = new FormData(form), data = {};
    fd.forEach(function (v, k) { data[k] = typeof v === "string" ? v.trim() : v; });
    data.consent = !!form.querySelector('[name="consent"]').checked;
    data.lang = TR.lang;

    var errs = localCheck(data), keys = Object.keys(errs);
    if (keys.length) {
      keys.forEach(function (k) { showFieldError(k, errs[k]); });
      showError(T("js.err.check"));
      var first = fieldBox(keys[0]);
      if (first) { first.scrollIntoView({ behavior: "smooth", block: "center" }); var inp = first.querySelector("input,select,textarea"); if (inp) inp.focus({ preventScroll: true }); }
      return;
    }

    btn.disabled = true;
    btn.textContent = T("js.sending");
    fetch(API, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(data) })
      .then(function (r) { return r.json().catch(function () { return { ok: false, error: T("js.err.unavailable") }; }); })
      .then(function (res) {
        if (res.ok) { showDone(res, data); return; }
        if (res.fields) Object.keys(res.fields).forEach(function (k) { showFieldError(k, res.fields[k]); });
        showError(res.error || T("js.err.generic"));
      })
      .catch(function () {
        showError(T("js.err.network"));
      })
      .then(function () { btn.disabled = false; btn.textContent = T("js.submit"); });
  });

  form.addEventListener("input", function (e) {
    var box = e.target.closest(".field.invalid");
    if (box) { box.classList.remove("invalid"); var m = box.querySelector(".msg"); if (m) m.remove(); }
  });

  document.getElementById("signup-again").addEventListener("click", function () {
    ["first_name", "birth_date", "tshirt", "notes"].forEach(function (n) {
      form.querySelectorAll('[name="' + n + '"]').forEach(function (el) { if (el.type === "radio") el.checked = false; else el.value = ""; });
    });
    done.hidden = true;
    form.hidden = false;
    form.querySelector('[name="first_name"]').focus();
  });
})();
