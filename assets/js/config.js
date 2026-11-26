/*
 * GODIŠNJE POSTAVKE — svake godine promijeni samo ovo.
 * Kad prođe datum "end", stranica sama prebacuje u način "okupljanje je završilo"
 * (gumbi za prijavu se sakriju). Za pregled stranice kao da su prijave otvorene,
 * dodaj ?prijave=1 na kraj adrese (npr. index.html?prijave=1).
 */
window.CBS_EVENT = {
  number: 17,
  year: 2026,
  theme: "Sidro duše",
  start: "2026-06-11T14:00:00+02:00",   // početak registracije
  end:   "2026-06-14T14:00:00+02:00",   // kraj okupljanja
  dateLabel: "11. – 14. 6. 2026.",
  place: "Etno selo Škopljanci",
  town: "Radošić, Dalmacija",
  mapUrl: "https://www.google.com/maps/search/?api=1&query=Etno+selo+%C5%A0kopljanci+Rado%C5%A1i%C4%87",
  formUrl: "https://forms.gle/bobnBrW4tnH2HR3aA",
  email: "cuvar.brata.svoga@gmail.com",
  facebook: "https://www.facebook.com/CuvarBrataSvoga/",
  youtube: "https://www.youtube.com/@CuvarBrataSvoga"
};

/* Uplata akontacije po državama */
window.CBS_PAYMENTS = [
  { country: "Hrvatska", rows: [
      ["Primatelj", "EPC Milost i Istina, Strossmayerov trg 1, Karlovac"],
      ["Banka", "Raiffeisen Banka"],
      ["IBAN", "HR33 2484 0081 5020 0770 1", "copy"],
      ["Svrha uplate", "Donacija za ČBS 2026."],
      ["Za više osoba", "Pošaljite imena na <a href=\"mailto:epcmilostiistina@gmail.com\">epcmilostiistina@gmail.com</a>"],
      ["Kontakt", "Oliver Buljat · <a href=\"tel:+385921662858\">+385 92 166 2858</a>"] ] },
  { country: "Bosna i Hercegovina", rows: [
      ["Primatelj", "Udruženje „Dobra Vijest“"],
      ["Račun", "1610000271100073", "copy"],
      ["Svrha uplate", "Donacija za ČBS 2026."],
      ["Za više osoba", "Pošaljite imena na <a href=\"mailto:slavko.hadzic@gmail.com\">slavko.hadzic@gmail.com</a>"],
      ["Kontakt", "Slavko Hadžić · <a href=\"tel:+38761255309\">+387 61 255 309</a>"] ] },
  { country: "Srbija", rows: [
      ["Primatelj", "Miroslav Radovanović"],
      ["Račun", "205-9011006981377-53", "copy"],
      ["Svrha uplate", "Donacija za ČBS 2026."],
      ["Za više osoba", "Pošaljite imena na <a href=\"mailto:radovanovic7@gmail.com\">radovanovic7@gmail.com</a>"],
      ["Kontakt", "Miroslav Radovanović · <a href=\"tel:+381606312910\">+381 60 631 2910</a>"] ] },
  { country: "Crna Gora", rows: [
      ["Primatelj", "Hrišćanski Centar „Svetionik“"],
      ["Račun", "510-95067-19", "copy"],
      ["Za više osoba", "Pošaljite imena na <a href=\"mailto:dragan.novakovic7@gmail.com\">dragan.novakovic7@gmail.com</a>"],
      ["Kontakt", "Dragan Novaković · <a href=\"tel:+38267270933\">+382 67 270 933</a>"] ] },
  { country: "Sjeverna Makedonija", rows: [
      ["Primatelj", "Evangelska crkva vo RM – Gevgelija"],
      ["Banka", "Komercijalna banka AD Skopje"],
      ["Račun", "300-0000035620-70", "copy"],
      ["Opis", "ČBS 2026. (ime i prezime)"],
      ["Za više osoba", "Pošaljite imena na <a href=\"mailto:halev.gevgelija@gmail.com\">halev.gevgelija@gmail.com</a>"],
      ["Kontakt", "Zoran Kolev · <a href=\"tel:+38970342705\">070 342 705</a>"] ] },
  { country: "Slovenija", rows: [
      ["Prejemnik", "BC Emanuel"],
      ["Banka", "Delavska hranilnica d.d."],
      ["IBAN", "SI56 6100 0001 7056 245", "copy"],
      ["Namen", "ČBS 2026."],
      ["Za več oseb", "Imena pošljite na <a href=\"mailto:saso.krope@gmail.com\">saso.krope@gmail.com</a>"],
      ["Kontakt", "Srečko Kranjc · <a href=\"tel:+38641586511\">+386 41 586 511</a>"] ] }
];
