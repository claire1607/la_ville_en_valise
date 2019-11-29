<?php
require 'config.php'; 

$DOC_TITLE = "Gestion des cartes d'événements ";
include("modeles/haut.php");

switch ($mode) {

	case "ajout" :
		if (! isset($categorie_id) ) {$categorie_id = 0;};
		affiche_formulaire_ajout_evenements ($categorie_id);
		break;
		
	
	case "modif" :
		affiche_formulaire_modif_evenements ($id);
		break;

	case "suppr" :
		supprime_evenements ($id);
		affiche_liste_evenements ($start);
		break;

	case "insere" :
		insere_c  evenements ($id, $HTTP_POST_VARS);
		affiche_liste_evenements ($start);
		break;

	case "maj" :
		maj_evenements ($id, $HTTP_POST_VARS);
		affiche_liste_evenements ($start);
		break;

	default :
		affiche_liste_evenements ($start);
		break;
}

include("modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/

function affiche_formulaire_ajout_evenements ($categorie_id = 0) {
/* Affiche un formulaire vierge pour ajouter  */

	global $frm;

	/* Valeurs par défaut */
	$frm["categories"] = array($categorie_id);
	$frm["nouveau_mode"] = "insere";
	$frm["nom"] = "";
	$frm["lang"] = "";
	$frm["titre_bouton"] = "Ajouter";
	
	affiche_formulaire_evenements();
}

function affiche_formulaire_modif_evenements($id) {
  /* Affiche le formulaire de modification  */

	global $PHP_SELF,$frm,$categorie_options;

	/* Charge les informations  */
	$qid = query("
	SELECT nom
	FROM   evenements
	WHERE id = $id
	");
	$frm = fetch_assoc($qid);
	
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = "Sauvegarder changements";

	affiche_formulaire_evenements();
}

function affiche_formulaire_evenements() {
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

function supprime_evenements($id) {
/* Supprime l'element spécificié par $id. Il faut supprimer la carte usage qui correspond
 * puis les entrées correspondantes de la table  evenements_categories. */

	/* Efface la carte */
	query("DELETE FROM   evenements  WHERE id = $id");

	?>
		<p class=normal>
		La carte a été effacée.

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

function insere_evenements ($id, $frm) {
	/*ajoute la carte  evenements  dans table  evenements */
	$qid = query("
	INSERT INTO   evenements (nom) VALUES ('".addslashes($frm['nom'])."')");
	
}

function maj_evenements ($id, $frm) {
/* Met à jour de la carte  evenements  $id avec de nouvelles valeurs. Les champs sont dans $frm */

	/* Met à jour la table  evenements */
	$qid = query("
	UPDATE   evenements SET
		nom = '".addslashes($frm['nom'])."'
	WHERE id = $id
	");

}

function affiche_liste_evenements($start) 
{

?>
<table border=0 cellpadding=0 cellspacing=1 width=100%>
<tr><td colspan="3" height="1" bgcolor="#336699"><img src="/images/blank.gif" height="1"></td></tr>
<tr bgcolor="#EEEEEE">
	<td class=label colspan="3">Gérer les cartes  événements</td>
</tr>
<tr><td colspan="3" height="1" bgcolor="#336699"><img src="/images/blank.gif" height="1"></td></tr>

<tr>
	<td colspan=4><a class=petit href="<?=$PHP_SELF?>?mode=ajout"><b>Ajouter une carte  événements </b></a></td>
</tr>
			<?php
			$result = query("SELECT id, nom FROM   evenements  ORDER BY id DESC"); 
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