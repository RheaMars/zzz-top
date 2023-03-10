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
    <link rel="stylesheet" href="src/css/styles.css">
    <title>ZZZ Top</title>
    <meta name="robots" content="noindex,nofollow"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h1>ZZZ Top</h1>
<p id="headlineSubtitle">
    For more information take a look at <a href="https://github.com/RheaMars/zzz-top" target="_blank"><img id="githubImage" alt="Github Logo" src="src/img/github.png" title="github.com/RheaMars/zzz-top"/></a>
</p>

<form method="POST" action="index.php">
    <p>
        <label for="ambiguousString">Enter a value:</label><br/><br/>
        <input type="text" id="ambiguousString" name="ambiguousString" value="<?php echo $ambiguousString ?>">
    </p>
    <p>
        <input type="radio" id="setLimitToShownOptions" name="setShowOptionsLimit" value="true" <?php echo $checkedValueForSetLimitToShownOptions ?>>
        <label for="setLimitToShownOptions">Show first two alternatives</label><br/>
        <input type="radio" id="setNoLimitToShownOptions" name="setShowOptionsLimit" value="false" <?php echo $checkedValueForSetNoLimitToShownOptions?>>
        <label for="setNoLimitToShownOptions">Show all alternatives</label>
    </p>
    <input type="submit" id="submit" value="Submit">
</form>

<?php
if ($formWasSubmitted) {
    $resultPrinter = new ResultPrinter();
    echo $resultPrinter->printResult($ambiguousString, "true" === $setShowOptionsLimit);
}
?>

</body>