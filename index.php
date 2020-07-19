<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokedex</title>
    <link rel="stylesheet" href="pokedex.css">
</head>
<body>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if(!empty($_GET['poke'])) {
    $data = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . strtolower($_GET['poke']));
    $pokemon = json_decode($data, true);
    $species_data = file_get_contents("https://pokeapi.co/api/v2/pokemon-species/" . strtolower($_GET['poke']));
    $species = json_decode($species_data, true);
    $evolution_data = file_get_contents($species["evolution_chain"]["url"]);
    $evolutions = json_decode($evolution_data, true);
    $first_form_data_get = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $evolutions["chain"]["species"]['name']);
    $first_form_data = json_decode($first_form_data_get, true);
    $first_form = $first_form_data['sprites']['front_default'];
    $second_form = null;
    $third_form = null;
    //var_dump($evolutions);

    if (count($evolutions['chain']['evolves_to']) >= 1) {
        //var_dump($evolutions['chain']['evolves_to']);
        $second_form = $evolutions['chain']['evolves_to'];
        if (count($second_form[0]['evolves_to']) >= 1) {
            $third_form = $evolutions['chain']['evolves_to'][0]['evolves_to'];
            //var_dump($third_form);
        }
    }
    $types = $pokemon['types'];
    $moves = $pokemon['moves'];
    $nrOfMoves = count($moves);
}
?>

<div class="pokedex">
    <div class="left-container">
        <div class="left-container_main-section-container">
            <div class="left-container_main-section">
                <div class="main-section_white">
                    <div class="main-section_black">
                        <div class="main-screen hide">
                            <div class="screen_header">
                                <span class="poke-name">
                                    <?php
                                    if(!empty($_GET['poke'])) {
                                        echo ucfirst($pokemon['name']) . "<br>";
                                    }
                                    ?>
                                </span>
                                <span class="poke-id">
                                    <?php
                                    if(!empty($_GET['poke'])) {
                                        echo "ID: " . $pokemon['id'];
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="screen_image">
                                <!--<img src="" class="poke-front-image" alt="front">
                                <img src="" class="poke-back-image" alt="back">-->
                                <?php
                                if(!empty($_GET['poke'])) {
                                    echo "<img class='poke-front-image' src='" . $pokemon['sprites']['front_default'] . "'/><br>";
                                }
                                ?>
                            </div>
                            <div class="screen_description">
                                <div class="stats_types">
                                    <?php
                                    if(!empty($_GET['poke'])) {
                                        foreach ($types as $type) {
                                            echo "<span class='poke-type " . $type['type']['name'] . "'></span>";
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="screenStats">
                                    <p class="stats_weight">
                                            <?php
                                            if(!empty($_GET['poke'])) {
                                                echo "weight: <span class=\"poke-weight\">";
                                            echo $pokemon['weight'] / 10 . "kg<br>";
                                            echo "</span>";
                                            }
                                            ?>
                                    </p>
                                    <p class="stats__height">
                                            <?php
                                            if(!empty($_GET['poke'])) {
                                                echo "height: <span class='poke-height'>";
                                                echo $pokemon['height'] / 10 . "m<br>";
                                                echo "</span>";
                                            }
                                            ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-container_controllers">
                    <div class="left-container_buttons">
                        <?php
                        if(!empty($_GET['poke'])){
                        var_dump($pokemon['id']);
                        if($pokemon['id'] !== 1){
                            echo "<a href='./index.php?poke=" . --$pokemon['id'] . "' class=\"link-button\">Previous</a>";
                        } else {
                            echo "<a href='./index.php?poke=807' class='LinkButton'>Previous</a>";
                        }
                        echo "<br>";
                        if($pokemon['id'] !== 807){
                            echo "<a href='./index.php?poke=" . ++$pokemon['id'] . "' class=\"LinkButton\">Next</a>";
                        } else {
                            echo "<a href='./index.php?poke=1' class='LinkButton'>Next</a>";
                        }
                        }
                        ?>

                        <!--<div class="left-button" id="previous">Prev</div>
                        <div class="right-button" id="next">Next</div>-->
                        <!--<div class="search-button" id="search">Search</div>-->

                        <form action="index.php" method="get">
                            <input type="text" name="poke">
                            <input type="submit" value="Search"><br>
                        </form>
                        <form action="random.php" method="get">
                            <input type="text" name="random" value="random" style="display: none">
                            <input type="submit" value="Random">
                        </form>
                    </div>
                    <div id="hidden"><input type="text" placeholder="Pokemon" id="input">
                        <input type="button" id="temp" value="Go"></div>
                </div>
            </div>
            <div class="left-container_right">
                <div class="left-container_hinge"></div>
                <div class="left-container_hinge"></div>
            </div>
        </div>
    </div>
    <div class="right-container">
        <div class="right-container_black">
            <div class="moves">
                <?php
                $moveCount = 1;
                if(!empty($_GET['poke'])) {
                    echo "Moves: <br>";
                    if ($nrOfMoves = count($moves) >= 4) {
                        foreach (array_rand($moves, 4) as $index) {
                            echo $moveCount++ . ". " . $moves[$index]['move']['name'] . "<br>";
                        }
                    } else {
                        foreach ($moves as $move) {
                            echo $moveCount++ . ". " . $move['move']['name'] . "<br>";
                        }
                    }
                }
                ?>
            </div>
            <div class="evolutions">
                <?php
                if(!empty($_GET['poke'])) {
                    echo "Evolutions: <br><hr>";
                    echo "<img src='" . $first_form . "' title='" . ucfirst($first_form_data['name']) . "'/>";
                    //var_dump(json_decode($data, true));

                    if ($second_form) {
                        foreach ($second_form as $evo) {
                            $second_evo_data = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $evo['species']['name']);
                            $second_evo = json_decode($second_evo_data, true);
                            $sprite = $second_evo['sprites']['front_default'];
                            echo "<img src='" . $sprite . "' title='" . ucfirst($second_evo['name']) . "'/>";
                        }
                    }
                    if ($third_form) {
                        foreach ($third_form as $evo) {
                            $third_evo_data = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $evo['species']['name']);
                            $third_evo = json_decode($third_evo_data, true);
                            $sprite = $third_evo['sprites']['front_default'];
                            echo "<img src='" . $sprite . "' title='" . ucfirst($third_evo['name']) . "'/>";
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div class="right-container_buttons">
            <div class="left-button">Prev evolution</div>
            <div class="right-button">Next evolution</div>
            <!--        <div class="random-button">Random</div>
                    <div class="search-button">Search</div>-->

        </div>
    </div>
</div>


<!---->

<?php
/*


    echo "Evolutions: <br><hr>";
    echo "<img src='" . $first_form . "' title='" . ucfirst($first_form_data['name']) . "'/>";
    //var_dump(json_decode($data, true));

    if($second_form){
        foreach ($second_form as $evo){
            $second_evo_data = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $evo['species']['name']);
            $second_evo = json_decode($second_evo_data, true);
            $sprite = $second_evo['sprites']['front_default'];
            echo "<img src='" . $sprite . "' title='" . ucfirst($second_evo['name']) . "'/>";
        }
    }
    if($third_form){
        foreach ($third_form as $evo){
            $third_evo_data = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $evo['species']['name']);
            $third_evo = json_decode($third_evo_data, true);
            $sprite = $third_evo['sprites']['front_default'];
            echo "<img src='" . $sprite . "' title='" . $third_evo['name'] . "'/>";
        }
    }


}


*/?>

</body>
</html>
