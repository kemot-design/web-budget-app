<?PHP

    session_start();
    
    if(!isset($_POST['income_value'])){
        header('Location: addincome.php');
        exit();
    }

    $income_amount = filter_input(INPUT_POST, 'income_value', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    $date_of_income = $_POST['income_date'];
    if($date_of_income <= "0000-00-00") {
        $date_of_income = date('Y-m-d');
    }

    $income_category = $_POST['income_category'];

    $income_comment = $_POST['income_comment'];

    $user_id = $_SESSION['user_id'];

    $_SESSION['income_added'] = true;

    require_once 'database.php';

    $query = $db->prepare('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)');
    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':income_category_assigned_to_user_id', $income_category);
    $query->bindParam(':amount', $income_amount);
    $query->bindParam(':date_of_income', $date_of_income);
    $query->bindParam(':income_comment', $income_comment);
    $query->execute();

    header('Location: addincome.php');

?>