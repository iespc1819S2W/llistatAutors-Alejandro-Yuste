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
        button, submit, .botoCerca {
        background-color: lightgrey;
        font-weight: bold;
        font-size: 16pt;
        }
        .general {
            margin: 0 auto;
            width: 50vw;
        }
        .taula {
            margin: 3vh auto;
            border-collapse: collapse;
        }
        .afegir-out {
            margin: 0 auto;
            width: 35vw;
        }
        .taula tr:first-child td {
            font-size: 15pt;
            font-weight: bold;
            text-align: center;
        }

        .taula tr td:first-child {
            text-align: center;
        }
        
        .taula tr td:nth-child(3) {
            text-align: center;
        }

        .taula tr td:nth-child(4) {
            align: center;
        }

        .formulari {
            text-align: center;
        }
        td {
            padding: 1vw;
            width: 15vw;
            border: 1px solid black;
        }
        .pagines {
            text-align: center;
        }
        .numeropagina {
            margin-left: 5px;
            width: 40px;
            float: left;
            margin-top: 5px;
        }
        .afegir {
            float: left;
            margin: 0vh 0vw 1vh 2vw;
        }
        .botoTaula {
            font-size: 11pt;
        }
        .taula tr td:nth-child(4) button:nth-child(2) {
            margin-left: 2px;
        }
    </style>
    <script>
    <?php
require_once "funcions.php";
echo "<h1>AUTORS</h1>";
$mysqli = new mysqli();
$mysqli->connect("localhost", "root", "alex", "biblioteca");
$mysqli->set_charset("utf8");
$currentPage = 1;
$totalPagines = 0;
$numberofrows = 20;
$offset = 0;
$cerca = "";
$textAfegir = "";

// ---------CONSERVAR EL ORDRE -------------
if (isset($_POST["hiddenOrdre"])) {
    $orderby = $_POST["hiddenOrdre"];
} else {
    $orderby = "ID_AUT ASC";
}

// --------- SI SE ENVIA EL BOTO DE AFEGIR AUTOR -------------------
if (isset($_POST["botoAfegir"])) {
    $textAfegir = $mysqli->real_escape_string($_POST["textAfegirAutor"]);
}

//------ RECOGE LA PAGINA -------------------------------------------
if (isset($_POST["pagina"])) {
    $currentPage = $_POST["pagina"];
}

//------- RECOGE LA BUSQUEDA -------------------------------------------
if (isset($_POST["cerca"])) {
    $cerca = $mysqli->real_escape_string($_POST["cerca"]);
}

// --------- SI SE ENVIA EL BOTON DE BORRAR ------------------------
if (isset($_POST["botoBorrar"])) {
    $idBorrar = $_POST["botoBorrar"];
    $queryBorrar = "DELETE FROM AUTORS WHERE ID_AUT = " . $idBorrar;
    $mysqli->query($queryBorrar);
}

// ------------ SI SI ACEPTA LA EDICIÓN DE AUTOR ---------
if (isset($_POST["botoConfirmar"])) {
    $nomEditat = $mysqli->real_escape_string($_POST["nomEditat"]);
    if ($nomEditat != "") {
        $queryEditar = "UPDATE AUTORS SET NOM_AUT = '" . $nomEditat . "', FK_NACIONALITAT = '" . $_POST['editarNacionalitat'] . "' WHERE ID_AUT = " . $_POST['botoConfirmar'];
        $mysqli->query($queryEditar);
    }

}

//------------- CONTADOR -----------------------------------------------
if ($_POST["cerca"] != "") {
    $queryContador = "SELECT COUNT(*) as total FROM AUTORS WHERE ID_AUT='" . $_POST['cerca'] . "' OR NOM_AUT LIKE '%" . $_POST["cerca"] . "%'";
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
$offset = $numberofrows * ($currentPage - 1);
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

//---------- BOTÓ DE AFEGIR ---------
if (isset($_POST["botoAfegir"]) && isset($_POST["afegirNacionalitat"])) {
    $queryMaxId = "SELECT MAX(ID_AUT)+1 AS 'MAXID' FROM AUTORS";
    $maxId = "";
    if ($cursor = $mysqli->query($queryMaxId) or die($queryMaxId)) {
        while ($row = $cursor->fetch_assoc()) {
            $maxId = $row["MAXID"];
        }
    }
    if ($textAfegir != "") {
        if ($_POST["afegirNacionalitat"] == "null") {
            $queryAfegir = "INSERT INTO AUTORS (ID_AUT,NOM_AUT,FK_NACIONALITAT) VALUES (" . $maxId . ", '" . $textAfegir . "'," . null . ");";
        } else {
            $queryAfegir = "INSERT INTO AUTORS (ID_AUT,NOM_AUT,FK_NACIONALITAT) VALUES (" . $maxId . ", '" . $textAfegir . "','" . $_POST["afegirNacionalitat"] . "');";
        }

        $mysqli->query($queryAfegir);
    }

}

//----------------- MIRO SI HAGO LA QUERY POR LA BUSQUEDA O NORMAL ---------
if (isset($_POST["botocerca"])) {
    $query = "SELECT ID_AUT, NOM_AUT, FK_NACIONALITAT FROM AUTORS WHERE ID_AUT ='" . $_POST['cerca'] . "' OR NOM_AUT LIKE '%" . $_POST["cerca"] . "%' ORDER BY $orderby LIMIT $offset,$numberofrows";
} else {
    $query = "SELECT ID_AUT, NOM_AUT, FK_NACIONALITAT FROM AUTORS WHERE ID_AUT ='" . $_POST['cerca'] . "' OR NOM_AUT LIKE '%" . $_POST["cerca"] . "%' ORDER BY $orderby LIMIT $offset,$numberofrows";
}
//---------------------------------------------------------------------------

?>
    </script>
</head>
<body>
<div class="general">
<form action="autors-segon.php" method="post" class="formulari" id="formulari">
<input type="text" name="cerca" value="<?=$cerca?>">
<input type="submit" name="botocerca" value="Cerca"  id="botoCerca"><br><br>
<input type="hidden" name="pagina" value="<?=$currentPage?>">
<input type="hidden" name="hiddenOrdre" value="<?=$orderby?>">
<button type="submit" name="id_aut_desc">CODI DESCENDENT</button>
<button type="submit" name="id_aut_asc">CODI ASCENDENT</button>
<button type="submit" name="nom_aut_desc">NOM DESCENDENT</button>
<button type="submit" name="nom_aut_asc">NOM ASCENDENT</button><br><br>
<input type="submit" name="primer" value="&laquo&laquo">
<input type="submit" name="anterior" value="&laquo">
<input type="submit" name="posterior" value="&raquo">
<input type="submit" name="darrer" value="&raquo&raquo">
</form>
    <table class="taula">
        <tr>
        <td>CODI</td>
        <td>NOM</td>
        <td>NACIONALITAT</td>
        <td>ACCIÓ</td>
        </tr>
        <?php
if ($cursor = $mysqli->query($query) or die($query)) {
    while ($row = $cursor->fetch_assoc()) {

        if (isset($_POST["botoEditar"]) && $row["ID_AUT"] == $_POST["botoEditar"]) {
            echo "<tr>";
            echo "<td>" . $row["ID_AUT"] . "</td>";
            echo "<td><input type='text' form='formulari' name='nomEditat' value='" . $row["NOM_AUT"] . "'></td>";
            echo "<td>" . crearSelect($mysqli, "editarNacionalitat", "NACIONALITATS", "NACIONALITAT", "NACIONALITAT", "NACIONALITAT", "formulari", $row["FK_NACIONALITAT"]) . "</td>";
            echo "<td><button type='submit' name='botoConfirmar' form='formulari' value='" . $row["ID_AUT"] . "' class='botoTaula'>Confirmar</button><button type='submit' name='botoCancelar' form='formulari' class='botoTaula'>Cancelar</button></td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td>" . $row["ID_AUT"] . "</td>";
            echo "<td>" . $row["NOM_AUT"] . "</td>";
            echo "<td>" . $row["FK_NACIONALITAT"] . "</td>";
            echo "<td><button type='submit' name='botoEditar' form='formulari' value='" . $row["ID_AUT"] . "' class='botoTaula'>Editar</button><button type='submit' name='botoBorrar' form='formulari' value='" . $row["ID_AUT"] . "' class='botoTaula'>Borrar</button></td>";
            echo "</tr>";
        }

    }
}
?>
<tr>
<td colspan="4" class="pagines"><?=$currentPage?>/<?=$totalPagines?></td>
</tr>
    </table>
    <div class="afegir-out">
    <div class="afegir">
    <form action="autors-segon.php" method="post">
    <label for="textAfegirAutor">Nom: </label>
    <input type="text" name="textAfegirAutor" id="textAfegirAutor" form="formulari">
    <label for="afegirNacionalitat">Nacionalitat: </label>
    <?php echo crearSelect($mysqli, "afegirNacionalitat", "NACIONALITATS", "NACIONALITAT", "NACIONALITAT", "NACIONALITAT", "formulari", ""); ?>
        <button type="submit" form="formulari" name="botoAfegir">Afegir</button>
    </select>
    </form>
    </div>

    </div>
</body>
</html>