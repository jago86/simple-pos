<?php

function toCents($value)
{
    // Multiplicar por 100 y redondear hacia abajo para obtener un número entero
    $cents = floor($value * 100);

    return $cents;
}
