<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ReleaseRepositoryRepository;
use App\Entities\ReleaseRepository;
use App\Validators\ReleaseRepositoryValidator;

/**
 * Class MasterRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ReleaseRepositoryRepositoryEloquent extends BaseRepository implements ReleaseRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ReleaseRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
