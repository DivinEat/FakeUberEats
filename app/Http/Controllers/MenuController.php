<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Store;
use http\Env\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index(string $storeID, Request $request)
    {
        $this->validate($request, [
            'menu_type' => [
                'sometimes',
                Rule::in(['MENU_TYPE_FULFILLMENT_DELIVERY', 'MENU_TYPE_FULFILLMENT_PICK_UP'])
            ]
        ]);

        $menuType = $request->get('menu_type') ?? 'MENU_TYPE_FULFILLMENT_PICK_UP';

        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        return response()->json(Menu::all()->where('menu_type', $menuType));
    }

    public function upload(string $storeID, Request$request)
    {
        $this->validate($request, [
            'menus' => 'required|array',
            'categories' => 'required|array',
            'items' => 'required|array',
            'modifier_groups' => 'required|array',
            'display_options' => 'required',
            'menu_type' => [
                'required',
                Rule::in(['MENU_TYPE_FULFILLMENT_DELIVERY', 'MENU_TYPE_FULFILLMENT_PICK_UP'])
            ]
        ]);


        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);
    }
}
