<?php

echo '<option value="0">---Comunas---</option>';
foreach($comunas as $c){
    echo '<option value="'.$c->getCodigoInterno().'">'.$c->getNombre().'</option>';
}

?>
