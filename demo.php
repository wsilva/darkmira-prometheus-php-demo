<?php

use Prometheus\Counter;
use Prometheus\Gauge;
use Prometheus\Histogram;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\APC;

require_once 'vendor/autoload.php';

if ($_SERVER['REQUEST_URI'] === "/") {
    print <<<END
    <ul>
    <li><a href="http://localhost:8888/metrics">Metrics endpoint</a></li>
    <li><a href="http://localhost:8888/mamae">Mamae endpoint</a></li>
    <li><a href="http://localhost:8888/maguila">Maguila endpoint</a></li>
    <li><a href="http://localhost:9090/graph">Prometheus home</a></li>
    <li><a href="http://localhost:9090/graph?g0.range_input=15m&g0.stacked=0&g0.expr=sum(darkmirademo_count_total)&g0.tab=0">Query Counter</a></li>
    <li><a href="http://localhost:9090/graph?g0.range_input=15m&g0.stacked=0&g0.expr=sum(darkmirademo_some_gauge)&g0.tab=0">Query Gauge</a></li>
    <li><a href="http://localhost:9090/graph?g0.range_input=15m&g0.stacked=0&g0.expr=sum(darkmirademo_count_total)%20by%20(status)&g0.tab=0">Counter by Status</a></li>
    <li><a href="http://localhost:9090/graph?g0.range_input=15m&g0.stacked=0&g0.expr=sum(darkmirademo_some_gauge)%20by%20(status)&g0.tab=0">Gauge by Status</a></li>
    <li><a href="http://localhost:9090/graph?g0.range_input=15m&g0.stacked=0&g0.expr=histogram_quantile(0.99%2C%20sum(rate(darkmirademo_response_time_ms_bucket%7Bstatus%3D%22200%22%7D%5B1m%5D))%20by%20(le%2C%20url%2C%20status))&g0.tab=0">Query Percentile 99</a></li>
    <li><a href="http://localhost:3000/">Grafana</a></li>
    </ul>
    END;
}

$httpResponseCodes = [200, 201, 202, 301, 302, 304, 400, 401, 403, 404, 405, 500, 502, 503, 504];
$randomStatus = $httpResponseCodes[array_rand($httpResponseCodes)];

$endpoints = ["/metrics","/mamae","/maguila"];
$randomEndpoint = $endpoints[array_rand($endpoints)];

$ramdomDuration = random_int(1,100);

$adapter = new APC();

$counter = new Counter($adapter,"darkmirademo","count_total","Demo counter",["status","url"]);
$counter->inc(["200","/mamae"]);
$counter->inc(["200","/maguila"]);
$counter->incBy(5,[$randomStatus,$randomEndpoint]);

$gauge = new Gauge($adapter,"darkmirademo","some_gauge","Demo Gauge",["status","url"]);
$gauge->set($ramdomDuration,[$randomStatus,$randomEndpoint]);

$histogram = new Histogram($adapter,"darkmirademo","response_time_ms","Demo histogram",["status","url"],[0,7,17,27]);
$histogram->observe($ramdomDuration,[$randomStatus,$randomEndpoint]);

$response = new RenderTextFormat();

header("Content-Type: text/plain");
echo $response->render($adapter->collect());