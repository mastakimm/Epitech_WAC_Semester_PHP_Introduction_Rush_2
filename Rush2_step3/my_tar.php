<?php

// Nom de la tarball de sortie
$outputFilename = 'output.mytar';

// Ouvrir la tarball en écriture (écrase le fichier s'il existe déjà)
$tar = fopen($outputFilename, 'w');

// Parcourir les arguments de la ligne de commande
for ($i = 1; $i < $argc; $i++) {
    $input = $argv[$i];
    if (is_dir($input)) {
        // Si l'argument est un répertoire, ajoutez tous les fichiers et sous-répertoires à la tarball
        addFilesInDirectory($tar, $input);
    } elseif (is_file($input)) {
        // Si l'argument est un fichier, l'ajoute à la tarball
        addFile($tar, $input);
    } else {
        echo "Erreur: '$input' n'est ni un fichier ni un répertoire valide.\n";
    }
}

// Fermer la tarball
fclose($tar);

echo "Tarball créée avec succès dans '$outputFilename'.\n";

// Fonction pour ajouter un fichier à la tarball
function addFile($tar, $file)
{
    $fileSize = filesize($file);
    $header = pack("a100a8a8a8a12a12", basename($file), "777", "777", decoct(fileperms($file)), decoct($fileSize), decoct(filemtime($file)));
    $compressedHeader = compress($header);

    // Écrire le header compressé dans le .tar
    foreach ($compressedHeader as $code) {
        $byte1 = $code >> 8;
        $byte2 = $code & 0xFF;
        fwrite($tar, pack("CC", $byte1, $byte2));
    }

    $fileContent = file_get_contents($file);
    $compressedContent = compress($fileContent);

    // Écrire les données compressées dans le .tar
    foreach ($compressedContent as $code) {
        $byte1 = $code >> 8;
        $byte2 = $code & 0xFF;
        fwrite($tar, pack("CC", $byte1, $byte2));
    }

    // Remplir le reste du bloc avec des zéros pour s'assurer qu'il a la taille correcte
    //$blockSize = ceil(strlen($fileContent) / 256) * 256 - strlen($fileContent);
    //fwrite($tar, str_repeat("\0", $blockSize));
}

// Fonction pour ajouter tous les fichiers d'un répertoire à la tarball (récursivement)
function addFilesInDirectory($tar, $dir)
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                addFilesInDirectory($tar, $path);
            } elseif (is_file($path)) {
                addFile($tar, $path); // Appeler addFile pour compresser et écrire le contenu du fichier
            }
        }
    }
}

function compress($texte)
{
    $dictionnaire = array_combine(range("\0", "\xFF"), range("\0", "\xFF")); // Dictionnaire initial avec les caractères ASCII

    $w = '';
    $resultat = [];
    $code = 256;

    for ($i = 0; $i < strlen($texte); $i++) {
        $c = $texte[$i];
        $wc = $w . $c;

        if (array_key_exists($wc, $dictionnaire)) {
            $w = $wc;
        } else {
            $resultat[] = ord($dictionnaire[$w]); // Utilisation du code ASCII du caractère

            // Ajoute le nouveau motif dans le dictionnaire
            $dictionnaire[$wc] = chr($code);
            $code++;

            $w = $c;
        }
    }

    if ($w !== '') {
        $resultat[] = ord($dictionnaire[$w]); // Utilisation du code ASCII du caractère
    }

    return $resultat;
}