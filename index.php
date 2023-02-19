<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/font.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <title>EU Indentation Converter</title>
</head>
<body>

<?php
include "src/Calculator.php";
include "src/Combinatorics.php";

use src\Calculator;

$originalInput = "1234abcze";

$calculator = new Calculator($originalInput);

if (!$calculator->isInputValid($originalInput)) {
    echo "Input is invalid: It must start with a positive number, followed by lowercase chars, eg. 12abzaazx.";
    exit;
}

echo '<h2>Indentation alternatives for "' . $originalInput . '":</h2>';

$table = "
<table>
<tr>
 <th>lexicographic</th>
 <th>arabic</th>
 <th>greek</th>
</tr>";

foreach ($calculator->computeOutput() as $outputEntry) {
    $table .= "<tr>";
    $table .= "<td>" . $outputEntry["lexicographic"] . "</td>";
    $table .= "<td>" . $outputEntry["arabic"] . "</td>";
    $table .= "<td>" . $outputEntry["greek"] . "</td>";
    $table .= "</tr>";
}

$table .= "</table>";
echo $table;
?>

</body>