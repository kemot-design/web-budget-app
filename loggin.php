<?PHP

    session_start();

    if(isset($_POST['userlogin']) == false || isset($_POST['userpassword']) == false){
        header('Location: logpage.php');
        exit();
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try{
        $dbConnection = new mysqli($host, $db_user, $db_password, $db_name);
        
        if($dbConnection->connect_errno != 0){
            throw new Exception(mysqli_connect_errno());
        }
        else{
            $login = $_POST['userlogin'];
            $password = $_POST['userpassword'];

            $login = htmlentities($login, ENT_QUOTES, "UTF-8");
            
            if($queryResult = $dbConnection->query(sprintf("SELECT id, password FROM users WHERE username = '%s'", mysqli_real_escape_string($dbConnection, $login)))){
                
                 $recordsNum = $queryResult->num_rows;
                
                if($recordsNum > 0){
                    
                    $dataRow = $queryResult->fetch_assoc();
                    
                    if(password_verify($password, $dataRow['password'])){
                        $_SESSION['login_success'] = true;
                        $_SESSION['user_id'] = $dataRow['id'];
                        $_SESSION['user_name'] = $dataRow['username'];
                        $_SESSION['user_email'] = $dataRow['email'];
                        
                        $queryResult->free_result();
                        unset($_SESSION['error_login']);
                        header('Location: mainmenu.php');
                    }
                    else{
                        $_SESSION['error_login'] = "Nieprawidłowy login lub hasło!";
                        header('Location: logpage.php');
                    }
                       
                }
                else{
                    $_SESSION['error_login'] = "Nieprawidłowy login lub hasło!";
                    header('Location: logpage.php');
                }
                
            }
            else{
                throw new Exception($dbConnection->error);
            }
            
           $dbConnection->close(); 
        }
        
        
    }
    catch(Exception $e){
        echo "Błąd serwera, prosze o zalogowanie się w późniejszym terminie <br/>";
        echo "Info: ".$e."<br/>";
    }

        
?>