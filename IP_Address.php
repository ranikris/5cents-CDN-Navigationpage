<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/styles.css">
  <title>By IP Address</title>
</head>

<body>

  <div class="centerText">
    <h1>IP Address</h1>
  </div>

  <?php require "vendor/autoload.php"; ?>
  <?php include "php/date_selector.php"; ?>

  <form action="<?php ?>" method="GET">
    <label for="start_date">Start Date: </label>
    <input id="start_date" type="date" name="gte">
    <br>
    <label for="end_date">End Date: </label>
    <input id="end_date" type="date" name="lte">
    <br>
    <input type="submit"/>
  </form>

  <?php include "php/IP_Address.php"; ?>

</body>
</html>
