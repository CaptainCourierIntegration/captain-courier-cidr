

// var Browser = require("zombie");
var $ = require("jquery");
var _ = require("underscore");

module.exports = track;

// callback takes an array of objects with properties: date, time, location, trackingEvent.
function track(callback, trackingNumber) {
	page1(
		_.partial(
			page2, 
			_.partial(page3, callback)
		), 
		trackingNumber
	);
}

function page1(success, trackingNumber) {
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

			success(properties);
		}
	);
}

function page2(success, properties) {
	$.post(
		"http://www.parcelforce.com/track-trace",
		properties,
		function (html) {
			var trackingNumber = $(html).find("div#tnt-results > dl.dt-left-align > dd:nth-child(2) a").text();
			var parcelNumber = $(html).find("div#tnt-results > dl.dt-left-align > dd:nth-child(4) a").text();

			success(trackingNumber, parcelNumber);
		}
	);
}

function page3(success, trackingNumber, parcelNumber) {
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
			success(rows);
		}
	);
}

if(require.main === module) {
	var argv = process.argv;

	if(argv.length != 3) {
		console.log("takes argument trackingNumber");
		process.exit(1);
	}

	track(
		function (rows) {
			console.log("date\t\ttime\t\tlocation\t\tevent");
			rows.forEach(function(obj) {
				console.log(obj.date + "\t" + obj.time + "\t\t" + obj.location + "\t\t" + obj.trackingEvent)
			})
		},
		argv[2]
	);
}


