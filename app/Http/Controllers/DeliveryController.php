<?php

namespace App\Http\Controllers;


use App\Mail\NewDelivery;
use App\Http\Resources\DeliveryCollection;
use App\Models\Delivery;
use App\Http\Resources\Delivery as DeliveryResource;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;


class DeliveryController extends Controller
{
    private static $messages = [
        'description.max' => 'La descripción es muy extensa',
        'quantity.integer' => 'La cantidad debe especificarse en enteros',
        //'picture.url' => 'La imagen no se encuentra en Storage',
    ];

    // FUNCIONA LAS VALIDACIONES
    public function index()
    {
        //muestra entregas de acuerdo al role y de acuerdo al id
        $this->authorize('viewAny', Delivery::class);
        $user = Auth::user();
        if ($user->role === 'ROLE_FARM') {
            return response()->json(new DeliveryCollection(Delivery::where('user_id', $user->id)->get()));
        } else {
            return response()->json(new DeliveryCollection(Delivery::where('for_user_id', $user->id)->get()));
        }
    }

    public function show(Delivery $delivery)
    {
        $this->authorize('view', $delivery);
        return response()->json(new DeliveryResource($delivery), 200);
    }

    public function store(Request $request, Delivery $delivery)
    {
        $this->authorize('create', Delivery::class);
        $request->validate([
            'description' => 'required|max:500',
            'quantity' => 'required|integer',
            //'image' => 'required|image',
            //'address' => 'required',
            'for_user_id' => 'required',
            //  'state' => 'required'
        ], self::$messages);

        $delivery = Delivery::create($request->all());
        $delivery->latitude = $request->latitude;
        $delivery->longitude = $request->longitude;
        //$path = $request->image->storeAs('public/deliveries', $request->user()->id . '_' . $delivery->id . '.' . $request->image->extension());
        //$delivery->image = $path;
        $delivery->save();
        return response()->json($delivery, 201);
    }

    //ACTUALIZAR EL ESTADO DE LA ENTREGA POR EL CENTRO DE ACOPIO
    public function updateByCollectionCenter(Request $request, Delivery $delivery)
    {
        $this->authorize('updateByCollectionCenter', $delivery);

        $request->validate([
            'state' => 'required'
        ]);

        $delivery->state = $request->state;
        $delivery->save();

        return response()->json($delivery, 200);
    }

    //ACTUALIZAR LA NOTIFICACIÓN DE FECHA Y HORA DEL RETIRO DE LA ENTREGA POR EL CENTRO DE ACOPIO
    public function updateNotification(Request $request, Delivery $delivery)
    {
        $this->authorize('updateNotification', $delivery);

        $request->validate([
            'date' => 'required',
            'hour' => 'required'
        ]);

        $delivery->date = $request->date;
        $delivery->hour = $request->hour;

        $delivery->save();

        return response()->json($delivery, 200);
    }

    //ACTUALKZAR LA ENTREGA POR EL DUEÑO DE FINCA
    public function updateByFarm(Request $request, Delivery $delivery)
    {
        $this->authorize('updateByFarm', $delivery);

        $delivery->description = $request->description;
        $delivery->quantity = $request->quantity;
        $delivery->for_user_id = $request->for_user_id;
        $delivery->longitude = $request->longitude;
        $delivery->latitude = $request->latitude;
        $delivery->save();

        return response()->json($delivery, 200);
    }

    //ACTUALIZAR LA CALIFICACIÓN DEL RETIRO DE LA ENTREGA POR EL DUEÑO DE FINCA
    public function updateScore(Request $request, Delivery $delivery)
    {
        $this->authorize('updateScore', $delivery);

        $request->validate([
            'score' => 'required',
        ]);

        $delivery->score = $request->score;

        $delivery->save();

        return response()->json($delivery, 200);
    }

    //ACTUALKZAR EL ESTADO DE LA ENTREGA POR EL DUEÑO DE FINCA
    public function updateStateByFarm(Request $request, Delivery $delivery)
    {
        $this->authorize('updateStateByFarm', $delivery);

        $request->validate([
            'state' => 'required',
        ]);

        $delivery->state = $request->state;

        $delivery->save();

        return response()->json($delivery, 200);
    }

    //ACTUALIZAR LA NOTIFICACIÓN DE LA ENTREGA RECHAZADA POR EL CENTRO DE ACOPIO
    public function updateRejectedByAcopio(Request $request, Delivery $delivery)
    {
        $this->authorize('updateRejectedByAcopio', $delivery);

        $request->validate([
            'rejected' => 'required',
        ]);

        $delivery->rejected = $request->rejected;

        $delivery->save();

        return response()->json($delivery, 200);
    }

    //ACTUALIZAR LA NOTIFICACIÓN DEL COMENTARIO DE LA CALIFICACIÓN MENOR A 5 POR EL DUEÑO DE FINCA
    public function updateScoreCommentByFarm(Request $request, Delivery $delivery)
    {
        $this->authorize('updateScoreCommentByFarm', $delivery);

        $request->validate([
            'scorecomment' => 'required',
        ]);

        $delivery->scorecomment = $request->scorecomment;

        $delivery->save();

        return response()->json($delivery, 200);
    }
}


