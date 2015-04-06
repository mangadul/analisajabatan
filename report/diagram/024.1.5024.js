
			var page = require('webpage').create();
			page.open('http://localhost:82/anforjab/index.php/entry_sdm/isian_jabatan/diagram_sotk/024.1.5/024', function() {
				page.viewportSize = {
				    width: 600,
				    height: 250
				};				
			  page.render('C:\/xampp\/htdocs\/anforjab\/report\/diagram\/024.1.5024.png');
			  phantom.exit();
			});
			