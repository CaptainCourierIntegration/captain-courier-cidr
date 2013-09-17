

var Browser = require("zombie");
var $ = require("jquery");



// trackingNumber is string
// callback is function which takes an array of objects with properties: 'Date', 'Time', 'Location', 'TrackingEvent'
function track(trackingNumber, callback) {

	var debug = false;
	var agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.65 Safari/537.36";
	var url = "http://www.parcelforce.com/track-trace";

	var browser = new Browser({
			debug: debug, 
			userAgent: agent
	});

	var pfBrowser = browser.visit(url, {async: false}, function () {
		browser
			.fill("#edit-track-id", trackingNumber)
			.pressButton("#edit-track", function() {
				browser.clickLink(".tracking-details-secondary", function() {

					var tableHtml = browser.html("table.sticky-enabled.sticky-table");
					var headers = $(tableHtml).find("th").map(function(i, header) {
						return $(header).text();
					}).get();

					
					var data = $(tableHtml).find("tbody > tr").map(function(_i, rowArray) {
						var row = {};
						for(var i = 0; i < headers.length; i++) {
							row[headers[i]] = $(rowArray).find(":nth-child(" + (i + 1) + ")").text();
						}
						return row;
					}).get();

					return callback(data);
				})
			});
	});
}


// TODO delete these examples
track("CN5752885", function (table) {
	console.log(table);
});




