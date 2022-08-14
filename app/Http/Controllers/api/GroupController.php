<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Group;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupResourceCollection;

use App\Traits\Messages;

class GroupController extends Controller
{

    use Messages;

    /**
     * @group Groups
     * 
     * Groups List
     * 
     * @queryParam page integer
     * 
     * @authenticated
     */
    public function index()
    {
        $records = Group::latest()->paginate(10);

        $data = new GroupResourceCollection($records);

        return $this->jsonSuccessResponse($data, 200);
    }

    private function rules($isNew,$model=null)
    {
        $rules = [
            'name' => 'required|string|unique:groups',
            'description' => 'string|nullable',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('groups')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [
            // 'name.required' => 'Group name is required',
            // 'description.required' => 'Group description is required',
        ];

        return $messages;
    }

    public function create()
    {
        //
    }

    /**
     * @group Groups
     * 
     * Add New Group
     * 
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(true));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        $record = new Group;
        $record->fill($data);
        $record->save();

        return $this->jsonSuccessResponse(null, 200, "Group succesfully added");
    }

    /**
     * @group Groups
     * 
     * Show Group
     * 
     * @queryUrl id string
     * 
     * @authenticated
     */
    public function show($id)
    {
        $record = Group::find($id);

        if (is_null($record)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new GroupResource($record);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @group Groups
     * 
     * Update Group Info
     * 
     * @queryUrl id string
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $record = Group::find($id);

        if (is_null($record)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$record));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        $record->fill($data);
        $record->save();

        return $this->jsonSuccessResponse(null, 200, "Group info succesfully updated");
    }

    /**
     * @group Groups
     * 
     * Delete group
     * 
     * @queryUrl id string required
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $record = Group::find($id);

        if (is_null($record)) {
			return $this->jsonErrorResourceNotFound();
        }

        $record->delete();

        return $this->jsonDeleteSuccessResponse();
    }
}
