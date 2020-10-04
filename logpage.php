<?PHP

    session_start();

    if(isset($_SESSION['login_success'])){
        header('Location: mainmenu.php');
        exit();
    }


?>


<!DOCTYPE html>
<html lang="pl">

<head>
	
	<meta charset="utf-8"  />
	<meta name="description" content="Kontroluj swoje przychody i wydatki, budżet pod kontrolą" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Login</title>
    
	<meta name="keywords" content="budżet, przychody, wydatki, finanse domowe" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
	
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
                                    <h1 class="content-header">LOGOWANIE</h1>
                                </header>

                                <form action="loggin.php" method="POST">

                                    <div class="input-container">
                                        <i class="icon-user-1 icon"></i>
                                        <input class="input-field" type="text" name="userlogin" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'">
                                    </div>		
                                    <?PHP

                                        if(isset($_SESSION['error_login'])){
                                            echo '<div class="error">'.$_SESSION['error_login'].'</div>';
                                        }

                                    ?>

                                    <div class="input-container">
                                        <i class="icon-lock-1 icon"></i>
                                        <input class="input-field" type="password" name="userpassword" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='password'">
                                    </div>

                                    <input type="submit" class="btn btn-success btn-block my-4" name="login" value="Zaloguj">

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