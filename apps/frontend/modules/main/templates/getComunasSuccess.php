<?php

echo '<option value="0">Selecciona la Comuna</option>';
foreach($comunas as $c){
    echo '<option value="'.$c->getCodigoInterno().'">'.$c->getNombre().'</option>';
}

?>
