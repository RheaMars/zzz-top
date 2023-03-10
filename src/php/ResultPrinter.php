<?php
declare(strict_types = 1);

namespace src\php;

final class ResultPrinter
{
    private const LIMIT_OPTIONS_NUMBER = 2;

    public function printResult(string $ambiguousString, bool $setShowOptionsLimit): string
    {
        $dataProvider = new DataProvider();

        if (!$dataProvider->isInputValid($ambiguousString)) {
            $htmlOutput = "<p class='warning'>Input is invalid: It must start with a positive number, followed by lower case latin characters, for example \"12zzztop\".</p>";
        }
        else {
            $disambiguatedData = $dataProvider->getData($ambiguousString, $setShowOptionsLimit);

            $numberOfAlternatives = $dataProvider->getNumberOfAlternatives($ambiguousString);

            if (1 === $numberOfAlternatives) {
                $resultHeadline = '<h3>Value "' . $ambiguousString . '" is unambiguous:</h3>';
            }
            else {
                $resultHeadline = '<h3>Value "' . $ambiguousString . '" disambiguates in ' . $numberOfAlternatives . ' ways:</h3>';
            }

            $htmlOutput = $resultHeadline;
            $htmlOutput .= "
            <table>
            <tr>
             <th>#</th>
             <th>lexicographic</th>
             <th>arabic</th>
             <th>greek</th>
            </tr>";

            foreach ($disambiguatedData as $key => $outputEntry) {

                if (true === $setShowOptionsLimit && $key >= self::LIMIT_OPTIONS_NUMBER) {
                    break;
                }

                $htmlOutput .= "<tr>";
                $htmlOutput .= "<td>" . ($key + 1) . "</td>";
                $htmlOutput .= "<td>" . $outputEntry["lexicographic"] . "</td>";
                $htmlOutput .= "<td>" . $outputEntry["arabic"] . "</td>";
                $htmlOutput .= "<td>" . $outputEntry["greek"] . "</td>";
                $htmlOutput .= "</tr>";
            }

            $htmlOutput .= "</table>";
        }

        return $htmlOutput;
    }
}