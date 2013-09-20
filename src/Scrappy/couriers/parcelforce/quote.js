

var $ = require("jquery");

module.exports = quote;

// callback takes an array of objects with properties: service, delivery, compensation, tracking, price, vatIncluded
// weight in kg
function quote(callback, weight, collectionPostcode, deliveryPostcode) {
	collectionPostcode = collectionPostcode.replace(" ", "%20");
	deliveryPostcode = deliveryPostcode.replace(" ", "%20");

	$.get(
		"http://www.parcelforce.com/pricefinder/ajax/UK/0/" + weight + "/0/" + collectionPostcode + "/" + deliveryPostcode + "/0/0/0/0/0/kg/0/1",
		function (response) {
			var table = $(response.data).find("table#results");
			var rows = $(table).find("tbody > tr.display-result-data");
			var structuredRows = $(rows).map(function(_i, row) {
				var priceString = $(row).find("td:nth-child(5)").text();
				var price;
				var vatIncluded;
				if(-1 === priceString.indexOf(" + VAT")) {
					price = priceString;
					vatIncluded = true;
				} else {
					price = priceString.match(/[0-9]+.[0-9]{2}/gi)[0];
					vatIncluded = false;
				}

				return {
					service: $(row).find("td:nth-child(1) font").text(),
					delivery: $(row).find("td:nth-child(2)").text(),
					compensation: $(row).find("td:nth-child(3)").text(),
					tracking: $(row).find("td:nth-child(4)").text(),
					price: price,
					vatIncluded: vatIncluded
				};
			}).get();
			callback(structuredRows);
		}
	)
}

if(require.main === module) {
	var argv = process.argv;

	if(argv.length != 5) {
		console.log("takes arguments: weight, collectionAddress, deliveryAddress");
		process.exit(1);
	}

	quote(
		function (rows) {
			rows.forEach(function(row) {
				console.log("");
				console.log("==============");
				console.log("service:      " + row.service);
				console.log("delivery:     " + row.delivery);
				console.log("compensation: " + row.compensation);
				console.log("tracking:     " + row.tracking);
				console.log("price:        " + row.price);
				console.log("VAT included: " + row.vatIncluded);
				console.log("==============");
			})
		},
		argv[2],
		argv[3],
		argv[4]
	);
}
