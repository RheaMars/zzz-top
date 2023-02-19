<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/font.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <title>EU Indentation Converter</title>
</head>
<body>

<h1>EU Indentation Converter</h1>

<form method="POST" action="index.php">
    <label for="originalInput">Enter a value:</label><br/><br/>
    <input type="text" id="originalInput" name="originalInput" value="<?php echo isset($_POST['originalInput']) ? $_POST['originalInput'] : '' ?>"><br/><br/>
    <input type="submit" value="Submit">
</form>

<?php
include "src/Converter.php";
include "src/Combinatorics.php";

use src\Converter;

if(isset($_POST['originalInput'])) {

    $originalInput = $_POST['originalInput'];

    $converter = new Converter($originalInput);

    if (!$converter->isInputValid($originalInput)) {
        $htmlOutput = "<p class='warning'>Input is invalid: It must start with a positive number, followed by lower case characters, for example \"12abzaazx\".</p>";
    }
    else {
        $htmlOutput = '<h2>Indentation alternatives for "' . $originalInput . '":</h2>';
        $htmlOutput .= "
            <table>
            <tr>
             <th>lexicographic</th>
             <th>arabic</th>
             <th>greek</th>
            </tr>";

        foreach ($converter->computeOutput() as $outputEntry) {
            $htmlOutput .= "<tr>";
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