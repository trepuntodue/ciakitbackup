<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CompositoriRepositoryRepository;
use App\Entities\CompositoriRepository;
use App\Validators\CompositoriRepositoryValidator;

/**
 * Class CompositoriRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CompositoriRepositoryRepositoryEloquent extends BaseRepository implements CompositoriRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CompositoriRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
