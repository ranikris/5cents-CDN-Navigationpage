<?php
if ((isset($_GET["gte"]) ? true : false) && (isset($_GET["gte"]) ? true : false) && (isset($_GET["freq"]) ? true : false)) {
    $gte = $_GET["gte"];
    $lte = $_GET["lte"];
    $freq = $_GET["freq"];
} else {
    $gte = date('2019-10-01');
    $lte = date('Y-m-d');
    $freq = "1d";
}
echo "Current Start: $gte";
echo "<br>";
echo "Current End: $lte";
echo "<br>";
echo "Current Frequency: $freq";
echo "<br>";
echo "<br>";
