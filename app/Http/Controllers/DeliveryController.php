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
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;


class DeliveryController extends Controller
{
    private static $messages = [
        'description.max' => 'La descripción es muy extensa',
        'quantity.integer' => 'La cantidad debe especificarse en enteros',
        'picture.url' => 'La imagen no se encuentra en Storage',
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

    public function store(Request $request)
    {
        $this->authorize('create', Delivery::class);
        $request->validate([
            'description' => 'required|max:500',
            'quantity' => 'required|integer',
            'image' => 'required|image',
            'address' => 'required',
            'for_user_id' => 'required',
            //  'state' => 'required'
        ], self::$messages);

        $delivery = Delivery::create($request->all());
        $path = $request->image->storeAs('public/deliveries', $request->user()->id . '_' . $delivery->id . '.' . $request->image->extension());
        $delivery->image = $path;
        $delivery->save();
//        Mail::to($delivery->user)->send(new NewDelivery($delivery));
        return response()->json($delivery, 201);
    }

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

    public function updateByFarm(Request $request, Delivery $delivery)
    {
        $this->authorize('updateByFarm', $delivery);
        $request->validate([
           //'image' => 'required|image'
        ]);

        //$path = $request->image->storeAs('public/deliveries', $request->user()->id . '_' . $delivery->id . '.' . $request->image->extension());
        $delivery->description = $request->description;
        $delivery->quantity = $request->quantity;
        //$delivery->image = $path;
        $delivery->address = $request->address;
        $delivery->for_user_id = $request->for_user_id;
        $delivery->save();
        return response()->json($delivery, 200);
    }

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

    public function image(Delivery $delivery)
    {
        return response()->download(public_path(Storage::url($delivery->image)), $delivery->id . '.jpg');
    }

}


