<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Model\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
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
                'href' => url('/api/jadwal/' . $item->id),
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
                    'href' => url('/api/jadwal/' . $item->id)
                ],
                [
                    'rel' => 'edit',
                    'href' => url('/api/jadwal/' . $item->id)
                ]
            ];

            if ($item->kelas_id) {
                $itemData['links'][] = [
                    'rel' => 'kelas',
                    'href' => url('/api/kelas/' . $item->kelas_id)
                ];
            }

            if ($item->mapel_id) {
                $itemData['links'][] = [
                    'rel' => 'mapel',
                    'href' => url('/api/mapel/' . $item->mapel_id)
                ];
            }

            if ($item->guru_id) {
                $itemData['links'][] = [
                    'rel' => 'guru',
                    'href' => url('/api/guru/' . $item->guru_id)
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
                'href' => url('/api/jadwal')
            ]
        ], $links);

        if ($item->kelas_id) {
            $itemData['links'][] = [
                'rel' => 'kelas',
                'href' => url('/api/kelas/' . $item->kelas_id)
            ];
        }

        if ($item->mapel_id) {
            $itemData['links'][] = [
                'rel' => 'mapel',
                'href' => url('/api/mapel/' . $item->mapel_id)
            ];
        }

        if ($item->guru_id) {
            $itemData['links'][] = [
                'rel' => 'guru',
                'href' => url('/api/guru/' . $item->guru_id)
            ];
        }

        return response()->json(['item' => $itemData], 200);
    }

    public function index()
    {
        $jadwals = Jadwal::with(['kelas','mapel','guru'])->get();
        return $this->collectionJsonResponse($jadwals, url('/api/jadwal'), [
            [
                'rel' => 'create',
                'href' => url('/api/jadwal')
            ]
        ]);
    }

    public function store(Request $request)
    {
        $jadwal = Jadwal::create($request->all());
        return $this->itemJsonResponse($jadwal, url('/api/jadwal/' . $jadwal->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/jadwal')
            ]
        ])->setStatusCode(201);
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['kelas','mapel','guru']);
        return $this->itemJsonResponse($jadwal, url('/api/jadwal/' . $jadwal->id), [
            [
                'rel' => 'edit',
                'href' => url('/api/jadwal/' . $jadwal->id)
            ],
            [
                'rel' => 'delete',
                'href' => url('/api/jadwal/' . $jadwal->id)
            ]
        ]);
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $jadwal->update($request->all());
        return $this->itemJsonResponse($jadwal, url('/api/jadwal/' . $jadwal->id), [
            [
                'rel' => 'collection',
                'href' => url('/api/jadwal')
            ]
        ]);
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return response()->json([
            'collection' => [
                'version' => '1.0',
                'href' => url('/api/jadwal'),
                'links' => [
                    [
                        'rel' => 'create',
                        'href' => url('/api/jadwal')
                    ]
                ]
            ]
        ], 200);
    }
}