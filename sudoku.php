<?php
/**
 * Grille de sudoku
 *
 * @package Actualite
 * @author  Corin ALEXANDRU <corin.alex@gmail.com>
 *
 */

/*
 * TODO
 * Afficher une version sans la solution
 *
 * Respecter les vraies règles du Sudoku
 * (Un chiffre doit aussi être unique par bloc)
 */

$rows = array();
$cols = array();

$rowTries = 0;
$tries = 0;

/**
 * Génère la première ligne de façon aléatoire
 */
function generateFirstRow() {
    global $rows, $cols;

    $rows = array();
    $cols = array();

    $row = array(1,2,3,4,5,6,7,8,9);
    shuffle($row);

    for ($i = 0; $i < 9; $i++) {
        $cols[] = array($row[$i]);
    }

    $rows[] = $row;
}

/**
 * Génère une ligne en essayant d'avoir un chiffre unique par colone
 * Si plus de 100 essais, on abandone
 * @return bool
 */
function generateRow() {
    global $rows, $cols;
    $row = array();
    $tempCols = $cols;
    $tries = 0;
    for ($i = 0; $i < 9; $i++) {
        do {
            if ($tries > 100) return false;
            $random = mt_rand(1,9);
            $tries ++;
        }
        while (in_array($random, $row) or in_array($random, $tempCols[$i]));

        $row[] = $random;
        $tempCols[$i][] = $random;
    }

    $rows[] = $row;
    $cols = $tempCols;
    return true;
}

/**
 * Essaye de generer une ligne.
 * Si plus de 100 essais, on abandone
 * @return bool
 */
function createRow() {
    global $rowTries;

    if ($rowTries > 100) return false;
    $rowTries++;

    if (!generateRow()) {
        createRow();
    }

    return true;
}

/**
 * Génération du sudoku
 * Si une ligne est impossible à générer, on regenère le sudoku
 * Apres 100 essais on abandonne.
 */
function generateSudoku() {
    global $tries;
    generateFirstRow();

    for ($i = 0; $i < 8; $i++) {
        if (!createRow()) {
            $tries++;
            if ($tries > 100) {
                exit("Echec! \n\n");
            }
            createRow();
        }
    }
}

/**
 * Affichage
 */
function render() {
    global $rows;

    generateSudoku();
    $rowCount = 1;
    $colCount = 1;
    echo "\n   -   -   -    -   -   -    -   -   -    \n";
    foreach ($rows as $r) {
        echo ' | ';
        for ($i = 0; $i < 9; $i++) {
            echo $r[$i];
            echo ($colCount == 3 or $colCount == 6)  ? " || " :" | ";
            $colCount++;
        }
        if ($rowCount == 3) {
            echo "\n   -   -   -    -   -   -    -   -   -    ";
            $rowCount = 0;
        }
        echo "\n";
        $rowCount++;
        $colCount = 1;
    }
    echo "\n";
}
render();