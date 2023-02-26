<!DOCTYPE html>
<html lang="en">
<head>
    <link href="src/css/font.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/styles.css">
    <title>ZZZ Top</title>
</head>
<body>

<h1>ZZZ Top</h1>

<form method="POST" action="index.php">
    <label for="ambiguousString">Enter a value:</label><br/><br/>
    <input type="text" id="ambiguousString" name="ambiguousString" value="<?php echo isset($_POST['ambiguousString']) ? $_POST['ambiguousString'] : '' ?>"><br/><br/>
    <input type="submit" value="Submit">
</form>

<?php
require 'vendor/autoload.php';

use src\php\DataProviderService;

if(isset($_POST['ambiguousString'])) {

    $ambiguousString = $_POST['ambiguousString'];

    $service = new DataProviderService();

    if (!$service->isInputValid($ambiguousString)) {
        $htmlOutput = "<p class='warning'>Input is invalid: It must start with a positive number, followed by lower case characters, for example \"12abzaazx\".</p>";
    }
    else {
        $disambiguatedData = $service->getData($ambiguousString);
        $htmlOutput = '<h2>Value "' . $ambiguousString . '" disambiguates in ' . count($disambiguatedData) . ' ways:</h2>';
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