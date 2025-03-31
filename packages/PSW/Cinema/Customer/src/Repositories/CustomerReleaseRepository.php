<?php

namespace PSW\Cinema\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerReleaseRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Customer\Contracts\CustomerRelease';
    }

    /**
     * @param  array  $data
     * @return \PSW\Cinema\Customer\Contracts\CustomerRelease
     */
    public function create(array $data)
    {
        // die("muori male release");
        Event::dispatch('customer.releases.create.before');

        $data['default_release'] = isset($data['default_release']) ? 1 : 0;

        if(!isset($data['url_key']) || $data['url_key'] == '') $data['url_key'] = Str::slug($data['original_title']); // PWS#8-link-master

        $default_release = $this
            ->findWhere(['customer_id' => $data['customer_id'], 'default_release' => 1])
            ->first();

        $data['release_status'] = -1; // PWS#230101

        if(isset($data['language'])){ // PWS#chiusura-lang
          $language = $data['language'];
          $data['language'] = '';
        } else{
          $language = array();
          $data['language'] = '';
        }

        // if (
        //     isset($default_release->id)
        //     && $data['default_release']
        // ) {
        //     $default_release->update(['default_release' => 0]);
        // }
        $release = $this->model->create($data);

        $release_id = DB::getPdo()->lastInsertId();
        DB::table('release_user')->insert(
            array(
            'release_id' => $release_id,
            'user_id'   => $data['customer_id'],
            )
        ); // PWS#13-release

        $languages = array(); // PWS#chiusura
        foreach ($language as $key => $lang) {
          array_push($languages,['release_id' => $release_id, 'elemento_id' => (int) $lang, 'tipo_relazione' => config('constants.release.relazioni.lingua')]);
        }
        if(count($languages)){
          DB::table('release_relazioni')
            ->where('release_id',$release_id)
            ->where('tipo_relazione',config('constants.release.relazioni.lingua'))
            ->delete();
          DB::table('release_relazioni')->insert($languages);
        } // PWS#chiusura

        Event::dispatch('customer.releases.create.after', $release);

        return $release;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @return \PSW\Cinema\Customer\Contracts\CustomerRelease
     */
    public function update(array $data, $id)
    {
        // die("2");
        $release = $this->find($id);

        Event::dispatch('customer.releases.update.before', $id);

       // $data['default_release'] = isset($data['default_release']) ? 1 : 0;

        $default_release = $this
            ->findWhere(['customer_id' => $release->customer_id])
            ->first();

        // if (
        //     isset($default_release->id)
        //     && $data['default_release']
        // ) {
        //     if ($default_release->id != $release->id) {
        //         $default_release->update(['default_release' => 0]);
        //     }

        //     $release->update($data);
        // } else {
        //     $release->update($data);
        // }
        $release->update($data);
        Event::dispatch('customer.releases.update.after', $id);

        return $release;
    }
}
