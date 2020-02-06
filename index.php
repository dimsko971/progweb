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
        require_once ("Class/LinkAPI.php");
        $link = new linkAPI();
        $link->resetLink();
        $_stat = $link->getLink();
        $json = file_get_contents($link->getLink());
        $parsed_json = json_decode($json, true);

        if (empty($parsed_json)) exit('ERROR : EMPTY');
        ?>

    </head>

	<body>
	    <h1>TROUVE TA FORMATION !!</h1>
	    <div>
	        <FORM  action = "recherche.php" method = "POST" >
	
				<table id="tableRecherche" >
					<div id="divR">
						
						<div class="submit">
							<input type="submit" value="Rechercher" class="bouton"/>
							<input type="reset" value="Réinitialisé la recherche" class="bouton"/>
						</div>
                        <br/>

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
