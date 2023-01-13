<?php
$input = fopen('input', 'r');
$output = fopen('output', 'w');

$winCombination = [
    [[0, 0], [1, 0], [2, 0]],
    [[0, 1], [1, 1], [2, 1]],
    [[0, 2], [1, 2], [2, 2]],
    [[0, 0], [0, 1], [0, 2]],
    [[1, 0], [1, 1], [1, 2]],
    [[2, 0], [2, 1], [2, 2]],
    [[0, 0], [1, 1], [2, 2]],
    [[0, 2], [1, 1], [2, 0]]
];


fscanf($input, "%d", $testCount);

for ($t = 0; $t < $testCount; $t++) {
    $field = [];
    $xCount = 0;
    $oCount = 0;
    fscanf($input, "%s\n", $field[]);
    fscanf($input, "%s\n", $field[]);
    fscanf($input, "%s\n", $field[]);
    foreach ($field as &$row) {
        $row = str_split($row);
        for ($i = 0; $i < count($row); $i++) {
            if ($row[$i] == 'X') $xCount++;
            if ($row[$i] == 'O') $oCount++;
        }
    }
    if ($xCount >= 0 && $xCount < 3 && ($xCount == $oCount + 1 || $xCount == $oCount)) {
        fwrite($output, 'yes');
    } else if ($xCount > 5 || $oCount > 4 || $oCount>$xCount) {
        fwrite($output, 'no');
    } else if($xCount == $oCount + 1 || $xCount == $oCount){
        $combinationCount = 0;
        $combinationSymbol = [];
        foreach ($winCombination as $combination) {
            $symbol = '';
            $counter = 0;
            foreach ($combination as $coordinate) {
                $x = $coordinate[0];
                $y = $coordinate[1];

                if ($field[$y][$x] != '.') {
                    if ($symbol === '') {
                        $symbol = $field[$y][$x];
                    } else if ($symbol != $field[$y][$x]) {
                        break;
                    }
                } else {
                    break;
                }
                $counter++;
            }
            if ($counter == 3) {
                $combinationCount++;
                if ($combinationCount > 2) {
                    break;
                }
                $combinationSymbol[] = $symbol;
            }

        }
        if($combinationCount>0) {
            if($combinationCount == 2 && $combinationSymbol[0] == $combinationSymbol[1]) {
                fwrite($output, 'yes');
            } else if($combinationCount == 1 && (($combinationSymbol[0] == 'X' && $xCount > $oCount) || ($oCount == $xCount && $combinationSymbol[0] == 'O')))
            {
                fwrite($output, 'yes');
            } else {
                fwrite($output, 'no');
            }
        } else {
            fwrite($output, 'yes');
        }
    } else {
        fwrite($output, 'no');
    }
    fwrite($output, PHP_EOL);
    if ($t < $testCount - 1) {
        fscanf($input, '%s', $empty);

    }
}

