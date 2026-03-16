<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Model\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    private function response($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    private function collectionJsonResponse($items, $href, $links = [])
    {
        $collection = [
            'collection' => [
                'version' => '1.0',
                'href' => $href,
                'links' => $links,
                'items' => []
            ]
        ];

        foreach ($items as $item) {
            $itemData = [
                'href' => url('/api/mapel/' . $item->id),
                'data' => []
            ];

            foreach ($item->toArray() as $key => $value) {
                $itemData['data'][] = [
                    'name' => $key,
                    'value' => $value
                ];
            }

            $itemData['links'] = [
                [
                    'rel' => 'self',
                    'href' => url('/api/mapel/' . $item->id)
                ],
                [
                    'rel' => 'edit',
                    'href' => url('/api/mapel/' . $item->id)
                ]
            ];

            $collection['collection']['items'][] = $itemData;
        }

        return response()->json($collection, 200);
    }

    private function itemJsonResponse($item, $href, $links = [])
    {
        $itemData = [
            'href' => $href,
            'data' => []
        ];

        foreach ($item->toArray() as $key => $value) {
            $itemData['data'][] = [
                'name' => $key,
                'value' => $value
            ];
        }

        $itemData['links'] = array_merge([
            [
                'rel' => 'self',
                'href' => $href
            ],
            [
                'rel' => 'collection',
                'href' => url('/api/mapel')
            ]
        ], $links);

        return response()->json(['item' => $itemData], 200);
    }

    public function index()
    {
        $mapels = Mapel::all();
        return $this->collectionJsonResponse($mapels, url('/api/mapel'), [
            [
                'rel' => 'create',
                'href' => url('/api/mapel')
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mapel = Mapel::create($request->all());
        return $this->itemJsonResponse($mapel, url('/api/mapel/' . $mapel->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/mapel')
            ]
        ])->setStatusCode(201);
    }

    public function show(Mapel $mapel)
    {
        return $this->itemJsonResponse($mapel, url('/api/mapel/' . $mapel->id), [
            [
                'rel' => 'edit',
                'href' => url('/api/mapel/' . $mapel->id)
            ],
            [
                'rel' => 'delete',
                'href' => url('/api/mapel/' . $mapel->id)
            ]
        ]);
    }

    public function update(Request $request, Mapel $mapel)
    {
        $mapel->update($request->all());
        return $this->itemJsonResponse($mapel, url('/api/mapel/' . $mapel->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/mapel')
            ]
        ]);
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return response()->json([
            'collection' => [
                'version' => '1.0',
                'href' => url('/api/mapel'),
                'links' => [
                    [
                        'rel' => 'create',
                        'href' => url('/api/mapel')
                    ]
                ]
            ]
        ], 200);
    }
}