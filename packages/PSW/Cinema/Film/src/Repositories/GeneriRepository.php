<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class GeneriRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\GeneriPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\GeneriPage
     */
    public function create($attributes)
    {
    
        Event::dispatch('film.genere.create.before');

        $genere = parent::create($attributes);
       
        Event::dispatch('film.genere.create.after', $genere);

        return $genere;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.genere.update.before');

        $genere = parent::update($attributes, $id);

        Event::dispatch('film.genere.update.after', $genere);

        return $genere;
    }
    
    public function delete($id)
    {
 
        $genere = $this->findOneByField('master_id',$id);
           
        Event::dispatch('film.genere.delete.before', $id);
        $genere->delete();

        Event::dispatch('film.genere.delete.after', $id);
    }

   
}
