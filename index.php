<?php
require 'vendor/autoload.php';

use src\php\ResultPrinter;

$formWasSubmitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$ambiguousString = $_POST['ambiguousString'] ?? '';
$setShowOptionsLimit = $_POST['setShowOptionsLimit'] ?? "true";
$checkedValueForSetLimitToShownOptions = !isset($_POST['setShowOptionsLimit']) || "true" === $_POST['setShowOptionsLimit'] ? 'checked' : '';
$checkedValueForSetNoLimitToShownOptions = isset($_POST['setShowOptionsLimit']) && "false" === $_POST['setShowOptionsLimit'] ? 'checked' : '';
?>

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
    <p>
        <label for="ambiguousString">Enter a value:</label><br/><br/>
        <input type="text" id="ambiguousString" name="ambiguousString" value="<?php echo $ambiguousString ?>">
    </p>
    <p>
        <input type="radio" id="setLimitToShownOptions" name="setShowOptionsLimit" value="true" <?php echo $checkedValueForSetLimitToShownOptions ?>>
        <label for="setLimitToShownOptions">Show best options</label><br/>
        <input type="radio" id="setNoLimitToShownOptions" name="setShowOptionsLimit" value="false" <?php echo $checkedValueForSetNoLimitToShownOptions?>>
        <label for="setNoLimitToShownOptions">Show all options</label>
    </p>
    <input type="submit" value="Submit">
</form>

<?php
if($formWasSubmitted) {
    $resultPrinter = new ResultPrinter();
    echo $resultPrinter->printResult($ambiguousString, "true" === $setShowOptionsLimit);
}
?>

</body>