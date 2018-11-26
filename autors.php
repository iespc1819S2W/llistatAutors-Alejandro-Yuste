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
        button, submit {
        border:none;
        background-color: white;
        font-weight: bold;
        font-size: 16pt;
        }
        .pagines {
            width: 40px;
            float: left;
        }
        .numeropagina {
            margin-left: 5px;
            width: 40px;
            float: left;
            margin-top: 5px;
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
$count = 0;
$offset = 0;
$numberofrows = 20;

//

//

$query = "";
if (isset($_POST["cercaNom"])) {
    if ($_POST["cercaNom"] != "") {
        $count = 0;
        $queryContador = "SELECT COUNT(*) as total FROM AUTORS WHERE NOM_AUT LIKE '%" . $_POST["cercaNom"] . "%'";
        $resultat = $mysqli->query($queryContador) or die($queryContador);
        if ($cursor = $resultat->fetch_assoc()) {
            $count = $cursor["total"];
            echo $count;
        }
        $query = "SELECT ID_AUT, NOM_AUT FROM AUTORS WHERE NOM_AUT LIKE '%" . $_POST["cercaNom"] . "%' ORDER by $orderby LIMIT $offset,$numberofrows";
        print_r($_POST["cercaNom"]);
    } else if ($_POST["cercaId"] != "") {
        $count = 0;
        $queryContador = "SELECT COUNT(*) as total FROM AUTORS WHERE ID_AUT = " . $_POST["cercaId"];
        $resultat = $mysqli->query($queryContador) or die($queryContador);
        if ($cursor = $resultat->fetch_assoc()) {
            $count = $cursor["total"];
            echo $count;
        }
        $query = "SELECT ID_AUT, NOM_AUT FROM AUTORS WHERE ID_AUT = " . $_POST["cercaId"] . " ORDER by $orderby LIMIT $offset,$numberofrows";
    }
} else {
    $count = 0;
    $queryContador = "SELECT COUNT(*) as total FROM AUTORS";
    $resultat = $mysqli->query($queryContador) or die($queryContador);
    if ($cursor = $resultat->fetch_assoc()) {
        $count = $cursor["total"];
        echo $count;
    }
    $query = "SELECT ID_AUT, NOM_AUT FROM AUTORS ORDER by $orderby LIMIT $offset,$numberofrows";
}

if (isset($_POST["anterior"])) {
    if ($currentPage > 1) {
        $offset = $offset - $numberofrows;
        $currentPage--;
    }
}
if (isset($_POST["posterior"])) {
    if ($currentPage < ($count / $numberofrows)) {
        $offset = $offset + $numberofrows;
        $currentPage++;
    }
}

if (isset($_POST["hiddenpagina"])) {
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
if (isset($_POST["hiddenoffset"])) {
    $offset = $_POST["hiddenoffset"];
}
if ($cursor = $mysqli->query($query) OR DIE($query)) {
    /* fetch associative array */
    echo '<form action="autors.php" method="post">';
    echo "<input type='hidden' name='ordre' value='$orderby'>";
    echo "<label for='cercaNom'>Nom:</label>";
    echo "<input type='text' name='cercaNom'><br>";
    echo "<label for='cercaId'>ID:</label>";
    echo "<input type='text' name='cercaId'><br>";
    echo '<button type="submit" name="id_aut_desc">CODI DESCENDENT</button>';
    echo '<button type="submit" name="id_aut_asc">CODI ASCENDENT</button>';
    echo '<button type="submit" name="nom_aut_desc">NOM DESCENDENT</button>';
    echo '<button type="submit" name="nom_aut_asc">NOM ASCENDENT</button>';
    echo "<table>";
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
    echo "<input type='hidden' name='hiddenoffset' value='$offset'>";
    echo "<input type='hidden' name='hiddenpagina' value='$currentPage'>";
    echo "<input type='submit' name='anterior' value='&laquo' class='pagines'>";
    echo "<p class='numeropagina'>$currentPage/$count</p>";
    echo "<input type='submit' name='posterior' value='&raquo' class='pagines'>";
    echo "</form>";
    echo "</div>";
}

?>
</body>
</html>