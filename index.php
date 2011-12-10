<?php
/**
 * Class Presentation on XSS
 *
 * @package		XSS
 * @version		0.1
 * @author		Juan "JayCreations" Hernandez
 * @license		MIT
 * @copyright		2011 Juan J. Hernandez
 * @link
 */

include('Comments.php');

if (isset($_POST['submit']))
{
	$comments = new Comments();
	$escape = isset($_POST['escape']) ? true : false;
	$comments->add_comment(array(
		'username' => $_POST['username'],
		'email' => $_POST['email'],
		'comment' => $_POST['comment'],
	), $escape);

	header('Location: ' . $_SERVER['PHP_SELF']);
}

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Juan Hernandez - Class Comments</title>
	<meta name="description" content="">
	<meta name="author" content="Juan 'JayBachatero' Hernandez">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/libs/modernizr-2.0.6.min.js"></script>
</head>
<body>
	<div id="container">
		<div id="header-container">
			<header>
				<h1>Class Comments</h1>
				<nav>
					<ul>
						<li><a href="#" id="add">add comment.</a></li>
						<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">view all.</a></li>
					</ul>
				</nav>
			</header>
			<div class="clearfix"></div>
		</div>
		<div id="main" role="main">
			<section id="add_comment">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<h2>Add Comment</h2>
					<p><label for="username">Name:</label> <input type="text" name="username" id="username" value="" /></p>
					<p><label for="email">Email:</label> <input type="text" name="email" id="email" value="" /></p>
					<p><label for="comment">Comment:</label><br /> <textarea name="comment" id="comment"></textarea></p>
					<p><input type="checkbox" id="escape" name="escape" value="escape" /> <label for="escape">Escape Input</label></p>
					<p><input type="submit" name="submit" value="Submit" /></p>
				</form>
			</section>
		<?php
			$comments = new Comments();
			$view_comments = $comments->get_comments();

			foreach ($view_comments as $comment)
			{
				echo '
			<article>
				<section>
					<header>
						<h2>' . $comment['username'] . '</h2>
						<span>' . date('F d, Y @ g:ia', $comment['time_added']) . '</span>
					</header>
					<p>' . $comment['comment'] . '</p>
				</section>
			</article>
			<div class="clearfix"></div>';
			}
		?>
		</div>
		<footer>
			<p>&copy;Juan J. Hernandez 2011</p>
		</footer>
	</div> <!--! end of #container -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>
	<script defer src="js/script.js"></script>
</body>
</html>