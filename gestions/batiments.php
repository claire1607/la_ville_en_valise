<?php
//include("../config.php");


$DOC_TITLE = "G&eacute;rer les batiments";

include("modeles/haut.php");

//$start = vn($_REQUEST['start']);// D&eacute;termine la variable start (d&eacute;but de page)

switch (vb($_REQUEST['mode'])) {

	case "ajout" :
		affiche_formulaire_ajout_batiments(vn($_REQUEST['categorie_id']));
		break;

	case "modif" :
		affiche_formulaire_modif_batiments(vn($_REQUEST['id']));
		break;
		
	

	case "suppr" :
		supprime_batiments(vn($_REQUEST['id']));
		affiche_liste_batiments($start, $_POST);
		break;
		
	case "supprfile" :
		supprime_fichier(vn($_REQUEST['id']), $_GET['file']);
		affiche_formulaire_modif_batiments(vn($_REQUEST['id']));
		break;

	case "insere" :
	
		if (sizeof($_POST) > 0) {
		
		$frm = $_POST;
		
		$message_erreur = valide_form_batiments($frm, $erreurs);
		
		}
	
		if (empty($message_erreur)) {
		
		if (!empty($_FILES['image1']['name'])) {$img1 = upload($_FILES['image1']);}
		
		if (!empty($_FILES['image2']['name'])) {$img2 = upload($_FILES['image2']);}
		
		if (!empty($_FILES['image3']['name'])) {$img3 = upload($_FILES['image3']);}
		
		if (!empty($_FILES['image4']['name'])) {$img4 = upload($_FILES['image4']);}
		
		if (!empty($_FILES['image5']['name'])) {$img5 = upload($_FILES['image5']);}
		
		if (!empty($_FILES['image6']['name'])) {$img6 = upload($_FILES['image6']);}
		
		if (!empty($_FILES['image7']['name'])) {$img7= upload($_FILES['image7']);}
		
		if (!empty($_FILES['image8']['name'])) {$img8= upload($_FILES['image8']);}
		
		
		
	
		insere_batiments(vn($_REQUEST['id']), vb($img1), vb($img2), vb($img3), vb($img4),vb($img5), vb($img6), vb($img7), vb($img8), $_POST);
	
		affiche_liste_batiments($start, 0);
		
		}
	
		if (!empty($message_erreur)) {
		
		echo "<span class='normal'><font color='red' ><b>Attention, votre formulaire est incomplet.</b></font></span><p></p>";
	
		if (! isset($categorie_id) ) {$categorie_id = 0;};
	
		affiche_formulaire_ajout_batiments(vn($_REQUEST['id']));
		
		}
		break;
		
	case "insere_translate" :
	
		if (sizeof($_POST) > 0) {
		
		$frm = $_POST;
		
		$message_erreur = valide_form_batiments($frm, $erreurs);
		
		}
	
		if (empty($message_erreur)) {
		
		if (!empty($_POST['image1'])) {$img1 = $_POST['image1'];
		
		} else {
	
			if (!empty($_FILES['image1']['name'])) {$img1 = upload($_FILES['image1']);}	
	
		}
		
		if (!empty($_REQUEST['image2'])) {$img2 = $_POST['image2'];
		
		} else {
	
		if (!empty($_FILES['image2']['name'])) {$img2 = upload($_FILES['image2']);}	
	
		}
		
		if (!empty($_REQUEST['image3'])) {$img3 = $_POST['image3'];
		
		} else {
	
		if (!empty($_FILES['image3']['name'])) {$img3 = upload($_FILES['image3']);}	
	
		}
		
		if (!empty($_REQUEST['image4'])) {$img4 = $_POST['image4'];
		
		} else {
	
		if (!empty($_FILES['image4']['name'])) {$img4 = upload($_FILES['image4']);}	
		
		
		if (!empty($_POST['image5'])) {$img5 = $_POST['image5'];
		
		} else {
	
			if (!empty($_FILES['image5']['name'])) {$img5 = upload($_FILES['image5']);}	
	
		}
		
		if (!empty($_REQUEST['image6'])) {$img6= $_POST['image6'];
		
		} else {
	
		if (!empty($_FILES['image6']['name'])) {$img6 = upload($_FILES['image6']);}	
	
		}
		
		if (!empty($_REQUEST['image7'])) {$img7= $_POST['image7'];
		
		} else {
	
		if (!empty($_FILES['image7']['name'])) {$img7 = upload($_FILES['image7']);}	
	
		}
		
		if (!empty($_REQUEST['image8'])) {$img8 = $_POST['image8'];
		
		} else {
	
		if (!empty($_FILES['image8']['name'])) {$img8 = upload($_FILES['image8']);}	
	
	
		}	
		
		
		insere_batiments(vn($_REQUEST['id']), vb($img1), vb($img2), vb($img3), vb($img4),  vb($img5), vb($img6), vb($img7), vb($img8), $_POST);
	
		affiche_liste_batiments($start, 0);
		
		}
	
		if (!empty($message_erreur)) {
		
		echo "<span class='normal'><font color='red' ><b>Attention, votre formulaire est incomplet.</b></font></span><p></p>";
	
		if (! isset($categorie_id) ) {$categorie_id = 0;};
	
		affiche_formulaire_ajout_batiments(vn($_REQUEST['id']));
		
		}
		break;

	case "maj" :
	
		if (sizeof($_POST) > 0) {
		
		$frm = $_POST;
		
		$message_erreur = valide_form_batiments($frm, $erreurs);
		
		}
	
		if (empty($message_erreur)) {
		
		if (!empty($_POST['image1'])) {$img1 = $_POST['image1'];
		
		} else {
	
			if (!empty($_FILES['image1']['name'])) {$img1 = upload($_FILES['image1']);}	
	
		}
		
		if (!empty($_REQUEST['image2'])) {$img2 = $_POST['image2'];
		
		} else {
	
		if (!empty($_FILES['image2']['name'])) {$img2 = upload($_FILES['image2']);}	
	
		}
		
		if (!empty($_REQUEST['image3'])) {$img3 = $_POST['image3'];
		
		} else {
	
		if (!empty($_FILES['image3']['name'])) {$img3 = upload($_FILES['image3']);}	
	
		}
		
		if (!empty($_REQUEST['image4'])) {$img4 = $_POST['image4'];
		
		} else {
	
		if (!empty($_FILES['image4']['name'])) {$img4 = upload($_FILES['image4']);}	
	
		}
			
		 if (!empty($_POST['image5'])) {$img5 = $_POST['image5'];
		
		} else {
	
			if (!empty($_FILES['image5']['name'])) {$img5 = upload($_FILES['image5']);}	
	
		}
		
		if (!empty($_REQUEST['image6'])) {$img6= $_POST['image6'];
		
		} else {
	
		if (!empty($_FILES['image6']['name'])) {$img6 = upload($_FILES['image6']);}	
	
		}
		
		if (!empty($_REQUEST['image7'])) {$img7= $_POST['image7'];
		
		} else {
	
		if (!empty($_FILES['image7']['name'])) {$img7 = upload($_FILES['image7']);}	
	
		}
		
		if (!empty($_REQUEST['image8'])) {$img8 = $_POST['image8'];
		
		} else {
	
		if (!empty($_FILES['image8']['name'])) {$img8 = upload($_FILES['image8']);}		
	
		}
			
		maj_batiments($frm['id'], vb($img1), vb($img2), vb($img3), vb($img4),  vb($img5), vb($img6), vb($img7), vb($img8), $_POST);
	
		affiche_liste_batiments($start, 0);
		
		}
	
		if (!empty($message_erreur)) {
		
		echo "<span class='normal'><font color='red' ><b>Attention, votre formulaire est incomplet.</b></font></span><p></p>";
	
		affiche_formulaire_modif_batiments($frm['id']);
		
		}
	
		break;

	default :
		affiche_liste_batiments($start, $_POST);
		break;
}

include("modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/
function affiche_arbo_cat(&$sortie, &$selectionne, $parent=0, $indent="") {

	GLOBAL $frm, $repertoire_images, $wwwroot;

	$sql = "(SELECT id,  nom, description, image, etat, position, nb FROM categories WHERE parent_id = $parent ORDER BY position" or DIE('Une erreur de connexion &agrave; la base s est batimentse ' . __LINE__ . '.<p>' . MYSQL_ERROR());
    
function affiche_formulaire_ajout_batiments($categorie_id = 0) {
/* Affiche un formulaire vierge pour ajouter un batiments */

	global $categorie_options, $frm;
    $categorie_options = get_categories_output(null, 'categories',  vb($frm['categories']), 'option', '&nbsp;&nbsp;', null, null, true, 80);
	/* Valeurs par d&eacute;faut */
	$frm['categories'] = array($categorie_id);
	$frm['nouveau_mode'] = "insere";
	
	$frm['aspect'] = "";
	$frm[' usage'] = "";
	$frm['evenements'] = "";
	
				
	$frm['nom'] = "Nom de batiments";
	$frm['descriptif'] = "";
	$frm['description'] = "";
							

	
	$frm['image1'] = "";
	$frm['image2'] = "";
	$frm['image3'] = "";
	$frm['image4'] = "";
	$frm['image5'] = "";
	$frm['image6'] = "";
	$frm['image7'] = "";
	$frm['image8'] = "";
	
	
	$frm['etat'] = "";		
	$frm['date_insere'] = "";	
	$frm['date_maj'] = "";	
	
	
	$frm['normal_bouton'] = "Ajouter";
	/* Construit la liste des cat&eacute;gories, pr&eacute;selectionne la cat&eacute;gorie racine */
	construit_arbo_cat($categorie_options, $frm['categories']);

	affiche_formulaire_batiments();
}

function affiche_formulaire_modif_batiments($id) {
  /* Affiche le formulaire de modification pour l'batiments s&eacute;lectionn&eacute; */

	global $frm,$categorie_options;

	/* Charge les informations  batiments */
	$qid = query("
	SELECT *
	FROM batiments
	WHERE id = $id
	");
	
	$frm = fetch_assoc($qid);
	
	/* Charge les cat&eacute;gories du batiments */
	$qid = query("
	SELECT categorie_id
	FROM batiments_categories
	WHERE batiments_id = $id
	");
	

	
	$frm['categories'] = array();
	
	if ($qid) {
	
		if ($qid->RowCount() > 0){
		
			while ($cat = fetch_assoc($qid)) {
			
				$frm['categories'] = $cat['categorie_id'];
		$GLOBALS['categorie_names_by_id'][$cat['categorie_id']] = $cat['nom_categorie'];
			}
		}		
	}
	
	$frm['nouveau_mode'] = "maj";
	
	$frm['normal_bouton'] = "Sauvegarder changements";

	
	construit_arbo_cat($categorie_options, $frm['categories']);
	
	affiche_formulaire_batiments();
}



function affiche_formulaire_batiments() {
	GLOBAL $frm;
	GLOBAL $id;
	GLOBAL $categorie_options;
	GLOBAL $wwwroot;
	GLOBAL $message_erreur;
	GLOBAL $erreurs;
	GLOBAL $repertoire_images;
	GLOBAL $wwwroot;
	$marqueur_erreur = "<font color=RED>".htmlspecialchars("=>")."</font>";
	
	if ($frm['nouveau_mode'] == "maj") {
	
	}
	?>


		<form name="entryform" method="post" action="<?=$_SERVER['PHP_SELF']?>?start=0" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?=vb($frm['nouveau_mode'])?>">
		<input type="hidden" name="id" value="<?=vb($frm['id'])?>">
             <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="0" width="760" class="tablespace">
<tr>
	<td class="entete" colspan="2">Ajouter un &eacute;le&eacute;ment</td>
</tr>
<tr>
	<td class="label">Cat&eacute;gorie :</td>
	
</tr>
<tr>
	<td width="50%">
		<select class="formulaire1" name="categories[]" multiple size="5" style="width: 100%">
		<?=$categorie_options?>
		</select>
	</td>
</tr>

				<tr>
					<td class="normal">Etat du batiments :</td>
					<td>
					  <input type="radio" name="etat" value="1" <?php if(vb($frm['etat'])=="1") {echo "checked";} ?>>En ligne<br />
            		  <input type="radio" name="etat" value="0" <?php if(vb($frm['etat'])=="0") {echo "checked";} ?>>En attente
					</td>
				</tr>
				
				
				<tr>
					<td class="label" colspan=2><font color="red">Nom du batiments *</font>:
					<?php
									
					 if (!empty($erreurs['nom'])) {echo $marqueur_erreur." ".$message_erreur['nom']; }?></td>
				</tr>
				<tr>
					<td colspan="2"><input style="width: 100%" class="formulaire1" type="text" name="nom"  value="<?echo html_entity_decode(vb($frm['nom']))?>" /></td>
				</tr>
				
				<?php }  ?>
				<tr>
					<td class="normal" colspan="2">
					
								</td>
				</tr>
				<tr>
					<td class="entete" colspan="2">Photos des batiments :</td>
				</tr>
									
				<?php
				// Traitement de l'image 1
				 if (!empty($frm["image1"])){ ?>
				<tr valign=top>
					<td colspan=2 class=label>Image 1 (vignette) : <br /> 
					<img src="<?=$repertoire_images.$frm["image1"]?>" width="200"><br />
					Nom du fichier :<?=$frm["image1"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image1"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer cette image</a>
					<input type="hidden" name="image1" value="<?=$frm["image1"]?>">
					</td>
				</tr>
				<? } else {?>
				
				<tr valign=top>
					<td class=label>Image 1 (vignette) :</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image1" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>
				<?php
				// Traitement de l'image 2
				 if (!empty($frm["image2"])){ ?>
				<tr valign=top>
					<td colspan=2 class=label>Image 2 (zoom sur la vignette) : <br />
					<img src="<?=$repertoire_images.$frm["image2"] ?>" width="200"><br />
					Nom du fichier :<?=$frm["image2"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image2">
					<img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer cette image</a>
					<input type="hidden" name="image2" value="<?=$frm["image2"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 2 (zoom sur la vignette) :</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image2" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>
				<?php 
				// Traitement de l'image 3
				if (vb($frm['image3']) != "" ){ ?>
				<tr valign=top>
					<td colspan=2 class=normal>Image 3 : <br />
					<img src="<?=$repertoire_images.vb($frm['image3'])?>"width="200"><br />
					Nom du fichier :<?=$frm["image3"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image3"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer cette image</a>
					<input type="hidden" name="image3" value="<?=$frm["image3"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 3 :</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image3" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>
				
				<?php
				// Traitement de l'image 4
				 if (vb($frm['image4']) != "" ){ ?>
				<tr valign=top>
					<td colspan=2 class=normal>Image 4: <br />
					<img src="<?=$repertoire_images.vb($frm['image4'])?>"width="200"><br />
					Nom du fichier :<?=$frm["image4"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image4"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer ce fichier</a>
					<input type="hidden" name="image4" value="<?=$frm["image4"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 4 :</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image4" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>


<?php
				// Traitement de l'image 5
				 if (vb($frm['image5']) != "" ){ ?>
				<tr valign=top>
					<td colspan=2 class=normal>Image 5: <br />
					<img src="<?=$repertoire_images.vb($frm['image5'])?>"width="200"><br />
					Nom du fichier :<?=$frm["image5"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image5"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer ce fichier</a>
					<input type="hidden" name="image5" value="<?=$frm["image5"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 5 :</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image5" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>
				
				<?php
				// Traitement de l'image 6
				 if (vb($frm['image6']) != "" ){ ?>
				<tr valign=top>
					<td colspan=2 class=normal>Image 6: <br />
					<img src="<?=$repertoire_images.vb($frm['image6'])?>"width="200"><br />
					Nom du fichier :<?=$frm["image6"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image6"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer ce fichier</a>
					<input type="hidden" name="image4" value="<?=$frm["image6"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 6:</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image6" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>

                 <?php
				// Traitement de l'image 7
				 if (vb($frm['image7']) != "" ){ ?>
				<tr valign=top>
					<td colspan=2 class=normal>Image 7: <br />
					<img src="<?=$repertoire_images.vb($frm['image7'])?>"width="200"><br />
					Nom du fichier :<?=$frm["image7"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image7"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer ce fichier</a>
					<input type="hidden" name="image7" value="<?=$frm["image7"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 7:</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image7" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>
				
				
				<?php
				// Traitement de l'image 8
				 if (vb($frm['image8']) != "" ){ ?>
				<tr valign=top>
					<td colspan=2 class=normal>Image 8: <br />
					<img src="<?=$repertoire_images.vb($frm['image8'])?>"width="200"><br />
					Nom du fichier :<?=$frm["image8"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image8"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer ce fichier</a>
					<input type="hidden" name="image7" value="<?=$frm["image7"]?>">
					</td>
				</tr>
				<?php } else {?>
				
				<tr valign=top>
					<td class=label>Image 8:</tr>
					<tr>
						<td class=normal>
					<input style="width: 100%" name="image8" type="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<?php }?>



				<?php

				foreach ($_SESSION['lng'] as $lng) {
				
				?>
				<tr>
					<td class="entete" colspan="2">R&eacute;dactionnel li&eacute; </td>
				</tr>
				<tr valign="top">
					<td class="label" colspan=2>Descriptif (quelques mots affich&eacute; dans les pages urbanisme) :</td>
				</tr>
				<tr valign=top>
					<td class=normal colspan=2>
					<input style="width: 100%" class="formulaire1" maxlength="255" type="text" name="descriptif"  value="<?echo html_entity_decode(vb($frm['descriptif']))?>" />
					</td>
				</tr>

				<tr valign=top>
					<td class="label" colspan="2">Description (affichage dans les fiches batiments) :<br /></td>
				</tr>
				<tr valign=top>
					<td class=normal colspan=2>
					<p>
Format <select name="p_format" tabindex="2" id="p_format" >
<option value="html" selected="selected">HTML</option>
<option value="wiki">Wiki</option>
</select>
</p>
<p>
<textarea style="width:100%" rows="10" name="description" id="description" class="formulaire1"><?echo html_entity_decode(vb($frm['description']))?></textarea>
 </p>
 					
					</td>
				</tr>
			
								
                <?php } ?>			
			
				
				<tr><td colspan="2" style="padding:6px;">
				
				
				</td></tr>
				<tr>
					<td colspan="2" valign="top">
					
					<table class="normal" border="0" width="760" >
				
	
				<tr>
					<td colspan="2" align="center" stype="padding:3px;"><input class="bouton" type="submit" value="<?echo $frm['normal_bouton']; ?>"></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</form>
	<?php


function supprime_batiments($id) {
/* Supprime l batiments sp&eacute;cifici&eacute; par $id. Il faut supprimer l batiments
 * puis les entr&eacute;es correspondantes de la table batiments_categories. */

	/* Charge les infos du batiments. */
	$sql = query("
	SELECT nom_".$_SESSION['langue']."
	FROM batiments
	WHERE id = $id
	");
	
	$prod = fetch_assoc($qid);

		/* Efface l batiments */
	query("DELETE FROM batiments WHERE id = $id");

	/* Efface ce batiments de la table batiments_categories */
	
	$sqlCat = "SELECT categorie_id FROM batiments_categories WHERE batiments_id = $id";
	
	$resCat = query($sqlCat);
	
	if ($resCat->RowCount() > 0){
		
		while ($cat = fetch_assoc($resCat)) {
	
			query("UPDATE categories SET nb = nb-1 WHERE id = '".intval($Cat['categorie_id'])."'")
			or DIE('Une erreur de connexion &agrave; la base s est batimentse ' . __LINE__ . '.<p>' . MYSQL_ERROR());
		
		}
		
	}
	
	query("DELETE FROM batiments_categories WHERE batiments_id = $id");
	
	
	
	?>
		<p class="normal">
		L batiments <b><?echo html_entity_decode($prod['nom']); ?></b> a &eacute;t&eacute; effac&eacute;.
		</p>
	<?php
}

function supprime_fichier($id, $file) {
/* Supprime le batiments sp&eacute;cifici&eacute; par $id. Il faut supprimer le batiments
 * puis les entr&eacute;es correspondantes de la table batiments_categories. */
	global $the_path ;
	/* Charge les infos du batimentsx. */

	switch($file) {

	case "image1" :
	$sql = "SELECT image1 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	query("UPDATE batiments SET image1 = '' WHERE id = '$id'");
	break;
	
	case "image2" :
	$sql = "SELECT image2 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image2 = '' WHERE id = '$id'");
	break;
	
	case "image3" :
	$sql = "SELECT image3 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image3 = '' WHERE id = '$id'");
	break;
	
	case "image4" :
	$sql = "SELECT image4 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image4 = '' WHERE id = '$id'");
	break;
	
    case "image5" :
	$sql = "SELECT image5 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image5 = '' WHERE id = '$id'");
	break;
	
	case "image6" :
	$sql = "SELECT image6 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image6 = '' WHERE id = '$id'");
	break;
	
	case "image7" :
	$sql = "SELECT image7 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image7 = '' WHERE id = '$id'");
	break;

    case "image8" :
	$sql = "SELECT image8 FROM batiments WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	mysql_query("UPDATE batiments SET image8 = '' WHERE id = '$id'");
	break;
   
	}
	@unlink($the_path.$file[0]);
	?>
	<div class="normal">
	Le fichier <b><?=$file[0]?> </b>a &eacute;t&eacute; effac&eacute; du serveur.
	</div>

	<?php
}


function insere_batiments($id, $img1, $img2, $img3, $img4, $img5, $frm) {
/* Ajoute un nouveau sous-batiments sous le parent $id.  Les champs sont dans la variable $frm */

	valide($frm['etat']);
	valide($frm['on_aspect']);
	valide($frm['on_usage']);
	valide($frm['on_evenement']);
		
	$nom = htmlspecialchars($frm['nom'], ENT_QUOTES);
	$description = htmlspecialchars($frm['description'], ENT_QUOTES);
	$descriptif = htmlspecialchars($frm['descriptif'], ENT_QUOTES);
	
	$batiments = addSlashes($frm['batiments']);
	$infrastructure = addSlashes($frm['infrastructure']);
	$batiments = addSlashes($frm['batiments']);
	$type_batiments = addSlashes($frm['batiments']);
	$materiaux = addSlashes($frm['materiaux']);
	/*ajoute l batiments dans la table batiments */
	
	$sqlProd = "";
	
	$sqlProd = "
	INSERT INTO batiments (
	reference
	";
	
	$sqlProd .= ", nom", descriptif", description";
					
	}
	
	$sqlProd .= "
	, image1
	, image2
	, image3
	, image4
	, image5
	, image6
	, image7
	, image8
	, pdf
	, etat
	, date_insere
	, date_maj
	
	, on_batiments
	
	 )
	VALUES (
	'$frm[reference]'";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sqlProd .= ", '".htmlspecialchars($frm['nom'], ENT_QUOTES)."'";
	$sqlProd .= ", '".htmlspecialchars($frm['descriptif'], ENT_QUOTES)."'";
	$sqlProd .= ", '".htmlspecialchars($frm['description'], ENT_QUOTES)."'";
	}
	
	$sqlProd .= ", 
	, '$img1'
	, '$img2'
	, '$img3'
	, '$img4'
	, '$img5'
	, '$frm[etat]'
	, now()
	, now()
	, '".strtoupper($frm['nom']{0})."'
	, '$frm[comments]'
	,'".$frm['on_batiments']."'
	
	)
	";
	
	$qid =query($sqlProd) or DIE('Une erreur de connexion &agrave; la base s est batimentse ' . __LINE__ . '.<p>' . MYSQL_ERROR());
	
	/* r&eacute;cup&egrave;re l'id du batiments cr&eacute;&eacute; */
	$batiments_id = mysql_insert_id();

	/* ajoute le batiments sous les cat&eacute;gories sp&eacute;cifi&eacute;es */
	for ($i = 0; $i < count(vn($frm['categories'])); $i++) {
		$qid = query("
		INSERT INTO batiments_categories (categorie_id, batiments_id)
		VALUES ('{$frm['categories'][$i]}', '$batiments_id')
		");
		
		query("UPDATE categories SET nb = nb+1 WHERE id = '".intval($frm['categories'][$i])."'");
		
	}
	
}

function maj_batiments($id, $img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8,  $frm) {
/* Met &agrave; jour le batiments $id avec de nouvelles valeurs. Les champs sont dans $frm */

	valide($frm['on_batiments']);
	
	/* Met &agrave; jour la table batiments */
	
	$sql = "";
	$sql .= "";
	UPDATE batiments SET
		
		$sql .= ",nom = '"htmlspecialchars($frm['nom'], ENT_QUOTES)."'";
		$sql .= ",descriptif = '".htmlspecialchars($frm['descriptif'], ENT_QUOTES)."'";		
		$sql .= ",description = '".htmlspecialchars($frm['description'], ENT_QUOTES)."'";
	
	
	$sql .= "
		,image1 = '$img1'
		,image2 = '$img2'
		,image3 = '$img3'
		,image4 = '$img4'
		,image5 = '$img5'
		,image6 = '$img6'
		,image7 = '$img7'
		,image8 = '$img8'
		
		,etat = '$frm[etat]'
		,date_maj = now()
		,on_batiments= '$frm[onbatiments]'

	WHERE id = ".intval($id)."
	";
	
	$qid = query($sql) or DIE('Une erreur de connexion &agrave; la base s est batimentse ' . __LINE__ . '.<p>' . MYSQL_ERROR());
	
	$sqlCat = "SELECT categorie_id FROM batiments_categories WHERE batiments_id = $id";
	
	$resCat = query($sqlCat);
	
	if ($resCat->RowCount() > 0){
		
		while ($cat = fetch_assoc($resCat)) {
	
			query("UPDATE categories SET nb = nb-1 WHERE id = '".intval($Cat['categorie_id'])."'")
			or DIE('Une erreur de connexion &agrave; la base s est batimentse ' . __LINE__ . '.<p>' . MYSQL_ERROR());
		
		}
		
	}
	
	/* Efface toutes les cat&eacute;gories auxquelles le batiments est associ&eacute; */
	$qid = query("
	DELETE FROM batiments_categories
	WHERE batiments_id = $id
	");
	

	/* Ajoute les  associations pour toutes les cat&eacute;gories auxquelles ce batiments
	 * appartient. Si aucune cat&eacute;gorie n'a &eacute;t&eacute; s&eacute;lectionn&eacute;e, il appartient
	 * &agrave; la cat&eacute;gorie racine. */
	if (count(vn($frm['categories'])) == 0) {
		$frm['categories'][] = 0;
	}
	
	
	for ($i = 0; $i < count($frm['categories']); $i++) {
		
		
		$qid = query("
		INSERT INTO batiments_categories (categorie_id, batiments_id)
		VALUES ('{$frm['categories'][$i]}', '$id')
		");
		
		query("UPDATE categories SET nb = nb+1 WHERE id = '".intval($frm['categories'][$i])."'");
		
		
	}

}

function affiche_liste_batiments($start, $frm) 
{
global $wwwroot;
global $categorie_options;
global $repertoire_images;

/* Charge les informations du batiments */
	$qid = query("
	SELECT *
	FROM batiments
	WHERE id = $id
	");
	

?>
<table border="0" class="tablespace" width="760">
<tr>
	<td class="normal" colspan="10">
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>?start=0&mode=recherche">
<table border="0" cellpadding="0" cellspacing="2" width="100%">


    <tr><td colspan="2" class="entete">Choisir vos crit&egrave;res de recherche</td></tr>
    <tr>
      <td class="normal">Etat du batiments : <br />
	  <span class="normal">
	   <input type="radio" name="etat" value="NULL" checked>peu importe<br />
       <input type="radio" name="etat" value="1" >En ligne<br />
	   <input type="radio" name="etat" value="0" >En attente
	   </span>
	  </td>
      <td class="normal">Cat&eacute;gories<br />
	   <select size="1" name="categorie" class="formulaire1">
	    <option value="NULL">Toutes les cat&eacute;gories</option>
		<?php
		if (!isset($categorie_id)) { $categorie_id = 0; }

$frm['categories'] = array($categorie_id);

construit_arbo_cat($categorie_options, $frm['categories']);

echo $categorie_options;
?>

		?>
		</select>	
		</td>
    </tr>
  
<tr>
	<td class="entete" colspan="10">Liste des batiments</td>
</tr>
<tr><td colspan="10" class="normal">
<img src="images/add.png" width="16" height="16" alt="" border="0" align="middle"><a href="<?echo $wwwroot?>/gestions/batiments.php?mode=ajout">Ajouter un batiments</a>
</td></tr>

			<?php 
			$Links = new MultiPage();
			$Links-> ResultPerPage = 200;
			$Links-> LinkPerPage   = 15;
			$Links-> Template	   = "tpl1.htm";
			
			// Construction de la clause WHERE
			$where = "";
			
			
			
			if (isset($frm['etat'])) {if ($frm['etat'] != "NULL") {$where .= " AND p.etat = '".$frm['etat']."'";}}
			
			if (isset($frm['nbparpage'])) {if (!empty($frm['nbparpage'])) {$nb = $frm['nbparpage'];}}//nombre d'enregistrement par page 
			
			
			if (isset($frm['categorie'])) {
			
				if ($frm['categorie'] != "NULL") {$where .= " AND c.id = '".$frm['categorie']."'";}
			
				$Links-> SqlRequest = "SELECT p.id, p.nom, p.description, p.image1, p.etat, p.date_maj FROM batiments p, batiments_categories pc,categories c WHERE p.id = pc.batiments_id AND c.id = pc.categorie_id ".$where." ORDER BY parent_id ASC";	
				
			} else {
			
				$Links-> SqlRequest = "SELECT p.id, p.nom, p.description, p.image1, p.etat, p.date_maj FROM batiments p ORDER BY id DESC";
				
			}
			
			$Links-> Initialize();
			
			$result=query($Links-> LimitSQL); 
			
			if ($result ->RowCount()== 0)
			
			{
			
			echo "<tr><td class=\"normal\"><b>Aucun batiment enregistr&eacute; dans la base pour ce crit&egrave;re</b></td></tr>"; } 
			
			else {
			?>
			<tr bgcolor="#6699ff">
				<th class="menu">Action</th>
              <th class="menu">Image</th>
				<th class="menu">Cat&eacute;gorie</th>
				<th class="menu">Nom</th>
				<th class="menu">Etat</th>
				<th class="menu" align="center">Cr&eacute;ation / Mise &agrave; jour</th>
			</tr>
			<?php 
			$i = 0;
			
			while ($ligne = fetch_assoc($result)) { 
			
			?>
					<tr bgcolor="<?echo ($i % 2 == 0 ? '#F4F4F4' : '#ffffff' );?>">
						<td class="normal" align="center">
						<a onClick="Javascript:return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer le batiments <?=$ligne['nom'] ?> ?');" class=normal title="Supprimer <?=$ligne['nom'] ?>" href="<?=$_SERVER['PHP_SELF']?>?mode=suppr&id=<?=$ligne['id'] ?>">
						<img src="<?=$wwwroot?>/administrer/images/b_drop.png" border="0"></a>
						</td>
                                         <td class="normal">

		<?php
		
		if (!empty($ligne['image1'])) {
					
							
					echo "<img src=\"$wwwroot/".$ligne['image1']."\" border=\"0\"   height=\"50\"/>";
						
				
				} else {
				
				echo "<img src=\"$wwwroot/images/photo-non-disponible.gif\">";
				
				}
				
		echo "</td>";
?>
						
						<td class=normal align="center"><a class=normal title="Modifier  batiments" href="<?=$_SERVER['PHP_SELF']?>?mode=modif&id=<?=$ligne['id'] ?>"><?=html_entity_decode($ligne['nom'])?></a></td>
						<td align=center class=normal><?if (empty($ligne['etat'])) {echo "<img src=".$wwwroot."/administrer/images/puce-blanche.gif>";} else {echo "<img src=".$wwwroot."/administrer/images/puce-verte.gif>";} ?></td>	
						
						<td class=normal align="center"><?=return_date_fr($ligne['date_maj']) ?></td>
					</tr>
					<tr><td align="center" class="normal" colspan="10">
			<?php 
				$i++;
			} 
			
			}
			//Boutons pr&eacute;c&eacute;dent et suivant 
			$Links-> pMultipage(); 

			echo "</td></tr></table>";
}


			?>
					
			<?php			
			echo "</td></tr></table>";

				?>		
						
			

<?PHP
function valide_form_batiments(&$frm, &$erreurs) {

	$erreurs = array();
	$msg = array();
	
	
	if (empty($frm['image1'])) {
	
		$erreurs['image1'] = true;
		$msg['nom'] = " Vous devez ins&eacute;rer une image ";

	} 
	
	return $msg;
}

}
?>





