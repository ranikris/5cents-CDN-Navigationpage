<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/styles.css">
    <title>By Browser</title>
</head>

<body>

<div class="centerText">
    <h1>By Browser</h1>
</div>

<?php require "vendor/autoload.php"; ?>
<?php include "php/date_selector.php"; ?>

<form action="<?php ?>" method="GET">
    <label for="minute">Minute: </label>
    <input id="minute" type="radio" name="freq" value="5m">
    <br>
    <label for="hour">Hour: </label>
    <input id="hour" type="radio" name="freq" value="1h">
    <br>
    <label for="day">Day: </label>
    <input id="day" type="radio" name="freq" value="1d" checked="checked">
    <br>
    <label for="start_date">Start Date: </label>
    <input id="start_date" type="date" name="gte">
    <br>
    <label for="end_date">End Date: </label>
    <input id="end_date" type="date" name="lte">
    <br>
    <input type="submit"/>
</form>

<?php include "php/by_browser.php"; ?>

</body>
</html>