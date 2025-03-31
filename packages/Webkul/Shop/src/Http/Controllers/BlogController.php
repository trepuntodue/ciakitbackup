<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class BlogController extends Controller

{
	public function showLatestArticles()
	{
		$client = new Client(['verify' => false]); // PWS#chiusura
		$response = $client->get('https://ciakit.com/blog/wp-json/wp/v2/posts?per_page=3');
		$responseMedia = $client->get('https://ciakit.com/blog/wp-json/wp/v2/media');

		// Controlla se la richiesta ha avuto successo
		if ($response->getStatusCode() == 200) {
			$articles = json_decode($response->getBody());
			$medias = json_decode($responseMedia->getBody());

			// Unifica i dati in un array associativo
			$blogArticles = [
				'articles' => $articles,
				'medias' => $medias,
			];

			// Restituisci l'array di dati alla vista
			return $blogArticles;
		}

		// Gestisci eventuali errori
		// return view('blog.error');
		return [];
	}
}
