<?php require('start.php'); ?>
<?php require('Class/LinkAPI.php');
    $link = new linkAPI();
    $link->resetLink();
?>
<?php

if (isset($_GET['linkForm'])){
    echo "deja fait";
    $link->setLink($_GET['linkForm']);
}else{
    $link->resetLink();
    if (isset($_POST['niveau'])){
        $link->addToLink("&refine.niveau_lib=".$_POST['niveau']);
        //$_URL_dyn = $_URL_dyn."&refine.niveau_lib=".$_POST['niveau'];
    }
    if (isset($_POST['domaine'])){
        $link->addToLink("&refine.sect_disciplinaire_lib=".$_POST['domaine']);
        //$_URL_dyn = $_URL_dyn."&refine.sect_disciplinaire_lib=".$_POST['domaine'];
    }
    if (isset($_POST['region'])){
        $link->addToLink("&refine.reg_etab_lib=".$_POST['region']);
        //$_URL_dyn = $_URL_dyn."&refine.reg_etab_lib=".$_POST['region'];
    }
}
//echo $_URL_dyn;

if (isset($_GET['linkScho'])){
    $_URLetab = $_GET['linkScho'];
}else{
    $_URLetab = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&rows=323&facet=uai&facet=type_d_etablissement&facet=com_nom&facet=dep_nom&facet=aca_nom&facet=reg_nom&facet=pays_etranger_acheminement";
}

$list = $link->parsedLink();
$total = sizeof($list);

$json = file_get_contents($_URLetab);
$parsed_json = json_decode($json, true);
$listEtab = $parsed_json['records'];


if (isset($_GET['page'])){
    $page = $_GET['page'];
}else {
    $page = 1;
}

$nb = 20;
$limit = min($nb * $page, $total);
$begin = max($limit - $nb, 0);

function afficheTab($_begining, $_ending, $_list_var){
    //$limit - $page
    for ($i = $_begining; $i <= ($_ending); $i++){
        $form = $_list_var[$i]['fields'];
        echo '<div id="formation">';
        echo '<titleF>'.$form['libelle_intitule_1'].'</titleF>';
        echo '<h5>'."Académie : ".$form['aca_ins_lib'].'</h5>';
        echo '<h5>'."Ville : ".$form['com_ins_lib'].'</h5>';
        echo '<p>'.$form['reg_ins_lib'].'</p>';
        echo '</div>';
    }
}

?>

<h1 ><a href="index.php">Ta future formation</a></h1>

<div id="tabs">

    <?php require ("menuBar.php"); ?>

    <div id="tableau" >
        <?php

        if ($page>1){
            echo '<a href="recherche.php?page='.($page-1).'linkForm='.($link->getLink()).'linkScho='.($_URLetab).'"> previous</a>';
        }

        echo '<h3>'.$begin.'...'.$limit." / ".$total.'</h3>';

        if ($limit != $total) {
            echo '<a href="recherche.php?page='.($page + 1).'&linkForm='.($link->getLink()).'&linkScho='.($_URLetab).'"> next</a>';
        }

        afficheTab($begin, $limit - $page, $list);
        ?>
    </div>

    <div id="map">
        <ul>
            <li>
                <div id="formMap">
                    <h3>Formations</h3>
                    <?php

                    ?>
                </div>
            </li>
            <div id="mapP">
                <script >
                    var mymap = L.map('mapP').setView([46.227638, 2.213749], 6);

                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 18,
                        attribution: 'Map data &copy; &lt;a href="https://www.openstreetmap.org/">OpenStreetMap&lt;/a> contributors, ' +
                            '&lt;a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA&lt;/a>, ' +
                            'Imagery © &lt;a href="https://www.mapbox.com/">Mapbox&lt;/a>',
                        id: 'mapbox/streets-v11'
                    }).addTo(mymap);

                    <?php
                    for ($i = $begin; $i <= ($limit - $page); $i++){
                        $ecole = $list[$i];
                        foreach($listEtab as $etab) {
                            if (isset($etab['fields']['uai'])) {
                                if (strcmp($etab['fields']['uai'], $ecole['fields']['etablissement']) == 0) {
                                    $localisation = $etab['fields']['coordonnees'];
                                    echo "var m = L.marker([" . $localisation[0] . "," . $localisation[1] . "]).addTo(mymap);";
                                    if (isset($etab['fields']['url'])) {
                                        $url = $etab['fields']['url'];
                                        if (isset($ecole['fields']['reg_ins'])) {
                                            $formation = $ecole['fields']['reg_ins'];
                                            echo "m.bindPopup('<a href=\"" . $url . "\">" . $formation . "</a>');";
                                            echo "m.bindPopup('<a href=\"https://www.google.com/maps/dir/domicile/".$localisation[0].",".$localisation[1]."/@".$localisation[0].",".$localisation[1].",14z target=\"blank\"\">" . $formation . "</a>');";
                                        } else {
                                            echo "m.bindPopup('<a href=\"" . $url . "\">" . $url . "</a>');";
                                        }
                                    } else {
                                        if (isset($ecole['fields']['discipline_lib'])) {
                                            echo "m.bindPopup('" . $ecole['fields']['discipline_lib'] . "');";
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </script>
            </div>
        </ul>



    </div>

    <div id="recherche">
        <?php
        $newLink = new linkAPI();
        $newLink->resetLink();
        $json = file_get_contents($newLink->getLink());
        $parsed_json = json_decode($json, true);

        ?>
        <FORM  method="post" action="recherche.php">

            <table id="tableRecherche" >
                <div id="divR">

                    <div class="submit">
                        <input type="submit" value="Rechercher" class="bouton">
                        <input type="reset" value="Réinitialisé la recherche" class="bouton">
                    </div>
                    </br>

                    <SELECT class="selectRech" name="niveau">
                        <?php
                        echo $_POST['niveau'];
                        echo $_POST['domaine'];
                            if (isset($_POST['niveau'])){
                                echo "<OPTION value='".$_POST['niveau']."' disabled selected hidden>".$_POST['niveau']."</OPTION>";
                            }else{
                                echo "<OPTION value='' disabled selected hidden>niveau d'étude</OPTION>";
                            }
                        ?>
                        <OPTION value="" disabled selected hidden></OPTION>
                        <?php
                        $niveau_lib = $parsed_json['facet_groups'][25]['facets'];
                        foreach ($niveau_lib  as $value) {
                            echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                        }
                        ?>
                    </SELECT>

                    <br/>

                    <SELECT class="selectRech" name="domaine">
                        <OPTION value="" disabled selected hidden></OPTION>
                        <?php
                        $sect_disciplinaire_lib = $parsed_json['facet_groups'][30]['facets'];
                        foreach ($sect_disciplinaire_lib as $value) {
                            echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                        }
                        ?>
                    </SELECT>
                    </br>
                    <SELECT class="selectRech" name="region">
                        <OPTION value="" disabled selected hidden></OPTION>
                        <?php
                        $reg_ins_lib = $parsed_json['facet_groups'][19]['facets'];
                        foreach ($reg_ins_lib as $value) {
                            echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                        }
                        ?>
                    </SELECT>
                </div>
            </table>
        </FORM>
    </div>

    <div id="about">
        <div class="about">
            <h3>Site réalisé en cours de Programmation Web répondant à la problématique suivante :</h3>
            <img class="question-picture"
                 src="Images/problematique.png"
                 alt="Je suis futur bachelier ou étudiant en bac +x ,à quelle formation puis-je m’inscrire l’an prochain et où ?">
        </div>
    </div>
<?php require ("end.php"); ?>


