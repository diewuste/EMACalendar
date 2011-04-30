<?php

/**
 * Classe planning EMA
 * 
 * Tout les retours sont effectués en JSON pour le web-service (webservice.php)
 */
class Planning {

	/**
	 * @var int ID de la promo
	 */
	private $promoId;

	/**
	 * @param int $promoId
	 */
	public function __construct($promoId = null) {
		$this->promoId = $promoId;
	}

	/**
	 * Génère un objet JSON contenant la liste des promos
	 * 
	 * @return JSON object
	 */
	public function getPromos() {
		$url = 'http://webdfd.mines-ales.fr/cybema/cgi-bin/cgiempt.exe?TYPE=promos_txt';

		$promos = array();
		$i = -1;

		if (($ha = fopen($url, 'r')) !== FALSE) {
			while (($data = fgetcsv($ha, 1000, ";")) !== FALSE) {
				foreach ($data as $key => $val) {
					switch (trim($val)) {
						case 'P0' :
							++$i;
							$promos[$i]['id'] = trim($data[$key + 1]);
							break;
						case 'NOM' :
							$promos[$i]['nom'] = htmlentities(trim($data[$key + 1]));
							break;
					}
				}
			}
			fclose($ha);
		}

		return json_encode($promos);
	}

	/**
	 * Génère un objet JSON contenant la liste des cours
	 * 
	 * @param int $dateDebut (Ymd)
	 * @param int $dateFin (Ymd)
	 * @return JSON object
	 */
	public function getCours($dateDebut, $dateFin) {
		$url = 'http://webdfd.mines-ales.fr/cybema/cgi-bin/cgiempt.exe?'
				. 'TYPE=planning_txt'
				. '&DATEDEBUT=' . $dateDebut
				. '&DATEFIN=' . $dateFin
				. '&TYPECLE=p0cleunik'
				. '&VALCLE=' . $this->promoId
				. '&UNIQUE=0';

		$cours = array();
		$i = -1;

		if (($ha = fopen($url, 'r')) !== FALSE) {
			while (($data = fgetcsv($ha, 1000, ";")) !== FALSE) {
				foreach ($data as $key => $val) {
					switch (trim($val)) {
						case 'PL' :
							++$i;
							break;
						case 'DATE' :
							$date = trim($data[$key + 1]);
							break;
						case 'HD' :
							$cours[$i]['dateDebut'] = $date . 'T' . trim($data[$key + 1]) . '00';
							break;
						case 'HF' :
							$cours[$i]['dateFin'] = $date . 'T' . trim($data[$key + 1]) . '00';
							break;
						case 'COURS' :
							$cours[$i]['intitule'] = trim($data[$key + 1]);
							break;
						case 'PROF' :
							$cours[$i]['prof'] = trim($data[$key + 1]);
							break;
						case 'SALLE' :
							$cours[$i]['salle'] = trim($data[$key + 1]);
							break;
						case 'GROUPE' :
							$cours[$i]['groupe'] = trim($data[$key + 1]);
							break;
						case 'TYPE' :
							$cours[$i]['type'] = trim($data[$key + 1]);
							break;
						case 'LANOTE' :
							$cours[$i]['lanote'] = trim($data[$key + 1]);
							break;
					}
				}
			}
			fclose($ha);
		}

		return json_encode($cours);
	}

	/**
	 * Echo planning au format iCal
	 * 
	 * @param JSON object $cours
	 */
	public function getICal($cours) {
		header('Content-type: text/calendar; charset=utf-8');
		header('Content-Disposition: inline; filename=calendar.ics');

		$ical = 'BEGIN:VCALENDAR' . "\n";
		$ical .= 'VERSION:2.0' . "\n";
		$ical .= 'PRODID:-//Apple Inc.//iCal 4.0.4//EN' . "\n";
		$ical .= 'X-WR-TIMEZONE:Europe/Paris' . "\n";
		$ical .= 'CALSCALE:GREGORIAN' . "\n";

		$ical .= 'BEGIN:VTIMEZONE' . "\n";
		$ical .= 'TZID:Europe/Paris' . "\n";
		$ical .= 'BEGIN:DAYLIGHT' . "\n";
		$ical .= 'TZOFFSETFROM:+0100' . "\n";
		$ical .= 'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU' . "\n";
		$ical .= 'DTSTART:19810329T020000' . "\n";
		$ical .= 'TZNAME:GMT+02:00' . "\n";
		$ical .= 'TZOFFSETTO:+0200' . "\n";
		$ical .= 'END:DAYLIGHT' . "\n";
		$ical .= 'BEGIN:STANDARD' . "\n";
		$ical .= 'TZOFFSETFROM:+0200' . "\n";
		$ical .= 'RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU' . "\n";
		$ical .= 'DTSTART:19961027T030000' . "\n";
		$ical .= 'TZNAME:GMT+01:00' . "\n";
		$ical .= 'TZOFFSETTO:+0100' . "\n";
		$ical .= 'END:STANDARD' . "\n";
		$ical .= 'END:VTIMEZONE' . "\n";

		foreach (json_decode($cours) as $val) {
			$title = (!empty($val->groupe) && $val->groupe != '-' ? $val->groupe . ': ' : '') . (!empty($val->type) ? $val->type . ': ' : '') . $val->intitule . '\n' . $val->salle . '\n' . $val->prof . '\n\n' . (isset($val->lanote) ? $val->lanote : '');

			$ical .= 'BEGIN:VEVENT' . "\n";
			$ical .= 'X-APPLE-DONTSCHEDULE:TRUE' . "\n";
			$ical .= 'UID:' . md5(uniqid(mt_rand(), true)) . '+nicolas.talon@mines-ales.org' . "\n";
			$ical .= 'DTSTAMP:' . gmdate('Ymd') . 'T' . gmdate('His') . 'Z' . "\n";
			$ical .= 'SUMMARY:' . $title . "\n";
			$ical .= 'DTSTART;TZID=Europe/Paris:' . $val->dateDebut . "\n";
			$ical .= 'DTEND;TZID=Europe/Paris:' . $val->dateFin . "\n";
			$ical .= 'END:VEVENT' . "\n";
		}

		$ical .= 'END:VCALENDAR' . "\n";

		echo $ical;
	}

}

?>