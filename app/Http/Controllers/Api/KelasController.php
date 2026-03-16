<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Model\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
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
                'href' => url('/api/kelas/' . $item->id),
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
                    'href' => url('/api/kelas/' . $item->id)
                ],
                [
                    'rel' => 'edit',
                    'href' => url('/api/kelas/' . $item->id)
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
                'href' => url('/api/kelas')
            ]
        ], $links);

        return response()->json(['item' => $itemData], 200);
    }

    public function index()
    {
        $kelases = Kelas::all();
        return $this->collectionJsonResponse($kelases, url('/api/kelas'), [
            [
                'rel' => 'create',
                'href' => url('/api/kelas')
            ]
        ]);
    }

    public function store(Request $request)
    {
        $kelas = Kelas::create($request->all());
        return $this->itemJsonResponse($kelas, url('/api/kelas/' . $kelas->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/kelas')
            ]
        ])->setStatusCode(201);
    }

    public function show(Kelas $kelas)
    {
        return $this->itemJsonResponse($kelas, url('/api/kelas/' . $kelas->id), [
            [
                'rel' => 'edit',
                'href' => url('/api/kelas/' . $kelas->id)
            ],
            [
                'rel' => 'delete',
                'href' => url('/api/kelas/' . $kelas->id)
            ]
        ]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $kelas->update($request->all());
        return $this->itemJsonResponse($kelas, url('/api/kelas/' . $kelas->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/kelas')
            ]
        ]);
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return response()->json([
            'collection' => [
                'version' => '1.0',
                'href' => url('/api/kelas'),
                'links' => [
                    [
                        'rel' => 'create',
                        'href' => url('/api/kelas')
                    ]
                ]
            ]
        ], 200);
    }
}