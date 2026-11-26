/*
 * Popis videa s YouTube kanala Čuvar Brata Svoga.
 * Za novu propovijed dodaj red: { id: "YOUTUBE_ID", year: 2027, title: "Naslov", speaker: "Ime Prezime", duration: "35:10" }
 * YOUTUBE_ID je dio linka nakon "watch?v=" (npr. youtube.com/watch?v=18b8sSPA3bY -> "18b8sSPA3bY").
 * type: "talk" (propovijed, zadano) ili "promo" (o pokretu – prikazuje se samo na stranici Povijest).
 */
window.CBS_YEARS = {
  2010: { no: 1,  theme: "Prvo okupljanje", place: "Zagreb, Hrvatska" },
  2012: { no: 3,  theme: "Božji upravitelji – Muškarac i njegove financije" },
  2013: { no: 4,  theme: "Ratnici i ljubavnici" },
  2014: { no: 5,  theme: "Disciplinom do izvrsnosti" },
  2015: { no: 6,  theme: "Čast i poštovanje", place: "Boračko jezero, BiH" },
  2016: { no: 7,  theme: "Počinak", place: "Adlešiči, Slovenija" },
  2017: { no: 8,  theme: "Vjernost" },
  2019: { no: 10, theme: "Evangelizacija" },
  2020: { no: 11, theme: "Prijenos uživo" },
  2022: { no: 13, theme: "Junaštvo i prijateljstvo" },
  2023: { no: 14, theme: "Svetost", place: "Crna Gora" },
  2024: { no: 15, theme: "Godina jubileja", place: "Etno selo Škopljanci, Radošić" },
  2026: { no: 17, theme: "Sidro duše", place: "Etno selo Škopljanci, Radošić" }
};

window.CBS_TALKS = [
  // 2026 – Sidro duše
  { id: "18b8sSPA3bY", year: 2026, title: "Isus – sidro duše", speaker: "Filip Ivanov", duration: "32:16" },
  { id: "VFg4aKk8M5c", year: 2026, title: "Mir sa sobom vs. potreba za odobravanjem", speaker: "Tonček Sukič", duration: "30:11" },
  { id: "BcOnFAulrG4", year: 2026, title: "U sredini je bila zbunjenost", speaker: "Saša Nikolinović", duration: "43:13" },
  { id: "G0V__d-lyxg", year: 2026, title: "Biti pouzdan kad nitko ne gleda", speaker: "Miki Kamberović", duration: "28:58" },
  { id: "inWN_0d28bY", year: 2026, title: "Prepoznati kada treba dići sidro i otploviti", speaker: "Dušan Beređi Bera", duration: "37:21" },
  { id: "yf-JzZ5jiMc", year: 2026, title: "Rekapitulacija ČBS 2026.", speaker: "Petar Fabijanić", duration: "40:26" },

  // 2024 – Godina jubileja
  { id: "cNvgVNqyzWY", year: 2024, title: "Dobrodošli na praznik jubileja", speaker: "Oliver Buljat", duration: "32:35" },
  { id: "87I4TuAtlx0", year: 2024, title: "Razotkrivanje jubileja", speaker: "Miroslav Radovanović", duration: "22:41" },
  { id: "sixK9wzf-Gw", year: 2024, title: "Demonstracija jubileja", speaker: "Jasmin Avdagić", duration: "47:31" },

  // 2023 – Svetost
  { id: "krtH_015xrA", year: 2023, title: "Božja svetost", speaker: "Boris Babarović", duration: "31:33" },
  { id: "TruNdo7Ke-4", year: 2023, title: "Svetost Božjeg naroda", speaker: "Miki Kamberović", duration: "27:20" },
  { id: "HKXGw1NZax8", year: 2023, title: "Svetost u akciji", speaker: "Dušan Beređi Bera", duration: "41:56" },
  { id: "hIFZ3QKxufs", year: 2023, title: "Završna poruka", speaker: "Miroslav Radovanović", duration: "22:17" },
  { id: "z5zPth-s2jk", year: 2023, title: "Što je ČBS", speaker: "Oliver Buljat", duration: "14:35", type: "promo" },

  // 2022
  { id: "lAnUhKkXE5g", year: 2022, title: "Junak s junakom – prijateljstvo", speaker: "Nebojša Savić", duration: "35:02" },
  { id: "54BRsq-IfNQ", year: 2022, title: "Što je pravo junaštvo", speaker: "Vladimir Čizmanski", duration: "35:25" },
  { id: "dYKcwwWY-js", year: 2022, title: "Propovijed", speaker: "Slavko Hadžić", duration: "19:32" },

  // 2020
  { id: "AErp0E4PkaE", year: 2020, title: "Prijenos uživo – petak, 19. 6. 2020.", speaker: "ČBS", duration: "46:19" },
  { id: "cFnvBc2FKrA", year: 2020, title: "Prijenos uživo – subota, 20. 6. 2020.", speaker: "ČBS", duration: "53:58" },

  // 2019 – Evangelizacija
  { id: "2lGtW16pq_c", year: 2019, title: "Preuzmi odgovornost za evangelizaciju", speaker: "Slavko Hadžić", duration: "36:01" },
  { id: "t0EfR1NMWzw", year: 2019, title: "Podrška evangelizatorima", speaker: "Oliver Buljat", duration: "31:30" },
  { id: "6ynMHva-sAo", year: 2019, title: "Evangelizacija i tehnologija", speaker: "David Hadžić", duration: "11:26" },
  { id: "0d8gQInMlmA", year: 2019, title: "Prepreke za evangelizaciju i Božje rešenje", speaker: "Goran Ilijev", duration: "24:05" },
  { id: "VvmSWKJV1Q0", year: 2019, title: "Uloga Duha Svetoga u evangelizaciji", speaker: "Denis Turk", duration: "41:44" },
  { id: "wKdP1NxuMqk", year: 2019, title: "Žetva počinje kad se uzme srp", speaker: "Miroslav Radovanović", duration: "33:39" },
  { id: "39Qd53JDycg", year: 2019, title: "Vrijednosti ČBS-a", speaker: "ČBS", duration: "13:16", type: "promo" },

  // 2017 – Vjernost
  { id: "mplnId8DOiE", year: 2017, title: "Zanesen Božjom vjernošću", speaker: "Steve Telzerow", duration: "32:57" },
  { id: "jUeKa2qnw-4", year: 2017, title: "Vjeran za vječnost", speaker: "Slavko Hadžić", duration: "55:38" },
  { id: "s_gn68__ZcY", year: 2017, title: "Vjerni i Istiniti Bog", speaker: "Laslo Hornjik", duration: "20:21" },
  { id: "1hblbWzK2oo", year: 2017, title: "Ostajem vjeran bez obzira na cijenu", speaker: "Jasmin Avdagić", duration: "1:09:32" },
  { id: "8PCm0n74Zyk", year: 2017, title: "Vjernost – moja privilegija i odgovornost", speaker: "Miroslav Radovanović", duration: "27:57" },
  { id: "GKGpF8m1-9c", year: 2017, title: "ČBS 2017 – utisci", speaker: "ČBS", duration: "5:52", type: "promo" },
  { id: "CQL0wFO066k", year: 2017, title: "„My brother's keeper” na slovenskoj televiziji", speaker: "TV prilog", duration: "15:31", type: "promo" },

  // 2016 – Počinak (Adlešiči, Slovenija)
  { id: "6JCrreitKmo", year: 2016, title: "Počinak u svome identitetu u Kristu", speaker: "Viktor Sabo", duration: "23:54" },
  { id: "60Of-E8GnGo", year: 2016, title: "Bijeg ili odmor", speaker: "Slavko Hadžić", duration: "50:41" },
  { id: "AHCH-x-SXMM", year: 2016, title: "Pogled u budućnost", speaker: "Oliver Buljat", duration: "24:59" },
  { id: "i3Jh9ajo0N8", year: 2016, title: "Odmori muški – odmor s obzirom na osobnost", speaker: "Miroslav Radovanović", duration: "52:14" },
  { id: "0Cz-Jn_0evk", year: 2016, title: "Pregled ČBS okupljanja", speaker: "Damir Špoljarić", duration: "21:19" },
  { id: "mlt4ExwiS7g", year: 2016, title: "Počinak iz Božje perspektive", speaker: "", duration: "50:44" },
  { id: "b4IkiBaUX6A", year: 2016, title: "Slušaj Izraele – slavljenje, Adlešiči", speaker: "Slavljenje", duration: "7:18", type: "promo" },

  // 2015 – Čast i poštovanje

  // 2014 – Disciplinom do izvrsnosti
  { id: "AI2YkDhLT34", year: 2014, title: "Disciplinom do izvrsnosti", speaker: "Oliver Buljat", duration: "40:47" },
  { id: "aWIvuC6UtU4", year: 2014, title: "Prosječnost ili izvrsnost u Crkvi", speaker: "Slavko Hadžić", duration: "1:00:45" },
  { id: "QEGIAy-HFns", year: 2014, title: "Disciplina i izvrsnost u tvom posebnom pozivu", speaker: "Steve Telzerow", duration: "1:06:25" },
  { id: "-_RnqZGcIpg", year: 2014, title: "Izvrstan karakter i disciplina", speaker: "Karmelo Kresonja", duration: "39:23" },
  { id: "Gxsx63WBmPA", year: 2014, title: "Disciplina i nagrada", speaker: "Jasmin Avdagić", duration: "1:15:27" },

  // 2013 – Ratnici i ljubavnici
  { id: "IG2zY5spNV0", year: 2013, title: "Hrabro srce", speaker: "Nebojša Savić", duration: "36:50" },
  { id: "2iOO8xY1xJY", year: 2013, title: "Ljubavnik", speaker: "Zoran Pavić", duration: "35:27" },
  { id: "ewLsVKv5yAY", year: 2013, title: "Od dječaka do muškarca – od djeteta do ratnika", speaker: "", duration: "37:08" },
  { id: "UnzE67jQeE8", year: 2013, title: "Razotkrivanje (glavna svrha seksualnog odnosa)", speaker: "Oliver Buljat", duration: "1:00:35" },
  { id: "witMRA0UgoA", year: 2013, title: "Steći ženu junaštvom", speaker: "Jasmin Avdagić", duration: "1:11:23" },

  // 2012 – Božji upravitelji
  { id: "hHOrb13rVfs", year: 2012, title: "Odgovornost, posljedice i nagrade", speaker: "Steve Telzerow", duration: "46:41" },
  { id: "jYjoKwqzm4c", year: 2012, title: "Balkanski protiv biblijskog", speaker: "Miroslav Radovanović", duration: "1:10:31" },
  { id: "vduDKt3HBys", year: 2012, title: "Spoji vjeru s poštenjem", speaker: "Jasmin Avdagić", duration: "1:04:35" },
  { id: "N2KbxU4Z6P4", year: 2012, title: "Prosjaci ili sinovi", speaker: "Oliver Buljat", duration: "44:37" },

  // 2010 – Zagreb
  { id: "gCy82101O1c", year: 2010, title: "Muškarac i njegova svrha", speaker: "Karmelo Kresonja", duration: "36:35" },
  { id: "N91ifFCYfRI", year: 2010, title: "Biblijsko prijateljstvo", speaker: "Aleksandar Mitrović", duration: "44:30" },
  { id: "8KthtbRKQfI", year: 2010, title: "Muškarac i njegova žena", speaker: "Steve Telzerow", duration: "46:59" },
  { id: "_lKTVaQNswM", year: 2010, title: "Srca očeva i sinova", speaker: "Jasmin Avdagić", duration: "1:07:38" },
  { id: "ah6Pi-8Tir4", year: 2010, title: "Zemlja bez očeva", speaker: "Andrija Stanley", duration: "22:11" },
  { id: "SJywpeNTcPE", year: 2010, title: "Život dostojan smrti", speaker: "Oliver Buljat", duration: "56:44" }
];
