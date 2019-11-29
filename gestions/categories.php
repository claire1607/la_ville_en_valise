<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Document sans titre</title>
</head>

<?php 
//include("../configuration.inc.php");
//necessite_identification();
//necessite_priv("admin");

$DOC_TITLE = "G&eacute;rer les cat&eacute;gories";
include("modeles/haut.php");

function affiche_arbo_cat(&$sortie, &$selectionne, $parent=0, $indent="") {

	GLOBAL $frm, $repertoire_images, $wwwroot;

	$qid = query("SELECT id,  nom, description, image, etat, position, nb FROM categories WHERE parent_id = $parent ORDER BY position") or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());

	if ($qid) {
	
		if ($qid->RowCount() > 0){
		
			$i = 0;

			while ($cat = fetch_assoc($qid)) {

				if ($cat['image'] != "") {$logo = "<img src=".$repertoire_images.$cat['image']."  width=\"50\" />";} else { $logo = "";}
				
				if (empty($cat['etat'])) {$etat = "<img src=".$wwwroot."/administrer/images/puce-blanche.gif>";} else {$etat = "<img src=".$wwwroot."/administrer/images/puce-verte.gif>";} 

				$sortie .= "
				<tr bgcolor=\"#ffffff\">
				<td align=\"center\">
				<a href=". $_SERVER['PHP_SELF']. "?mode=ajout&id=". $cat['id'] ."><img src=../administrer/images/rubrique-24.gif width=24 border=0></a>
				&nbsp;<a href=produits.php?mode=ajout&categorie_id=" . $cat['id'] ."><img src=../administrer/images/prod-cat-24.gif width=24  border=0></a>
				&nbsp;<a onClick=\"Javascript:return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer la cat&eacute;gorie ?');\" href=". $_SERVER['PHP_SELF'] ."?mode=suppr&id=". $cat['id'] ."><img src=$wwwroot/administrer/images/b_drop.png border=0></a>
				</td>
				<td align=\"center\" class=\"normal\">". $cat['reference'] ."</td>
				<td align=\"left\" class=\"normal\">$indent<a class=normal href=". $_SERVER['PHP_SELF'] ."?mode=modif&id=" . $cat['id'] .">" . html_entity_decode($cat['nom_'.$_SESSION['langue'].'']) ."</a>&nbsp;(". $cat['nb'] .")</td>
				<td align=\"center\" class=\"normal\">". $cat['position'] ."</td>
				<td align=\"center\">". $logo ."</td>
				<td align=\"center\" class=\"normal\">". $etat ."</td>
				<td align=\"center\" bgcolor=\"".$cat['color']."\" class=\"normal\">". $cat['color'] ."</td>
				</tr>";
				$i++;
				if ($cat['id'] != $parent) {
				
					affiche_arbo_cat($sortie, $selectionne, $cat['id'], $indent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
				}
			}
		}
	}
}

$start = vn($_REQUEST['start']);// D&eacute;termine la variable start (d&eacute;but de page)

switch (vb($_REQUEST['mode'])) {

	case "ajout" :

		affiche_formulaire_ajout_categorie(vn($_REQUEST['id']));

	break;
		
	case "modif" :

	affiche_formulaire_modif_categorie($_REQUEST['id']);

	break;
	
	case "traduire" :

	affiche_formulaire_traduire_categorie($_REQUEST['id']);

	break;
	
	
	case "suppr" :
	
		supprime_categorie($_REQUEST['id']);
		
		affiche_formulaire_liste_categorie($_REQUEST['id']);

    break;
	
	case "supprfile" :
	
		supprime_fichier(vn($_REQUEST['id']), $_GET['file']);
	
		affiche_formulaire_modif_categorie(vn($_REQUEST['id']));
	
	break;

	case "insere" :
	
		if (!empty($_FILES['image']['name'])) {
	
			$img = upload($_FILES['image']);	
		}

		insere_sous_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

		break;
		
		
	case "insere_translate" :
	
		if (!empty($_REQUEST['image'])) {
	
			$img = $_POST['image'];
		
		} else {
	
			if (!empty($_FILES['image']['name'])) {
			$img = upload($_FILES['image']);	
			}	
	
		}

		insere_sous_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

		break;

	case "maj" :
	
	if (!empty($_REQUEST['image'])) {
	
		$img = $_POST['image'];
		
	} else {
	
		if (!empty($_FILES['image']['name'])) {
			$img = upload($_FILES['image']);	
		}	
	
	}

		maj_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

	break;
		
	case "recherche" :

	affiche_recherche_liste_categorie($_REQUEST['id'], $_POST);

	break;
	
	default :

		affiche_formulaire_liste_categorie($_REQUEST['id']=0);

	break;
}


include("modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/
function affiche_formulaire_ajout_categorie($id) {
	GLOBAL $categorie_options, $frm;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom'] = "Nom de votre cat&eacute;gorie ";
	$frm['description'] = "";
					
	}
	
	$frm["image"] = "";
	$frm['position'] = "";
	$frm["etat"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";
	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}
 
function affiche_formulaire_modif_categorie($id) {
/* Affiche le formulaire de modification de cat&eacute;gorie. */

	GLOBAL $frm, $categorie_options;
	/* Charge les infos de la cat&eacute;gorie. */
	$qid = query("
	SELECT *
	FROM categories
	WHERE id = $id
	");

	$frm = fetch_assoc($qid);

	$frm["parent"] = array($frm["parent_id"]);
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = "Sauvegarder changements";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}

function affiche_formulaire_traduire_categorie($id) {
/* Affiche le formulaire de modification de cat&eacute;gorie. */

	GLOBAL $frm, $categorie_options;
	/* Charge les infos de la cat&eacute;gorie. */
	$qid = query("
	SELECT *
	FROM categories
	WHERE id = $id
	");

	$frm = fetch_assoc($qid);

	$frm["parent"] = array($frm["parent_id"]);
	$frm["nouveau_mode"] = "insere_translate";
	$frm["titre_soumet"] = "traduire";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}

function supprime_categorie($id) {
/* Supprime la cat&eacute;gorie sp&eacute;cifi&eacute;e par $id, et d&eacute;place tous les produits sous
 * cette cat&eacute;gorie au parent imm&eacute;diat. */

	/* Trouve le parent de cette cat&eacute;gorie */
	$qid = query("
	SELECT cat.nom, cat.parent_id, parent.nom_ AS parent
	FROM categories cat, categories parent
	WHERE parent.id = cat.parent_id
	AND cat.id = $id
	");
	
	if ($qid) {
	
		if ($qid->RowCount() > 0){ 

			$cat =fetch_assoc($qid);
			/* efface cette cat&eacute;gorie */

			/* R&eacute;affecte tous les elements de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE produits_categories
			SET categorie_id = ".$cat["parent_id"]."
			WHERE categorie_id = $id
			");

			/* R&eacute;affecte toutes les sous-cat&eacute;gories de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE categories
			SET parent_id = ".$cat["parent_id"]."
			WHERE parent_id = $id
			");
			
		$message = "<p class=normal>La cat&eacute;gorie <b>".html_entity_decode($cat['nom'])."</b> a &eacute;t&eacute; effac&eacute;e.  Tous ses elements et sous-cat&eacute;gories ont &eacute;t&eacute; r&eacute;assign&eacute;s &agrave; la cat&eacute;gorie <b>".html_entity_decode($cat["parent"])."</b>.";

			
		} else {
		
			/* R&eacute;affecte tous les éléments de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE produits_categories
			SET categorie_id = '0'
			WHERE categorie_id = $id
			");
			
			/* R&eacute;affecte toutes les sous-cat&eacute;gories de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE categories
			SET parent_id = '0'
			WHERE parent_id = $id
			");
			
		$message = "<p class=normal>La cat&eacute;gorie a &eacute;t&eacute; effac&eacute;e.  Tous ses elements et sous-cat&eacute;gories ont &eacute;t&eacute; r&eacute;assign&eacute;s &agrave; la cat&eacute;gorie sup&eacute;rieure.";

		}
		
		query("DELETE FROM categories WHERE id = $id");
		
		echo nl2br(html_entity_decode($message));
	}
	?>
	<?php
}

function insere_sous_categorie($id, $img, $frm) {
/* Ajoute une nouvelle sous-cat&eacute;gorie sous le parent $id. Tous les champs sont 
   stock&eacute;s dans la variable $frm */
	valide($frm['on_special']);
	valide($frm['etat']);
	
	if (!empty($frm['nom_'.$_SESSION['langue'].''])) {
	
	$sql = "";
	$sql .= "INSERT INTO categories (parent_id";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sql .= ", nom_".$lng.", description_".$lng."";
					
	}
	
	$sql .= "	
	, image
	, etat
	, on_special
	, alpha
	, position
	, meta_titre
	, meta_key
	, meta_desc
	, color)
	VALUES (
	$frm[parent]";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sql .= ", '".htmlspecialchars($frm['nom_'.$lng.''], ENT_QUOTES)."', '".htmlspecialchars($frm['description_'.$lng.''], ENT_QUOTES)."'";
					
	}
	
	$sql .= ", '$img'";
	$sql .= ",'".$frm['etat']."'";
	$sql .= ",'".$frm['on_special']."'";
	$sql .= ", '".strtoupper($frm['nom_fr']{0})."'";
	$sql .= ",'".$frm['position']."'";
	$sql .= ",'".htmlspecialchars($frm['meta_titre'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['meta_key'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['meta_desc'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['color'], ENT_QUOTES)."')";
	
	
	
	mysql_query($sql)
	or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());
	
	} else {
	
	echo "<font color=\"red\"><b>Vous devez inserer un nom de cat&eacute;gorie.</b></font>";
	
	
	
	}
	
	
}

function maj_categorie($id, $img, $frm) {
/* Met &agrave; jour la cat&eacute;gorie $id avec les nouvelles valeurs contenues dans $frm */
	valide($frm['on_special']);
	valide($frm['etat']);
	if ($frm['parent'] == $id) {
	
		$parent_id = 0;
		
	} else {
	
		$parent_id = $frm['parent'];
		
	
	}
	$sql = "";
	$sql .= "
	UPDATE categories SET
		 parent_id = '$parent_id'";
		 
	 foreach ($_SESSION['lng'] as $lng) {
		 
		$sql .=",nom_".$lng." = '".htmlspecialchars($frm['nom_'.$lng.''], ENT_QUOTES)."'
		,description_".$lng." = '".htmlspecialchars($frm['description_'.$lng.''], ENT_QUOTES)."'";
		
	}
		
		$sql .=",image = '$img'
		,etat = '$frm[etat]'
		,position = '$frm[position]'
		,on_special = '$frm[on_special]'
		,alpha = '".strtoupper($frm['nom_fr']{0})."'
		, meta_titre = '".htmlspecialchars($frm['meta_titre'], ENT_QUOTES)."'
		, meta_key = '".htmlspecialchars($frm['meta_key'], ENT_QUOTES)."'
		, meta_desc = '".htmlspecialchars($frm['meta_desc'], ENT_QUOTES)."'
		, color = '".htmlspecialchars($frm['color'], ENT_QUOTES)."'
	WHERE id = $id
	";
	
	query($sql) or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());
}

function affiche_formulaire_liste_categorie($id) {
	GLOBAL $categorie_options;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["position"] = "";
	$frm["image"] = "";
	$frm["etat"] = "";
	$frm["color"] = "";
	$frm["on_special"] = "";
	$frm["alpha"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	affiche_arbo_cat($categorie_options, $frm["parent"]);
	affiche_liste_categorie($frm["parent"]);
}

function affiche_recherche_liste_categorie($id, $frm) {
	GLOBAL $categorie_options;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["position"] = "";
	$frm["image"] = "";
	$frm["etat"] = "";
	$frm["alpha"] = "";
	$frm["color"] = "";
	$frm['lang'] =  $_SESSION['langue'];
	$frm["on_special"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";
	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	affiche_arbo_cat($categorie_options, $frm["parent"]);
	affiche_liste_categorie($frm["parent"]);
}



function affiche_liste_categorie($_POST) {
	GLOBAL $categorie_options;
	GLOBAL $id;
	GLOBAL $wwwroot;
?>
			
<table border=0 cellpadding="0" cellspacing="0" width="640">
<tr valign=top><td colspan="7" class="entete">Liste des cat&eacute;gories</td></tr>
<tr valign=top>
		<td colspan="7"><a class=normal href="<?=$_SERVER['PHP_SELF']?>?mode=ajout">
		<img src="<?=$wwwroot?>/administrer/images/rubrique-24.gif" widtd="25" height="25" align="middle" alt="Cat&eacute;gorie +" border="0"> Ajouter une cat&eacute;gorie</a></td>
	</tr>
	<tr>
		<td class="menu">Action</td>
		<td class="menu">R&eacute;f.</td>
		<td class="menu" align="left">Cat&eacute;gories</td>
		<td class="menu">Position</td>
		<td class="menu">Image</td>
		<td class="menu">Etat</td>
		<td class="menu">Couleur</td>
		

	</tr>
		<?=$categorie_options?>
</table>
<?}?>

<?
function affiche_formulaire_categorie() {
	GLOBAL $frm,$categorie_options,$id,$repertoire_images, $wwwroot;
?>

<form name="entryform" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type="hidden" name="mode" value="<?=$frm["nouveau_mode"]?>">
<input type="hidden" name="id" value="<?=$frm['id']?>">
<table border="0" cellpadding="3" cellspacing="3" width="760">
<tr>
	<td class="entete" colspan="2">Ajouter une cat&eacute;gorie</td>
</tr>
<tr>
	<td colspan="2" class="label">Choisir la cat&eacute;gorie :</td>
</tr>
<tr>
	<td colspan="2" class="normal">
		<select class="formulaire1" name="parent" style="width:100%" size="10">
		<option value="0" SELECTED>A la racine</option>
		<?=$categorie_options?>
		</select>
	</td>
</tr>
<tr><td valign="top" class="normal">
					Afficher la cat&eacute;gorie en page d'accueil :
					</td>
					<td><input type="checkbox" name="on_special" <?=frmvalide($frm['on_special'])?>></td>
</tr>
<tr>
					<td class="normal">Position dans la cat&eacute;gorie :</td>
					<td class="normal"><input  size="1" class="formulaire1" type="text" name="position" value="<?=$frm["position"] ?>"></td>
				</tr>
<tr>
					<td class=normal>Etat de la cat&eacute;gorie :</td>
					<td class=normal>
					  <input type="radio" name="etat" value="1" <?php if(vb($frm['etat'])=="1") {echo "checked";} ?>>En ligne<br />
            		  <input type="radio" name="etat" value="0" <?php if(vb($frm['etat'])=="0") {echo "checked";} ?>>En attente
					</td>
</tr>
<tr>
	<td class="normal" bgcolor="<? echo vb($frm['color']); ?>">Couleur : </td>
	<td><input class="formulaire1" type="text" size="10" maxlength="7" name="color" value="<? echo vb($frm['color']); ?>" >
	<img src="<?echo $wwwroot?>/images/color.gif" width="21" height="20" border="0" align="absmiddle" onClick="fctShow(document.entryform.color);" >
	</td>
</tr>
				
<?php

foreach ($_SESSION['lng'] as $lng) {
				
?>

		<tr><td colspan="2" class="bloc">BLOC DE LANGUE <?echo strtoupper($lng) ?></td><tr>
		<tr>
			<td class="label">Nom <?echo $lng ?>:</td>
			<td class="normal"><input style="width:460px" class="formulaire1" type="text" name="nom_<?echo $lng ?>" value="<?=$frm['nom_'.$lng.''] ?>"></td>
	    </tr>
		<tr valign=top>
			<td colspan="2" class="label">Description  <?echo $lng ?>:<br /></td>
		</tr>
		<tr>
			<td colspan="2" class="normal" >
			<p>
Format <select name="p_format" tabindex="2" id="p_format" >
<option value="html" selected="selected">HTML</option>
<option value="wiki">Wiki</option>
</select>
</p>
<p>
<textarea style="width:100%" rows="5" name="description_<?echo $lng ?>" id="description_<?echo $lng ?>" class="formulaire1"><?=$frm['description_'.$lng.''] ?></textarea>
 </p>
 <script src="<?echo $wwwroot?>/lib/js/toolbar.js" type="text/javascript"></script>
 <script type="text/javascript">if (document.getElementById) {
		var tb = new dcToolBar(document.getElementById('description_<?echo $lng ?>'),
		document.getElementById('p_format'),'images/');
		
		tb.btStrong('Forte emphase');
		tb.btEm('Emphase');
		tb.btIns('Ins&eacute;r&eacute;');
		tb.btDel('Supprim&eacute;');
		tb.btQ('Citation en ligne');
		tb.btCode('Code');
		tb.addSpace(10);
		tb.btBr('Saut de ligne');
		tb.addSpace(10);
		tb.btBquote('Bloc de citation');
		tb.btPre('Texte pr&eacute;format&eacute;');
		tb.btList('Liste non ordonn&eacute;e','ul');
		tb.btList('Liste ordonn&eacute;e','ol');
		tb.addSpace(10);
		tb.btLink('Lien',
			'URL ?',
			'Langue ?',
			'fr');
		tb.btImgLink('Image externe',
			'URL ?');
		tb.addSpace(10);
		tb.draw('Vous pouvez utiliser les raccourcis suivants pour enrichir votre presentation.');
	}
	</script>
			</td>
		</tr>
<? } ?>		
				<? if (!empty($frm["image"])){ ?>
				<tr valign=top>
					<td colspan=2 class=label>Image : <br />
					<img src="<?=$repertoire_images.$frm["image"] ?>"><br />
					nom_fr du fichier :<?=$frm["image"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer cette image</a>
					<input type="hidden" name="image" value="<?=$frm["image"]?>">
					</td>
				</tr>
				<? } else {?>
				
				<tr valign=top>
					<td colspan=2 class="label">Image :</tr>
					<tr>
						<td colspan=2 class=normal>
					<input style="width: 100%" name="image" TYPE="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<? }?>
				<tr>
					<td colspan="2" valign="top">
					
					<table class="normal" border="0" width="760" >
				<tr>
					<td class="entete">META DE LA CATEGORIE</td>
				</tr>
				<tr>
					<td class="label">META DU TITRE PRINCIPAL :</td>
				</tr>
				<tr >
					<td ><input class="formulaire1" type="text" name="meta_titre" size=70 value="<?=$frm["meta_titre"] ?>"></td>
				</tr>
				<tr >
					<td class="label">META MOT CLE (s&eacute;parer les mots cl&eacute;s par des virgules) :</td>
				</tr>
				<tr valign="top" >
					<td ><textarea  name="meta_key" style="width:100%" rows="5"><?echo strip_tags(html_entity_decode(nl2br($frm["meta_key"]))) ?></textarea></td>
				</tr>
				<tr >
					<td class="label">META DESCRIPTION :</td>
				</tr>
				<tr valign="top" >
					<td ><textarea name="meta_desc" style="width:100%" rows="10"><?echo  strip_tags(html_entity_decode(nl2br($frm["meta_desc"]))) ?></textarea></td>
				</tr>		
				</table>
					
					</td>
				</tr>
				
				
		<tr>
		<td colspan="2" align="center"><input class="bouton" type="submit" value="<?=$frm["titre_soumet"] ?>"></td>
	</tr>
</table>
</form>
<?}

function supprime_fichier($id, $file) {
/* Supprime le produit sp&eacute;cifici&eacute; par $id. Il faut supprimer le produit
 * puis les entr&eacute;es correspondantes de la table produits_categories. */
	global $the_path ;
	/* Charge les infos du produit. */

	switch($file) {

	case "image" :
	$sql = "SELECT image FROM categories WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	query("UPDATE categories SET image = '' WHERE id = '$id'");
	break;
	
	}
	@unlink($the_path.$file[0]);
	?>
	<div class="normal">
	Le fichier <b><?=$file[0]?> </b>a &eacute;t&eacute; effac&eacute; du serveur.
	</div>

	<?
}

?><?php 
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin");

$DOC_TITLE = "G&eacute;rer les cat&eacute;gories";
include("modeles/haut.php");

function affiche_arbo_cat(&$sortie, &$selectionne, $parent=0, $indent="") {

	GLOBAL $frm, $repertoire_images, $wwwroot;

	$qid = query("SELECT id, reference, color, nom_".$_SESSION['langue'].", description_".$_SESSION['langue'].", image, etat, position, nb FROM categories WHERE parent_id = $parent ORDER BY position") or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());

	if ($qid) {
	
		if ($qid->RowCount() > 0){

			$i = 0;
			

		while ($cat = fetch_assoc($qid)) {

				if ($cat['image'] != "") {$logo = "<img src=".$repertoire_images.$cat['image']."  width=\"50\" />";} else { $logo = "";}
				
				if (empty($cat['etat'])) {$etat = "<img src=".$wwwroot."/administrer/images/puce-blanche.gif>";} else {$etat = "<img src=".$wwwroot."/administrer/images/puce-verte.gif>";} 

				$sortie .= "
				<tr bgcolor=\"#ffffff\">
				<td align=\"center\">
				<a href=". $_SERVER['PHP_SELF']. "?mode=ajout&id=". $cat['id'] ."><img src=../administrer/images/rubrique-24.gif width=24 border=0></a>
				&nbsp;<a href=produits.php?mode=ajout&categorie_id=" . $cat['id'] ."><img src=../administrer/images/prod-cat-24.gif width=24  border=0></a>
				&nbsp;<a onClick=\"Javascript:return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer la cat&eacute;gorie ?');\" href=". $_SERVER['PHP_SELF'] ."?mode=suppr&id=". $cat['id'] ."><img src=$wwwroot/administrer/images/b_drop.png border=0></a>
				</td>
				<td align=\"center\" class=\"normal\">". $cat['reference'] ."</td>
				<td align=\"left\" class=\"normal\">$indent<a class=normal href=". $_SERVER['PHP_SELF'] ."?mode=modif&id=" . $cat['id'] .">" . html_entity_decode($cat['nom_'.$_SESSION['langue'].'']) ."</a>&nbsp;(". $cat['nb'] .")</td>
				<td align=\"center\" class=\"normal\">". $cat['position'] ."</td>
				<td align=\"center\">". $logo ."</td>
				<td align=\"center\" class=\"normal\">". $etat ."</td>
				<td align=\"center\" bgcolor=\"".$cat['color']."\" class=\"normal\">". $cat['color'] ."</td>
				</tr>";
				$i++;
				if ($cat['id'] != $parent) {
				
					affiche_arbo_cat($sortie, $selectionne, $cat['id'], $indent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
				}
			}
		}
	}
}

$start = vn($_REQUEST['start']);// D&eacute;termine la variable start (d&eacute;but de page)

switch (vb($_REQUEST['mode'])) {

	case "ajout" :

		affiche_formulaire_ajout_categorie(vn($_REQUEST['id']));

	break;
		
	case "modif" :

	affiche_formulaire_modif_categorie($_REQUEST['id']);

	break;
	
	case "traduire" :

	affiche_formulaire_traduire_categorie($_REQUEST['id']);

	break;
	
	
	case "suppr" :
	
		supprime_categorie($_REQUEST['id']);
		
		affiche_formulaire_liste_categorie($_REQUEST['id']);

    break;
	
	case "supprfile" :
	
		supprime_fichier(vn($_REQUEST['id']), $_GET['file']);
	
		affiche_formulaire_modif_categorie(vn($_REQUEST['id']));
	
	break;

	case "insere" :
	
		if (!empty($_FILES['image']['name'])) {
	
			$img = upload($_FILES['image']);	
		}

		insere_sous_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

		break;
		
		
	case "insere_translate" :
	
		if (!empty($_REQUEST['image'])) {
	
			$img = $_POST['image'];
		
		} else {
	
			if (!empty($_FILES['image']['name'])) {
			$img = upload($_FILES['image']);	
			}	
	
		}

		insere_sous_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

		break;

	case "maj" :
	
	if (!empty($_REQUEST['image'])) {
	
		$img = $_POST['image'];
		
	} else {
	
		if (!empty($_FILES['image']['name'])) {
			$img = upload($_FILES['image']);	
		}	
	
	}

		maj_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

	break;
		
	case "recherche" :

	affiche_recherche_liste_categorie($_REQUEST['id'], $_POST);

	break;
	
	default :

		affiche_formulaire_liste_categorie($_REQUEST['id']=0);

	break;
}


include("modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/
function affiche_formulaire_ajout_categorie($id) {
	GLOBAL $categorie_options, $frm;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "Nom de votre cat&eacute;gorie en langue $lng";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["image"] = "";
	$frm['position'] = "";
	$frm["etat"] = "";
	$frm['on_special'] = "";
	$frm['alpha'] = "";	
	$frm['meta_titre'] = "";
	$frm['meta_key'] = "";
	$frm['meta_desc'] = "";
	$frm['color'] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";
	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}
 
function affiche_formulaire_modif_categorie($id) {
/* Affiche le formulaire de modification de cat&eacute;gorie. */

	GLOBAL $frm, $categorie_options;
	/* Charge les infos de la cat&eacute;gorie. */
	$qid = mysql_query("
	SELECT *
	FROM categories
	WHERE id = $id
	");

	$frm = fetch_assoc($qid);

	$frm["parent"] = array($frm["parent_id"]);
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = "Sauvegarder changements";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}

function affiche_formulaire_traduire_categorie($id) {
/* Affiche le formulaire de modification de cat&eacute;gorie. */

	GLOBAL $frm, $categorie_options;
	/* Charge les infos de la cat&eacute;gorie. */
	$qid = query("
	SELECT *
	FROM categories
	WHERE id = $id
	");

	$frm = fetch_assoc($qid);

	$frm["parent"] = array($frm["parent_id"]);
	$frm["nouveau_mode"] = "insere_translate";
	$frm["titre_soumet"] = "traduire";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}

function supprime_categorie($id) {
/* Supprime la cat&eacute;gorie sp&eacute;cifi&eacute;e par $id, et d&eacute;place tous les produits sous
 * cette cat&eacute;gorie au parent imm&eacute;diat. */

	/* Trouve le parent de cette cat&eacute;gorie */
	$qid = query("
	SELECT cat.nom_".$_SESSION['langue'].", cat.parent_id, parent.nom_".$_SESSION['langue']." AS parent
	FROM categories cat, categories parent
	WHERE parent.id = cat.parent_id
	AND cat.id = $id
	");
	
	if ($qid) {
	
		if ($qid->RowCount() > 0){

			$cat = fetch_assoc($qid);
			/* efface cette cat&eacute;gorie */

			/* R&eacute;affecte tous les produits de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE produits_categories
			SET categorie_id = ".$cat["parent_id"]."
			WHERE categorie_id = $id
			");

			/* R&eacute;affecte toutes les sous-cat&eacute;gories de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE categories
			SET parent_id = ".$cat["parent_id"]."
			WHERE parent_id = $id
			");
			
		$message = "<p class=normal>La cat&eacute;gorie <b>".html_entity_decode($cat['nom_fr'])."</b> a &eacute;t&eacute; effac&eacute;e.  Tous ses produits et sous-cat&eacute;gories ont &eacute;t&eacute; r&eacute;assign&eacute;s &agrave; la cat&eacute;gorie <b>".html_entity_decode($cat["parent"])."</b>.";

			
		} else {
		
			/* R&eacute;affecte tous les produits de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE produits_categories
			SET categorie_id = '0'
			WHERE categorie_id = $id
			");
			
			/* R&eacute;affecte toutes les sous-cat&eacute;gories de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE categories
			SET parent_id = '0'
			WHERE parent_id = $id
			");
			
		$message = "<p class=normal>La cat&eacute;gorie a &eacute;t&eacute; effac&eacute;e.  Tous ses produits et sous-cat&eacute;gories ont &eacute;t&eacute; r&eacute;assign&eacute;s &agrave; la cat&eacute;gorie sup&eacute;rieure.";

		}
		
		query("DELETE FROM categories WHERE id = $id");
		
		echo nl2br(html_entity_decode($message));
	}
	?>
	<?
}

function insere_sous_categorie($id, $img, $frm) {
/* Ajoute une nouvelle sous-cat&eacute;gorie sous le parent $id. Tous les champs sont 
   stock&eacute;s dans la variable $frm */
	valide($frm['on_special']);
	valide($frm['etat']);
	
	if (!empty($frm['nom_'.$_SESSION['langue'].''])) {
	
	$sql = "";
	$sql .= "INSERT INTO categories (parent_id";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sql .= ", nom_".$lng.", description_".$lng."";
					
	}
	
	$sql .= "	
	, image
	, etat
	, on_special
	, alpha
	, position
	, meta_titre
	, meta_key
	, meta_desc
	, color)
	VALUES (
	$frm[parent]";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sql .= ", '".htmlspecialchars($frm['nom_'.$lng.''], ENT_QUOTES)."', '".htmlspecialchars($frm['description_'.$lng.''], ENT_QUOTES)."'";
					
	}
	
	$sql .= ", '$img'";
	$sql .= ",'".$frm['etat']."'";
	$sql .= ",'".$frm['on_special']."'";
	$sql .= ", '".strtoupper($frm['nom_fr']{0})."'";
	$sql .= ",'".$frm['position']."'";
	$sql .= ",'".htmlspecialchars($frm['meta_titre'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['meta_key'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['meta_desc'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['color'], ENT_QUOTES)."')";
	
	
	
	query($sql)
	or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());
	
	} else {
	
	echo "<font color=\"red\"><b>Vous devez inserer un nom de cat&eacute;gorie.</b></font>";
	
	
	
	}
	
	
}

function maj_categorie($id, $img, $frm) {
/* Met &agrave; jour la cat&eacute;gorie $id avec les nouvelles valeurs contenues dans $frm */
	valide($frm['on_special']);
	valide($frm['etat']);
	if ($frm['parent'] == $id) {
	
		$parent_id = 0;
		
	} else {
	
		$parent_id = $frm['parent'];
		
	
	}
	$sql = "";
	$sql .= "
	UPDATE categories SET
		 parent_id = '$parent_id'";
		 
	 foreach ($_SESSION['lng'] as $lng) {
		 
		$sql .=",nom_".$lng." = '".htmlspecialchars($frm['nom_'.$lng.''], ENT_QUOTES)."'
		,description_".$lng." = '".htmlspecialchars($frm['description_'.$lng.''], ENT_QUOTES)."'";
		
	}
		
		$sql .=",image = '$img'
		,etat = '$frm[etat]'
		,position = '$frm[position]'
		,on_special = '$frm[on_special]'
		,alpha = '".strtoupper($frm['nom_fr']{0})."'
		, meta_titre = '".htmlspecialchars($frm['meta_titre'], ENT_QUOTES)."'
		, meta_key = '".htmlspecialchars($frm['meta_key'], ENT_QUOTES)."'
		, meta_desc = '".htmlspecialchars($frm['meta_desc'], ENT_QUOTES)."'
		, color = '".htmlspecialchars($frm['color'], ENT_QUOTES)."'
	WHERE id = $id
	";
	
	query($sql) or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());
}

function affiche_formulaire_liste_categorie($id) {
	GLOBAL $categorie_options;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["position"] = "";
	$frm["image"] = "";
	$frm["etat"] = "";
	$frm["color"] = "";
	$frm["on_special"] = "";
	$frm["alpha"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	affiche_arbo_cat($categorie_options, $frm["parent"]);
	affiche_liste_categorie($frm["parent"]);
}

function affiche_recherche_liste_categorie($id, $frm) {
	GLOBAL $categorie_options;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["position"] = "";
	$frm["image"] = "";
	$frm["etat"] = "";
	$frm["alpha"] = "";
	$frm["color"] = "";
	$frm['lang'] =  $_SESSION['langue'];
	$frm["on_special"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";
	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	affiche_arbo_cat($categorie_options, $frm["parent"]);
	affiche_liste_categorie($frm["parent"]);
}



function affiche_liste_categorie($_POST) {
	GLOBAL $categorie_options;
	GLOBAL $id;
	GLOBAL $wwwroot;
?>
			
<table border=0 cellpadding="0" cellspacing="0" width="640">
<tr valign=top><td colspan="7" class="entete">Liste des cat&eacute;gories</td></tr>
<tr valign=top>
		<td colspan="7"><a class=normal href="<?=$_SERVER['PHP_SELF']?>?mode=ajout">
		<img src="<?=$wwwroot?>/administrer/images/rubrique-24.gif" widtd="25" height="25" align="middle" alt="Cat&eacute;gorie +" border="0"> Ajouter une cat&eacute;gorie</a></td>
	</tr>
	<tr>
		<td class="menu">Action</td>
		<td class="menu">R&eacute;f.</td>
		<td class="menu" align="left">Cat&eacute;gories</td>
		<td class="menu">Position</td>
		<td class="menu">Image</td>
		<td class="menu">Etat</td>
		<td class="menu">Couleur</td>
		

	</tr>
		<?=$categorie_options?>
</table>
<?}?>

<?
function affiche_formulaire_categorie() {
	GLOBAL $frm,$categorie_options,$id,$repertoire_images, $wwwroot;
?>

<form name="entryform" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type="hidden" name="mode" value="<?=$frm["nouveau_mode"]?>">
<input type="hidden" name="id" value="<?=$frm['id']?>">
<table border="0" cellpadding="3" cellspacing="3" width="760">
<tr>
	<td class="entete" colspan="2">Ajouter une cat&eacute;gorie</td>
</tr>
<tr>
	<td colspan="2" class="label">Choisir la cat&eacute;gorie :</td>
</tr>
<tr>
	<td colspan="2" class="normal">
		<select class="formulaire1" name="parent" style="width:100%" size="10">
		<option value="0" SELECTED>A la racine</option>
		<?=$categorie_options?>
		</select>
	</td>
</tr>
<tr><td valign="top" class="normal">
					Afficher la cat&eacute;gorie en page d'accueil :
					</td>
					<td><input type="checkbox" name="on_special" <?=frmvalide($frm['on_special'])?>></td>
</tr>
<tr>
					<td class="normal">Position dans la cat&eacute;gorie :</td>
					<td class="normal"><input  size="1" class="formulaire1" type="text" name="position" value="<?=$frm["position"] ?>"></td>
				</tr>
<tr>
					<td class=normal>Etat de la cat&eacute;gorie :</td>
					<td class=normal>
					  <input type="radio" name="etat" value="1" <?php if(vb($frm['etat'])=="1") {echo "checked";} ?>>En ligne<br />
            		  <input type="radio" name="etat" value="0" <?php if(vb($frm['etat'])=="0") {echo "checked";} ?>>En attente
					</td>
</tr>
<tr>
	<td class="normal" bgcolor="<? echo vb($frm['color']); ?>">Couleur : </td>
	<td><input class="formulaire1" type="text" size="10" maxlength="7" name="color" value="<? echo vb($frm['color']); ?>" >
	<img src="<?echo $wwwroot?>/images/color.gif" width="21" height="20" border="0" align="absmiddle" onClick="fctShow(document.entryform.color);" >
	</td>
</tr>
				
<?php

foreach ($_SESSION['lng'] as $lng) {
				
?>

		<tr><td colspan="2" class="bloc">BLOC DE LANGUE <?echo strtoupper($lng) ?></td><tr>
		<tr>
			<td class="label">Nom <?echo $lng ?>:</td>
			<td class="normal"><input style="width:460px" class="formulaire1" type="text" name="nom_<?echo $lng ?>" value="<?=$frm['nom_'.$lng.''] ?>"></td>
	    </tr>
		<tr valign=top>
			<td colspan="2" class="label">Description  <?echo $lng ?>:<br /></td>
		</tr>
		<tr>
			<td colspan="2" class="normal" >
			<p>
Format <select name="p_format" tabindex="2" id="p_format" >
<option value="html" selected="selected">HTML</option>
<option value="wiki">Wiki</option>
</select>
</p>
<p>
<textarea style="width:100%" rows="5" name="description_<?echo $lng ?>" id="description_<?echo $lng ?>" class="formulaire1"><?=$frm['description_'.$lng.''] ?></textarea>
 </p>
 <script src="<?echo $wwwroot?>/lib/js/toolbar.js" type="text/javascript"></script>
 <script type="text/javascript">if (document.getElementById) {
		var tb = new dcToolBar(document.getElementById('description_<?echo $lng ?>'),
		document.getElementById('p_format'),'images/');
		
		tb.btStrong('Forte emphase');
		tb.btEm('Emphase');
		tb.btIns('Ins&eacute;r&eacute;');
		tb.btDel('Supprim&eacute;');
		tb.btQ('Citation en ligne');
		tb.btCode('Code');
		tb.addSpace(10);
		tb.btBr('Saut de ligne');
		tb.addSpace(10);
		tb.btBquote('Bloc de citation');
		tb.btPre('Texte pr&eacute;format&eacute;');
		tb.btList('Liste non ordonn&eacute;e','ul');
		tb.btList('Liste ordonn&eacute;e','ol');
		tb.addSpace(10);
		tb.btLink('Lien',
			'URL ?',
			'Langue ?',
			'fr');
		tb.btImgLink('Image externe',
			'URL ?');
		tb.addSpace(10);
		tb.draw('Vous pouvez utiliser les raccourcis suivants pour enrichir votre presentation.');
	}
	</script>
			</td>
		</tr>
<? } ?>		
				<? if (!empty($frm["image"])){ ?>
				<tr valign=top>
					<td colspan=2 class=label>Image : <br />
					<img src="<?=$repertoire_images.$frm["image"] ?>"><br />
					nom_fr du fichier :<?=$frm["image"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer cette image</a>
					<input type="hidden" name="image" value="<?=$frm["image"]?>">
					</td>
				</tr>
				<? } else {?>
				
				<tr valign=top>
					<td colspan=2 class="label">Image :</tr>
					<tr>
						<td colspan=2 class=normal>
					<input style="width: 100%" name="image" TYPE="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<? }?>
				<tr>
					<td colspan="2" valign="top">
					
					<table class="normal" border="0" width="760" >
				<tr>
					<td class="entete">META DE LA CATEGORIE</td>
				</tr>
				<tr>
					<td class="label">META DU TITRE PRINCIPAL :</td>
				</tr>
				<tr >
					<td ><input class="formulaire1" type="text" name="meta_titre" size=70 value="<?=$frm["meta_titre"] ?>"></td>
				</tr>
				<tr >
					<td class="label">META MOT CLE (s&eacute;parer les mots cl&eacute;s par des virgules) :</td>
				</tr>
				<tr valign="top" >
					<td ><textarea  name="meta_key" style="width:100%" rows="5"><?echo strip_tags(html_entity_decode(nl2br($frm["meta_key"]))) ?></textarea></td>
				</tr>
				<tr >
					<td class="label">META DESCRIPTION :</td>
				</tr>
				<tr valign="top" >
					<td ><textarea name="meta_desc" style="width:100%" rows="10"><?echo  strip_tags(html_entity_decode(nl2br($frm["meta_desc"]))) ?></textarea></td>
				</tr>		
				</table>
					
					</td>
				</tr>
				
				
		<tr>
		<td colspan="2" align="center"><input class="bouton" type="submit" value="<?=$frm["titre_soumet"] ?>"></td>
	</tr>
</table>
</form>
<?}

function supprime_fichier($id, $file) {
/* Supprime le produit sp&eacute;cifici&eacute; par $id. Il faut supprimer le produit
 * puis les entr&eacute;es correspondantes de la table produits_categories. */
	global $the_path ;
	/* Charge les infos du produit. */

	switch($file) {

	case "image" :
	$sql = "SELECT image FROM categories WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	query("UPDATE categories SET image = '' WHERE id = '$id'");
	break;
	
	}
	@unlink($the_path.$file[0]);
	?>
	<div class="normal">
	Le fichier <b><?=$file[0]?> </b>a &eacute;t&eacute; effac&eacute; du serveur.
	</div>

	<?
}

?><?php 
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin");

$DOC_TITLE = "G&eacute;rer les cat&eacute;gories";
include("modeles/haut.php");

function affiche_arbo_cat(&$sortie, &$selectionne, $parent=0, $indent="") {

	GLOBAL $frm, $repertoire_images, $wwwroot;

	$qid =query("SELECT id, reference, color, nom_".$_SESSION['langue'].", description_".$_SESSION['langue'].", image, etat, position, nb FROM categories WHERE parent_id = $parent ORDER BY position") or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());

	if ($qid) {
	
		if ($qid->RowCount() > 0){

			$i = 0;
			

		while ($cat = fetch_assoc($qid)) {

				if ($cat['image'] != "") {$logo = "<img src=".$repertoire_images.$cat['image']."  width=\"50\" />";} else { $logo = "";}
				
				if (empty($cat['etat'])) {$etat = "<img src=".$wwwroot."/administrer/images/puce-blanche.gif>";} else {$etat = "<img src=".$wwwroot."/administrer/images/puce-verte.gif>";} 

				$sortie .= "
				<tr bgcolor=\"#ffffff\">
				<td align=\"center\">
				<a href=". $_SERVER['PHP_SELF']. "?mode=ajout&id=". $cat['id'] ."><img src=../administrer/images/rubrique-24.gif width=24 border=0></a>
				&nbsp;<a href=produits.php?mode=ajout&categorie_id=" . $cat['id'] ."><img src=../administrer/images/prod-cat-24.gif width=24  border=0></a>
				&nbsp;<a onClick=\"Javascript:return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer la cat&eacute;gorie ?');\" href=". $_SERVER['PHP_SELF'] ."?mode=suppr&id=". $cat['id'] ."><img src=$wwwroot/administrer/images/b_drop.png border=0></a>
				</td>
				<td align=\"center\" class=\"normal\">". $cat['reference'] ."</td>
				<td align=\"left\" class=\"normal\">$indent<a class=normal href=". $_SERVER['PHP_SELF'] ."?mode=modif&id=" . $cat['id'] .">" . html_entity_decode($cat['nom_'.$_SESSION['langue'].'']) ."</a>&nbsp;(". $cat['nb'] .")</td>
				<td align=\"center\" class=\"normal\">". $cat['position'] ."</td>
				<td align=\"center\">". $logo ."</td>
				<td align=\"center\" class=\"normal\">". $etat ."</td>
				<td align=\"center\" bgcolor=\"".$cat['color']."\" class=\"normal\">". $cat['color'] ."</td>
				</tr>";
				$i++;
				if ($cat['id'] != $parent) {
				
					affiche_arbo_cat($sortie, $selectionne, $cat['id'], $indent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
				}
			}
		}
	}
}

$start = vn($_REQUEST['start']);// D&eacute;termine la variable start (d&eacute;but de page)

switch (vb($_REQUEST['mode'])) {

	case "ajout" :

		affiche_formulaire_ajout_categorie(vn($_REQUEST['id']));

	break;
		
	case "modif" :

	affiche_formulaire_modif_categorie($_REQUEST['id']);

	break;
	
	case "traduire" :

	affiche_formulaire_traduire_categorie($_REQUEST['id']);

	break;
	
	
	case "suppr" :
	
		supprime_categorie($_REQUEST['id']);
		
		affiche_formulaire_liste_categorie($_REQUEST['id']);

    break;
	
	case "supprfile" :
	
		supprime_fichier(vn($_REQUEST['id']), $_GET['file']);
	
		affiche_formulaire_modif_categorie(vn($_REQUEST['id']));
	
	break;

	case "insere" :
	
		if (!empty($_FILES['image']['name'])) {
	
			$img = upload($_FILES['image']);	
		}

		insere_sous_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

		break;
		
		
	case "insere_translate" :
	
		if (!empty($_REQUEST['image'])) {
	
			$img = $_POST['image'];
		
		} else {
	
			if (!empty($_FILES['image']['name'])) {
			$img = upload($_FILES['image']);	
			}	
	
		}

		insere_sous_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

		break;

	case "maj" :
	
	if (!empty($_REQUEST['image'])) {
	
		$img = $_POST['image'];
		
	} else {
	
		if (!empty($_FILES['image']['name'])) {
			$img = upload($_FILES['image']);	
		}	
	
	}

		maj_categorie($_REQUEST['id'], vb($img), $_POST);

		affiche_formulaire_liste_categorie($_REQUEST['id']);

	break;
		
	case "recherche" :

	affiche_recherche_liste_categorie($_REQUEST['id'], $_POST);

	break;
	
	default :

		affiche_formulaire_liste_categorie($_REQUEST['id']=0);

	break;
}


include("modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/
function affiche_formulaire_ajout_categorie($id) {
	GLOBAL $categorie_options, $frm;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "Nom de votre cat&eacute;gorie en langue $lng";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["image"] = "";
	$frm['position'] = "";
	$frm["etat"] = "";
	$frm['on_special'] = "";
	$frm['alpha'] = "";	
	$frm['meta_titre'] = "";
	$frm['meta_key'] = "";
	$frm['meta_desc'] = "";
	$frm['color'] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";
	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}
 
function affiche_formulaire_modif_categorie($id) {
/* Affiche le formulaire de modification de cat&eacute;gorie. */

	GLOBAL $frm, $categorie_options;
	/* Charge les infos de la cat&eacute;gorie. */
	$qid = query("
	SELECT *
	FROM categories
	WHERE id = $id
	");

	$frm = fetch_assoc($qid);
	$frm["parent"] = array($frm["parent_id"]);
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = "Sauvegarder changements";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}

function affiche_formulaire_traduire_categorie($id) {
/* Affiche le formulaire de modification de cat&eacute;gorie. */

	GLOBAL $frm, $categorie_options;
	/* Charge les infos de la cat&eacute;gorie. */
	$qid = query("
	SELECT *
	FROM categories
	WHERE id = $id
	");

	$frm = fetch_assoc($qid);

	$frm["parent"] = array($frm["parent_id"]);
	$frm["nouveau_mode"] = "insere_translate";
	$frm["titre_soumet"] = "traduire";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	construit_arbo_cat($categorie_options, $frm["parent"]);
	affiche_formulaire_categorie();
}

function supprime_categorie($id) {
/* Supprime la cat&eacute;gorie sp&eacute;cifi&eacute;e par $id, et d&eacute;place tous les produits sous
 * cette cat&eacute;gorie au parent imm&eacute;diat. */

	/* Trouve le parent de cette cat&eacute;gorie */
	$qid = query("
	SELECT cat.nom_".$_SESSION['langue'].", cat.parent_id, parent.nom_".$_SESSION['langue']." AS parent
	FROM categories cat, categories parent
	WHERE parent.id = cat.parent_id
	AND cat.id = $id
	");
	
	if ($qid) {
	
		if ($qid->RowCount() > 0){

			$cat = fetch_assoc($qid);
			/* efface cette cat&eacute;gorie */

			/* R&eacute;affecte tous les produits de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE produits_categories
			SET categorie_id = ".$cat["parent_id"]."
			WHERE categorie_id = $id
			");

			/* R&eacute;affecte toutes les sous-cat&eacute;gories de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE categories
			SET parent_id = ".$cat["parent_id"]."
			WHERE parent_id = $id
			");
			
		$message = "<p class=normal>La cat&eacute;gorie <b>".html_entity_decode($cat['nom_fr'])."</b> a &eacute;t&eacute; effac&eacute;e.  Tous ses produits et sous-cat&eacute;gories ont &eacute;t&eacute; r&eacute;assign&eacute;s &agrave; la cat&eacute;gorie <b>".html_entity_decode($cat["parent"])."</b>.";

			
		} else {
		
			/* R&eacute;affecte tous les produits de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE produits_categories
			SET categorie_id = '0'
			WHERE categorie_id = $id
			");
			
			/* R&eacute;affecte toutes les sous-cat&eacute;gories de cette cat&eacute;gorie &agrave; la cat&eacute;gorie parente */
			query("
			UPDATE categories
			SET parent_id = '0'
			WHERE parent_id = $id
			");
			
		$message = "<p class=normal>La cat&eacute;gorie a &eacute;t&eacute; effac&eacute;e.  Tous ses produits et sous-cat&eacute;gories ont &eacute;t&eacute; r&eacute;assign&eacute;s &agrave; la cat&eacute;gorie sup&eacute;rieure.";

		}
		
		query("DELETE FROM categories WHERE id = $id");
		
		echo nl2br(html_entity_decode($message));
	}
	?>
	<?
}

function insere_sous_categorie($id, $img, $frm) {
/* Ajoute une nouvelle sous-cat&eacute;gorie sous le parent $id. Tous les champs sont 
   stock&eacute;s dans la variable $frm */
	valide($frm['on_special']);
	valide($frm['etat']);
	
	if (!empty($frm['nom_'.$_SESSION['langue'].''])) {
	
	$sql = "";
	$sql .= "INSERT INTO categories (parent_id";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sql .= ", nom_".$lng.", description_".$lng."";
					
	}
	
	$sql .= "	
	, image
	, etat
	, on_special
	, alpha
	, position
	, meta_titre
	, meta_key
	, meta_desc
	, color)
	VALUES (
	$frm[parent]";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$sql .= ", '".htmlspecialchars($frm['nom_'.$lng.''], ENT_QUOTES)."', '".htmlspecialchars($frm['description_'.$lng.''], ENT_QUOTES)."'";
					
	}
	
	$sql .= ", '$img'";
	$sql .= ",'".$frm['etat']."'";
	$sql .= ",'".$frm['on_special']."'";
	$sql .= ", '".strtoupper($frm['nom_fr']{0})."'";
	$sql .= ",'".$frm['position']."'";
	$sql .= ",'".htmlspecialchars($frm['meta_titre'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['meta_key'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['meta_desc'], ENT_QUOTES)."'";
	$sql .= ",'".htmlspecialchars($frm['color'], ENT_QUOTES)."')";
	
	
	
	query($sql)
	or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());
	
	} else {
	
	echo "<font color=\"red\"><b>Vous devez inserer un nom de cat&eacute;gorie.</b></font>";
	
	
	
	}
	
	
}

function maj_categorie($id, $img, $frm) {
/* Met &agrave; jour la cat&eacute;gorie $id avec les nouvelles valeurs contenues dans $frm */
	valide($frm['on_special']);
	valide($frm['etat']);
	if ($frm['parent'] == $id) {
	
		$parent_id = 0;
		
	} else {
	
		$parent_id = $frm['parent'];
		
	
	}
	$sql = "";
	$sql .= "
	UPDATE categories SET
		 parent_id = '$parent_id'";
		 
	 foreach ($_SESSION['lng'] as $lng) {
		 
		$sql .=",nom_".$lng." = '".htmlspecialchars($frm['nom_'.$lng.''], ENT_QUOTES)."'
		,description_".$lng." = '".htmlspecialchars($frm['description_'.$lng.''], ENT_QUOTES)."'";
		
	}
		
		$sql .=",image = '$img'
		,etat = '$frm[etat]'
		,position = '$frm[position]'
		,on_special = '$frm[on_special]'
		,alpha = '".strtoupper($frm['nom_fr']{0})."'
		, meta_titre = '".htmlspecialchars($frm['meta_titre'], ENT_QUOTES)."'
		, meta_key = '".htmlspecialchars($frm['meta_key'], ENT_QUOTES)."'
		, meta_desc = '".htmlspecialchars($frm['meta_desc'], ENT_QUOTES)."'
		, color = '".htmlspecialchars($frm['color'], ENT_QUOTES)."'
	WHERE id = $id
	";
	
	query($sql) or DIE('Une erreur de connexion &agrave; la base s est produite ' . __LINE__ . '.<p>' . MYSQL_ERROR());
}

function affiche_formulaire_liste_categorie($id) {
	GLOBAL $categorie_options;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["position"] = "";
	$frm["image"] = "";
	$frm["etat"] = "";
	$frm["color"] = "";
	$frm["on_special"] = "";
	$frm["alpha"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";

	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	affiche_arbo_cat($categorie_options, $frm["parent"]);
	affiche_liste_categorie($frm["parent"]);
}

function affiche_recherche_liste_categorie($id, $frm) {
	GLOBAL $categorie_options;
/* Affiche un formulaire de cat&eacute;gorie vide */
	/* Valeurs par d&eacute;faut */
	$frm["parent"] = array($id);
	$frm["nouveau_mode"] = "insere";
	
	foreach ($_SESSION['lng'] as $lng) {
				
	$frm['nom_'.$lng.''] = "";
	$frm['description_'.$lng.''] = "";
					
	}
	
	$frm["position"] = "";
	$frm["image"] = "";
	$frm["etat"] = "";
	$frm["alpha"] = "";
	$frm["color"] = "";
	$frm['lang'] =  $_SESSION['langue'];
	$frm["on_special"] = "";
	$frm["titre_soumet"] = "Ajouter une sous-cat&eacute;gorie";
	/* Affiche la liste des cat&eacute;gories, en pr&eacute;s&eacute;lectionnant la cat&eacute;gorie choisie. */
	affiche_arbo_cat($categorie_options, $frm["parent"]);
	affiche_liste_categorie($frm["parent"]);
}



function affiche_liste_categorie($_POST) {
	GLOBAL $categorie_options;
	GLOBAL $id;
	GLOBAL $wwwroot;
?>
			
<table border=0 cellpadding="0" cellspacing="0" width="640">
<tr valign=top><td colspan="7" class="entete">Liste des cat&eacute;gories</td></tr>
<tr valign=top>
		<td colspan="7"><a class=normal href="<?=$_SERVER['PHP_SELF']?>?mode=ajout">
		<img src="<?=$wwwroot?>/administrer/images/rubrique-24.gif" widtd="25" height="25" align="middle" alt="Cat&eacute;gorie +" border="0"> Ajouter une cat&eacute;gorie</a></td>
	</tr>
	<tr>
		<td class="menu">Action</td>
		<td class="menu">R&eacute;f.</td>
		<td class="menu" align="left">Cat&eacute;gories</td>
		<td class="menu">Position</td>
		<td class="menu">Image</td>
		<td class="menu">Etat</td>
		<td class="menu">Couleur</td>
		

	</tr>
		<?=$categorie_options?>
</table>
<?}?>

<?
function affiche_formulaire_categorie() {
	GLOBAL $frm,$categorie_options,$id,$repertoire_images, $wwwroot;
?>

<form name="entryform" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type="hidden" name="mode" value="<?=$frm["nouveau_mode"]?>">
<input type="hidden" name="id" value="<?=$frm['id']?>">
<table border="0" cellpadding="3" cellspacing="3" width="760">
<tr>
	<td class="entete" colspan="2">Ajouter une cat&eacute;gorie</td>
</tr>
<tr>
	<td colspan="2" class="label">Choisir la cat&eacute;gorie :</td>
</tr>
<tr>
	<td colspan="2" class="normal">
		<select class="formulaire1" name="parent" style="width:100%" size="10">
		<option value="0" SELECTED>A la racine</option>
		<?=$categorie_options?>
		</select>
	</td>
</tr>
<tr><td valign="top" class="normal">
					Afficher la cat&eacute;gorie en page d'accueil :
					</td>
					<td><input type="checkbox" name="on_special" <?=frmvalide($frm['on_special'])?>></td>
</tr>
<tr>
					<td class="normal">Position dans la cat&eacute;gorie :</td>
					<td class="normal"><input  size="1" class="formulaire1" type="text" name="position" value="<?=$frm["position"] ?>"></td>
				</tr>
<tr>
					<td class=normal>Etat de la cat&eacute;gorie :</td>
					<td class=normal>
					  <input type="radio" name="etat" value="1" <?php if(vb($frm['etat'])=="1") {echo "checked";} ?>>En ligne<br />
            		  <input type="radio" name="etat" value="0" <?php if(vb($frm['etat'])=="0") {echo "checked";} ?>>En attente
					</td>
</tr>
<tr>
	<td class="normal" bgcolor="<? echo vb($frm['color']); ?>">Couleur : </td>
	<td><input class="formulaire1" type="text" size="10" maxlength="7" name="color" value="<? echo vb($frm['color']); ?>" >
	<img src="<?echo $wwwroot?>/images/color.gif" width="21" height="20" border="0" align="absmiddle" onClick="fctShow(document.entryform.color);" >
	</td>
</tr>
				
<?php

foreach ($_SESSION['lng'] as $lng) {
				
?>

		<tr><td colspan="2" class="bloc">BLOC DE LANGUE <?echo strtoupper($lng) ?></td><tr>
		<tr>
			<td class="label">Nom <?echo $lng ?>:</td>
			<td class="normal"><input style="width:460px" class="formulaire1" type="text" name="nom_<?echo $lng ?>" value="<?=$frm['nom_'.$lng.''] ?>"></td>
	    </tr>
		<tr valign=top>
			<td colspan="2" class="label">Description  <?echo $lng ?>:<br /></td>
		</tr>
		<tr>
			<td colspan="2" class="normal" >
			<p>
Format <select name="p_format" tabindex="2" id="p_format" >
<option value="html" selected="selected">HTML</option>
<option value="wiki">Wiki</option>
</select>
</p>
<p>
<textarea style="width:100%" rows="5" name="description_<?echo $lng ?>" id="description_<?echo $lng ?>" class="formulaire1"><?=$frm['description_'.$lng.''] ?></textarea>
 </p>
 <script src="<?echo $wwwroot?>/lib/js/toolbar.js" type="text/javascript"></script>
 <script type="text/javascript">if (document.getElementById) {
		var tb = new dcToolBar(document.getElementById('description_<?echo $lng ?>'),
		document.getElementById('p_format'),'images/');
		
		tb.btStrong('Forte emphase');
		tb.btEm('Emphase');
		tb.btIns('Ins&eacute;r&eacute;');
		tb.btDel('Supprim&eacute;');
		tb.btQ('Citation en ligne');
		tb.btCode('Code');
		tb.addSpace(10);
		tb.btBr('Saut de ligne');
		tb.addSpace(10);
		tb.btBquote('Bloc de citation');
		tb.btPre('Texte pr&eacute;format&eacute;');
		tb.btList('Liste non ordonn&eacute;e','ul');
		tb.btList('Liste ordonn&eacute;e','ol');
		tb.addSpace(10);
		tb.btLink('Lien',
			'URL ?',
			'Langue ?',
			'fr');
		tb.btImgLink('Image externe',
			'URL ?');
		tb.addSpace(10);
		tb.draw('Vous pouvez utiliser les raccourcis suivants pour enrichir votre presentation.');
	}
	</script>
			</td>
		</tr>
<? } ?>		
				<? if (!empty($frm["image"])){ ?>
				<tr valign=top>
					<td colspan=2 class=label>Image : <br />
					<img src="<?=$repertoire_images.$frm["image"] ?>"><br />
					nom_fr du fichier :<?=$frm["image"]?>&nbsp;
					<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=supprfile&id=<?=vb($frm['id'])?>&file=image"><img src="<?=$wwwroot?>/administrer/images/b_drop.png" width="16" height="16" alt="" border="0">supprimer cette image</a>
					<input type="hidden" name="image" value="<?=$frm["image"]?>">
					</td>
				</tr>
				<? } else {?>
				
				<tr valign=top>
					<td colspan=2 class="label">Image :</tr>
					<tr>
						<td colspan=2 class=normal>
					<input style="width: 100%" name="image" TYPE="file" class="formulaire1" value="">
					</td>
				</tr>
				
				<? }?>
				<tr>
					<td colspan="2" valign="top">
					
					<table class="normal" border="0" width="760" >
				<tr>
					<td class="entete">META DE LA CATEGORIE</td>
				</tr>
				<tr>
					<td class="label">META DU TITRE PRINCIPAL :</td>
				</tr>
				<tr >
					<td ><input class="formulaire1" type="text" name="meta_titre" size=70 value="<?=$frm["meta_titre"] ?>"></td>
				</tr>
				<tr >
					<td class="label">META MOT CLE (s&eacute;parer les mots cl&eacute;s par des virgules) :</td>
				</tr>
				<tr valign="top" >
					<td ><textarea  name="meta_key" style="width:100%" rows="5"><?echo strip_tags(html_entity_decode(nl2br($frm["meta_key"]))) ?></textarea></td>
				</tr>
				<tr >
					<td class="label">META DESCRIPTION :</td>
				</tr>
				<tr valign="top" >
					<td ><textarea name="meta_desc" style="width:100%" rows="10"><?echo  strip_tags(html_entity_decode(nl2br($frm["meta_desc"]))) ?></textarea></td>
				</tr>		
				</table>
					
					</td>
				</tr>
				
				
		<tr>
		<td colspan="2" align="center"><input class="bouton" type="submit" value="<?=$frm["titre_soumet"] ?>"></td>
	</tr>
</table>
</form>
<?php }

function supprime_fichier($id, $file) {
/* Supprime le produit sp&eacute;cifici&eacute; par $id. Il faut supprimer le produit
 * puis les entr&eacute;es correspondantes de la table produits_categories. */
	global $the_path ;
	/* Charge les infos du produit. */

	switch($file) {

	case "image" :
	$sql = "SELECT image FROM categories WHERE id = '$id'";
	$res = query($sql);
	$file =fetch_assoc($res);
	query("UPDATE categories SET image = '' WHERE id = '$id'");
	break;
	
	}
	@unlink($the_path.$file[0]);
	?>
	<div class="normal">
	Le fichier <b><?=$file[0]?> </b>a &eacute;t&eacute; effac&eacute; du serveur.
	</div>

	<?php
}

?>
</html>