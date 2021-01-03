<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Searchable\Search;
use Wink\Http\Resources\PostsResource;
use Wink\WinkPost;
use Wink\WinkTag;
use Wink\WinkCategory;

class PostsController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkPost::when(request()->has('search'), function ($q) {
            $q->where('title', 'LIKE', '%'.request('search').'%');
        })->when(request('status'), function ($q, $value) {
            $q->$value();
        })->when(request('author_id'), function ($q, $value) {
            $q->whereAuthorId($value);
        })->when(request('tag_id'), function ($q, $value) {
            $q->whereHas('tags', function ($query) use ($value) {
                $query->where('id', $value);
            });
        })->when(request('category_id'), function ($q, $value) {
            $q->whereCategoryId($value);
        
        })  
        
            ->orderBy('created_at', 'DESC')
            ->with('tags')
            ->paginate(30);

        return PostsResource::collection($entries);
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
                'entry' => WinkPost::make([
                    'id' => Str::uuid(),
                    'publish_date' => now()->format('Y-m-d H:i:00'),
                    'markdown' => null,
                ]),
            ]);
        }

        $entry = WinkPost::with('tags')->findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {

        $data = [
            'title' => request('title'),
            'excerpt' => request('excerpt', ''),
            'locale' => request('locale','en'),
            'featured' => request('featured'),
            'slug' => request('slug'),
            'body' => request('body', ''),
            'published' => request('published'),
            'markdown' => request('markdown'),
            'author_id' => request('author_id'),
            'category_id' => request('category_id'),
            'featured_image' => request('featured_image'),
            'featured_image_caption' => request('featured_image_caption', ''),
            'publish_date' => request('publish_date', ''),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'publish_date' => 'required|date',
            'author_id' => 'required',
            'title' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_posts', 'slug')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? WinkPost::findOrFail($id) : new WinkPost(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();


        $entry->tags()->sync(
            $this->collectTags(request('tags'))
        );

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Tags incoming from the request.
     *
     * @param  array  $incomingTags
     * @return array
     */
    private function collectTags($incomingTags)
    {
        $allTags = WinkTag::all();

        return collect($incomingTags)->map(function ($incomingTag) use ($allTags) {
            $tag = $allTags->where('id', $incomingTag['id'])->first();

            if (! $tag) {
                $tag = WinkTag::create([
                    'id' => $id = Str::uuid(),
                    'name' => $incomingTag['name'],
                    'slug' => Str::slug($incomingTag['name']),
                ]);
            }

            return (string) $tag->id;
        })->toArray();
    }


    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
        $entry = WinkPost::findOrFail($id);

        $entry->delete();
    }


    public function search()
    {

        if (request()->input('search') === Null) {
            
            return view('search-results');
        }
        $data = (new Search())
        ->registerModel(WinkPost::class, 'title')
        ->registerModel(WinkTag::class, 'name')
        ->registerModel(WinkCategory::class, 'name')
        ->perform(request()->input('search'));
        
        return view('search-results', compact('data'));
    }
}
