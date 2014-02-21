<?php

include_partial('pedidos', array(
    'reservasRealizadas' => $reservasRealizadas,
    'reservasRecibidas' => $reservasRecibidas,
    'reservasRecibidasOportunidades' => $reservasRecibidasOportunidades,
    'fechaReservasRealizadas' => $fechaReservasRealizadas));

