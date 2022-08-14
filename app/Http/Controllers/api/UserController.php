<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;

use App\Traits\Messages;

class UserController extends Controller
{
    use Messages;

    /**
     * @group Users
     * 
     * Users List
     * 
     * @queryUrl page integer
     * 
     * @authenticated
     */
    public function index()
    {
        $records = User::latest()->paginate(10);

        $data = new UserResourceCollection($records);
        
        return $this->jsonSuccessResponse($data, 200);
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

    private function rules($isNew,$model=null)
    {
        $rules = [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'string|required',
            'password' => 'string|required',
            'profile_picture' => 'mimes:jpg,bmp,png|max:1024|nullable',
            'group_id' => 'string|nullable',
            'is_super_admin' => 'string|nullable',
        ];

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Users
     * 
     * Add New User
     * 
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam profile_picture file
     * @bodyParam group_id string required
     * @bodyParam is_super_admin boolean
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
        $data['is_super_admin'] = ($data['is_super_admin']=='true')?1:0;

        $record = new User;
        $data['password'] = $password = Hash::make($data['password']);
        $record->fill($data);
        $record->save();

        // Upload profile_picture
        if (isset($data['profile_picture'])) {
            $folder = "uploads";
            $filename = $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs("public/$folder", $filename);
            $record->profile_picture = "$folder/$filename";
            $record->save();
        }

        return $this->jsonSuccessResponse(null, 200, "User succesfully added");
    }

    /**
     * @group Users
     * 
     * Show User
     * 
     * @queryParam id string required
     * 
     * @authenticated
     */
    public function show($id)
    {
        $record = User::find($id);

        if (is_null($record)) {
            return $this->jsonErrorResourceNotFound();
        }
        
        $data = new UserResource($record);
        
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
     * @group Users
     * 
     * Update User Info
     * 
     * @queryUrl id string required
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam profile_picture file
     * @bodyParam group_id string required
     * @bodyParam is_super_admin boolean
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $record = User::find($id);

        if (is_null($record)) {
            return $this->jsonErrorResourceNotFound();
        }
        
        $validator = Validator::make($request->all(), $this->rules(false,$record));
        
        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }
        
        $data = $validator->valid();
        $data['is_super_admin'] = ($data['is_super_admin']=='true')?1:0;
        
        $record->fill($data);
        $record->save();

        // Upload profile_picture
        if (isset($data['profile_picture'])) {
            $folder = "uploads";
            $filename = $request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->storeAs("public/$folder", $filename);
            $record->profile_picture = "$folder/$filename";
            $record->save();
        }
        
        return $this->jsonSuccessResponse(null, 200, "User info succesfully updated");
    }

    /**
     * @group Users
     * 
     * Delete User
     * 
     * @queryUrl id string required
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $record = User::find($id);

        if (is_null($record)) {
            return $this->jsonErrorResourceNotFound();
        }
        
        $record->delete();
        
        return $this->jsonDeleteSuccessResponse();
    }
}
