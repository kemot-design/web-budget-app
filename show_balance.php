<?PHP

    session_start();

    if((!isset($_SESSION['login_success'])) || ($_SESSION['login_success'] != true)){
        header('Location: logpage.php');
        exit();
    }

    if(isset($_POST['balance_period']) && $_POST['balance_period'] != 0){
        
        $balance_period = $_POST['balance_period'];
        
            if($balance_period == 4){
                $_SESSION['balance_period'] = $balance_period;
                header('Location: select_balance_period.php');
                exit();
            }
        
        require_once "database.php";
        $query_defined_by_period = "";

        try{
            
            switch($balance_period){
                    
                case 1:
                    $query_defined_by_period = "SELECT name, SUM(amount) AS IncomesSum FROM incomes_category_assigned_to_users, incomes WHERE incomes.user_id = :user_id AND date_of_income >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND date_of_income < LAST_DAY(CURDATE()) + INTERVAL 1 DAY AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id GROUP BY name ORDER BY IncomesSum DESC";
                    break;
                    
                case 2:
                    $query_defined_by_period = "SELECT name, SUM(amount) AS IncomesSum FROM incomes_category_assigned_to_users, incomes WHERE incomes.user_id = :user_id AND date_of_income >= (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 2 MONTH) AND date_of_income < (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id GROUP BY name ORDER BY IncomesSum DESC";
                    break;
                    
                case 3:
                    $query_defined_by_period = "SELECT name, SUM(amount) AS IncomesSum FROM incomes_category_assigned_to_users, incomes WHERE incomes.user_id = :user_id AND YEAR(date_of_income) = YEAR(CURDATE()) AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id GROUP BY name ORDER BY IncomesSum DESC";
                    break;
                    
            }
            
            $query = $db->prepare($query_defined_by_period);
            $query->bindParam(':user_id', $_SESSION['user_id']);
            $query->execute();

            while($dataRow = $query->fetch(PDO::FETCH_ASSOC)){
                $incomes[] = $dataRow;
            }
            
            switch($balance_period){
                    
                case 1:
                    $query_defined_by_period = "SELECT name, SUM(amount) AS expensesSum FROM expenses_category_assigned_to_users, expenses WHERE expenses.user_id = :user_id AND date_of_expense >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND date_of_expense < LAST_DAY(CURDATE()) + INTERVAL 1 DAY AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id GROUP BY name ORDER BY expensesSum DESC";
                    break;
                    
                case 2:
                    $query_defined_by_period = "SELECT name, SUM(amount) AS expensesSum FROM expenses_category_assigned_to_users, expenses WHERE expenses.user_id = :user_id AND date_of_expense >= (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 2 MONTH) AND date_of_expense < (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id GROUP BY name ORDER BY expensesSum DESC";
                    break;
                    
                case 3:
                    $query_defined_by_period = "SELECT name, SUM(amount) AS expensesSum FROM expenses_category_assigned_to_users, expenses WHERE expenses.user_id = :user_id AND YEAR(date_of_expense) = YEAR(CURDATE()) AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id GROUP BY name ORDER BY expensesSum DESC";
                    break;
    
            }
            
            $query = $db->prepare($query_defined_by_period);
            $query->bindParam(':user_id', $_SESSION['user_id']);
            $query->execute();

            while($dataRow = $query->fetch(PDO::FETCH_ASSOC)){
                $expenses[] = $dataRow;
            }
        }
        catch(PDOException $error){
            echo "Serwer Error";
            echo $error->getMEssage();
            exit();
        }

        $sum_of_incomes = 0;
        $sum_of_expenses = 0;    
        
    }
    else if(isset($_SESSION['balance_start_date']) && $_SESSION['balance_end_date']){
    
        require_once "database.php";
        
        $query = $db->prepare("SELECT name, SUM(amount) AS expensesSum FROM expenses_category_assigned_to_users, expenses WHERE expenses.user_id = :user_id AND date_of_expense >= :balance_start_date AND date_of_expense <= :balance_end_date AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id GROUP BY name ORDER BY expensesSum DESC");
        $query->bindParam(':user_id', $_SESSION['user_id']);
        $query->bindParam(':balance_start_date', $_SESSION['balance_start_date']);
        $query->bindParam(':balance_end_date', $_SESSION['balance_end_date']);
        $query->execute();

        while($dataRow = $query->fetch(PDO::FETCH_ASSOC)){
            $expenses[] = $dataRow;
        }
        
        $query = $db->prepare("SELECT name, SUM(amount) AS IncomesSum FROM incomes_category_assigned_to_users, incomes WHERE incomes.user_id = :user_id AND date_of_income >= :balance_start_date AND date_of_income <= :balance_end_date AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id GROUP BY name ORDER BY IncomesSum DESC");
        $query->bindParam(':user_id', $_SESSION['user_id']);
        $query->bindParam(':balance_start_date', $_SESSION['balance_start_date']);
        $query->bindParam(':balance_end_date', $_SESSION['balance_end_date']);
        $query->execute();

        while($dataRow = $query->fetch(PDO::FETCH_ASSOC)){
            $incomes[] = $dataRow;
        }
        
        $sum_of_incomes = 0;
        $sum_of_expenses = 0; 
        
    }
    else{
        header('Location: balance.php');
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
    <link rel="stylesheet" href="mystyle.css" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="fontello/css/fontello.css" type="text/css">	
	

</head>

<body>

	<div id="page-container">	
		<div id="content-wrap">
		
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
                
                    <div class="col-sm-12 col-lg-10 offset-lg-1 my-3 content">

                        <article>
                            
                            <header>

                                <h2 class="content-header"><i class="icon-chart-bar"></i> Bilans </h2>

                            </header>								

                            <div class="row">

                                <div class="col-sm-12 col-md-6 py-2">
                                    
                                    <h3>Przychody</h3>

                                    <table>
                                        <tr class="incomes-header">
                                            <th> Kategoria </th><th> Suma </th>
                                        </tr>
                                        
                                        <?PHP
                                            
                                            if(!empty($incomes)){
                                                foreach($incomes as $income){
                                                    echo "<tr><td>".$income['name']."</td>
                                                    <td>".$income['IncomesSum']."</td></tr>";

                                                    $sum_of_incomes += $income['IncomesSum'];
                                                }    
                                            }
                                        
                                        ?>
                                        
                                        <tr>
                                            <td class="sum-row"> Suma </td><td class="sum-row"> <?= $sum_of_incomes ?></td>
                                        </tr>									

                                    </table>

                                </div>

                                <div class="col-sm-12 col-md-6 py-2">
                                    
                                    <h3>Wydatki</h3>

                                    <table>
                                        <tr class="expenses-header">
                                            <th> Kategoria </th><th> Suma </th>
                                        </tr>
                                        
                                        <?PHP
                                            if(!empty($expenses)){
                                                foreach($expenses as $expense){
                                                    echo "<tr><td>".$expense['name']."</td>
                                                    <td>".$expense['expensesSum']."</td></tr>";

                                                    $sum_of_expenses += $expense['expensesSum'];
                                                }    
                                            }
                                                
                                        
                                        ?>
                                        
                                        <tr >
                                            <td class="sum-row"> Suma </td><td class="sum-row"> <?= $sum_of_expenses ?> </td>
                                        </tr>									
                                    </table>						

                                </div>	

                            </div>

                            <div class="balance-row">

                                <?PHP
    
                                    $balance = $sum_of_incomes - $sum_of_expenses;
                                    echo "Twój bilans: ".$balance." zł <br/>";
                                    echo ($balance >= 0) ? "Brawo, świetnie zarządzasz finansami!" : "Uważaj! Wydajesz więcej niż zarabiasz.";
    
                                ?>
                                
                            </div>
                            
                            <form action="show_balance.php" method="POST">
                                <div class="row my-2"> 
                                    <div class="col-sm-12 col-md-6">

                                        <label for="balance-date"> Wybierz okres: </label>
                                        <select	class="custom-select mb-2" name="balance_period" id="balance_period">
                                            <option value="0" selected>Wybierz okres</option>
                                            <option value="1">Bierzący miesiąc</option>
                                            <option value="2">Poprzedni miesiąc</option>
                                            <option value="3">Bierzący rok</option>
                                            <option value="4">Niestandardowa</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="submit" class="btn btn-success btn-block mt-3" value="Pokaż bilans">
                                    </div>
                                </div>
                            </form>	
                                                        
                        </article>
                    
                    </div>    
                        
                </div>    
				
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