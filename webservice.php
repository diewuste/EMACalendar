<?php

/**
 * Web-service pour ceux qui voudraient créer une autre aplication ;)
 * 
 * Utilisation :
 * 
 * - _URL_/webservice.php : retourne objet JSON contenant la liste promo/id
 * 
 * - _URL_/webservice.php?promo=id_promo&dateDebut=AAAAMMJJ&dateFin=AAAAMMJJ : 
 *      retourne un objet contenant la liste des cours de dateDebut à dateFin.
 * 
 */
require_once 'Planning.class.php';

header('Content-type: application/json');

$promo = isset($_GET['promo']) ? $_GET['promo'] : null;

$planning = new Planning($promo);
if ($promo) {
	if (!isset($_GET['dateDebut']) || !isset($_GET['dateFin']))
		echo 'USE : ' . $_SERVER['PHP_SELF'] . '?promo=id_promo&dateDebut=AAAAMMJJ&dateFin=AAAAMMJJ';
	else
		echo $planning->getCours($_GET['dateDebut'], $_GET['dateFin']);
} else {
	echo $planning->getPromos();
}
?>