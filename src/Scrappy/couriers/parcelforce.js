
var sprintf = require("sprintf");
var track = require("./parcelforce/track.js");

function makeParcelForce() {
	return {

		gearmanFnsToRegister: ["getTracking"],

		gearmanWorkerPrefix: "ParcelForce",

		getTracking: function(gearmanPayloadData, worker, success) {
			var shipmentNumber = gearmanPayloadData.shipmentNumber;
			var trackingLog = track(
				success,
				shipmentNumber
			);
		}
	};
}

module.exports = makeParcelForce();


