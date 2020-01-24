<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Ta futur formation</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="generator" content="Geany 1.29" />
        <link rel="stylesheet" href="style.css"/>
        <script type="text/javascript" src="JavaScript.js"></script>
        <script type="text/javascript" src="js/jquery-3.4.1.min"></script>

        <?php
        //$json = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=0&sort=-rentree_lib&facet=sect_disciplinaire_lib&facet=reg_ins_lib&facet=niveau_lib&facet=typ_diplome_lib&refine.rentree_lib=2017-18");
        $json = file_get_contents("https://data.enseignementsup-recherche.gouv.fr/api/records/1.0/search/?dataset=fr-esr-principaux-diplomes-et-formations-prepares-etablissements-publics&rows=200&sort=-rentree_lib&facet=rentree_lib&facet=etablissement_type2&facet=etablissement_type_lib&facet=etablissement&facet=etablissement_lib&facet=champ_statistique&facet=dn_de_lib&facet=cursus_lmd_lib&facet=diplome_rgp&facet=diplome_lib&facet=typ_diplome_lib&facet=diplom&facet=niveau_lib&facet=disciplines_selection&facet=gd_disciscipline_lib&facet=discipline_lib&facet=sect_disciplinaire_lib&facet=spec_dut_lib&facet=localisation_ins&facet=com_etab&facet=com_etab_lib&facet=uucr_etab&facet=uucr_etab_lib&facet=dep_etab&facet=dep_etab_lib&facet=aca_etab&facet=aca_etab_lib&facet=reg_etab&facet=reg_etab_lib&facet=com_ins&facet=com_ins_lib&facet=uucr_ins&facet=dep_ins&facet=dep_ins_lib&facet=aca_ins&facet=aca_ins_lib&facet=reg_ins&facet=reg_ins_lib");
        $parsed_json = json_decode($json, true);
        echo '<h1>'.sizeof($parsed_json['records']).'</h1>';
        ?>

    </head>

	<body>
        
	    <h1>TROUVE TA FORMATION !!</h1>
	    <div>
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
	</body>
</html>
