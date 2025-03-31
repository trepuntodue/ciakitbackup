<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class CompositoriRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\CompositoriPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\CompositoriPageeriPage
     */
    public function create($attributes)
    {
    //dd($attributes);
        Event::dispatch('film.compositore.create.before');

        $compositore = parent::create($attributes);
       
        Event::dispatch('film.compositore.create.after', $compositore);

        return $compositore;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.compositore.update.before');

        $compositore = parent::update($attributes, $id);

        Event::dispatch('film.compositore.update.after', $compositore);

        return $compositore;
    }
    
    public function delete($id)
    {
 
        $compositore = $this->findOneByField('id',$id);
           
        Event::dispatch('film.compositore.delete.before', $id);
        $compositore->delete();

        Event::dispatch('film.compositore.delete.after', $id);
    }

   
}
