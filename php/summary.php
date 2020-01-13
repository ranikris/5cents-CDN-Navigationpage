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
    $q->aggs->{1}->sum->field = "client.bytes";
    $q->aggs->{2}->sum->field = "destination.bytes";
    $q->aggs->{3}->sum->field = "network.bytes";
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

    echo "<h3>$website</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Download</th>";
    echo "<th>Upload</th>";
    echo "<th>Total</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    $download = $obj->aggregations->{1}->value;
    echo formatBytes($download);
    echo "</td>";
    echo "<td>";
    $upload = $obj->aggregations->{2}->value;
    echo formatBytes($upload);
    echo "</td>";
    echo "<td>";
    $total = $obj->aggregations->{3}->value;
    echo formatBytes($total);
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
