<?php
session_start();
// Connexion à la base de données
/* TODO */
$servername = "localhost";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=minichat", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

if ($_POST) {
    // Insertion du message à l'aide d'une requête préparée
    /* TODO */
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $_SESSION['pseudo'] = $pseudo;
    $message = htmlspecialchars($_POST['message']);

    $request = $conn->prepare('INSERT INTO messages (pseudo, message) VALUES (:pseudo ,:message)');
    $request->bindParam(':pseudo', $pseudo);
    $request->bindParam(':message', $message);

    $request->execute();

}

?>
<!DOCTYPE>
<html>

<head>
    <title>MiniChat Project II - The Return</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Material Design Light -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
</head>

<body>
    <div class="mdl-layout mdl-js-layout">
        <main class="mdl-layout__content">
            <div class="page-content">
                <ul class="demo-list-item mdl-list" id="conversation">
<?php
// Récupération des 10 derniers messages
/* TODO */
if (isset($_GET['page'])){
$page =  htmlspecialchars($_GET['page']);

$debut = ($page -1 )* 10;
}else{
    $debut = 1;
}
var_dump($page);
var_dump($debut);
$request = $conn->prepare( "SELECT * FROM messages ORDER BY id DESC LIMIT 10 OFFSET :debut");
$request->bindValue('debut', $debut, PDO::PARAM_INT);
$result = $request->execute();
$data = $result->fetch();
$data =  array_reverse($data);

foreach ($data as $message){
?>
                    <li class="mdl-list__item">
                        <span class="mdl-list__item-primary-content">
                            <strong><?= $message['pseudo'] ?></strong>: <?= $message['message'] ?>
                        </span>
                    </li>
<?php
}
?>
                </ul>

                <form action="#" class="mdl-grid" method="POST">
                    <div class="mdl-cell mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="pseudo" id="pseudo"
                            <?php
                            if (isset($_SESSION['pseudo'])){
                                echo 'value="'. $_SESSION['pseudo'] .'"';
                            }
                            ?>
                        >
                        <label class="mdl-textfield__label" for="sample3">Pseudo</label>
                    </div>
                    <div class="mdl-cell mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="message" id="message">
                        <label class="mdl-textfield__label" for="sample3">Message</label>
                    </div>
                    <button id="send" class="mdl-cell mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
                        <i class="material-icons">send</i>
                    </button>
                </form>
                <a href="javascript:window.location.reload()">Raffraichir la page</a>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <!-- Material Design Light -->
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <script>
        $(document).ready(function(){
            function autorefresh(){
                setTimeout(function(){
                    window.location.reload();
                    autorefresh();
                    }, 60000);
            }
            autorefresh();
        })

    </script>
</body>

</html>
