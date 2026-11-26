/*
 * GODIŠNJE POSTAVKE (datumi i brojevi). Tekstovi za svaki jezik su u mapi /lang.
 * - Prijave se otvaraju/zatvaraju u api/config.php ('registration_open').
 * - Kad prođe datum "end", stranica sama prebacuje u način "okupljanje je završilo".
 * - Za pregled stranice kao da su prijave otvorene dodaj ?prijave=1 na kraj adrese.
 */
window.CBS_EVENT = {
  year: 2027,
  start: "2027-06-24T12:00:00+02:00",   // dolazak / registracija (za odbrojavanje)
  end:   "2027-06-27T14:00:00+02:00",   // kraj okupljanja
  dateLabel: "24. – 27. 6. 2027.",
  mapUrl: "https://www.google.com/maps/search/?api=1&query=Bora%C4%8Dko+jezero",
  email: "cuvar.brata.svoga@gmail.com",
  facebook: "https://www.facebook.com/CuvarBrataSvoga/",
  youtube: "https://www.youtube.com/@CuvarBrataSvoga"
};

/*
 * Uplata akontacije po državama.
 * Prvi stupac je vrsta retka (naziv se prevodi): recipient, bank, iban, account, purpose, multi, contact.
 * "multi" = e-mail kamo se šalju imena kad netko uplaćuje za više osoba. "copy" = gumb za kopiranje.
 * Svrha uplate ostaje na jeziku banke te države.
 */
window.CBS_PAYMENTS = [
  { code: "HR", rows: [
      ["recipient", "EPC Milost i Istina, Strossmayerov trg 1, Karlovac"],
      ["bank", "Raiffeisen Banka"],
      ["iban", "HR33 2484 0081 5020 0770 1", "copy"],
      ["purpose", "Donacija za ČBS 2027."],
      ["multi", "epcmilostiistina@gmail.com"],
      ["contact", "Oliver Buljat · <a href=\"tel:+385921662858\">+385 92 166 2858</a>"] ] },
  { code: "BA", rows: [
      ["recipient", "Udruženje „Dobra Vijest“"],
      ["account", "1610000271100073", "copy"],
      ["purpose", "Donacija za ČBS 2027."],
      ["multi", "slavko.hadzic@gmail.com"],
      ["contact", "Slavko Hadžić · <a href=\"tel:+38761255309\">+387 61 255 309</a>"] ] },
  { code: "RS", rows: [
      ["recipient", "Miroslav Radovanović"],
      ["account", "205-9011006981377-53", "copy"],
      ["purpose", "Donacija za ČBS 2027."],
      ["multi", "radovanovic7@gmail.com"],
      ["contact", "Miroslav Radovanović · <a href=\"tel:+381606312910\">+381 60 631 2910</a>"] ] },
  { code: "ME", rows: [
      ["recipient", "Hrišćanski Centar „Svetionik“"],
      ["account", "510-95067-19", "copy"],
      ["multi", "dragan.novakovic7@gmail.com"],
      ["contact", "Dragan Novaković · <a href=\"tel:+38267270933\">+382 67 270 933</a>"] ] },
  { code: "MK", rows: [
      ["recipient", "Evangelska crkva vo RM – Gevgelija"],
      ["bank", "Komercijalna banka AD Skopje"],
      ["account", "300-0000035620-70", "copy"],
      ["purpose", "ČBS 2027. (ime i prezime)"],
      ["multi", "halev.gevgelija@gmail.com"],
      ["contact", "Zoran Kolev · <a href=\"tel:+38970342705\">070 342 705</a>"] ] },
  { code: "SI", rows: [
      ["recipient", "BC Emanuel"],
      ["bank", "Delavska hranilnica d.d."],
      ["iban", "SI56 6100 0001 7056 245", "copy"],
      ["purpose", "ČBS 2027."],
      ["multi", "saso.krope@gmail.com"],
      ["contact", "Srečko Kranjc · <a href=\"tel:+38641586511\">+386 41 586 511</a>"] ] }
];
