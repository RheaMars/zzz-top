<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/font.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <title>ZZZ Top</title>
</head>
<body>

<h1>ZZZ Top</h1>

<form method="POST" action="index.php">
    <label for="originalInput">Enter a value:</label><br/><br/>
    <input type="text" id="originalInput" name="originalInput" value="<?php echo isset($_POST['originalInput']) ? $_POST['originalInput'] : '' ?>"><br/><br/>
    <input type="submit" value="Submit">
</form>

<?php
include "src/DataProviderService.php";
include "src/Disambiguator.php";
include "src/Converter.php";
include "src/Combinatorics.php";

use src\DataProviderService;

if(isset($_POST['originalInput'])) {

    $originalInput = $_POST['originalInput'];

    $service = new DataProviderService();

    if (!$service->isInputValid($originalInput)) {
        $htmlOutput = "<p class='warning'>Input is invalid: It must start with a positive number, followed by lower case characters, for example \"12abzaazx\".</p>";
    }
    else {
        $disambiguatedData = $service->getData($originalInput);
        $htmlOutput = '<h2>Value "' . $originalInput . '" disambiguates in ' . count($disambiguatedData) . ' ways:</h2>';
        $htmlOutput .= "
            <table>
            <tr>
             <th>#</th>
             <th>lexicographic</th>
             <th>arabic</th>
             <th>greek</th>
            </tr>";

        foreach ($disambiguatedData as $key => $outputEntry) {

            $htmlOutput .= "<tr>";
            $htmlOutput .= "<td>" . ($key + 1) . "</td>";
            $htmlOutput .= "<td>" . $outputEntry["lexicographic"] . "</td>";
            $htmlOutput .= "<td>" . $outputEntry["arabic"] . "</td>";
            $htmlOutput .= "<td>" . $outputEntry["greek"] . "</td>";
            $htmlOutput .= "</tr>";
        }

        $htmlOutput .= "</table>";
    }

    echo $htmlOutput;
}
?>

</body>