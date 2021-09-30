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

        $json = json_encode($arrayPerson, JSON_PRETTY_PRINT); 
        echo($json);
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

            $json = json_encode($arrayOrg, JSON_PRETTY_PRINT); 
            echo($json);
        }   
    }
}

// $result->free();

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
            "affiliations" => $affiliations
        );

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
            array_push($arrayWinners, $temp);
            
        }
    

        $query4 = "SELECT *
                FROM Affiliations A, Prizes P
                WHERE id='$id' AND P.dateAwarded=A.dateAwarded AND P.prizeAmount=A.prizeAmount AND P.awardYear='$category';";
     
        $result4=$db->query($query4);

        if (mysqli_num_rows($result4) > 0){
            while ($row = $result4->fetch_assoc()){
                        
                $tempaff = array();
                $aff_name = $row["aff_name"];
                $aff_city = $row["aff_city"];
                $aff_country = $row["aff_country"];

                
                
                $tempaff = array(
                    "name" => 
                        array ("en" => $aff_name),
                    "city" => 
                        array ("en" => $aff_city),
                    "country" => 
                        array ("en" => $aff_country)
                );

                
                $temp['affiliations'] = $tempaff;

                array_push($temp, $tempaff);

                // array_push($arrayWinners, $temp);
            }
            // echo "hi";
            // print_r($tempaff[0]);
            // echo "bye";

            array_push($arrayWinners, $temp);

        }

    }   
}
$json2 = json_encode($arrayWinners, JSON_PRETTY_PRINT); 
echo($json2);

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

        $json3 = json_encode($arrayOrg, JSON_PRETTY_PRINT); 
   
        // Display the output 
        echo($json3);
    }   
}

?>
