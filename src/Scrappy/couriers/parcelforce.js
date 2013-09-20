
var sprintf = require("sprintf");
var track = require("./parcelforce/track.js");

function makeParcelForce() {
	return {

		gearmanFnsToRegister: ["getTracking"],

		gearmanWorkerPrefix: "ParcelForce",

		getTracking: function(gearmanPayloadData) {
		
			var shipmentNumber = gearmanPayloadData.shipmentNumber;
			var trackingLog = track(
				function () {
				},
				shipmentNumber
			);
			console.log(trackingLog);
			return trackingLog;
		}
	};
}

module.exports = makeParcelForce();