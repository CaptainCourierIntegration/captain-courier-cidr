var fs = require('fs');
var path = require('path');
var Gearman = require("node-gearman");
var _ = require("underscore");
var util = require("util");
var sprintf = require("sprintf");

var couriers = loadCouriers();

// is this a unit test
var isUnitTest = _.some(process.argv, function(v){
    return (v == '--unittest' || v == '-t' );
});

// build gearman object and add some features for working
var jobPrefix = 'node.scrappy';

var gearman = monkeyPatchGearman( new Gearman() );

// register each courier with gearman
_.each( loadCouriers(), _.partial( registerCourierWithGearman, gearman ) );

// register a courier with gearna
function registerCourierWithGearman (gearman, courier) {

    // iterate each couriers
    courier.gearmanFnsToRegister.forEach(function(fnName){

        var jobName = jobPrefix+'.'+courier.gearmanWorkerPrefix+'.'+fnName;

        // little bit of output logging
        console.log( "registering worker `"+jobName+"`" );

        // register worker
        // wrap the JSON encoding decoding so the calling function doesn't have to do this
        gearman.registerWorker( jobName, function (payload, worker) {

            // catch passed bad json
            try {
                var data = JSON.parse( payload.toString("utf-8") );
            } catch (e) {
                throw new Error(
                    [
                        "Gearman job `",
                        jobName,
                        "` was passed a payload which isn't valid JSON.\n--- PAYLOAD START ---\n",
                        payload.toString('utf-8'),
                        "\n--- PAYLOAD END ---\n",
                    ].join('')
                );
            }

            // we don't actually give a shit any more about the return value
            var returnValue = courier[fnName](
                data,
                worker,
                getWorkerSuccessCallback(worker, ++gearman.jobCount)
            );

        });

    });

}

function getWorkerSuccessCallback (worker, jobCount)
{
    // benchmarking and do work
    var start = process.hrtime();
    return function success (output) {
        worker.end( JSON.stringify(output) );
        console.log( worker.name + ' ' + jobCount );
    }
}

// function formathr(start, end)
// {
//     start = start[0] + start[1] / 1000000;
//     end = end[0] + end[1] / 1000000;
//     console.log( end- start );
//     return end - start;
//     //return (1 * diff[0]) + ( diff[1] / 1000000);
// }
//
// function formathrdiff(start, end)
// {
//     console.log( start );
//     console.log( end );
//     start = start[0] + (start[1] / 1000000);
//     end = end[0] + ( end[1] / 1000000 );
//
//     console.log( end- start );
//     return end - start;
//     //return (1 * diff[0]) + ( diff[1] / 1000000);
// }

// test a gearman worker

var c = 0;
setInterval(function(){
    var payload = c++;
    gearman.submitJobJson(
        'node.scrappy.Sample.getTracking',
        payload,
        {
            timeout: 2000,
            onSuccess: function (data) {
            }
        }
    );
},250);

// get all supported couriers
function loadCouriers () {

    var courierDir = path.resolve(__dirname, 'couriers');
    var couriers = {};
    fs.readdirSync(courierDir)
        .forEach(function(file){

            var absPath = courierDir+'/'+file;
            var stat = fs.statSync(absPath);

            if (!stat.isDirectory()) {
                couriers[file] = require(absPath);
            }

        });

    return couriers;
}

// monkeypatch gearman
function monkeyPatchGearman (gearman) {

    // is unittest argument set
    var gearmanJobPrefix = isUnitTest ? 'Unit.' : '';

    // add a submitJobJson and add some debugging wrapper, sensible default timeout and some minor error trapping
    gearman.submitJobJson = function (functionName, data, options) {
        options = _.defaults(options || {}, {
            timeout: 1000,
            onSuccess: function(data) {
//                console.log( data );
            },
            onTimeout: function() {
                throw "gearman job `" + functionName + "` timed out. Check gearman server is running and workers are registered.";
            }
        });
        // submitting this to a unit test database
        var job = this.submitJob( gearmanJobPrefix + functionName, JSON.stringify(data) );
        job.payloadData = data;
        // timeout callback
        job.on("data", function(data){
            options.onSuccess.call(
                this,
                JSON.parse( data.toString("utf-8") )
            );
        });
        job.setTimeout( options.timeout );
        job.on("timeout", function(){
            // log the timeout somewhere
            options.onTimeout.apply( this, arguments );
        });
        return job;
    }

    // register a gearman status handler
    // returns information about this running node process
    var nodeStatus = "node." + process.pid + ".status";
    gearman.registerWorker( nodeStatus, function(payload, worker){
        memoryUsage = process.memoryUsage().heapTotal;
        var output = {
            memoryUsage: memoryUsage,
            memoryUsageOperatingSystem: memoryUsage,
            startTime: new Date().getTime() / 1000,
            uptime: process.uptime(),
            dbConnections: 0,
            jobCount: gearman.jobCount,
            timeLimit: 0,
            memoryLimit: 0,
            pid: process.pid,
            script: process.mainModule.filename
        }
        worker.end( JSON.stringify(output) );
    });

    gearman.jobCount = 0;

    // get this node processes' gearman status
    // gearman.submitJobJson(
    //     nodeStatus,
    //     null,
    //     {
    //         onSuccess: function (data) {
    //             console.log("Worker");
    //             console.log( data );
    //         }
    //     }
    // );

    return gearman;

}