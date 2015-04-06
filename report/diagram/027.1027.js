
			var page = require('webpage').create();
			page.open('http://localhost:82/anforjab/index.php/entry_sdm/isian_jabatan/diagram_sotk/027.1/027', function() {
				page.viewportSize = {
				    width: 600,
				    height: 250
				};				
			  page.render('C:\/xampp\/htdocs\/anforjab\/report\/diagram\/027.1027.png');
			  phantom.exit();
			});
			