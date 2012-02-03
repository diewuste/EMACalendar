<?php
require_once '../Planning.class.php';
require_once '../functions.php';

$planning = new Planning();
$promos = $planning->getPromos();

ob_start('clean_source');
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>EMACalendar</title>
		<meta name="viewport" content="user-scalable=no,width=device-width" />
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
		<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
		<script type="text/javascript">
			function add(addGCal) {
				var dateDebut = $('#dateDebut').val();
				var dateFin = $('#dateFin').val();
				dateDebut = dateDebut.replace(/\//g, '');
				dateFin = dateFin.replace(/\//g, '');
				
				var uriPart = '://<?php echo $_SERVER['SERVER_NAME']; ?>/cal.php'
					+ '?promo='+$('select#promo').val()
					+ '&dateDebut='+dateDebut
					+ '&dateFin='+dateFin;
				var fullUri = (addGCal?'http://www.google.com/calendar/render?cid='+encodeURIComponent('http'+uriPart):'webcal'+uriPart);
				window.open(fullUri);
			}
			
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-22980463-1']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>
	<body>
		<div data-role="page">
			<div data-role="header">
				<h1><span style="color: blue">E</span><span style="color: yellow">M</span><span style="color: red">A</span>Calendar</h1>
			</div>
		
			<div data-role="content">
				<span>Choisissez une promo :</span>
				<select data-native-menu="false" data-icon="gear" name="promo" id="promo">
					<option>Choisissez...</option>
					<?php foreach (json_decode($promos) as $promo) : ?>
						<option value="<?php echo $promo->id; ?>"><?php echo $promo->nom; ?></option>
					<?php endforeach; ?>
				</select>
				<span>Date de début d'import</span>
				<input type="text" name="dateDebut" id="dateDebut" value="<?php echo date("Y/m/d", strtotime("-1 weeks")); ?>"/>
				<br/>
				<span>Date de fin d'année</span>
				<input type="text" name="dateFin" id="dateFin" value="<?php echo (date("m", time()) >= 9) ? date("Y", strtotime("+1 years")) : date("Y", time()); ?>/08/31"/>
				<br/>
				<a data-role="button" data-icon="grid" onclick="add(false);">S'abonner sur iCal</a></td>
				<a data-role="button" data-icon="grid" onclick="add(true);">S'abonner sur Google Agenda</a>
			</div>
		</div>
	</body>
</html>
<?php ob_end_flush(); ?>