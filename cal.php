<?php

require_once 'Planning.class.php';

$planning = new Planning($_GET['promo']);
$cours = $planning->getCours($_GET['dateDebut'], $_GET['dateFin']);
$planning->getICal($cours);

?>