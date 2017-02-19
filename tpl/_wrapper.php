<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">

		<title>Bitcoin-node status</title>

		<link href="static/bootstrap.min.css" rel="stylesheet">
	</head>

	<body>
		<div class="container">
			<div class="header clearfix">
				<nav>
					<ul class="nav nav-pills float-right">
						<li class="nav-item">
							<a class="nav-link active" href="#">Status <span class="sr-only">(current)</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Peers</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Contact</a>
						</li>
					</ul>
				</nav>
				<h3 class="text-muted"><?=$this->info->network->subversion?></h3>
			</div>

<?=$this->content?>

			<footer class="footer">
				<p>&copy; Company 2017</p>
			</footer>
		</div>
	</body>
</html>