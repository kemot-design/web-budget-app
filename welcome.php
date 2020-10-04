<?PHP

    session_start();

    if(isset($_SESSION['registrationSuccess']) == false){
        header('Location: index.php');
        exit();
    }
    else{
        unset($_SESSION['registrationSuccess']);
    }

    if(isset($_SESSION['er_login'])) unset($_SESSION['er_login']);
    if(isset($_SESSION['er_pass'])) unset($_SESSION['er_pass']);
    if(isset($_SESSION['er_email'])) unset($_SESSION['er_email']);

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>MyBudget</title>
	
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

                <div class="container">

                    <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2 my-4 pb-2 content">

                        <section>

                            <header>
                                <h2 class="content-header"> Rejestracja zakończona pomyślnie! </h2>
                            </header>					 

                            <p style="text-align: center">	
                                Teraz możesz zalogowac się do swojego konta
                            </p>

                        </section>

                    </div>

                    <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2 my-4 content">

                        <section>

                            <div class="row pb-3">

                                <div class="col-md-6 offset-md-3">
                                    <p class="text-center mb-1 mt-3">Zaloguj się na swoje konto</p>
                                   <a href="http://localhost/web-budget-app/logpage.php" class="btn btn-primary btn-block">Logowanie</a>
                                </div>

                            </div>

                        </section>	

                    </div>

               </div>

            </main>    
        
        </div>    
            
        <footer>

            <div class="footer"> Aplikacja budżetowa by Kemot. Wszelkie prawa zastrzeżone &copy; - 2020 </div>

        </footer>	
	
    </div>    
        
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
</body>
</html>