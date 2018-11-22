<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Autors BBDD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    *{
        font-family: Arial;
        text-align: left;
    }
    input, button, submit {
        border:none;
        background-color: white;
        font-weight: bold;
        font-size: 16pt;
        }
    </style>
</head>
<body>
    <?php
echo "<h1>AUTORS</h1>";
$orderby = "ID_AUT ASC";
$mysqli = new mysqli();
$mysqli->connect("localhost", "root", "alex", "biblioteca");
$mysqli->set_charset("utf8");
$currentPage = 1;
if(isset($_POST["hiddenpagina"])){
    $currentPage = $_POST["hiddenpagina"];
}
if (isset($_POST["id_aut_desc"])) {
    $orderby = "ID_AUT DESC";
}

if (isset($_POST["nom_aut_desc"])) {
    $orderby = "NOM_AUT DESC";
}

if (isset($_POST["nom_aut_asc"])) {
    $orderby = "NOM_AUT ASC";
}
$numberofrows = 20;
$offset = 0;
if(isset($_POST["hiddenoffset"])){
    $offset = $_POST["hiddenoffset"];
}
$count = 0;
$queryContador = "SELECT COUNT(*) as total FROM AUTORS";
$resultat = $mysqli->query($queryContador) or die($queryContador);
if ($cursor = $resultat->fetch_assoc()) {
    $count = $cursor["total"];
}

if(isset($_POST["anterior"])){
    if($currentPage > 1){
        $offset = $offset - $numberofrows;
        $currentPage--;
    }
}
echo $currentPage;
if(isset($_POST["posterior"])){
    if($currentPage < ($count / $numberofrows)){
        $offset = $offset + $numberofrows;
        $currentPage++;
    }
}
echo $currentPage;

$query = "SELECT ID_AUT, NOM_AUT FROM AUTORS ORDER by $orderby LIMIT $offset,$numberofrows";
if ($cursor = $mysqli->query($query)) {
    /* fetch associative array */
    echo "<table>";
    echo "<tr>";
    echo '<form action="autors.php" method="post">';
    echo "<td>";
    echo '<button type="submit" name="id_aut_desc">CODI DESCENDENT</button>';
    echo "</td>";
    echo "<td>";
    echo '<button type="submit" name="id_aut_asc">CODI ASCENDENT</button>';
    echo "</td>";
    echo "<tr>";
    echo "<td>";
    echo '<button type="submit" name="nom_aut_desc">NOM DESCENDENT</button>';
    echo "</td>";
    echo "<td>";
    echo '<button type="submit" name="nom_aut_asc">NOM ASCENDENT</button>';
    echo "</td>";
    echo "</tr>";
    echo "</form>";
    echo "</tr>";
    echo "<tr>";
    echo '<td><p>ID AUTOR</p></td>';
    echo '<td><p>NOM AUTOR</p></td>';
    echo "</tr>";
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID_AUT"] . "</td>";
        echo "<td>" . $row["NOM_AUT"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<div>";
    echo "<form action='autors.php' method='post'>";
    echo "<input type='hidden' name='hiddenoffset' value='$offset'>";
    echo "<input type='hidden' name='hiddenpagina' value='$currentPage'>";
    echo "<input type='submit' name='anterior' value='&laquo'>";
    echo "<p class='numeropagina'>$currentPage</p>";
    echo "<input type='submit' name='posterior' value='&raquo'>";
    echo "</form>";
    echo "</div>";
}

?>
</body>
</html>