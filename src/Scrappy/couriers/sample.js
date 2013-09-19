
var models = require('../models.js');
var $ = require('jquery');
var http = require('http');

// object constructor
var Sample = function (credentials) {
    this.credentials = credentials;
};

// do this if you want to add event-i-ness to object
// see, http://nodejs.org/api/events.html
Sample.prototype = Object.create(process.EventEmitter.prototype);

// data used to register gearman workers
Sample.prototype.gearmanFnsToRegister = ['getTracking'];
Sample.prototype.gearmanWorkerPrefix = 'Sample';

// function that will be registered with gearman
// don't forget to add this to .prototype.gearmanFnsToRegister
Sample.prototype.getTracking = function (gearmanPayloadData) {

    var n = 1000000;
    while(--n) {
    }

    // return value will be json encoded and returned
    return [
        { what: 'collectedFromCustomer', when: 'yesterday' },
        { what: 'delivered', when: 'today' }
    ];

};

var config = {
    username: "username",
    password: "password"
};

// or alternatively if you want to read a config from a json file somewhere (which we probably do want to do)
// var config = JSON.parse(
//     fs.readFileSync( __dirname + '/config.json', 'UTF-8' )
// );

// instantiate new courier object
var sample = new Sample(config);

// return (ie, what is returned from require('sample')
module.exports = sample;