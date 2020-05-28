<?php
include_once (__DIR__).'/../api/utilities/db.php';

$database = new Database();
$conn = $database->getConnection();
$short_log_table_name = 'Log_Weekly';
$long_log_table_name = 'Log_Compiled';

$weekId = date('Y-W');
$pageViews = 0; // NYI
$uniques = 0;
$clicks = 0;
$curls = 0;
$creatureAdds = 0;
$creatureRemoves = 0;

$query = "SELECT COUNT(DISTINCT ip)
    FROM ".$short_log_table_name;
$stmt = $conn->prepare($query);
$stmt->execute();

$uniques = $stmt->fetchColumn();

$query = "SELECT * FROM ".$short_log_table_name;
$stmt = $conn->prepare($query);
$stmt->execute();

if($stmt->rowCount()>0) { 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $action = $row['action'];

        if($action=='click') {$clicks += $row['count'];} 
        else if($action=='curl') {$curls += $row['count'];} 
        else if($action=='creatureAdd') {$creatureAdds += $row['count'];} 
        else if($action=='creatureRemove') {$creatureRemoves += $row['count'];}
    }

    
    $query = "INSERT INTO ".$long_log_table_name." SET 
        weekId=:weekId, pageViews=:pageViews, uniques=:uniques, clicks=:clicks, curls=:curls, creatureAdds=:creatureAdds, creatureRemoves=:creatureRemoves";
    $stmt = $conn->prepare($query);

    // bind values
    $stmt->bindParam(":weekId", $weekId);
    $stmt->bindParam(":pageViews", $pageViews);
    $stmt->bindParam(":uniques", $uniques);
    $stmt->bindParam(":clicks", $clicks);
    $stmt->bindParam(":curls", $curls);
    $stmt->bindParam(":creatureAdds", $creatureAdds);
    $stmt->bindParam(":creatureRemoves", $creatureRemoves);

    $stmt->execute();

    $query = "TRUNCATE TABLE ".$short_log_table_name;
    $stmt = $conn->prepare($query);
    $stmt->execute();
}