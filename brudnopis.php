<?PHP

    session_start();

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try{
        $dbConnection = new mysqli($host, $db_user, $db_password, $db_name);
        
        if($dbConnection->connect_errno != 0){
            throw new Exception(mysqli_connect_errno());
        }
        else{
            
            $query = "SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = 11";
            $result = $dbConnection->query($query);
            
            while($row = $result->fetch_assoc()){
                $rows[] = $row;
            }
            foreach($rows as $row){
                echo $row['id']." and ".$row['name']."<br/>";
            }
            
            
            
            
        }
    }
    catch(Exception $e){
        echo "Błąd serwera, prosze o zalogowanie się w późniejszym terminie <br/>";
        echo "Info: ".$e."<br/>";
    }
        
        
        
?>