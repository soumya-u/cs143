<?php
# get the id parameter from the request
$id = intval($_GET['id']);

# set the Content-Type header to JSON, so that the client knows that we are returning a JSON data
header('Content-Type: application/json');




$db = new mysqli("localhost", "cs143", "", "cs143");
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}



$query1="SELECT *
        FROM Person P
        WHERE id='$id';";

$query2="SELECT *
        FROM Organization O
        WHERE id='$id';";

$result1=$db->query($query1);

$arrayPerson = array();
$arrayOrg = array();
$arrayWinners = array();
$arrayPrizes = array();
$arrayAff = array();

// person
if (mysqli_num_rows($result1) > 0){
    while ($row = $result1->fetch_assoc()){
        $givenName = $row["givenName"];
        $familyName = $row["familyName"];
        $gender = $row["gender"];
        $birthdate = $row["birthdate"];
        $city = $row["city"];
        $country = $row["country"];

        $arrayPerson = array(
            "id" => $id, 
            "givenName" => 
                array ("en" => $givenName),
            "familyName" => 
                array ("en" => $familyName),
            "gender" => $gender, 
            "birth" => 
                array ("date" => $birthdate,
                        "place" =>
                            array("city" => $city,
                                  "country" => $country)
                      )
        );

        // $json = json_encode($arrayPerson, JSON_PRETTY_PRINT); 
        // echo($json);
    }
}



// organization
else{
    $result2=$db->query($query2);

    if (mysqli_num_rows($result2) > 0){
        while ($row = $result2->fetch_assoc()){
            $orgName = $row["orgName"];
            $foundeddate = $row["foundeddate"];
            $city = $row["city"];
            $country = $row["birthdate"];

            $arrayOrg = array(
                "id" => $id, 
                "orgName" => 
                    array ("en" => $orgName),
                "founded" => 
                    array ("date" => $foundeddate,
                            "place" =>
                                array("city" => $city,
                                      "country" => $country)
                          )
            );

            // $json = json_encode($arrayOrg, JSON_PRETTY_PRINT); 
            // echo($json);
        }   
    }
}

// winners
$query3 = "SELECT *
           FROM Winners W
           WHERE id='$id';";

$result3=$db->query($query3);

$awardYear = "";
$category = "";
$sortOrder = "";
$portion = "";
$awardYear = "";
$prizeStatus = "";
$motivation = "";
$dateAwarded = "";
$prizeAmount = "";
$affiliations = [];
$nobelPrizes = [];

//winners for people
if (mysqli_num_rows($result3) > 0 && mysqli_num_rows($result1) > 0){
    while ($row = $result3->fetch_assoc()){
        $awardYear = intval($row["awardYear"]);
        $category = $row["category"];
        $sortOrder = intval($row["awardYear"]);
        $portion = $row["portion"];
        $prizeStatus = $row["prizeStatus"];
        $motivation = $row["motivation"];

        $temp = array();

        $query5 = "SELECT *
        FROM Prizes P, Winners W
        WHERE id='$id' AND P.awardYear=W.awardYear AND P.category=W.category;";
    
        $result5=$db->query($query5);
        $anothertemp = array();
        if (mysqli_num_rows($result5) > 0){
            while ($row = $result5->fetch_assoc()){
                // echo "hiff";
                $dateAwarded = $row["dateAwarded"];
                $prizeAmount = $row["prizeAmount"];
    
                $anothertemp = array(
                    "dateAwarded" => $dateAwarded,
                    "prizeAmount" => $prizeAmount 
                );

                if (substr($dateAwarded, 0, 4) == $awardYear){
                    $temp["dateAwarded"] = $dateAwarded;
                    $temp["prizeAmount"] = $prizeAmount;
                }
            }
            // array_push($arrayWinners, $temp);
            
        }
    

        $query4 = "SELECT *
                FROM Affiliations 
                WHERE id='$id' AND category='$category' AND awardYear='$awardYear';";
     
        $result4=$db->query($query4);
        
        $tempaff = array();
        if (mysqli_num_rows($result4) > 0){
            while ($row = $result4->fetch_assoc()){
                $aff_name = $row["aff_name"];
                $aff_city = $row["aff_city"];
                $aff_country = $row["aff_country"];
                
                $affiliation = (object) [
                    "name" => 
                        array ("en" => $aff_name),
                    "city" => 
                        array ("en" => $aff_city),
                    "country" => 
                        array ("en" => $aff_country)
                ];
                
                array_push($tempaff,$affiliation);
            }
        }

        $temp = array(
            "awardYear" => $awardYear, 
            "category" => 
                array ("en" => $category),
            "sortOrder" => $sortOrder,
            "portion" => $portion,
            "motivation" => 
                array ("en" => $motivation),
            "dateAwarded" => $dateAwarded,
            "prizeAmount" => $prizeAmount, 
            "affiliations" => $tempaff
        );

        array_push($arrayWinners, $temp);

    }   
    $nobelPrizes = array (
        "nobelPrizes" => $arrayWinners
    );
    // $json2 = json_encode($nobelPrizes, JSON_PRETTY_PRINT); 
    // echo($json2);
}


// winners for organizations (no affiliations)
if (mysqli_num_rows($result3) > 0 && mysqli_num_rows($result2) > 0){
    while ($row = $result3->fetch_assoc()){
        $awardYear = intval($row["awardYear"]);
        $category = $row["category"];
        $sortOrder = intval($row["awardYear"]);
        $portion = $row["portion"];
        $prizeStatus = $row["prizeStatus"];
        $motivation = $row["motivation"];
        $aff_name = $row["aff_name"];
        $aff_city = $row["aff_city"];
        $aff_country = $row["aff_country"];

        $arrayOrg = array(
            "awardYear" => $awardYear, 
            "category" => 
                array ("en" => $category),
            "sortOrder" => $sortOrder,
            "portion" => $portion,
            "motivation" => 
                array ("en" => $motivation)
        );
        
        array_push($arrayWinners, $arrayOrg);
    }   
    $nobelPrizes =  array (
        "nobelPrizes" => $arrayWinners
    );
    // $json3 = json_encode($nobelPrizes, JSON_PRETTY_PRINT); 
    // echo($json3);
}

$result = array_merge($arrayPerson, $arrayOrg, $nobelPrizes);
$json3 = json_encode($result, JSON_PRETTY_PRINT); 
echo($json3);

$result1->free();
$result2->free();
$result3->free();
$result4->free();
$result5->free();

?>