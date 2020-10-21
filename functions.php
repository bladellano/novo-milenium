<?php
// All functions

function url(string $path):string
{
    if($path)
        return URL_BASE_ADMIN. $path;
    return URL_BASE_ADMIN;
}

function message (string $message, string $type): string
{
    return "<div class=\"message {$type}\">{$message}</div>";
}

function toDatePtBr($date)
{
    $DateTime = new DateTime($date);
    return $DateTime->format("d/m/Y H:i:s");
}

function resume($string, $chars) 
{
    return mb_strimwidth($string, 0, $chars + 3, "...");
}

function formatPrice($vlprice)
{
    if (!$vlprice > 0) $vlprice = 0;
    return number_format($vlprice, 2, ",", ".");
}