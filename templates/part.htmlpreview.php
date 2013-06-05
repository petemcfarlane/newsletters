<?php 
$query = OCP\DB::prepare('SELECT * FROM *PREFIX*newsletters WHERE id = ?');
$result = $query->execute( array($_GET['id']) );
$data = $result->fetchRow();
$content = json_decode($data['content'], true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width" />
		<title><?php print $data['subject']; ?></title>
		<style>img {outline: none;	border: none;}h1, h2, h3, h4, h5, h6 {color: #000;}	a {	color: #3A76C3;	text-decoration: underline;}a:hover {text-decoration: none;}.more-link:after {content: ' \2192';}</style>
	</head>
	<body style="color:#333333;margin:0;background: #C9C5C4 url('https://www.sontia.com/newsletters/images/sparkle3.jpg') 0 0 repeat;font-family: Helvetica, Arial, sans-serif;">
		<table width="100%" cellspacing="0" cellpadding="0" style="background: url('https://www.sontia.com/newsletters/images/sparkle3.jpg')" >
			<tbody>
				<tr>
					<td align="center" valign="middle">
						<table cellspacing="0" cellpadding="15" border="0" width="550">
							<tbody>
								<tr>
									<td width="550" cellpadding="0">
										<p style="font-size: 0.8em;margin:0;" align="center">Email not displaying beautifully? <a href="http://www.sontia.com/newsletters/?id=<?php print $_GET['id']; ?>">View it in your browser</a></p>
									</td>
								</tr>
								<tr style="background:#00001b;">
									<td align="center"><a href="https://www.sontia.com"> <img src="https://www.sontia.com/newsletters/images/sontia_logo219x44.png" width="219" height="44" alt="Sontia"/> </a>
										<p style="margin-bottom:0;color:#ddd;font-size:0.8em;">Get the latest info and news form Sontia Logic ltd</p>
									</td>
								</tr>
								<tr style="background:#ffffff;" align="left">
									<td>
										<table>
										<tbody>
											<?php foreach ($content as $story) { ?>
												<tr>
													<td>
														<a href="<?php print $story['link']; ?>"><img src="data:image/jpeg;base64,<?php print $story['image']['encoded']; ?>" alt="<?php print $story['image']['name']; ?>" style="float: left;margin: 0 1em 1em 0;box-shadow: 0px 7px 5px -3px #CCC;" width="200" height="157" /></a>
													</td>
													<td>
														<h2 style="margin-top:0;"><?php print $story['heading']; ?></h2>
														<p><?php print nl2br($story['text']); ?><a href="<?php print $story['link']; ?>" class="more-link">Read more</a></p>
													</td>
												</tr>
												<tr><td colspan="2"><hr /></td></tr>												
											<?php } ?>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td align="center">
										<p style="margin-top:0;"><a href="//www.sontia.com">www.sontia.com</a></p>
										<table>
											<tbody align="center" cellpadding="20">
												<tr>
													<td><a href="//www.facebook.com/SontiaLogic"><img src="//www.sontia.com/newsletters/images/logo_fb.png" /></a></td>
													<td><a href="//twitter.com/sontialogic"><img src="//www.sontia.com/newsletters/images/logo_tw.png" /></a></td>
													<td><a href="//www.linkedin.com/company/sontia-logic-limited"><img src="//www.sontia.com/newsletters/images/logo_li.png" /></a></td>
												</tr>
												<tr>
													<td><a href="//www.facebook.com/SontiaLogic">Facebook</a></td>
													<td><a href="//twitter.com/sontialogic">Twitter</a></td>
													<td><a href="//www.linkedin.com/company/sontia-logic-limited">LinkedIn</a></td>
												</tr>
											</tbody>
										</table>
										<p>You are receiving this email because you have subscribed to our mailing list. You are free at any time to <a href="#">change your email preferences or unsubscribe here.</a></p>
										<p>&copy; Sontia Logic ltd 2013</p>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>