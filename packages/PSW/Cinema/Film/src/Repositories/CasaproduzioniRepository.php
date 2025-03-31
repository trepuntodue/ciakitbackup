<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class CasaproduzioniRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\CasaproduzioniPage';
    }

    /**
     * @param  array  $data
     * @return \PSW\Cinema\Film\Contracts\CasaproduzioniPage
     */
    public function create($attributes)
    {
    //dd($attributes);
        Event::dispatch('film.casaproduzione.create.before');

        $casaproduzione = parent::create($attributes);
       
        Event::dispatch('film.casaproduzione.create.after', $casaproduzione);

        return $casaproduzione;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.casaproduzione.update.before');

        $casaproduzione = parent::update($attributes, $id);

        Event::dispatch('film.casaproduzione.update.after', $casaproduzione);

        return $casaproduzione;
    }
    
    public function delete($id)
    {
 
        $casaproduzione = $this->findOneByField('id',$id);
           
        Event::dispatch('film.casaproduzione.delete.before', $id);
        $casaproduzione->delete();

        Event::dispatch('film.casaproduzione.delete.after', $id);
    }

   
}
