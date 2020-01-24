<!DOCTYPE html>
<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
        <script type="text/javascript" src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>
		<link type="text/css" rel="stylesheet" href="style.css">
		
<header class="header">
	<title>Résultats</title>
<ul id="navigation">
  <li><a href="accueil.php" title="accueil">Accueil</a></li>
  <li><a href="#" title="A propos de nous">A propos de nous</a></li>
</ul>
</header>
</head>

<body onload="initialize()">
  <h1><br>Résultats</br></h1>
  
   <fieldset>
    <legend>Récapitulatif</legend><br>
   
   <?php
        echo "Niveau: "; 
        echo $_GET['niveau'];
        echo " ; Département: ";
        echo $_GET['departement'];
        echo " ; Formation: ";
        echo  $_GET['formation'];
        echo " ; Filière: ";
        echo $_GET['filière'];


    ?>
     </fieldset> 
    <fieldset>
    <legend>Résultats</legend><br>


 <?php

    $URLstatic = "https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=50&sort=-rentree_lib&facet=niveau_lib&refine.rentree_lib=2017-18" ;
    $niveau =  "&refine.niveau_lib=".$_GET['niveau'];
    //~ echo $niveau;
    $dep = "&refine.dep_etab_lib=".$_GET['departement'];
    $formation = "&refine.typ_diplome_lib=".$_GET['formation'];
    $filiere = "&refine.libelle_intitule_1=".$_GET['filière'];
    //.$filiere.$formation.$dep
    $json = file_get_contents($URLstatic.$niveau.$dep.$filiere);
	$parsed_json = json_decode($json,true);

    //~ echo $URLstatic.$niveau.$filiere;
    
    //~ $json_geoloc = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&facet=uai&facet=type_d_etablissement&facet=com_nom&facet=dep_nom&facet=aca_nom&facet=reg_nom&facet=pays_etranger_acheminement");
	//~ $parsed_json2 = json_decode($json_geoloc,true);
	//~ var_dump($parsed_json2);
    
    
    print "<br>";
    
    //~ $facets_records = $parsed_json['records'][$cpt]["fields"];
	for ($cpt = 0 ; $cpt < sizeof($parsed_json['records']); $cpt++){
        //~ foreach($facets_records as $value) {
		echo "<h1>";
		$nomEtab = $parsed_json['records'][$cpt]["fields"]["etablissement_lib"];
		echo "$nomEtab";
		echo "</h1>";
		echo"<p> Académie: ";
		$acaEtab=  $parsed_json['records'][$cpt]["fields"]["aca_etab_lib"];
		echo"$acaEtab";
		echo "<br>" ;
		echo "Diplôme :";
		$typeDip=  $parsed_json['records'][$cpt]["fields"]["typ_diplome_lib"];
		echo $typeDip;
		echo "<br>" ;
		echo "Discipline : ";
		$discipline=  $parsed_json['records'][$cpt]["fields"]["discipline_lib"];
		echo $discipline;
		echo "<br>" ;
		echo "Effectifs : ";
		$effectif=  $parsed_json['records'][$cpt]["fields"]["effectif_total"];
		echo $effectif;
		echo "</p>";
		 
		print "<br>";
	$s="https://data.enseignementsup-recherche.gouv.fr/";
	$test = $parsed_json['records'][$cpt]["fields"]["etablissement"];
	$lien2= "$s/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&sort=uo_lib&facet=uai&refine.uai=$test";
                $json2  = file_get_contents("$lien2");
                $data2 = json_decode($json2, true);
                $nb2 = sizeof($data2['records']);
                //~ $nb2 = count($data2["records"]);


                if ( $nb2 >=1) {
                    $site = $data2["records"][0]["fields"]["url"]  ;
                    $facet_fil2 = $data2["records"][0]["fields"]["coordonnees"]  ;
                        echo "L.marker([$facet_fil2[0], $facet_fil2[1]]).addTo(carte).bindPopup('').openPopup();";
              } else { $site ="";}
              
		  }
	
	//~ $facets_records2 = $parsed_json2['records'][1];
	//~ var_dump($facets_records2);
	//~ $count = 0;
	//~ foreach($facets_records2 as $value) {
	//~ $facets_records = $parsed_json2['records'][$cpt][;
	//~ for ($cpt = 0 ; $cpt < sizeof($parsed_json2['records']); $cpt++){
	//~ $putain = $parsed_json['records'][$cpt];
		//~ echo "$putain";
	
	
		//~ $val = $value["coordonnees"][0];
		//~ $val2 = $value["coordonnees"][1];
		
		//~ var_dump($val);
		//~ echo  "$val" ;
		//~ echo "<br>";
		//~ echo "$val2";


	
       print "<br>";
         
	?>	
	</fieldset>
</legend>
<div id="map" style="width:100%; height:90%"></div>


<script type="text/javascript">
	
	
    function initialize() {
        var map = L.map('map').setView([48.833, 2.333], 7); 

        var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', { 
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        });
    
        map.addLayer(osmLayer);
       
    }
    <?php
    for ($cpt = 0 ; $cpt < sizeof($parsed_json['records']); $cpt++){
		$nomEtab = $parsed_json['records'][$cpt]["fields"]["etablissement_lib"];

		$acaEtab=  $parsed_json['records'][$cpt]["fields"]["aca_etab_lib"];

		$typeDip=  $parsed_json['records'][$cpt]["fields"]["typ_diplome_lib"];

		$discipline=  $parsed_json['records'][$cpt]["fields"]["discipline_lib"];

		$effectif=  $parsed_json['records'][$cpt]["fields"]["effectif_total"];

	$s="https://data.enseignementsup-recherche.gouv.fr/";
	$test = $parsed_json['records'][$cpt]["fields"]["etablissement"];
	$lien2= "$s/api/records/1.0/search/?dataset=fr-esr-principaux-etablissements-enseignement-superieur&sort=uo_lib&facet=uai&refine.uai=$test";
                $json2  = file_get_contents("$lien2");
                $data2 = json_decode($json2, true);
                $nb2 = sizeof($data2['records']);
                //~ $nb2 = count($data2["records"]);


                if ( $nb2 >=1) {
                    if (isset($data2["records"][0]["fields"]["url"])) {
                        $site = $data2["records"][0]["fields"]["url"];
                        $facet_fil2 = $data2["records"][0]["fields"]["coordonnees"];
                        echo "var m = L.marker([" . $facet_fil2[0]. "," . $facet_fil2[1] . "]).addTo(mymap);";
                        //echo "L.marker([$facet_fil2[0], $facet_fil2[1]]).addTo(carte).bindPopup(' $value3 ').openPopup();";
                        //echo "m.bindPopup('<a href=\"".$etab['fields']['url']."\">".$ecole['fields']['discipline_lib']."</a>');";
                    }
              } else { $site ="";}
              
		  }
		  ?>
		
</script>

<script>
	var marker = L.marker([46.1482, -1.15506]).addTo(map);
</script>

	</body>
</html>
