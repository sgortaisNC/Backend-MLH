<?php

add_filter('wds_metadesc', function($description) {
	if ( empty($description) ) {
		return ""; // Texte par défaut lorsque le contenu de la page est vide.
	}

	if ( strlen($description) > 172 ) {
		$description = substr(strip_tags($description), 0, 172) . '...';
	}

	return $description;
});
