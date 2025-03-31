<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;
use Webkul\Product\Repositories\SearchRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // PWS#15

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @param  \Webkul\Core\Repositories\SliderRepository  $sliderRepository
	 * @param  \Webkul\Product\Repositories\SearchRepository  $searchRepository
	 * @return void
	 */
	public function __construct(
		protected SliderRepository $sliderRepository,
		protected SearchRepository $searchRepository,
	) {
		parent::__construct();
	}

	/**
	 * Loads the home page for the storefront.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$sliderData = $this->sliderRepository->getActiveSliders();
		$masters = DB::table('master as ca')
			->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
			->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
			->leftJoin('generi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'generi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
			})
			->leftJoin('registi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'registi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
			})
			->leftJoin('master_user_favorite', function ($join) {
				$join->on('master_user_favorite.master_id', '=', 'ca.master_id');
			}) // PWS#13-home-2
			->leftJoin('language', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'language.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.lingua')));
			}) // PWS#10-lang
			->leftJoin('countries', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'countries.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
			}) // PWS#13-paese
			->where('master_images.position', 0)
			->where(function ($query) {
				$user = Auth::user();
				if (!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false))) {
					$query->where('ca.master_vt18', '!=', 1); // PWS#13-vt18 vietato ai minori // PWS#15
					$query->orWhere(function ($query) {
						$query->whereNotIn('ca.master_id', function ($query) {
							$query->select('mr.master_id')
								->from('master_relazioni as mr')
								->where('mr.elemento_id', '=', DB::raw(config('constants.master.generi.film_per_adulti')))
								->where('mr.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
						});
					});
				}
			}) // PWS#15
			->addSelect(
				'ca.master_id',
				DB::raw(DB::getTablePrefix() . 'master_images.path as path'),
				'ca.master_year',
				'ca.master_actors',
				'ca.url_key',
				'ca.master_scriptwriters',
				'ca.master_musiccomposers',
				'ca.master_othertitle',
				'ca.master_maintitle',
				'ca.country',
				'ca.master_type',
				'ca.master_status',
				'ca.created_at',
				'ca.master_vt18',
				'ca.master_is_visible',
				'ca.master_studios',
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lang_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.code SEPARATOR \', \') as country'),
				DB::raw('COUNT(' . DB::getTablePrefix() . 'master_user_favorite.master_id) AS favorite_counter'),
			)
			->groupBy('ca.master_id')
			->orderBy('favorite_counter', 'desc')
			->orderBy('created_at', 'asc')->take(28)->get()->all(); // PWS#frontend // PWS#13-home-2

		$masters_last = DB::table('master as ca')
			->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
			->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
			->leftJoin('generi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'generi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
			})
			->leftJoin('registi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'registi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
			})
			->leftJoin('language', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'language.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.lingua')));
			}) // PWS#10-lang
			->leftJoin('countries', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'countries.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
			}) // PWS#13-paese
			->whereRaw(DB::getTablePrefix() . 'ca.master_type = "movie"')
			->whereRaw(DB::getTablePrefix() . 'master_images.position = 0')
			->where('ca.master_is_visible', '=', DB::raw(1))
			->where(function ($query) {
				$user = Auth::user();
				if (!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false))) {
					$query->where('ca.master_vt18', '!=', 1); // PWS#13-vt18 vietato ai minori // PWS#15
					$query->orWhere(function ($query) {
						$query->whereNotIn('ca.master_id', function ($query) {
							$query->select('mr.master_id')
								->from('master_relazioni as mr')
								->where('mr.elemento_id', '=', DB::raw(config('constants.master.generi.film_per_adulti')))
								->where('mr.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
						});
					});
				}
			}) // PWS#15
			->addSelect(
				'ca.master_id',
				DB::raw(DB::getTablePrefix() . 'master_images.path as path'),
				'ca.master_year',
				'ca.master_actors',
				'ca.master_scriptwriters',
				'ca.master_musiccomposers',
				'ca.master_language',
				'ca.master_maintitle',
				'ca.country',
				'ca.master_type',
				'ca.master_status',
				'ca.url_key',
				'ca.created_at',
				'ca.master_vt18',
				'ca.master_is_visible',
				'ca.master_studios',
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
				DB::raw('GROUP_CONCAT(DISTINCT CONCAT(' . DB::getTablePrefix() . 'registi.id, "_*_",' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'), // PWS#chiusura
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lang_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.code SEPARATOR \', \') as country'),
			)
			->groupBy('ca.master_id')
			->orderBy('ca.created_at', 'desc')->take(28)->get()->all(); // PWS#frontend // PWS#13-home // PWS#chiusura

		$masters_series = DB::table('master as ca')
			->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
			->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
			->leftJoin('generi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'generi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
			})
			->leftJoin('registi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'registi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
			})
			->leftJoin('language', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'language.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.lingua')));
			})
			->leftJoin('countries', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'countries.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
			})
			->whereRaw(DB::getTablePrefix() . 'master_images.position = 0')
			->where(function ($query) {
				$user = Auth::user();
				if (!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false))) {
					$query->where('ca.master_vt18', '!=', 1);
					$query->orWhere(function ($query) {
						$query->whereNotIn('ca.master_id', function ($query) {
							$query->select('mr.master_id')
								->from('master_relazioni as mr')
								->where('mr.elemento_id', '=', DB::raw(config('constants.master.generi.film_per_adulti')))
								->where('mr.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
						});
					});
				}
			})
			->whereRaw(DB::getTablePrefix() . 'ca.master_type = "movie-episode-TV"')
			->where('ca.master_is_visible', '=', DB::raw(1))
			->addSelect(
				'ca.master_id',
				DB::raw(DB::getTablePrefix() . 'master_images.path as path'),
				'ca.master_year',
				'ca.master_actors',
				'ca.master_scriptwriters',
				'ca.master_musiccomposers',
				'ca.master_language',
				'ca.master_maintitle',
				'ca.country',
				'ca.master_type',
				'ca.master_status',
				'ca.url_key',
				'ca.created_at',
				'ca.master_vt18',
				'ca.master_is_visible',
				'ca.master_studios',
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
				DB::raw('GROUP_CONCAT(DISTINCT CONCAT(' . DB::getTablePrefix() . 'registi.id, "_*_",' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'), // PWS#chiusura
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lang_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.code SEPARATOR \', \') as country'),
			)
			->groupBy('ca.master_id')
			->orderBy('ca.created_at', 'desc')->take(28)->get()->all(); // PWS#04-23 // PWS#chiusura

		$releases_poster = DB::table('releases as ca')
			->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
			->leftJoin('language', 'ca.language', '=', 'language.id')
			->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
			->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
			->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
			->leftJoin('releaseType as rt', 'ca.releasetype', '=', 'rt.id')
			->leftJoin('release_aspect_ratio as rar', 'ca.aspect_ratio', '=', 'rar.id')
			->leftJoin('release_camera_format as rcf', 'ca.camera_format', '=', 'rcf.id')
			->leftJoin('release_tipologia as rtip', 'ca.tipologia', '=', 'rtip.id')
			->leftJoin('release_canali_audio as rca', 'ca.canali_audio', '=', 'rca.id')
			->leftJoin('release_poster_tipo as rpt', 'ca.poster_tipo', '=', 'rpt.id')
			->leftJoin('release_poster_formato as rpf', 'ca.poster_formato', '=', 'rpf.id')
			->leftJoin('release_poster_misure as rpm', 'ca.poster_misure', '=', 'rpm.id')
			->leftJoin('release_formato as rf', 'ca.formato', '=', 'rf.id')
			->leftJoin('generi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'generi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
			})
			->leftJoin('sottogeneri', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'sottogeneri.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.sottogeneri')));
			})
			->leftJoin('countries', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'countries.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
			})
			->leftJoin('registi', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'registi.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
			})
			->leftJoin('sceneggiatori', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'sceneggiatori.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.sceneggiatori')));
			})
			->leftJoin('compositori', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'compositori.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.compositori')));
			})
			->leftJoin('casaproduzione', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'casaproduzione.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.casaproduzione')));
			})
			->leftJoin('attori', function ($join) {
				$join->on('master_relazioni.elemento_id', '=', 'attori.id');
				$join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.attori')));
			})
			->addSelect(
				'ca.*',
				'master.*',
				'master.url_key AS master_url_key',
				'ca.url_key AS url_key', // PWS#finale
				'rt.name as release_type',
				'rar.nome as aspect_ratio',
				'rcf.nome as camera_format',
				'rpt.nome as poster_tipo',
				'rpf.nome as poster_formato',
				'rpm.nome as poster_misure',
				'rf.nome as formato',
				'rtip.nome as tipologia',
				'rca.nome as canali_audio',
				DB::raw(DB::getTablePrefix() . 'release_images.path as path'),
				DB::raw(DB::getTablePrefix() . 'master_images.path as master_path'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sottogeneri.subge_name SEPARATOR \', \') as subgenres_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.id SEPARATOR \', \') as genres_ids'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sceneggiatori.scene_nome_cognome SEPARATOR \', \') as sceneggiatori_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'attori.attori_nome_cognome SEPARATOR \', \') as attori_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'compositori.compo_alias SEPARATOR \', \') as compositori_name'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'casaproduzione.casa_nome SEPARATOR \', \') as casaproduzione_nome'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lingua'),
				DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.code SEPARATOR \', \') as country'),
			)
			->where('ca.releasetype', '=', DB::raw(config('constants.release.tipo.poster')))
			->where('ca.release_status', DB::raw(1))
			->where('ca.release_is_visible', DB::raw(1))
			->orderBy('ca.created_at', 'desc')
			->groupBy('ca.id')
			->take(28)->get()->all(); // PWS#04-23
		$blog = new BlogController(); // PWS#chiusura
		$blogArticles = $blog->showLatestArticles(); // PWS#chiusura
		return view($this->_config['view'], compact('sliderData', 'masters', 'masters_last', 'masters_series', 'releases_poster', 'blogArticles'));
		// return view($this->_config['view'], compact('sliderData'));


	}

	/**
	 * Loads the home page for the storefront if something wrong.
	 *
	 * @return \Exception
	 */
	public function notFound()
	{
		abort(404);
	}

	/**
	 * Upload image for product search with machine learning.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function upload()
	{
		return $this->searchRepository->uploadSearchImage(request()->all());
	}
}
