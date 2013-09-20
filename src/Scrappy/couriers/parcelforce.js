
var sprintf = require("sprintf");
var track = require("./parcelforce/track.js");
var quote = require("./parcelforce/quote.js");

function makeParcelForce() {
	return {

		gearmanFnsToRegister: ["getTracking", "getQuotes"],

		gearmanWorkerPrefix: "ParcelForce",

		getTracking: function(payload, worker, success) {
			var shipmentNumber = payload.shipmentNumber;
			var trackingLog = track(
				success,
				shipmentNumber
			);
		},

		getQuotes: function(payload, worker, success) {
			var collectionPostcode = payload.collectionPostcode;
			var deliveryPostcode = payload.deliveryPostcode;
			var weight = payload.weight;

			quote(success, weight, collectionPostcode, deliveryPostcode);
		}
	};
}

module.exports = makeParcelForce();


