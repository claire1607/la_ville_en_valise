<?php
require 'config.php'; 

$DOC_TITLE = "Gestion des cartes d'aspects ";
include("modeles/haut.php");

switch ($mode) {

	case "ajout" :
		if (! isset($categorie_id) ) {$categorie_id = 0;};
		affiche_formulaire_ajout_ aspects ($categorie_id);
		break;
		
	
	case "modif" :
		affiche_formulaire_modif_ aspects ($id);
		break;

	case "suppr" :
		supprime_ aspects s($id);
		affiche_liste_ aspects ($start);
		break;

	case "insere" :
		insere_c aspects s($id, $HTTP_POST_VARS);
		affiche_liste_ aspects ($start);
		break;

	case "maj" :
		maj_ aspects s($id, $HTTP_POST_VARS);
		affiche_liste_ aspects ($start);
		break;

	default :
		affiche_liste_ aspects ($start);
		break;
}

include("modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/

function affiche_formulaire_ajout_ aspects ($categorie_id = 0) {
/* Affiche un formulaire vierge pour ajouter  */

	global $frm;

	/* Valeurs par défaut */
	$frm["categories"] = array($categorie_id);
	$frm["nouveau_mode"] = "insere";
	$frm["nom"] = "";
	$frm["lang"] = "";
	$frm["titre_bouton"] = "Ajouter";
	
	affiche_formulaire_ aspects();
}

function affiche_formulaire_modif_ aspects($id) {
  /* Affiche le formulaire de modification  */

	global $PHP_SELF,$frm,$categorie_options;

	/* Charge les informations  */
	$qid = query("
	SELECT nom
	FROM  aspects
	WHERE id = $id
	");
	$frm = fetch_assoc($qid);
	
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = "Sauvegarder changements";

	affiche_formulaire_ aspects();
}

function affiche_formulaire_ aspects() {
	GLOBAL $PHP_SELF,$frm,$id,$categorie_options,$wwwroot;
	$repertoire_images = "$wwwroot/administrer/upload/";
	?>
		<form name="entryform" method="post" action="<?=$PHP_SELF?>?start=0">
		<input type="hidden" name="mode" value="<?=$frm["nouveau_mode"]?>">
		<input type="hidden" name="id" value="<?=$id?>">
		<table border=0 cellpadding=3 cellspacing=1 width=100%>
		<tr valign=top >
			<td>
			<table class=petit border=0 widtd=100%>
				<tr>
					<td class=petit colspan=2>Nom :</td>
				</tr>
				<tr>
					<td colspan=2><input class="formulaire1" type="text" name="nom" size=70 value="<?=$frm["nom"] ?>"></td>
				</tr>
				<tr>
					<td colspan="2"><input class="bouton" type="submit" value="<?=$frm["titre_bouton"] ?>"></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</form>
	<?php
}

function supprime_ aspects($id) {
/* Supprime l'element spécificié par $id. Il faut supprimer la carte usage qui correspond
 * puis les entrées correspondantes de la table aspects_categories. */

	/* Efface la carte */
	query("DELETE FROM  aspects  WHERE id = $id");

	?>
		<p class=normal>
		Le type de cuisine a été effacé.

	<?php
}

function frmvalide(&$var, $true_value = "checked", $false_value = "") {
/* Affiche le mot "checked" si la variable est vraie sinon rien */

	if ($var) {
		echo $true_value;
	} else {
		echo $false_value;
	}
}

function valide(&$var, $init_valeur = 1, $vide_valeur = 0) {
/* Si var est défini, place init_valeur dedans, sinon place vide_valeur.*/

	if (empty($var)) {
		$var = $vide_valeur;
	} else {
		$var = $init_valeur;
	}
}

function insere_ aspects ($id, $frm) {
	/*ajoute la carte aspects  dans table  aspects */
	$qid = query("
	INSERT INTO  aspects (nom) VALUES ('".addslashes($frm['nom'])."')");
	
}

function maj_ aspects ($id, $frm) {
/* Met à jour de la carte aspects  $id avec de nouvelles valeurs. Les champs sont dans $frm */

	/* Met à jour la table aspects */
	$qid = query("
	UPDATE  aspects SET
		nom = '".addslashes($frm['nom'])."'
	WHERE id = $id
	");

}

function affiche_liste_ aspects($start) 
{

?>
<table border=0 cellpadding=0 cellspacing=1 width=100%>
<tr><td colspan="3" height="1" bgcolor="#336699"><img src="/images/blank.gif" height="1"></td></tr>
<tr bgcolor="#EEEEEE">
	<td class=label colspan="3">Gérer les cartes aspects</td>
</tr>
<tr><td colspan="3" height="1" bgcolor="#336699"><img src="/images/blank.gif" height="1"></td></tr>

<tr>
	<td colspan=4><a class=petit href="<?=$PHP_SELF?>?mode=ajout"><b>Ajouter une carte aspects </b></a></td>
</tr>
			<?php
			$result = query("SELECT id, nom FROM  aspects  ORDER BY id DESC"); 
			if ($result->RowCount() == 0){ {echo "<tr><td class=normal><b>Aucune carte enregistrée dans la base.</b></td></tr>"; } 
			else {
			?>
			<tr bgcolor="#6699ff">
				<td class=petit>Action</td>
				<td class=petit>carte usage</td>
			</tr>
			<?php 
			while ($ligne = fetch_assoc($result)) { ?>
					<tr>
						<td class=petit><a class=petit onClick="Javascript:return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?');" title="Supprimer <?=$ligne['nom'] ?>" href="<?=$PHP_SELF?>?mode=suppr&id=<?=$ligne['id'] ?>"><img src=../images/poubelle.gif border=0></a></td>
						<td class=petit><a class=petit title="Modifier cette carte href="<?=$PHP_SELF?>?mode=modif&id=<?=$ligne['id'] ?>"><?=stripslashes($ligne['nom']) ?></a></td>
					</tr>
<?php
			}

		}
				echo "</table>";
	}
?>