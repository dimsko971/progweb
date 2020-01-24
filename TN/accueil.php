<!DOCTYPE html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="style.css">
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   
    <!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.js"></script>
   
  <title>Accueil</title>
   <meta charset="utf-8" />
</head>

<body>
 <header class="header">
<ul id="navigation">
  <li><a href="accueil.php" title="accueil">Accueil</a></li>
  <li><a href="#" title="A propos de nous">A propos de nous</a></li>

</ul>
    </header>

<!-- Contenu principal -->
<h1><br>Trouvez votre formation</br></h1>


<form method ="get" action="resultat.php">
    <fieldset>
		<legend>Recherche de formation affinée</legend>




   <p>    
	   <label for="niveau"> Niveau d'étude recherché</label> 
			<select id="niveau" name ="niveau">

	<?php
	
		$json = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&sort=-rentree_lib&facet=niveau_lib&refine.rentree_lib=2017-18");

		$parsed_json = json_decode($json,true);

		$facets_niveau = $parsed_json['facet_groups'][0]["facets"];

		foreach($facets_niveau as $facet_niv){

			$val = $facet_niv["name"]; 
			echo  "<option value=\"$val\">$val</option>";

		 }
         
         
	?>


		</select><br>

       <label for="departement">Département</label>
			<select id="departement" name ="departement">

	<?php

		$json = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&sort=-rentree_lib&facet=dep_etab_lib&refine.rentree_lib=2017-18");

		$parsed_json = json_decode($json,true);

		$facets_departements = $parsed_json['facet_groups'][0]["facets"];

		foreach($facets_departements as $facet_dep){

			$val = $facet_dep["path"]; 
			echo  "<option value=\"$val\">$val </option>";

		}	
    
	?>
        
         </select>
        
        
        </select><br>
             
        <label for="formation">Formation</label>
        <select id="formation" name ="formation">
            
	<?php
 
		$json = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&sort=-rentree_lib&facet=typ_diplome_lib&refine.rentree_lib=2017-18");

		$parsed_json = json_decode($json,true);

		$facets_formation = $parsed_json['facet_groups'][0]["facets"];

		foreach($facets_formation as $facet_forma){

			$val = $facet_forma["path"]; 
			echo  "<option value=\"$val\">$val </option>";

		}
         
         
	?>
            
        </select><br>
         
         
	<label for="filière">Filière</label>
		<select id="filière" name ="filière">
         
         
	<?php

	$json = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&sort=-rentree_lib&facet=libelle_intitule_1&refine.rentree_lib=2017-18");

	$parsed_json = json_decode($json,true);

	$facets_filliere = $parsed_json['facet_groups'][0]["facets"];

	foreach($facets_filliere as $facet_fill){

			$val = $facet_fill["path"]; 
			echo  "<option value=\"$val\">$val </option>";

		}
         
         
	?>
		</select><br>
  <br></br>
	
	<input class="button" type="submit" value="Rechercher" />
	</fieldset>
	</p>


</form> 
<fieldset>
    <legend>Carte</legend>
</fieldset>


 <div id="mapid"></div>
 <script>var maCarte = L.map('mapid').setView([48.53,2.14],10)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: ' <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(maCarte);

</script>



</body>
</html>
