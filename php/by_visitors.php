<?php
require("php/credentials.php");

function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

$websites = [
    "https://website1.com",
    "https://website2.com",
    "https://website3.com",
    "https://website4.com"
];

foreach ($websites as $website) {
    $q = new stdClass();
    $q->size = "0";
    $q->aggs->{2}->terms->field = "source.ip";
    $q->aggs->{2}->aggs->{3}->terms->field = "user_agent.original";
    $q->aggs->{2}->aggs->{3}->aggs->{4}->terms->field = "client.geo.country_iso_code";
    $q->aggs->{2}->aggs->{3}->aggs->{4}->aggs->{1}->sum->field = "network.bytes";
    $q->query->bool->filter[]->match_phrase->{"url.domain"}->query = "$website";
    $q->query->bool->filter[]->range->{"@timestamp"}->gte = "$gte";
    $q->query->bool->filter[]->range->{"@timestamp"}->lte = "$lte";

    $json = json_encode($q);

    $credentials = new credentials();
    $username = $credentials->username;
    $password = $credentials->password;

    $client = new \GuzzleHttp\Client([
        "base_uri" => "http://ipaddress",
        "auth" => ["$username", "$password"],
        "headers" => ["Content-Type" => "application/json"]
    ]);

    $request = $client->get("/packetbeat-*/_search", ["body" => $json]);
    $body = $request->getBody();

    $obj = json_decode($body);

//    print("<pre>" . print_r($obj, true) . "</pre>");

    echo "<h3>$website</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<th>IP</th>";
    echo "<th>User Agent</th>";
    echo "<th>Country</th>";
    echo "<th>Request</th>";
    echo "<th>Bandwidth</th>";
    echo "</tr>";
    foreach ($obj->aggregations->{2}->buckets as $value) {
        echo "<tr>";
        echo "<td>";
        echo $value->key;
        echo "</td>";
        echo "<td>";
        foreach ($value->{3}->buckets as $userAgent) {
            echo $userAgent->key;
        }
        echo "</td>";
        echo "<td>";
        foreach ($value->{3}->buckets as $userAgent) {
            foreach ($userAgent->{4}->buckets as $country) {
                echo $country->key;
            }
        }
        echo "</td>";
        echo "<td>";
        echo $value->doc_count;
        echo "</td>";
        echo "<td>";
        foreach ($value->{3}->buckets as $userAgent) {
            foreach ($userAgent->{4}->buckets as $country) {
                echo formatBytes($country->{1}->value);
            }
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
