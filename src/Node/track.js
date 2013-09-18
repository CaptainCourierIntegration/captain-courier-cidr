

// var Browser = require("zombie");
var $ = require("jquery");


// callback takes an array of objects with properties: date, time, location, trackingEvent.
function track(callback, trackingNumber) {
	$.get(
		"http://www.parcelforce.com/track-trace",
		function(data) {
			var properties = {};
			$(data).find("form#track-trace-request-form input[type=hidden]").each(function() {
				properties[$(this).attr("name")] = $(this).attr("value");
			});
			properties["track.x"] = 22;
			properties["track.y"] = 1;
			properties["track_id"] = trackingNumber;
			page2(callback, properties);
		}
	);
}

function page2(callback, properties) {
	$.post(
		"http://www.parcelforce.com/track-trace",
		properties,
		function (html) {
			var trackingNumber = $(html).find("div#tnt-results > dl.dt-left-align > dd:nth-child(2) a").text();
			var parcelNumber = $(html).find("div#tnt-results > dl.dt-left-align > dd:nth-child(4) a").text();
			page3(callback, trackingNumber, parcelNumber);
		}
	);
}

function page3(callback, trackingNumber, parcelNumber) {
	$.get(
		"http://www.parcelforce.com/track-trace",
		{
			trackNumber: trackingNumber,
			page_type: "parcel-tracking-details",
			parcel_number: parcelNumber
		},
		function(html) {
			var table = $(html).find("div#tnt-results table");
			var rows = $(table).find("tbody tr").map(function(i, row) {
				return {
					date: $(row).find(":nth-child(1)").text(),
					time: $(row).find(":nth-child(2)").text(),
					location: $(row).find(":nth-child(3)").text(),
					trackingEvent: $(row).find(":nth-child(4)").text()
				}
			}).get();
			callback(rows);
		}
	);
}


track(
	function (rows) {
		console.log(rows);
	},
	"CN5752885"
);


// // trackingNumber is string
// // callback is function which takes an array of objects with properties: 'Date', 'Time', 'Location', 'TrackingEvent'
// function track(trackingNumber, callback) {

// 	var debug = false;
// 	var agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.65 Safari/537.36";
// 	var url = "http://www.parcelforce.com/track-trace";

// 	var browser = new Browser({
// 			debug: debug, 
// 			userAgent: agent
// 	});

// 	var pfBrowser = browser.visit(url, {async: false}, function () {
// 		browser
// 			.fill("#edit-track-id", trackingNumber)
// 			.pressButton("#edit-track", function() {
// 				//browser.clickLink(".tracking-details-secondary", function() {

// 					var tableHtml = browser.html("table.sticky-enabled.sticky-table");
// 					var headers = $(tableHtml).find("th").map(function(i, header) {
// 						return $(header).text();
// 					}).get();

					
// 					var data = $(tableHtml).find("tbody > tr").map(function(_i, rowArray) {
// 						var row = {};
// 						for(var i = 0; i < headers.length; i++) {
// 							row[headers[i]] = $(rowArray).find(":nth-child(" + (i + 1) + ")").text();
// 						}
// 						return row;
// 					}).get();

// 					return callback(data);
// //				})
// 			});
// 	});
// }


// // TODO delete these examples
// track("CN5752885", function (table) {
// 	console.log(table);
// });




