<?PHP

    session_start();

    if(isset($_POST['userlogin'])){
    
        $allValid = true;
        
        //login validate
        $login = $_POST['userlogin'];
        
        if(strlen($login) < 3 || strlen($login) > 20){
            $_SESSION['er_login'] = 'Login musi składać się z od 3 do 20 znaków';
            $allValid = false;
        }
        
        if(ctype_alnum($login) == false){
            $_SESSION['er_login'] = '<div class="error"> Login może sładać się wyłącznie z liter i liczb </div>';
            $allValid = false;
        }
        
        //email validate
        $email = $_POST['useremail'];
		$emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) == false) || ($emailSanitized != $email))
		{
			$allValid = false;
			$_SESSION['er_email']="Podaj poprawny adres e-mail!";
		}
        
        //password validation
        $password = $_POST['user-password'];
        
        if(strlen($password) < 8 || strlen($password) > 20){
            $allValid = false;
            $_SESSION['er_pass'] = "Hasło musi mieć od 8 do 20 znaków!";
        }
        
        $passwordConfirmation = $_POST['confirmation-password'];
        if($password != $passwordConfirmation){
            $allValid = false;
            $_SESSION['er_pass'] = "Podane hasła nie są identyczne";
        }
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        //connect to data base and check if  login and email is unique      
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        try{
            
            $dbConnection = new mysqli($host, $db_user, $db_password, $db_name);
            
            if($dbConnection->connect_errno != 0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                
                //connection is ok so check login and email uniqueness
                //login uniqueness
                $queryResult = $dbConnection->query("SELECT id FROM users WHERE username = '$login'");
                
                if($queryResult == false){
                    throw new Exception($dbConnection->error);
                }
                else{
                    $recordsNum = $queryResult->num_rows;
                    
                    if($recordsNum > 0){
                        $allValid = false;
                        $_SESSION['er_login'] = "Istnieje już użytkownik o takiej nazwie!";
                    }
                    
                }
                
                //email uniqueness
                $queryResult = $dbConnection->query("SELECT id FROM users WHERE email = '$email'");
                
                if($queryResult == false){
                    throw new Exception($dbConnection->error);
                }
                else{
                    $recordNum = $queryResult->num_rows;
                    
                    if($recordNum > 0){
                        $allValid = false;
                        $_SESSION['er_email'] = "Podany adres email istnieje już w serwisie";
                    }
                }
                
                //we add new user if allValid is true
                if($allValid){
                    if($dbConnection->query("INSERT INTO users VALUES(NULL,'$login','$passwordHash','$email')")){
                        $_SESSION['registrationSuccess'] = true;
                        
                        //we copy default income, expense and payment methods categories from default tables to users assigned tables
                        $queryResult = $dbConnection->query("SELECT id FROM users WHERE email = '$email'");
                        
                        if($queryResult == false){
                            throw new Exception($dbConnection->error);
                        }
                        else{
                            $resultRow = $queryResult->fetch_assoc();
                            $new_user_id = $resultRow['id'];
                            
                            if(!$dbConnection->query("INSERT INTO expenses_category_assigned_to_users (user_id, name)
                            SELECT $new_user_id, name FROM expenses_category_default")){                            
                                throw new Exception($dbConnection->error);
                            }  
                            
                            if(!$dbConnection->query("INSERT INTO incomes_category_assigned_to_users (user_id, name)
                            SELECT $new_user_id, name FROM incomes_category_default")){
                                throw new Exception($dbConnection->error);
                            }
                            
                            if(!$dbConnection->query("INSERT INTO payment_methods_assigned_to_users (user_id, name)
                            SELECT $new_user_id, name FROM payment_methods_default")){
                                throw new Exception($dbConnection->error);
                            }
                            
                        }
                        
                        header('Location: welcome.php');
                    }
                    else{
                        throw new Exception($dbConnection->error);
                    }
                    
                }
                
            }
            
        }
        
        catch(Exception $e){
            echo "Błąd połączenia z serwerem, prosze o rejestracja w innym terminie. <br/> Przepraszamy za utrudnienia.<br/><br/>";
            echo "Info o błędie: <br/>".$e;
        }
        
    }
 
?>

<!DOCTYPE html>
<html lang="pl">

<head>
		
    <meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Registration</title>
	
	<meta name="description" content="Kontroluj swoje przychody i wydatki, budżet pod kontrolą" />
	<meta name="keywords" content="budżet, przychody, wydatki, finanse domowe" />
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="mystyle.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="fontello/css/fontello.css" type="text/css" />	


</head>

<body>

    <div class="page-container">	
		<div class="content-wrap">
    
            <header>

                <div class="topbar">

                    <nav class="navbar navbar-expand-sm py-0">

                        <a class="navbar-brand" href="index.php"><img src="img/budget.png" alt="Logo"/><span>My</span>Budget</a>

                    </nav>

                </div>	

            </header>

            <main>

                <article>

                    <div class="container">

                        <div class="row">

                            <div class="col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-xl-4 offset-xl-4 my-4 content">

                                <header>
                                    <h1 class="content-header">REJESTRACJA</h1>
                                </header>

                                <form action="#" method="post">

                                    <div class="input-container">
                                        <i class="icon-user-1 icon"></i>
                                        <input class="input-field" type="text" name="userlogin" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'">
                                    </div>

                                    <?PHP
                                        if(isset($_SESSION['er_login'])){
                                            echo '<div class="error">'.$_SESSION['er_login'].'</div>';
                                            unset ($_SESSION['er_login']);
                                        }
                                    ?>

                                    <div class="input-container">
                                        <i class="icon-mail-1 icon"></i>
                                        <input class="input-field" type="text" name="useremail" placeholder="email" onfocus="this.placeholder=''" onblur="this.placeholder='email'">
                                    </div>

                                    <?PHP
                                        if(isset($_SESSION['er_email'])){
                                            echo '<div class="error">'.$_SESSION['er_email'].'</div>';
                                            unset($_SESSION['er_email']);
                                        }

                                    ?>

                                    <div class="input-container">
                                        <i class="icon-lock-1 icon"></i>
                                        <input class="input-field" type="password" name="user-password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='password'">
                                    </div>	
                                    <?PHP
                                        if(isset($_SESSION['er_pass'])){
                                            echo '<div class="error">'.$_SESSION['er_pass'].'</div>';
                                            unset($_SESSION['er_pass']);
                                        }

                                    ?>

                                    <div class="input-container">
                                        <i class=" icon-lock-open-alt icon"></i>
                                        <input class="input-field" type="password" name="confirmation-password" placeholder="potwierdź hasło" onfocus="this.placeholder=''" onblur="this.placeholder='confirm password'">
                                    </div>	

                                    <input type="submit" class="btn btn-success btn-block my-4" name="register" value="Zarejestruj">

                                </form>

                            </div>    

                        </div>    

                    </div>

                </article>

            </main>
            
        </div>

        <footer>
            <div class="footer">
                Aplikacja budżetowa by Kemot. Wszelkie prawa zastrzeżone &copy; - 2020
            </div>
        </footer>
	
    </div>    
        
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
</body>
</html>