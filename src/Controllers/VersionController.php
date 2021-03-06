<?php

namespace Gluon\Backend\Controllers;


use Gluon\Backend\Api\API;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function log(Request $request, $type, $id) {
        $items = API::query('log', [
            'and' => [
                'type' => $type,
                'item_id' => $id,
            ],
            'with' => [
                'user' => ['one'=>'users', 'this' => 'user_id'],
            ],
            'order' => [
                'id' => 'ASC'
            ]
        ]);
        foreach ($items as &$item) {
            $item->content = json_decode($item->content);
        }
        return response()->json($items);
    }

    public function restore(Request $request, $type, $log) {
        $version = API::read('log', $log);
        $content = json_decode($version->content, true);
        $id = $content['id'];
        API::update($type, $id, $content);
        return response()->json();
    }
}

