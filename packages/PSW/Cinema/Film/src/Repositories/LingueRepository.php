<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class LingueRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\LinguePage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\LinguePage
     */
    public function create($attributes)
    {
    
        Event::dispatch('film.lingue.create.before');

        $lingue = parent::create($attributes);
       
        Event::dispatch('film.lingue.create.after', $lingue);

        return $lingue;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.lingue.update.before');

        $lingue = parent::update($attributes, $id);

        Event::dispatch('film.lingue.update.after', $lingue);

        return $lingue;
    }
    
    public function delete($id)
    {
 
        $lingue = $this->findOneByField('id',$id);
           
        Event::dispatch('film.lingue.delete.before', $id);
        $lingue->delete();

        Event::dispatch('film.lingue.delete.after', $id);
    }

   
}
