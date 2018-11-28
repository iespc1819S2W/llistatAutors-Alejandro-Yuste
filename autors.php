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
    <script>
    <?php
echo "<h1>AUTORS</h1>";
$orderby = "ID_AUT ASC";
$mysqli = new mysqli();
$mysqli->connect("localhost", "root", "alex", "biblioteca");
$mysqli->set_charset("utf8");
$currentPage = 1;
$totalPagines = 0;
$numberofrows = 20;
$offset = 0;
$cerca = "";

//------ RECOGE LA PAGINA -------
if (isset($_POST["pagina"])) {
    $currentPage = $_POST["pagina"];
}

//------- RECOGE LA BUSQUEDA -------
if(isset($_POST["cerca"])){
$cerca = $mysqli -> real_escape_string($_POST["cerca"]);
}


//------------- CONTADOR -----------------------------------------------
if($_POST["cerca"] != ""){
    $queryContador = "SELECT COUNT(*) as total FROM AUTORS WHERE ID_AUT='".$_POST['cerca']."' OR NOM_AUT LIKE '%".$_POST["cerca"]."%'";
    $resultat = $mysqli->query($queryContador) or die($queryContador);
    if ($cursor = $resultat->fetch_assoc()) {
        $totalPagines = ceil($cursor["total"] / $numberofrows);
    }  
} else {
    $queryContador = "SELECT COUNT(*) as total FROM AUTORS";
    $resultat = $mysqli->query($queryContador) or die($queryContador);
    if ($cursor = $resultat->fetch_assoc()) {
        $totalPagines = ceil($cursor["total"] / $numberofrows);
    } 
}

//----------------------------------------------------------------------


// BOTONES ANTES Y DESPUÉS------------------
if (isset($_POST["anterior"])) {
    if ($currentPage > 1) {
        $currentPage--;
    }
}
if (isset($_POST["posterior"])) {
    if ($currentPage < $totalPagines) {
        $currentPage++;
    }
}
if (isset($_POST["primer"])) {
        $currentPage = 1;
}

if (isset($_POST["darrer"])) {
    $currentPage = $totalPagines;
}

//--------------------------------------------


// EL OFFSET ÉS IGUAL AL NUMERO DE FILAS QUE ENSEÑO POR EL NUMERO DE PAGINA
$offset = $numberofrows * ($currentPage -1);
//--------------------------------------------


//----------- BOTONES ORDENAR ----------------
if (isset($_POST["id_aut_asc"])) {
    $currentPage = 1;
    $orderby = "ID_AUT ASC";
}
if (isset($_POST["id_aut_desc"])) {
    $currentPage = 1;
    $orderby = "ID_AUT DESC";
}

if (isset($_POST["nom_aut_desc"])) {
    $currentPage = 1;
    $orderby = "NOM_AUT DESC";
}

if (isset($_POST["nom_aut_asc"])) {
    $currentPage = 1;
    $orderby = "NOM_AUT ASC";
}
//-------------------------------------------------

//----------------- MIRO SI HAGO LA QUERY POR LA BUSQUEDA O NORMAL ---------
if(isset($_POST["botocerca"])){
    $query = "SELECT ID_AUT, NOM_AUT FROM AUTORS WHERE ID_AUT ='".$_POST['cerca']."' OR NOM_AUT LIKE '%".$_POST["cerca"]."%' ORDER BY $orderby LIMIT $offset,$numberofrows";
} else {
    $query = "SELECT ID_AUT, NOM_AUT FROM AUTORS WHERE ID_AUT ='".$_POST['cerca']."' OR NOM_AUT LIKE '%".$_POST["cerca"]."%' ORDER BY $orderby LIMIT $offset,$numberofrows";
}
//---------------------------------------------------------------------------

?>
    </script>
</head>
<body>
<form action="autors-segon.php" method="post">
<input type="text" name="cerca" value="<?=$cerca?>">
<input type="submit" name="botocerca" value="Cerca"><br>
<input type="hidden" name="pagina" value="<?=$currentPage?>">
<button type="submit" name="id_aut_desc">CODI DESCENDENT</button>
<button type="submit" name="id_aut_asc">CODI ASCENDENT</button>
<button type="submit" name="nom_aut_desc">NOM DESCENDENT</button>
<button type="submit" name="nom_aut_asc">NOM ASCENDENT</button><br>
<input type="submit" name="primer" value="&laquo&laquo">
<input type="submit" name="anterior" value="&laquo">
<input type="submit" name="posterior" value="&raquo">
<input type="submit" name="darrer" value="&raquo&raquo">
</form>
    <table>
        <tr>
        <td>CODI</td>
        <td>NOM</td>
        </tr>
        <?php
if ($cursor = $mysqli->query($query) or die($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID_AUT"] . "</td>";
        echo "<td>" . $row["NOM_AUT"] . "</td>";
        echo "</tr>";
    }
}
?>
<tr>
<td><?=$currentPage?>/<?=$totalPagines?><td>
</tr>
    </table>
</body>
</html>