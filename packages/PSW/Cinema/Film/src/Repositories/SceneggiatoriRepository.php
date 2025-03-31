<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class SceneggiatoriRepository extends Repository
{
   
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\SceneggiatoriPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\SceneggiatoriPage
     */
    public function create($attributes)
    {
    //dd($attributes);
        Event::dispatch('film.sceneggiatore.create.before');

        $sceneggiatore = parent::create($attributes);
       
        Event::dispatch('film.sceneggiatore.create.after', $sceneggiatore);

        return $sceneggiatore;
    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes, $id)
    {

        Event::dispatch('film.sceneggiatore.update.before');

        $sceneggiatore = parent::update($attributes, $id);

        Event::dispatch('film.sceneggiatore.update.after', $sceneggiatore);

        return $sceneggiatore;
    }
    
    public function delete($id)
    {
 
        $sceneggiatore = $this->findOneByField('id',$id);
           
        Event::dispatch('film.sceneggiatore.delete.before', $id);
        $sceneggiatore->delete();

        Event::dispatch('film.sceneggiatore.delete.after', $id);
    }

   
}
