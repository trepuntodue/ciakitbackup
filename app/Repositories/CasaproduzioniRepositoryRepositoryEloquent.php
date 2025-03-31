<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CasaproduzioniRepositoryRepository;
use App\Entities\CasaproduzioniRepository;
use App\Validators\CasaproduzioniRepositoryValidator;

/**
 * Class CasaproduzioniRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CasaproduzioniRepositoryRepositoryEloquent extends BaseRepository implements CasaproduzioniRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CasaproduzioniRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
