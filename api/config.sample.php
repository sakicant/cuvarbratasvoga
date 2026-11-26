<?php
/*
 * KOPIRAJ OVU DATOTEKU U  api/config.php  I UPIŠI SVOJE VRIJEDNOSTI.
 * config.php se NE stavlja na GitHub (u .gitignore je).
 */
return [

    // Trenutno okupljanje (koristi se u e-mailovima i za izračun dobi)
    'event' => [
        'year'       => 2027,
        'theme'      => '',                       // upiši kad bude poznata
        'start_date' => '2027-06-24',
        'place'      => 'Boračko jezero, Bosna i Hercegovina',
    ],

    // Jesu li prijave otvorene? (false = API odbija nove prijave)
    'registration_open' => true,

    // Baza podataka.
    // SQLite (najjednostavnije): datoteka. Preporuka: stavi je IZVAN public_html mape,
    //   npr. 'sqlite:/home/KORISNIK/cbs-data/cbs.sqlite'
    // MySQL (s hostinga):  'dsn' => 'mysql:host=localhost;dbname=IME_BAZE;charset=utf8mb4', 'user' => '...', 'pass' => '...'
    'db' => [
        'dsn'  => 'sqlite:' . __DIR__ . '/../data/cbs.sqlite',
        'user' => null,
        'pass' => null,
    ],

    // Dugi nasumični niz (min. 32 znaka). Koristi se za zaštitu IP adresa u bazi.
    'secret' => 'PROMIJENI-OVO-u-dugi-nasumicni-niz-znakova-1234567890',

    // Ključ za prvo postavljanje glavnog admina na /admin/ (nakon toga se više ne koristi).
    'setup_key' => 'PROMIJENI-OVO-setup-kljuc',

    // E-mail
    'mail' => [
        'enabled'       => true,                                // false = ne šalji e-mailove (npr. lokalno testiranje)
        'from'          => 'prijave@cuvarbratasvoga.com',       // adresa s tvoje domene
        'from_name'     => 'Čuvar brata svoga',
        'reply_to'      => 'cuvar.brata.svoga@gmail.com',
        'notify_admins' => true,                                // javi adminu države za svaku novu prijavu
    ],

    'site_url' => 'https://cuvarbratasvoga.com',
    'timezone' => 'Europe/Zagreb',

    // Upute za uplatu akontacije po državi (šalju se u potvrdnom e-mailu).
    // Šifre država: HR, BA, RS, ME, MK, SI, XX (ostalo)
    'payments' => [
        'HR' => "Primatelj: EPC Milost i Istina, Strossmayerov trg 1, Karlovac\nBanka: Raiffeisen Banka\nIBAN: HR33 2484 0081 5020 0770 1\nKontakt: Oliver Buljat, +385 92 166 2858",
        'BA' => "Primatelj: Udruženje „Dobra Vijest“\nRačun: 1610000271100073\nKontakt: Slavko Hadžić, +387 61 255 309",
        'RS' => "Primatelj: Miroslav Radovanović\nRačun: 205-9011006981377-53\nKontakt: Miroslav Radovanović, +381 60 631 2910",
        'ME' => "Primatelj: Hrišćanski Centar „Svetionik“\nRačun: 510-95067-19\nKontakt: Dragan Novaković, +382 67 270 933",
        'MK' => "Primatelj: Evangelska crkva vo RM – Gevgelija\nBanka: Komercijalna banka AD Skopje\nRačun: 300-0000035620-70\nKontakt: Zoran Kolev, 070 342 705",
        'SI' => "Prejemnik: BC Emanuel\nBanka: Delavska hranilnica d.d.\nIBAN: SI56 6100 0001 7056 245\nKontakt: Srečko Kranjc, +386 41 586 511",
        'XX' => "Javi nam se na cuvar.brata.svoga@gmail.com za upute o uplati.",
    ],
];
