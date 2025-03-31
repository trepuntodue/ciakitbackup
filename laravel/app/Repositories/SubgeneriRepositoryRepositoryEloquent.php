<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SubgeneriRepositoryRepository;
use App\Entities\SubgeneriRepository;
use App\Validators\SubgeneriRepositoryValidator;

/**
 * Class MasterRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SubgeneriRepositoryRepositoryEloquent extends BaseRepository implements SubgeneriRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SubgeneriRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
