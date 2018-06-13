<?php
require 'resources/includes/db_conn.php';

$message = '';

// Funktion som modifierar strängar så att tecken som inte tillhör ASCII samt mellanslag byts ut.
// Funktionen skulle kunna placeras i ett bibliotek för att sedan inkluderas vid behov.
function slugify($slug, $strict = false) {
    $slug = html_entity_decode($slug, ENT_QUOTES, 'UTF-8');
    // replace non letter or digits by -
    $slug = preg_replace('~[^\\pL\d.]+~u', '_', $slug);

    // trim
    $slug = trim($slug, '_');
    setlocale(LC_CTYPE, 'en_GB.utf8');
    // transliterate
    if (function_exists('iconv')) {
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    }
    // lowercase
    $slug = strtolower($slug);
    // remove unwanted characters
    $slug = preg_replace('~[^-\w.]+~', '', $slug);
    if (empty($slug)) {
        return 'empty_$';
    }
    if ($strict) {
        $slug = str_replace(".", "_", $slug);
    }
    return $slug;
}

if ($pdo) {

    // Ser ut som ett rätt enkelt SELECT kommando där du selectar ID och Username från tabell "Users" och sedan sorterar efter Username. Självklarligen lägger den till detta i variabeln $sql
    // Här lägger den till saker till $sql och lägger till $users till array.
    // den tar också och gör foreach kommando med variabeln $pdo, $sql och förlänger $pdo med en funktion.
    $sql = 'SELECT ID, Username FROM Users ORDER BY Username';
    $users = array();
    foreach ($pdo->query($sql) as $row) {
        $users += array(
            $row['ID'] => $row['Username']
        );
    }

    /**********************************************************/
    /*********************** C-UPPGIFT 3 **********************/
    /* Våra variabler $headline & $text tar emot information **/
    /** utan att kontrollera informationen före den skickas ***/
    /******************* till vår databas. ********************/
    /** För betyget C så kräver jag att ni säkerställer att ***/
    /** våra användare inte kan skicka med någon skadlig kod **/
    /********** genom variablerna $headline & $text. **********/
    /**********************************************************/

    // Här är det en IF fråga och isset. Så det frågar alltså om följande är set så gör följande
    if (isset($_POST['submit'])) {
        $user = $_POST['author']; // Variabeln $_POST blir tillagd i $user (vem som skrev inlägget blir tillagd)
        $headline = $_POST['title']; //Det som är titeln på inlägget blir sparat i $headline
        $headline = trim($headline);

        $slug = slugify($headline); //Här förkortar (slugify'ar) den headlinen så att en headline som är vardags middag blir till vardags_middag vilket är enklare för datorn att läsa

        $text = $_POST['message']; //Här gör den precis samma som author och title men med text. Den sparar alltså texten som posten innehåller till variabeln $text

        $sql = 'INSERT INTO Posts (User_ID, Slug, Headline, Text) VALUES ("'.$user.'", "'.$slug.'", "'.$headline.'", "'.$text.'")';

        /**********************************************************/
        /*********************** E-UPPGIFT 2 **********************/
        /* Variabeln $sql innehåller nu en query som kan användas */
        /* för att spara inlägget användaren skrivit i databasen. */
        /** För betyget E så krävs det att ni skriver en kod som **/
        /** använder variabeln $sql för att skicka inlägget till **/
        /* databasen. Tänk på att namn på tabell & kolumner i er **/
        /* databas kan skiljas något från det jag angivit i $sql. */
        /**********************************************************/
        if($pdo->query($sql)) {
            $message = 'Du har lyckats lägga upp ett inlägg';
        }

        else {
            $message = 'Du har inte lyckats lägga upp ett inlägg';
        }

    }
}
else {
    print_r($pdo->errorInfo());
}

 ?>
