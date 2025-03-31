<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GeneriRepositoryRepository;
use App\Entities\GeneriRepository;
use App\Validators\GeneriRepositoryValidator;

/**
 * Class MasterRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class GeneriRepositoryRepositoryEloquent extends BaseRepository implements GeneriRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GeneriRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
