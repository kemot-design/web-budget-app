<?PHP

    session_start();
    
    if(!isset($_POST['expense_amount'])){
        header('Location: addexpense.php');
        exit();
    }

    $expense_amount = filter_input(INPUT_POST, 'expense_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $date_of_expense = $_POST['date_of_expense'];

    $payment_method = $_POST['payment_method'];

    $expense_category = $_POST['expense_category'];

    $expense_comment = $_POST['expense_comment'];

    $user_id = $_SESSION['user_id'];

    $_SESSION['income_added'] = true;

    $is_form_ok = true;

    //check if the amount is selected
    if($expense_amount == 0) {
        $_SESSION['expense_amount_error'] = "Wybierz kwotę";
        $is_form_ok = false;
    }

    //check if the date is selected
    $today = date('Y-m-d');

    if($date_of_expense < "2000-01-01" || $date_of_expense == 0 || $date_of_expense > $today) {
        $_SESSION['date_of_expense_error'] = "Wybierz poprawną datę";
        $is_form_ok = false; 
    }

    // check if payment method and category is selected
    if($payment_method == 0){
        $_SESSION['payment_method_error'] = "Wybierz metodę płatności";
        $is_form_ok = false;
    }
    
    if($expense_category == 0){
        $_SESSION['expense_category_error'] = "Wybierz kategorię wydatku";
        $is_form_ok = false;
    }

    if($is_form_ok){
        
        require_once 'database.php';

    try{
        $query = $db->prepare('INSERT INTO expenses VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)');
        
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':expense_category_assigned_to_user_id', $expense_category);
        $query->bindParam(':payment_method_assigned_to_user_id', $payment_method);
        $query->bindParam(':amount', $expense_amount);
        $query->bindParam(':date_of_expense', $date_of_expense);
        $query->bindParam(':expense_comment', $expense_comment);
        $query->execute();
    }
    catch(PDOException $error){
        echo $error->getMessage()."<br/>";
        exit('Database error');
    }    

        $_SESSION['expense_added'] = true;    
        header('Location: addexpense.php');   
    }
    else{
        header('Location: addexpense.php');
    }
    
?>