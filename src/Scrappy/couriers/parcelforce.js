
var sprintf = require("sprintf");


console.log(sprintf("hello %s", "world"));

module.exports = {
	credentials: {
	    username: "testuser",
	    apiKey: "FI71OLRJB7LYC7BDRR0"		
	},
	gearmanFnsToRegister: ["getTracking"],
	gearmanWorkerPrefix: "ParcelForce",
	getTracking: function(gearmanPayloadData) {
		return [
	        { what: 'collectedFromCustomer', when: 'yesterday' },
	        { what: 'delivered', when: 'today' }
		]
	}
};