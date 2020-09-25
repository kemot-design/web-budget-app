<?PHP

    session_start();

    if((!isset($_SESSION['login_success'])) || ($_SESSION['login_success'] != true)){
        header('Location: logpage.php');
        exit();
    }
   

?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<title>MyBudget Add Expense</title>
	
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
                        <ul class="navbar-nav ml-auto">
                            <li class="navbar-item">Zalogowany jako: <span>John Doe</span></li>
                        </ul>
                    </nav>
                </div>	

				<div class="logo">

					<img src="img/budget.png" alt="rocket"/>
					<div style="float: left;">
						<p><span>My</span>Budget</p>
						<p> Wzleć na wyżyny swoich <br/> finansowych możliwości! </p>
					</div>
					<div style="clear: both;"></div>
					
				</div>

				<nav class="navbar bg-success navbar-expand-lg">
                    
                    <button class="navbar-toggler custom-toggler ml-2" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" style="background-color: #46c768;"><span class="navbar-toggler-icon"></span></button>
                    
					<div class="collapse navbar-collapse justify-content-center" id="collapsibleNavbar">
                        
						<ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="mainmenu.php"><i class="icon-user-1 menu-icon"></i>Strona główna</a></li>
                            <li class="nav-item"><a class="nav-link" href="addincome.php"><i class="icon-money-1 menu-icon"></i>Dodaj przychód</a></li>
                            <li class="nav-item"><a class="nav-link" href="addexpense.php"><i class="icon-basket-1 menu-icon"></i>Dodaj wydatek</a></li>
                            <li class="nav-item"><a class="nav-link" href="balance.php"><i class="icon-chart-bar menu-icon"></i>Bilans</a></li>
                            <li class="nav-item"><a class="nav-link" href="#"><i class="icon-wrench menu-icon"></i>Ustawienia</a></li>
                            <li class="nav-item"><a class="nav-link" href="logpage.php"><i class="icon-logout-1 menu-icon"></i>Wyloguj</a></li>
						</ul>
                        
					</div>
                    
				</nav>
                
            </header>
		
			<main>
				
				<div class="container">
				
                    <div class="col-sm-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2 my-3 content">
                        <article>

                            <header>

                                <h2 class="content-header"><i class="icon-money-1"></i> Dodaj przychód </h2>
                              
                            </header>

                            <form action="add_new_income.php" method="POST">

                                <div class="row">

                                    <label class="formLegend ml-3"> Kwota: <input type="number" name="income_value" id="expenseValue" step="0.01"></label>

                                    <label class="formLegend ml-3"> Data: <input type="date" name="income_date" class="ml-2"></label>
                                
                                </div>	

                                <hr>
                                
                                <h3 class="formLegend">Kategoria</h3>

                                <div class="row mt-1">
                                    <div class="col-md-12">

                                        <?PHP
                                        
                                            $income_categories = $_SESSION['income_categories'];
                                        
                                            foreach($income_categories as $income_category){
                                               echo '<div><label><input type="radio" name="income_category" value="'.$income_category['id'].'">'.$income_category['name'].'<label></div>';
                                            }
                                            
                                        
                                        ?>

                                    </div>
                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="comment" class="formLegend"> Komentarz (opcjonalnie): </label><br>
                                        <textarea id="comment" name="income_comment" rows="4" cols="44" placeholder="Twój komentarz ..."></textarea>
                                    </div>
                                </div>	

                                <div class="row py-3">

                                    <div class="col-md-6 my-1">
                                       <input type="submit" class="btn btn-success btn-block" value="Dodaj">
                                    </div>
                                    <div class="col-md-6 my-1">
                                       <a href="mainmenu.html" class="btn btn-danger btn-block">Anuluj</a>
                                    </div>

                                </div>

                            </form>

                        </article>
                    
                    </div>    
                        
				</div>
                
			</main>
		
		</div>

		<footer>
			<div class="footer position-absolute">
				Aplikacja budżetowa by Kemot. Wszelkie prawa zastrzeżone &copy; - 2020
			</div>
		</footer>
		
	</div>	
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
</body>
</html>