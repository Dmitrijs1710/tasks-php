<?php
$testCount = (int)readline();
for ($t = 0; $t < $testCount; $t++) {
    $wordsCount = (int)readline();
    $words = [];
    $letters = [];
    $list = [];
    $set = [];
    for ($i = 0; $i < $wordsCount; $i++) {
        $word = strtolower(trim(readline()));
        $start = $word[0];
        $end = $word[strlen($word) - 1];
        if ($start !== $end) {
            $words[] = $start . $end;
            if (empty($letters[$start])) {
                $letters[$start] = 0;
            }
            if (empty($letters[$end])) {
                $letters[$end] = 0;
            }
            $letters[$start]++;
            $letters[$end]--;
            if ($letters[$start] === 0) {
                unset($letters[$start]);
            }
            if ($letters[$end] === 0) {
                unset($letters[$end]);
            }
            $containFirst = in_array($start, $set);
            $containSecond = in_array($end, $set);
            $set[] = $start;
            $set[] = $end;
            if (!$containFirst && !$containSecond) {
                $list[] = [$start, $end];
            } else if (!$containFirst || !$containSecond) {
                foreach ($list as &$s) {
                    if (in_array($end, $s)) {
                        $s[] = $start;
                    }
                    if (in_array($start, $s)) {
                        $s[] = $end;
                    }
                }
            } else {
                $first = array_filter($list, function($s) use ($start) {
                    return in_array($start, $s);
                });
                $first = array_values($first)[0];
                $last = array_filter($list, function($s) use ($end) {
                    return in_array($end, $s);
                });
                $last = array_values($last)[0];
                $result = array_merge($first, $last);
                $result = array_unique($result);
                $key1 = array_search($first, $list);
                $key2 = array_search($last, $list);
                unset($list[$key1]);
                unset($list[$key2]);
                $list[] = $result;
            }
        }
    }
    $startNext = $words[0][0];
    $endNext = $words[0][1];
    unset($words[0]);
    $startCheck = (count($letters)==0);
    $endCheck = (count($letters)==0);
    if (count($letters) == 2) {
        foreach ($letters as $letter => $count) {
            if ($count == 1 && !$startCheck) {
                $startCheck = true;
            } else if ($count == -1 && !$endCheck) {
                $endCheck = true;
            } else {
                $startCheck = false;
                $endCheck = false;
                break;
            }
        }
    }
    if ($startCheck && $endCheck && count($list) == 1) {
        echo("Ordering is possible.");
    } else {
        echo("The door cannot be opened.");
    }
    if($t<$testCount-1){
        echo PHP_EOL;
    }
}
