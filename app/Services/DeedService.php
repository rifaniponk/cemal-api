<?php

namespace Cemal\Services;

use Cemal\Models\Deed;
use Cemal\Exceptions\FormException;

class DeedService
{
	/**
     * create deed.
     * @param  array  $data
     * @return Deed
     */
    public function create(array $data)
    {
        $validator = $this->validate($data);
        if ($validator->fails()) {
            throw new FormException($validator);
        }

        try {
            \DB::beginTransaction();

            $data['user_id'] = \Auth::user()->id;
            if ($data['public'] && \Gate::allows('create-public', Deed::class)){
            	$data['public'] = true; 
            } else {
            	$data['public'] = false;
            }
            $deed = Deed::create($data);
            // TODO
            // create groups & references

            \DB::commit();

            return $deed;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * find deed by id.
     * @param  string $id uuid
     * @return Deed
     */
    public function find($id)
    {
        return Deed::find($id);
    }

    /**
     * find deeds by params
     * @param  array  $params
     * @param  integer  $limit 
     * @param  integer $offset
     * @param  string  $func 	query builder function
     * @return Collection
     */
    public function all($params = array(), $sorts = array(), $limit = null, $offset = 0, $func = 'get')
    {
    	$deeds = Deed::where(function ($query){ });
    	foreach ($params as $key => $value) {
    		$key = strtolower($key);
    		if ($key === 'include_public'){
    			continue;
    		} else if ($key === 'user_id'){
    			if (array_key_exists('include_public', $params) && $params['include_public'] === true){
    				$deeds->where(function ($query) use($key, $value){ 
    					$query->where($key, $value)
    						  ->orWhere('public', true);
    				});
    			} else {
    				$deeds->where($key, $value);
    			}
    		} else {
    			$deeds->where($key, $value);
    		}
    	}

    	foreach ($sorts as $order) {
	        foreach ($order as $field => $type) {
	        	$deeds->orderBy($field, $type);
	        }
        }

        if ($limit){
        	$deeds->limit($limit);
        }

        $deeds->offset($offset);

        return $deeds->$func();
    }

    /**
     * Count number of deeds
     * @param  array  $params
     * @return int
     */
    public function count($params = array()){
    	return $this->all($params, array(), null, 0, 'count');
    }

    /**
     * validate data.
     * @param  array  $data
     * @param  array  $group
     * @param  array  $param
     * @return Validator
     */
    public function validate(array $data, array $group = [], array $param = [])
    {
        $rules = Deed::getValidationRules($group, $param);

        $validator = \Validator::make($data, $rules);

        return $validator;
    }
}