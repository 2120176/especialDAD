<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Resources\Card as CardResource;
use App\Http\Resources\Deck as DeckResource;
use Illuminate\Http\Request;
use App\Card;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class CardControllerAPI extends Controller
{
    public function getCards(Request $request)
    {
        $config = DB::table('config')->first();


        $cards = Card::all();
        foreach ($cards as $card) {
            $card->path = $config->img_base_path . $card->path;
            // $card->deck = Deck::where('id',$card->deck_id)->first();
        }

        return CardResource::collection($cards);
    }

    public function getCard($id)
    {
        return new CardResource(Card::find($id));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'card' => 'required',
            'value' => 'required',
            'suite' => 'required',
            'deck_id' => 'required'
        ]);

        if (!$validator->fails()) {

            $base64card = $request->get('card');
            $value = $request->get('value');
            $suite = $request->get('suite');
            $deck_id = $request->get('deck_id');

            /*$checkValue = DB::table('cards')->where('value', $value)->pluck('value');
            $checkSuite = DB::table('cards')->where('suite', $suite)->pluck('suite');
            $checkDeckID = DB::table('cards')->where('deck_id', $deck_id)->pluck('deck_id');
            //dd($checkValue);
            if((!empty($checkValue)) && (!empty($checkSuite)) && (!empty($checkDeckID))) {
                return response()->json(['msg' => 'Card already exists!'], 405);
            }*/
            $cardNameCheck = $value . '_of_' . $suite . 's' . '.png';

            $checkCardExists = DB::table('cards')->where('path', '=', $cardNameCheck)->get();
            $checkCardDeckID = $checkCardExists->pluck('deck_id');
            //dd($checkCardDeckID);
            if ($checkCardExists->isEmpty() || (($checkCardExists && ($checkCardDeckID[0] != $deck_id)))) {
                // Card doesn't exist
                $createCard = Card::create(['value' => $value, 'suite' => $suite, 'deck_id' => $deck_id, 'path' => '']);

                $createCard->path = $createCard->value . '_of_' . $createCard->suite . 's' . '.png';
                $createCard->save();

                $deck = Deck::findOrFail($deck_id);
                if($value == "Hidden"){
                    $deck->update(['hidden_face_image_path' => $createCard->path]);
                }

                $config = DB::table('config')->first();

                $card = Image::make($base64card);

                $card->resize(500, 726)->save($config->img_base_path . $createCard->value . '_of_' . $createCard->suite . 's' . '.png');
                return response(['msg' => 'Card created! '], 200);
            }
            return response()->json(['msg' => 'Card already exists!'], 405);

        }
        return response()->json(['msg' => 'Invalid request!'], 400);

    }

    public function deleteCard($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();
        $config = DB::table('config')->first();
        unlink($config->img_base_path . $card->path);
        return response()->json(null, 204);
    }

//DECKS
    public function createDeck(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $deck = new Deck();
        $deck->fill($request->all());
        $deck->hidden_face_image_path = '';
        $deck->active = 0;
        $deck->complete = 0;
        $deck->save();
        return response()->json(new DeckResource($deck), 201);
    }

    public function getDecks(Request $request)
    {
        $config = DB::table('config')->first();

        $decks = Deck::all();
        foreach ($decks as $deck) {
            $deck->hidden_face_image_path = $config->img_base_path . $deck->hidden_face_image_path;
        }

        return DeckResource::collection($decks);
    }

    public function deleteDeck($id)
    {
        $deck = Deck::findOrFail($id);

        $cardsAssociated = DB::table('cards')->where('deck_id','=', $deck->id)->count();

        if($cardsAssociated == 0){
            $deck->delete();
            return response(['msg' => 'Deck deleted! '], 204);
        }
        return response(['msg' => 'Cannot delete Deck with cards!'], 400);
    }


}
