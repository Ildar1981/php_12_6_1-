<?php
require('const.php');

function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}


function getPartsFromFullname($name) {
    $a = ['surname', 'name', 'patronomyc'];
    $b = explode(' ', $name);
    return array_combine($a, $b);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    print_r(getPartsFromFullname($name)) . PHP_EOL;
    
}


function getShortName($name) {
    $arr = getPartsFromFullname($name);
    $firstName = $arr['name'];
    $surname = $arr['surname'];
    return $firstName . ' ' . mb_substr($surname, 0, 1) . '.';
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getShortName($name) . PHP_EOL;
}

function getGenderFromName($name) {
    $arr = getPartsFromFullname($name);
    $surname = $arr['surname'];
    $firstName = $arr['name'];
    $patronomyc = $arr['patronomyc'];
    $sumGender = 0;

    if (mb_substr($surname, -1, 1) === 'в') {
        $sumGender++;
    } elseif (mb_substr($surname, -2, 2) === 'ва') {
        $sumGender--;
    }
    
    if ((mb_substr($firstName, -1, 1) == 'й') || (mb_substr($firstName, -1, 1) == 'н')) {
        $sumGender++;
    } elseif (mb_substr($firstName, -1, 1) === 'а') {
        $sumGender--;
    }
   
    if ((mb_substr($patronomyc, -2, 2) === 'ич') || (mb_substr($patronomyc, -3, 3) === 'уса')){
        $sumGender++;
    } elseif (mb_substr($patronomyc, -3, 3) === 'вна') {
        $sumGender--;
    }

    return ($sumGender <=> 0);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getGenderFromName($name) . PHP_EOL . PHP_EOL;
}

function getGenderDescription($team) {

    $men = array_filter($team, function ($persons) {
        $fullname = $persons['fullname'];
        $genderMen = getGenderFromName($fullname);
        if ($genderMen > 0) {
            return $genderMen;
        }
    });

    $women = array_filter($team, function ($persons) {
        $fullname = $persons['fullname'];
        $genderWomen = getGenderFromName($fullname);
        if ($genderWomen < 0) {
            return $genderWomen;
        }
    });

    $unknown = array_filter($team, function ($persons) {
        $fullname = $persons['fullname'];
        $unknown = getGenderFromName($fullname);   
        if ($unknown == 0) {                      
            return $unknown;                  
        }
    });

   
    $mens = count($men);                       
    $womens = count($women);                   
    $unknowns = count($unknown);     
    $all = $mens + $womens + $unknowns;   

    $percentMen = round((100 / $all * $mens), 0);
    $percentWomen = round((100 / $all * $womens), 0);
    $percenFailedGender = round((100 / $all * $unknowns), 0);

    echo 'Гендерный состав аудитории:' . PHP_EOL;
    echo '---------------------------' . PHP_EOL;
    echo "Мужчины - $percentMen% " . PHP_EOL;
    echo "Женщины - $percentWomen% " . PHP_EOL;
    echo "Неудалось определить - $percenFailedGender%" . PHP_EOL;;
}
getGenderDescription($example_persons_array);

$surname = 'ИваНов';
$name = 'Иван';
$patronomyc = 'иванович';

function getPerfectPartner($surname, $name, $patronomyc, $persons) {

    $candidateFullName = getFullnameFromParts($surname, $name, $patronomyc); 
    $candidateShortName = getShortName($candidateFullName);                                   
    $candidateGender = getGenderFromName($candidateFullName);                        

    $allCandidate = count($persons);

    function selectRandomPersonWomen($allCandidate, $persons, $candidateGender) {
        $candidateRand = rand(0, $allCandidate - 4);
        $candidateFullNameRand = $persons[$candidateRand]['fullname'];
        $candidateFullNameRandGender = getGenderFromName($candidateFullNameRand);
        
        if ($candidateGender == $candidateFullNameRandGender && $candidateFullNameRandGender == 1) {
            return selectRandomPersonWomen($allCandidate, $persons, $candidateGender); // Вызываем функцию рекурсивно
        } else {
            return $candidateFullNameRand;
        }
    }
    
    // Использование функции
    $selectedPerson = selectRandomPersonWomen($allCandidate, $persons, $candidateGender);

    $shortNameSelectedWomen = getShortName($selectedPerson);   
    $percentPerfect = rand(1000, 10000) / 100;                  
    
    echo "$candidateShortName + $shortNameSelectedWomen = " . PHP_EOL;;
    echo "♡ Идеально на $percentPerfect% ♡";
}
getPerfectPartner($surname, $name, $patronomyc, $example_persons_array);




