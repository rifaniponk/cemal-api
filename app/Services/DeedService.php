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
            if (\Gate::allows('create', Deed::class)){
            	$data['public'] = $data['public']; 
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