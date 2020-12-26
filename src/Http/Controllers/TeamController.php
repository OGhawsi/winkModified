<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Wink\Http\Resources\TeamResource;
use Wink\WinkAuthor;


class TeamController
{
   
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkAuthor::when(request()->has('search'), function ($q) {
            $q->where('name', 'LIKE', '%'.request('search').'%');
        })
            ->orderBy('created_at', 'DESC')
            ->withCount('posts')
            ->paginate(30);

        return TeamResource::collection($entries);
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if ($id === 'new') {
            return response()->json([
                'entry' => WinkAuthor::make([
                    'id' => Str::uuid(),
                ]),
            ]);
        }

        $entry = WinkAuthor::findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }
    /**
     * Return 
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
   
     public function storeOrUpdate($id)
     {
        
        if (Gate::allows('add-authors')){

            $this->store($id);

        } else if (Gate::allows('update-profile')) {
            
            $entry = WinkAuthor::findOrFail($id);

            if ($entry->id == auth('wink')->user()->id) {
                $this->store($entry->id);
            }
            else {
                return response()->json(['message' => ' You are not authorized'], 402);
            }

        } 
        
     }

    /**
     * Store a single category.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {

        $psName = request('Psname');
        $pashtoBio = request('pashtoBio');

        $data = [
            'name' => request('name'),
            'slug' => request('slug'),
            'email' => request('email'),
            'bio' => request('bio'),
            'avatar' => request('avatar'),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'meta.theme' => 'in:dark,light',
            'name' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_authors', 'slug')->ignore(request('id')),
            'email' => 'required|email|'.Rule::unique(config('wink.database_connection').'.wink_authors', 'email')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? WinkAuthor::findOrFail($id) : new WinkAuthor(['id' => request('id')]);

            if (request('password')) {
                $entry->password = Hash::make(request('password'));
            }
            
            if (request('email') !== $entry->email && Str::contains($entry->avatar, 'gravatar')) {
                unset($data['avatar']);
                
                $entry->avatar = null;
            }
            
            $entry->setTranslation('name', 'ps', $psName);
            $entry->setTranslation('name', 'fa', $psName);
            
            $entry->setTranslation('bio', 'ps', $pashtoBio);
            $entry->setTranslation('bio', 'fa', $pashtoBio);
            
            $entry->fill($data);
            
            
            $entry->save();

            $entry->assignRole('editor');
            
            return response()->json([
                'entry' => $entry->fresh(),
            ]);
        
    }

    /**
     * Return a single author.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function delete($id)
    {
        $entry = WinkAuthor::findOrFail($id);

        if ($entry->posts()->count()) {
            return response()->json(['message' => 'Please remove the author\'s posts first.'], 402);
        }

        if ($entry->id == auth('wink')->user()->id) {
            return response()->json(['message' => 'You cannot delete yourself.'], 402);
        }

        $entry->delete();
    }
}
