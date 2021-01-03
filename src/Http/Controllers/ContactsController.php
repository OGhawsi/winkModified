<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Wink\Http\Resources\ContactsResource;
use Illuminate\Support\Facades\Mail;
use Wink\Mail\SendContactInfoToAdmin;
use Illuminate\Validation\Rule;
use Wink\WinkContact;

class ContactsController
{
    /**
     * Return contacts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkContact::all();
        return ContactsResource::collection($entries);
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        $entry = WinkContact::findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

     /**
     * Store a single category.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        // $psName = request('Psname');
        // $pashtoBio = request('pashtoBio');

        $data = [
            'name' => request('fullname'),
            'email' => request('email'),
            'bio' => request('bio'),
        ];
        
        validator($data, [
            'name' => 'required',
            'bio' => 'required',
            'email' => 'required|email|unique:wink_contacts',
            ])->validate();
            
        
        $entry =  new WinkContact;

        $entry->fill($data);

        $entry->save();

        $this->sendContactInfoToAdmin($data);

        notify()->success('Thanks, Your details recieved successfully');
        return redirect()->to(route('home'));
    }

    public function sendContactInfoToAdmin($data)
    {
            Mail::to('ghawsiweb@gmail.com')->send(new SendContactInfoToAdmin(
                $data
            ));
    }


    
    /**
     * Return a single contact.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
            $entry = WinkContact::findOrFail($id);
            
            $entry->delete();
    }
}
