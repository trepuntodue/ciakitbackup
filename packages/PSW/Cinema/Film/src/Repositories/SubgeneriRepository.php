<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class SubgeneriRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\SubgeneriPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\SubgeneriPage
     */
    public function create($attributes)
    {
    
        Event::dispatch('film.subgenere.create.before');

        $subgenere = parent::create($attributes);
       
        Event::dispatch('film.subgenere.create.after', $subgenere);

        return $subgenere;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.subgenere.update.before');

        $subgenere = parent::update($attributes, $id);

        Event::dispatch('film.subgenere.update.after', $subgenere);

        return $subgenere;
    }
    
    public function delete($id)
    {
 
        $subgenere = $this->findOneByField('id',$id);
           
        Event::dispatch('film.subgenere.delete.before', $id);
        $subgenere->delete();

        Event::dispatch('film.subgenere.delete.after', $id);
    }

   
}
