<?php

//sum function
function strSum(string $number1, string $number2): string
{
    $next = false;
    $result = '';
    $lengthNumber1 = strlen($number1);
    $lengthNumber2 = strlen($number2);
    for ($g = 0; $g < min($lengthNumber1, $lengthNumber2); $g++) {
        $calculation = strval(intval($number1[$lengthNumber1 - 1 - $g]) + intval($number2[$lengthNumber2 - 1 - $g]) + ($next ? 1 : 0));
        if (strlen($calculation) > 1) {
            $next = true;
        } else {
            $next = false;
        }
        $result = $calculation[strlen($calculation) - 1] . $result;
    }
    if ($lengthNumber1 < $lengthNumber2) {
        $tmpLength = $lengthNumber2;
        $tmpNumber = $number2;
        $number1 = $tmpNumber;
        $lengthNumber2 = $lengthNumber1;
        $lengthNumber1 = $tmpLength;
    }
    for ($g = $lengthNumber2; $g < $lengthNumber1; $g++) {
        $calculation = strval(intval($number1[$lengthNumber1 - 1 - $g]) + ($next ? 1 : 0));
        if (strlen($calculation) > 1) {
            $next = true;
        } else {
            $next = false;
        }
        $result = $calculation[strlen($calculation) - 1] . $result;
    }
    if ($next) {
        $result = '1' . $result;
    }
    return $result;
}

//subtraction function
function strMinus(string $number1, string $number2): string
{
    $lengthNumber1 = strlen($number1);
    $lengthNumber2 = strlen($number2);
    $next = false;
    $result = '';
    for ($g = 0; $g < min($lengthNumber1, $lengthNumber2); $g++) {
        $calculation = intval($number1[$lengthNumber1 - 1 - $g]) - intval($number2[$lengthNumber2 - 1 - $g]) - ($next ? 1 : 0);
        if ($calculation < 0) {
            $next = true;
            $calculation += 10;
        } else {
            $next = false;
        }
        $calculation = strval($calculation);
        $result = $calculation[strlen($calculation) - 1] . $result;
    }

    for ($g = $lengthNumber2; $g < $lengthNumber1; $g++) {

        $calculation = intval($number1[$lengthNumber1 - 1 - $g]) - ($next ? 1 : 0);
        if ($calculation < 0) {
            $next = true;
            $calculation += 10;
        } else {
            $next = false;
        }
        $calculation = strval($calculation);
        $result = $calculation[strlen($calculation) - 1] . $result;
    }
    while ($result[0] == '0' && strlen($result) > 1) {
        $result = substr($result, 1);
    }
    return $result;
}

// input and output settings
// change to php://stdin to input from console and change to php://stdout to print it to the console
$input = fopen('input', 'r');
$output = fopen('output', 'w');

fscanf($input, '%d', $testCases);
$operators = ['*', '+', '-'];

for ($t = 0; $t < $testCases; $t++) {
    fscanf($input, '%s', $expression);
    $result = 0;
    $number1 = 0;
    $number2 = 0;
    $lengthNumber2 = 0;
    $lengthNumber1 = 0;
    $operation = '';
    foreach ($operators as $operator) {
        $operatorCheck = strpos($expression, $operator);
        if ($operatorCheck > 0) {

            $number1 = substr($expression, 0, $operatorCheck);
            $number2 = substr($expression, $operatorCheck + 1);
            $lengthNumber1 = strlen($number1);
            $lengthNumber2 = strlen($number2);
            $operation = $expression[$operatorCheck];
            break;
        }
    }
    if ($operation == '+') {
        $result = strSum($number1, $number2);
        $maxWidth = max(strlen($result), $lengthNumber2 + 1, $lengthNumber1);
        $borderWidth = max($lengthNumber2 + 1, $lengthNumber1);
        fwrite($output, str_repeat(" ", $maxWidth - $lengthNumber1) . $number1 . PHP_EOL);
        fwrite($output, str_repeat(" ", $maxWidth - $lengthNumber2 - 1) . "+" . $number2 . PHP_EOL);
        fwrite($output, str_repeat(' ', $maxWidth - $borderWidth) . str_repeat("-", $borderWidth) . PHP_EOL);
        fwrite($output, str_repeat(" ", $maxWidth - strlen($result)) . $result);
    } else if ($operation == '-') {
        $result = strMinus($number1, $number2);
        $maxWidth = max(strlen($result), $lengthNumber2 + 1, $lengthNumber1);
        $borderWidth = max($lengthNumber2 + 1, strlen($result));
        fwrite($output, str_repeat(" ", $maxWidth - $lengthNumber1) . $number1 . PHP_EOL);
        fwrite($output, str_repeat(" ", $maxWidth - $lengthNumber2 - 1) . "-" . $number2 . PHP_EOL);
        fwrite($output, str_repeat(' ', $maxWidth - $borderWidth) . str_repeat("-", $borderWidth) . PHP_EOL);
        fwrite($output, str_repeat(" ", $maxWidth - strlen($result)) . $result);
    } else {
        //operation = '*';
        $results = [];
        for ($i = 0; $i < $lengthNumber2; $i++) {
            $next = 0;
            $result = '';
            if ($number2[$lengthNumber2 - 1 - $i] != 0) {
                for ($g = 0; $g < $lengthNumber1; $g++) {
                    $calculation = strval(intval($number1[$lengthNumber1 - 1 - $g]) * intval($number2[$lengthNumber2 - 1 - $i]) + $next);
                    if (strlen($calculation) > 1) {
                        $next = $calculation[0];
                    } else {
                        $next = 0;
                    }
                    $result = $calculation[strlen($calculation) - 1] . $result;
                }
                if ($next !== 0) {
                    $result = $next . $result;
                }
            } else {
                $result = '0';
            }
            $results[] = $result;
        }
        $finalResult = '0';
        $borderWidth = max($lengthNumber2 + 1, strlen($results[0]));
        if (count($results) > 1) {
            foreach ($results as $key => $result) {
                $finalResult = strSum($finalResult, $result . str_repeat('0', $key));
            }
            $maxWidth = max(strlen($finalResult), $lengthNumber1, $lengthNumber2 + 1);
            fwrite($output, str_repeat(' ', $maxWidth - $lengthNumber1) . $number1 . PHP_EOL);
            fwrite($output, str_repeat(' ', $maxWidth - $lengthNumber2 - 1) . '*' . $number2 . PHP_EOL);
            fwrite($output, str_repeat(' ', $maxWidth - $borderWidth) . str_repeat('-', $borderWidth) . PHP_EOL);
            foreach ($results as $k => $result) {
                fwrite($output, str_repeat(' ', $maxWidth - strlen($result) - $k) . $result . PHP_EOL);
            }
            fwrite($output, str_repeat(' ', $maxWidth - strlen($finalResult)) . str_repeat('-', strlen($finalResult)) . PHP_EOL);
            fwrite($output, str_repeat(' ', $maxWidth - strlen($finalResult)) . $finalResult);
        } else {
            $maxWidth = max(strlen($results[0]), $lengthNumber1, $lengthNumber2 + 1);
            fwrite($output, str_repeat(' ', $maxWidth - $lengthNumber1) . $number1 . PHP_EOL);
            fwrite($output, str_repeat(' ', $maxWidth - $lengthNumber2 - 1) . '*' . $number2 . PHP_EOL);
            fwrite($output, str_repeat(' ', $maxWidth - $borderWidth) . str_repeat('-', $borderWidth) . PHP_EOL);
            fwrite($output, $results[0]);
        }
    }
    if ($t < $testCases - 1) {
        fwrite($output, PHP_EOL . PHP_EOL);
    }
}