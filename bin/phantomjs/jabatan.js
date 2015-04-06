var page = require('webpage').create();
page.open('http://localhost:82/anforjab/index.php/entry_sdm/isian_jabatan/cetak_jabatan/', function() {
  page.render('cetak-jabatan.pdf');
  phantom.exit();
});