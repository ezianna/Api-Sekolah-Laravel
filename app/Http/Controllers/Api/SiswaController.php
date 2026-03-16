<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Model\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
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
                'href' => url('/api/siswa/' . $item->id),
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
                    'href' => url('/api/siswa/' . $item->id)
                ],
                [
                    'rel' => 'edit',
                    'href' => url('/api/siswa/' . $item->id)
                ]
            ];

            if ($item->kelas_id) {
                $itemData['links'][] = [
                    'rel' => 'kelas',
                    'href' => url('/api/kelas/' . $item->kelas_id)
                ];
            }

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
                'href' => url('/api/siswa')
            ]
        ], $links);

        if ($item->kelas_id) {
            $itemData['links'][] = [
                'rel' => 'kelas',
                'href' => url('/api/kelas/' . $item->kelas_id)
            ];
        }

        return response()->json(['item' => $itemData], 200);
    }

    public function index()
    {
        $siswas = Siswa::with('kelas')->get();
        return $this->collectionJsonResponse($siswas, url('/api/siswa'), [
            [
                'rel' => 'create',
                'href' => url('/api/siswa')
            ]
        ]);
    }

    public function store(Request $request)
    {
        $siswa = Siswa::create($request->all());
        return $this->itemJsonResponse($siswa, url('/api/siswa/' . $siswa->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/siswa')
            ]
        ])->setStatusCode(201);
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('kelas');
        return $this->itemJsonResponse($siswa, url('/api/siswa/' . $siswa->id), [
            [
                'rel' => 'edit',
                'href' => url('/api/siswa/' . $siswa->id)
            ],
            [
                'rel' => 'delete',
                'href' => url('/api/siswa/' . $siswa->id)
            ]
        ]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->update($request->all());
        return $this->itemJsonResponse($siswa, url('/api/siswa/' . $siswa->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/siswa')
            ]
        ]);
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return response()->json([
            'collection' => [
                'version' => '1.0',
                'href' => url('/api/siswa'),
                'links' => [
                    [
                        'rel' => 'create',
                        'href' => url('/api/siswa')
                    ]
                ]
            ]
        ], 200);
    }
}