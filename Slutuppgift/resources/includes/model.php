<?php
require 'resources/includes/db_conn.php'; // Den require 'resources/includes/db_conn.php' alltså kräver att ha detta annars kommer det förmodligen inte fungera. Eller iallafall kommer den inte kunna använda db_conn vilket kan göra så det inte funkar. Du kan också använda dig av include för att säga till datorn att den får använda sig av en annan fil men måste inte göra det.

if ($pdo) {

    // Först så börjar datorn med att läsa det som är inne i en parantes så den börjar med
    // (U.Firstname, " ", U.Lastname)
    // Sedan läser den in allt annat och bestämmer att Posts kan förkortas P som vi ser används
    // i P.ID och P.Headline.
    // Den lägger också till resultatet i variabeln $sql så att man inte behöver skriva hela kommandot igen utan kan istället bara skriva $sql
    $sql = 'SELECT P.ID, P.Slug, P.Headline, CONCAT(U.Firstname, " ", U.Lastname) AS Name, P.Creation_time, P.Text FROM Posts AS P JOIN Users AS U ON U.ID = P.User_ID ORDER BY P.Creation_time DESC';

    // kommandot "isset" kollar om en variable är set eller inte.
    // vilket betyder att den kollar om något är ned tyckt eller om något funkar (om något är set)
    if (isset($_POST['search'])) {
        // Här tar den en variable är lika med en annan variable och kör kommandot 'what'
        // Den tar också och lägger till $_POST i $data
        $data = $_POST['what'];

        /**********************************************************/
        /*********************** C-UPPGIFT 1 **********************/
        /*** Variabeln $data kan innehålla, som den är utformad, **/
        /********* information som kan skada vår databas. *********/
        /*** För betyget C så kräver jag att ni säkerställer att **/
        /***** $data inte innehåller någon form av skadlig kod ****/
        /**********************************************************/

        /**********************************************************/
        /*********************** C-UPPGIFT 2 **********************/
        /* I filen all-posts.php så skrivs det ut en kortare text */
        /* följt av en länk till berört inlägg. Vore det inte mer */
        /* passande att det istället skrivs ut ord från inlägget? */
        /* För betyget C så kräver jag att ni tar fram en lösning */
        /**** där 10 ord från inlägget skrivs ut före läs mer. ****/
        /************ Tänk implode och/eller explode! *************/
        /**********************************************************/

        /**********************************************************/
        /************************ A-UPPGIFT ***********************/
        /** Som det är just nu så tar vi bara in en variabel som **/
        /******* vi använder när vi söker igenom databasen. *******/
        /* Tittar man på sidor som exempelvis google och facebook */
        /**** så kan din sökning oftast innehålla flera sökord ****/
        /* För betyget A så kräver jag att ni tar fram en lösning */
        /** som gör det möjligt för användaren att kunna söka på **/
        /** flera separerade ord. Att man exempelvis kan söka på **/
        /***** texter som innehåller både "Lorum" och "Ipsum." ****/
        /**********************************************************/

        // Detta är ett IF kommando vilket betyder "om" på svenska. alltså med andra ord så frågar du om detta stämmer så gör detta. Du kan också ta och lägga till "else" kommandon vilket gör en effekt om iffet inte stämmer.
        // Sedan efter IF kommandot så säger den !empty alltså om inte empty (tom) Kör $sql data
        if (!empty($data)) {
            $sql = 'SELECT p.ID, p.Slug, p.Headline, CONCAT(u.Firstname, " ", u.Lastname) AS Name, p.Creation_time, p.Text FROM Posts AS p JOIN Users AS u ON U.ID = P.User_ID WHERE p.Text LIKE "%'.$data.'%" ORDER BY P.Creation_time DESC';
        }
    }

    // Här så speciferar den att $model ska läggas till i array
    $model = array();
    foreach($pdo->query($sql) as $row) {
        // Här ser vi att den tar variabeln $model kör samma funktion som $model = $model + array
        // Sedan ser vi att den tar och använder sig av operator => vilket betyder lika med eller mer. (tror jag)
        $model += array(
            $row['ID'] => array(
                'slug' => $row['Slug'],
                'title' => $row['Headline'],
                'author' => $row['Name'],
                'date' => $row['Creation_time'],
                'text' => $row['Text']
            )
        );
    }
}
else {
    print_r($pdo->errorInfo());
}
?>
