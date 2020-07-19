<?php
$pokeID = rand(1, 807);
$poke= json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $pokeID), true)['name'];
$_GET['poke'] = $poke;

include 'index.php';