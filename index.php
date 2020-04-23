<?php
if (isset($_GET["lyrics"])) {
	$url = "https://genius.com".$_GET["lyrics"];
	$doc = new DOMDocument;
	libxml_use_internal_errors(true);
	$doc->loadHTMLFile($url);
	$xpath = new DOMXPath($doc);
	$query = "//div[@class='lyrics']";
	$entries = $xpath->query($query);
	$var = $entries->item(0)->textContent;
	$lyrics = trim($var)."\n";
	die($lyrics);
}

require_once('../../data/cply-web/vendor/autoload.php');

if (isset($_GET["search"])) {

	$access_token = 'access_token';

	$authentication = new \Genius\Authentication\OAuth2(
	  "",
	  "",
	  'https://github.com/asvvvad/cply',
	  new \Genius\Authentication\Scope([
		  \Genius\Authentication\Scope::SCOPE_ME,
		  \Genius\Authentication\Scope::SCOPE_CREATE_ANNOTATION,
		  \Genius\Authentication\Scope::SCOPE_MANAGE_ANNOTATION
	  ]),
	  null
	);

	$authentication->setAccessToken($access_token);

	$genius = new \Genius\Genius($authentication);

	$hits = $genius->getSearchResource()->get($_GET["search"])->response->hits;
	header('Content-Type: application/json');
	$response = [];
	foreach ($hits as $key => $hit) {
		$response[$key] = [
			"path" => $hit->result->path,
			"title" => $hit->result->full_title,
			"art" => $hit->result->song_art_image_thumbnail_url];
	}
	header("Content-Type: application/json");
	die(json_encode($response));
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="asvvvad">
		<meta name="description" content="Easily search, copy and view lyrics">
		<title>CPLY-WEB</title>
		<link rel="stylesheet" type="text/css" href="../assets/css/b0/b0.css">
		<link rel="stylesheet" type="text/css" href="../assets/css/b0/white.css">
		<style type="text/css">
		</style>
<script type="text/javascript">
function copyLyrics(path) {
	let el;
	let listItem = document.getElementById(path);
	if (listItem.children.length == 5) {
		el = listItem.children[4];
		navigator.clipboard.writeText(el.value);
	} else {
		el = document.createElement('textarea');
		el.classList.add('amiddle');
		el.setAttribute('readonly', '');
		el.style.height = "30rem";
		el.style.width = "93%";
		el.style.resize = "none";
		document.getElementById(path).appendChild(el);
		fetch('?lyrics=' + path)
			.then(res => res.text())
			.then(res => {
				el.value = res;
			navigator.clipboard.writeText(res)
		});
	}
	
}
function searchLyrics(q) {
	if (q != '') {
	fetch('?search=' + q)
		.then(res => res.json())
		.then(listData => {
			// Make a container element for the list
			listContainer = document.getElementById('results');
			// Set up a loop that goes through the items in listItems one at a time
			numberOfListItems = listData.length;
			if (numberOfListItems == 0) {
				listContainer.innerHTML = "<h2>No results found</h2>";
			} else {
				// clear previous results/error message
				listContainer.innerHTML = "";
				// Make the list
				listElement = document.createElement('ul');
				listElement.style = "list-style-type: none;";
				listContainer.appendChild(listElement);
				listContainer.classList.add('tacenter');
				// Add it to the page
				for (i = 0; i < numberOfListItems; i++) {
					// create an item for each one
					listItem = document.createElement('li');

					// Add the item text
					listItem.id = listData[i]["path"];
					art = document.createElement('img');
					art.src = listData[i]["art"];
					button = document.createElement('button');
					button.setAttribute("onClick", "copyLyrics(\""+listData[i]["path"]+"\")");
					button.innerHTML = "copy";
					title = document.createElement('h4');
					title.innerHTML = listData[i]["title"];
					listItem.appendChild(art);
					listItem.appendChild(document.createElement('br'));
					listItem.appendChild(title);
					listItem.appendChild(button);
					// Add listItem to the listElement
					listElement.appendChild(listItem);
				};
			}
		})
	}
}
</script>
	</head>
<body>
	<div class="container">
		<header class="tacenter">
			<h1><a href="https://github.com/asvvvad/cply-web" target="_blank">CPLY-WEB</a></h1>
		</header>
		<div class="content tacenter">
			<hr>
			<input type="text" name="search" placeholder="search for a song" onchange="searchLyrics(this.value)" style="width: 89%">
			<div id="results">
			</div>
			<footer class="tacenter">
				<hr>
				<a href="https://github.com/asvvvad/cply-web" target="_blank">CPLY-WEB</a> by <a href="https://asvvvad.eu.org/">asvvvad</a>.<br>
			</footer>
		</div>
	</div> 
</body>
