<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class RegistiRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\RegistiPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\RegistiPage
     */
    public function create($attributes)
    {
    //dd($attributes);
        Event::dispatch('film.regista.create.before');

        $regista = parent::create($attributes);
       
        Event::dispatch('film.regista.create.after', $regista);

        return $regista;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.regista.update.before');

        $regista = parent::update($attributes, $id);

        Event::dispatch('film.regista.update.after', $regista);

        return $regista;
    }
    
    public function delete($id)
    {
 
        $regista = $this->findOneByField('id',$id);
           
        Event::dispatch('film.regista.delete.before', $id);
        $regista->delete();

        Event::dispatch('film.regista.delete.after', $id);
    }

   
}
