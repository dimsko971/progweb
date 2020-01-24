<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Ta futur formation</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="generator" content="Geany 1.29" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="style.css"/>
    <script type="text/javascript" src="JavaScript.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#tabs" ).tabs();
        } );
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
            integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
            crossorigin=""></script>
</head>

<body onload="initialize()">

<h1 ><a href="index.php">Ta future formation</a></h1>

<div id="tabs">
    <ul>
        <li><a class="active" href="#tableau">tableau</a></li>
        <li><a href="#map">Carte</a></li>
        <li><a href="#recherche">Nouvelle recheche</a></li>
        <li style="float:right"><a class="active" href="#about">About</a></li>
    </ul>

    <?php
    if (isset($_POST['niveau'])){
        $_niveau = $_POST['niveau'];
    }
    if (isset($_POST['domaine'])){
        $_domaine = $_POST['domaine'];
    }
    if (isset( $_POST['region'])){
        $_region = $_POST['region'];
    }
    $_URLstat = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=500&sort=-rentree_lib&facet=rentree_lib&facet=etablissement_type2&facet=etablissement_type_lib&facet=etablissement&facet=etablissement_lib&facet=champ_statistique&facet=dn_de_lib&facet=cursus_lmd_lib&facet=diplome_rgp&facet=diplome_lib&facet=typ_diplome_lib&facet=diplom&facet=niveau_lib&facet=disciplines_selection&facet=gd_disciscipline_lib&facet=discipline_lib&facet=sect_disciplinaire_lib&facet=spec_dut_lib&facet=localisation_ins&facet=com_etab&facet=com_etab_lib&facet=uucr_etab&facet=uucr_etab_lib&facet=dep_etab&facet=dep_etab_lib&facet=aca_etab&facet=aca_etab_lib&facet=reg_etab&facet=reg_etab_lib&facet=com_ins&facet=com_ins_lib&facet=uucr_ins&facet=dep_ins&facet=dep_ins_lib&facet=aca_ins&facet=aca_ins_lib&facet=reg_ins&facet=reg_ins_lib&facet=reg_diplome_lib&refine.rentree_lib=2017-18";
    $_URLetab = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&rows=323&facet=uai&facet=type_d_etablissement&facet=com_nom&facet=dep_nom&facet=aca_nom&facet=reg_nom&facet=pays_etranger_acheminement";

    $_URLdyn = $_URLstat;
    if (isset($_niveau)){
        $_URLdyn = $_URLdyn."&refine.niveau_lib=".$_niveau;
    }
    if (isset($_domaine)){
        $_URLdyn = $_URLdyn."&refine.sect_disciplinaire_lib=".$_domaine;
    }
    if (isset($_region)){
        $_URLdyn = $_URLdyn."&refine.reg_etab_lib=".$_region;
    }

    $json = file_get_contents($_URLdyn);
    $parsed_json = json_decode($json, true);
    $list = $parsed_json['records'];

    $total = sizeof($list);

    $json = file_get_contents($_URLetab);
    $parsed_json = json_decode($json, true);
    $listEtab = $parsed_json['records'];


    if (isset($_GET['page'])){
        $page = $_GET['page'];
    }else {
        $page = 1;
    }

    $nb = 10;
    $limit = min($nb * $page, $total);
    $begin = max($limit - $nb, 0);
    ?>

    <div id="tableau" >
        <?php

        if ($page>1){
            echo '<a href="recherche.php?page='.($page-1) .'"> previous</a>';
        }

        echo '<h3>'.$begin.'...'.$limit." / ".$total.'</h3>';
        echo '<a href="recherche.php?page='.($page+1). '"> next</a>';

        echo '<a href="https://www.google.com/maps">google maps</a>';

        for ($i = $begin; $i <= ($limit - $page); $i++){
            $form = $list[$i]['fields'];
            echo '<div id="formation">';
            echo '<titleF>'.$form['libelle_intitule_1'].'</titleF>';
            echo '<h5>'."Académie : ".$form['aca_ins_lib'].'</h5>';
            echo '<h5>'."Ville : ".$form['com_ins_lib'].'</h5>';
            echo '<p>'.$form['reg_ins_lib'].'</p>';
            echo '</div>';
        }
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
        $json = file_get_contents("$_URLstat");
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
                        <OPTION value="" disabled selected hidden>Niveau d'etude</OPTION>
                        <?php
                        $niveau_lib = $parsed_json['facet_groups'][25]['facets'];
                        foreach ($niveau_lib  as $value) {
                            echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                        }
                        ?>
                    </SELECT>

                    <br/>

                    <SELECT class="selectRech" name="domaine">
                        <OPTION value="" disabled selected hidden>Domaines d'activités</OPTION>
                        <?php
                        $sect_disciplinaire_lib = $parsed_json['facet_groups'][30]['facets'];
                        foreach ($sect_disciplinaire_lib as $value) {
                            echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                        }
                        ?>
                    </SELECT>
                    </br>
                    <SELECT class="selectRech" name="region">
                        <OPTION value="" disabled selected hidden>Region</OPTION>
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
</body>
</html>


