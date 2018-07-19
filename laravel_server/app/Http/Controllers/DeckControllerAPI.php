<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deck;
use Illuminate\Support\Facades\DB;

class DeckControllerAPI extends Controller
{
    public function getDecks(Request $request)
    {
        $decks = DB::table('decks')->get();
        return $decks;

    }
   
    public function store(Request $request)
    {
        DB::table('decks')->insert(
            ['name' => $request->name, 
            'hidden_face_image_path' => $request->hidden_face_image_path,
            'active' => $request->active
            ]
        );
        return response()->json(['message' => 'deck created'], 200);   
    }

    public function update (Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'hidden_face_image_path' => 'required',
        ]);
        $deck = Deck::findOrFail($id);
        $deck->update($request->all());
        return $deck;
    }

    public function delete($id)
    {
        $deck = Deck::findOrFail($id);
        $deck->delete();
        return response()->json(null, 204);
    }
}
