<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MasterRepositoryRepository;
use App\Entities\MasterRepository;
use App\Validators\MasterRepositoryValidator;

/**
 * Class MasterRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MasterRepositoryRepositoryEloquent extends BaseRepository implements MasterRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MasterRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
