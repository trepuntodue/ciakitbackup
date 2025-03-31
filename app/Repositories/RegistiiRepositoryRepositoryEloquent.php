<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RegistiRepositoryRepository;
use App\Entities\RegistiRepository;
use App\Validators\RegistiRepositoryValidator;

/**
 * Class MasterRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RegistiiRepositoryRepositoryEloquent extends BaseRepository implements RegistiRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RegistiiRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
