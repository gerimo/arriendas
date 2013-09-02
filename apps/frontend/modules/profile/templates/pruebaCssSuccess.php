<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>

<div class="main_box_1">
    <div class="main_box_2">


        <div class="main_col_izq">
            <?php include_component('profile', 'profile') ?>
            <?php include_component('profile', 'userdocsvalidation') ?>

        </div><!-- main_col_izq -->

        <!--  contenido de la seccion -->
        <div class="main_contenido">
            <div class="barraSuperior"><p>TUS RESERVAS DE AUTOS</p></div>
        </div>

        <?php include_component('profile', 'preguntasFrecuentes') ?>
        <?php include_component('profile', 'autos') ?>

    </div><!-- main_box_2 -->
</div><!-- main_box_1 -->
