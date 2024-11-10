<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Receiver Page</title>
</head>
<body>
<?php 
session_start(); 

if (isset($_POST['return'])) {
    unset($_SESSION['last_id']);
    header("Location: index.php");
    exit;
}

$hostName = "localhost";
$userName = "root";
$password = "root";
$databaseName = "newmodeldb";
$TemCovid = null;
$Prediction = null;
$Porcentagem = null;
$color = "#000000";

if (isset($_SESSION['last_id'])) {
    $id = $_SESSION['last_id'];
    $conn = new mysqli($hostName, $userName, $password, $databaseName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT TemCovid, Porcentagem FROM prediction WHERE idPessoa = " . $id;
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $TemCovid = $row['TemCovid'];
        $Porcentagem = number_format((float)$row['Porcentagem'], 2);

        if ($TemCovid == 0) {
            $Prediction = "Negativo";
            $color = "#1abc1a";
        } else if ($TemCovid == 1) {
            $Prediction = "Positivo";
            $color = "#ff5050";
        } else {
            die("Erro com o resultado");
        }
    } else {
        echo "<p>ERRO: Sem resultado</p>";
    }

    $conn->close();
} else {
    echo "<p>ID não encontrado na sessão</p>";
}

if ($Prediction !== null && $Porcentagem !== null) {
?>
<div class="card card-with-top-line">
    <div class="card-top-line"></div>
    <br> <br> 
    <b>Resultado:</b>
    <b><span style="color: <?php echo $color; ?>"><?php echo $Prediction; ?></span></b>
    <br> <br> 
    <br> <br> 
    <b>Confiabilidade:</b>
    <b><?php echo $Porcentagem; ?></b>
</div>
<?php } ?>
<form method="post">
    <button type="submit" name="return">Voltar</button>
</form>
</body>
</html>
