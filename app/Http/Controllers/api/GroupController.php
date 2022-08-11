<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupResourceController;

use App\Traits\Messages;

class GroupController extends Controller
{

    use Messages;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
            'name.required' => 'Group name is required',
            'description.required' => 'Group description is required',
        ];

        return $messages;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = Group::find($id);

        if (is_null($group)) {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $record = Group::find($id);

        if (is_null($group)) {
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
