<?php
unset($argv[0]); /* enlève le nom du script et garde toute les entrées */

foreach ($argv as $key => $value) { /* Verification des arguments si fichier valide et existant*/
    if (!file_exists($value)) {
        echo "Veuillez exécuter le programme avec un/des fichier(s) existant\n";
    } else if (!str_pos($value, "tar")) {
        echo "Veuillez entrer un/des fichier(s) valide tarball\n";
    } else {
        echo "extraction... \n";
        /*Exécuter la fonction ici*/
    }
}

function choix()
{
    echo "1. Écraser \n2. Ne pas écraser\n3. Écraser pour tous (ne plus redemander)\n4. Ne pas écraser pour tous (ne plus redemander)\n5. Arrêter et quitter\n choisissez une option (1/2..): ";
    a:
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    /* $line = argument a effectuer */

    switch ($line) {
        case 1:
            /* function dans le cas 1*/
            echo "f1";
            break;
        case 2:
            /* function dans le cas 2*/
            echo "f2";
            break;
        case 3:
            /* function dans le cas 3 donnez une variable ou executer une fonction qui ecrasera tout les fichier sans redemander*/
            echo "f3";
            break;
        case 4:
            /* function dans le cas 4 donnez une variable ou executer une fonction qui n'ecrasera pas les fichier existant sans redemander*/
            echo "f4";
            break;
        case 5:
            /* function dans le cas 5*/
            echo "Décompression arrêter";
            break;
        default:
            echo "Entrez une option : ";
            goto a;
            break;
    }
}