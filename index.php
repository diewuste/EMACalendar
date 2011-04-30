<?php
require_once 'Planning.class.php';
require_once 'functions.php';

$planning = new Planning();
$promos = $planning->getPromos();

ob_start('clean_source');
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>EMACalendar</title>
		<link href="css/main.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/mootools.core.js" type="text/javascript"></script>
		<script src="js/mootools.more.js" type="text/javascript"></script>
		<script type="text/javascript">
			function add(addGCal) {
				var dateDebut = $('dateDebut').get('value');
				var dateFin = $('dateFin').get('value');
				dateDebut = dateDebut.replace(/\//g, '');
				dateFin = dateFin.replace(/\//g, '');
				
				var uriPart = '://<?php echo $_SERVER['SERVER_NAME']; ?>/cal.php'
					+ '?promo='+$('promo').getSelected().get('value')
					+ '&dateDebut='+dateDebut
					+ '&dateFin='+dateFin;
				var fullUri = (addGCal?'http://www.google.com/calendar/render?cid='+encodeURIComponent('http'+uriPart):'webcal'+uriPart);
				var uri = new URI(fullUri);
				uri.go();
			}
			
			window.addEvent('domready', function() {
				var tips = new Tips($$('.tooltip'), {
					onShow: function(tip, el) {
						tip.setStyles({
							visibility: 'hidden',
							display: 'block'
						}).fade('in');
					},
					onHide: function(tip, el) {
						tip.fade('out').get('tween').chain(function() {
							tip.setStyle('display', 'none');
						})
					}
				});
				
				doBump('.feedback_link', 200, 300, '111', '666', '0.8', 5, 1,'333', 15,'000', 3,
				Fx.Transitions.Quad.EaseIn,
				Fx.Transitions.Quad.EaseOut,
				'img/bumpbox.png',
				'top left',
				'repeat-x');
			});
			
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
		<div id="wrapper">
			<header>
				<h1><span style="color: blue">E</span><span style="color: yellow">M</span><span style="color: red">A</span>Calendar</h1>
			</header>

			<a href="feedback.php" class="feedback_link" rel="550-390" title="Feedback"><span id="feedback">Feedback</span></a>

			<article>
				<section>
					<form action='#'>
						<fieldset>
							<legend>Synchronisation</legend>
							<label for="promo">Promo</label>
							<select name="promo" id="promo">
								<?php foreach (json_decode($promos) as $promo) : ?>
									<option value="<?php echo $promo->id; ?>"><?php echo $promo->nom; ?></option>
								<?php endforeach; ?>
							</select>
							<br/>
							<label for="dateDebut">Date de début d'import</label>
							<input type="text" name="dateDebut" id="dateDebut" value="<?php echo date("Y/m/d", strtotime("-1 weeks")); ?>"/>
							<a class="tooltip" rel="Date au format AAAA/MM/JJ"><img src="img/question.png" alt="Date au format AAAA/MM/JJ"/></a>
							<br/>
							<label for="dateFin">Date de fin d'année</label>
							<input type="text" name="dateFin" id="dateFin" value="<?php echo (date("m", time()) >= 9) ? date("Y", strtotime("+1 years")) : date("Y", time()); ?>/08/31"/>
							<a class="tooltip" rel="Date au format AAAA/MM/JJ"><img src="img/question.png" alt="Date au format AAAA/MM/JJ"/></a>
							<br/>
							<table>
								<tr>
									<td><a class="ic tooltip" rel="Connecter l'emploi du temps avec iCal et autres logiciels acceptant le format iCalendar" onclick="add(false);">S'abonner</a></td>
									<td><a class="gc tooltip" rel="Connecter l'emploi du temps avec Google Agenda" onclick="add(true);">S'abonner</a></td>
								</tr>
							</table>
						</fieldset>
					</form>
				</section>
			</article>

			<footer class="center">
				<a href="http://validator.w3.org/check?uri=<?php echo urlencode($_SERVER['SERVER_NAME']); ?>">HTML5</a> / <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo urlencode($_SERVER['SERVER_NAME']); ?>&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;vextwarning=&amp;lang=en">CSS3</a> / <a href="http://www.webkit.org/">+WEBKIT</a>
			</footer>
		</div>

		<script type="text/javascript" src="js/bumpbox-2.0.1.js"></script> 

	</body>
</html>
<?php ob_end_flush(); ?>