<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Contact;
use App\Http\Resources\ContactResource;
use App\Http\Resources\ContactResourceCollection;

use App\Traits\Messages;

class ContactController extends Controller
{
    use Messages;

    /**
     * @group Contacts
     * 
     * Contacts List
     * 
     * @queryParam page integer
     */
    public function index()
    {
        $records = Contact::latest()->paginate(10);

        $data = new ContactResourceCollection($records);
        
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
            'user_id' => 'string|required',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'string|nullable',
            'cp_no' => 'string|nullable',
        ];
    
        return $rules;
    }
    
    private function rulesMessages($isNew)
    {
        $messages = [];
    
        return $messages;
    }

    /**
     * @group Contacts
     * 
     * Add New Contact
     * 
     * bodyParam user_id string required
     * bodyParam first_name string required
     * bodyParam last_name string required
     * bodyParam email string
     * bodyParam cp_no string
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(true));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        $record = new Contact;
        $record->fill($data);
        $record->save();
        
        return $this->jsonSuccessResponse(null, 200, "Contact succesfully added");
    }

    /**
     * @group Contacts
     * 
     * Show Contact
     * 
     * @queryUrl id string required
     */
    public function show($id)
    {
        $record = Contact::find($id);

        if (is_null($record)) {
            return $this->jsonErrorResourceNotFound();
        }
        
        $data = new ContactResource($record);
        
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
     * @group Contacts
     * 
     * Update Contact Info
     * 
     * queryUrl id string required
     * bodyParam user_id string required
     * bodyParam first_name string required
     * bodyParam last_name string required
     * bodyParam email string
     * bodyParam cp_no string
     */
    public function update(Request $request, $id)
    {
        $record = Contact::find($id);

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
        
        return $this->jsonSuccessResponse(null, 200, "Contact info succesfully updated");
    }

    /**
     * @group Contacts
     * 
     * Delete Contact
     * 
     * @queryUrl id string required
     */
    public function destroy($id)
    {
        $record = Contact::find($id);

        if (is_null($record)) {
            return $this->jsonErrorResourceNotFound();
        }
        
        $record->delete();
        
        return $this->jsonDeleteSuccessResponse();
    }
}
