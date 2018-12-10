<?php
function crearSelect($mysqli, $nomSelect, $table, $column, $internValue, $extValue, $form, $fkNacionalitat, $nulo = true)
{
    if ($table == "NACIONALITATS" && $column == "NACIONALITAT") {
        $queryMostrar = "SELECT " . $column . " FROM" . " $table";
        $cadena = "<select name='" . $nomSelect . "' form='" . $form . "'>";
        if ($nulo) {
            $cadena .= "<option value='NULL'>Tria una opció</option>";
        }
        if ($cursor = $mysqli->query($queryMostrar) or die($queryMostrar)) {
            while ($row = $cursor->fetch_assoc()) {
                if ($row[$internValue] == $fkNacionalitat) {
                    $cadena = $cadena . "<option value='" . $row[$internValue] . "' selected>" . $row[$extValue] . "</option>";
                } else {
                    $cadena = $cadena . "<option value='" . $row[$internValue] . "'>" . $row[$extValue] . "</option>";
                }

            }
        }
        $cadena .= "</select>";
    }
    return $cadena;
}
