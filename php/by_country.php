<?php
require("php/credentials.php");

$websites = [
    "https://website1.com",
    "https://website2.com",
    "https://website3.com",
    "https://website4.com"
];

foreach ($websites as $website) {
    $q = new stdClass();
    $q->size = "0";
    $q->aggs->{2}->terms->field = "client.geo.country_iso_code";
    $q->aggs->{2}->aggs->{3}->date_histogram->field = "@timestamp";
    $q->aggs->{2}->aggs->{3}->date_histogram->format = "yyyy-MM-dd HH:mm";
    $q->aggs->{2}->aggs->{3}->date_histogram->fixed_interval = "$freq";
    $q->aggs->{2}->aggs->{3}->date_histogram->min_doc_count = "1";
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
    echo "<th>Time</th>";
    echo "<th>Country</th>";
    echo "<th>Hits</th>";
    echo "</tr>";
    foreach ($obj->aggregations->{2}->buckets as $value) {
        foreach ($value->{3}->buckets as $time) {
            echo "<tr>";
            echo "<td>";
            echo $time->key_as_string;
            echo "</td>";
            echo "<td>";
            echo $value->key;
            echo "</td>";
            echo "<td>";
            echo $time->doc_count;
            echo "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}
