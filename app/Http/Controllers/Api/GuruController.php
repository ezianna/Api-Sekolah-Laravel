<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Hypermedia Collection Response (Collection+JSON Style)
    |--------------------------------------------------------------------------
    */
    private function collectionResponse($items)
    {
        $response = [
            'collection' => [
                'version' => '1.0',
                'href' => url('/api/guru'),
                'links' => [
                    [
                        'rel' => 'create',
                        'href' => url('/api/guru')
                    ]
                ],
                'items' => []
            ]
        ];

        foreach ($items as $item) {

            $data = [];
            foreach ($item->toArray() as $key => $value) {
                $data[] = [
                    'name' => $key,
                    'value' => $value
                ];
            }

            $response['collection']['items'][] = [
                'href' => url('/api/guru/' . $item->id),
                'data' => $data,
                'links' => [
                    [
                        'rel' => 'self',
                        'href' => url('/api/guru/' . $item->id)
                    ],
                    [
                        'rel' => 'update',
                        'href' => url('/api/guru/' . $item->id)
                    ],
                    [
                        'rel' => 'delete',
                        'href' => url('/api/guru/' . $item->id)
                    ]
                ]
            ];
        }

        return response()->json($response, 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Hypermedia Single Item Response
    |--------------------------------------------------------------------------
    */
    private function itemResponse($item)
    {
        $data = [];
        foreach ($item->toArray() as $key => $value) {
            $data[] = [
                'name' => $key,
                'value' => $value
            ];
        }

        return response()->json([
            'item' => [
                'href' => url('/api/guru/' . $item->id),
                'data' => $data,
                'links' => [
                    [
                        'rel' => 'self',
                        'href' => url('/api/guru/' . $item->id)
                    ],
                    [
                        'rel' => 'collection',
                        'href' => url('/api/guru')
                    ],
                    [
                        'rel' => 'update',
                        'href' => url('/api/guru/' . $item->id)
                    ],
                    [
                        'rel' => 'delete',
                        'href' => url('/api/guru/' . $item->id)
                    ]
                ]
            ]
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $gurus = Guru::all();
        return $this->collectionResponse($gurus);
    }

    public function store(Request $request)
    {
        $guru = Guru::create($request->all());

        return response()->json([
            'item' => [
                'href' => url('/api/guru/' . $guru->id),
                'links' => [
                    [
                        'rel' => 'self',
                        'href' => url('/api/guru/' . $guru->id)
                    ],
                    [
                        'rel' => 'collection',
                        'href' => url('/api/guru')
                    ]
                ]
            ]
        ], 201);
    }

    public function show(Guru $guru)
    {
        return $this->itemResponse($guru);
    }

    public function update(Request $request, Guru $guru)
    {
        $guru->update($request->all());
        return $this->itemResponse($guru);
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();

        return response()->json([
            'collection' => [
                'version' => '1.0',
                'href' => url('/api/guru'),
                'links' => [
                    [
                        'rel' => 'create',
                        'href' => url('/api/guru')
                    ]
                ]
            ]
        ], 200);
    }
}
