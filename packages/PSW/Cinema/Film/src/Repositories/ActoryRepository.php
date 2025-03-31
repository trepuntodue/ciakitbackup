<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class ActoryRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\ActoryPage';
    }

    /**
     * @param  array  $data
     * @return \PSW\Cinema\Film\Contracts\ActoryPageeriPage
     */
    public function create($attributes)
    {
    //dd($attributes);
        Event::dispatch('film.attore.create.before');

        $attore = parent::create($attributes);
       
        Event::dispatch('film.attore.create.after', $attore);

        return $attore;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.attore.update.before');

        $attore = parent::update($attributes, $id);

        Event::dispatch('film.attore.update.after', $attore);

        return $attore;
    }
    
    public function delete($id)
    {
 
        $attore = $this->findOneByField('id',$id);
           
        Event::dispatch('film.attore.delete.before', $id);
        $attore->delete();

        Event::dispatch('film.attore.delete.after', $id);
    }

   
}
