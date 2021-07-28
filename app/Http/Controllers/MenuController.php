<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Store;
use Illuminate\Http\Request;
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

        return response()->json([
            'menus' => Menu::all(),
            'categories' => Category::all(),
            'items' => Item::all(),
        ]);
    }

    public function upload(string $storeID, Request $request)
    {
        $this->validate($request, [
            'menus' => 'sometimes|array',
            'menus.*.title' => 'sometimes|string',
            'menus.*.subtitle' => 'sometimes|string',
            'menus.*.service_availability' => 'sometimes|array',
            'menus.*.service_availability.*.day_of_week' => [
                'sometimes',
                Rule::in('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')
            ],
            'menus.*.category_ids' => 'sometimes|array',
            'menus.*.category_ids.*' => 'string',

            'categories' => 'sometimes|array',
            'categories.*.title' => 'sometimes|string',
            'categories.*.subtitle' => 'sometimes|string',
            'categories.*.entities' => 'sometimes|array',
            'categories.*.entities.*.id' => 'sometimes|string',
            'categories.*.entities.*.type' => [
                'sometimes',
                Rule::in('ITEM', 'MODIFIER_GROUP')
            ],

            'items' => 'sometimes|array',
            'items.*.title' => 'sometimes|string',
            'items.*.description' => 'sometimes|string',
            'items.*.image_url' => 'sometimes|string',
            'items.*.price_info' => 'sometimes|integer',
            'items.*.tax_info' => 'sometimes|array',
            'items.*.nutritional_info' => 'sometimes|array',
            'items.*.dish_info' => 'sometimes|array',
            'items.*.visibility_info' => 'sometimes|array',

            'menu_type' => [
                'sometimes',
                Rule::in(['MENU_TYPE_FULFILLMENT_DELIVERY', 'MENU_TYPE_FULFILLMENT_PICK_UP'])
            ]
        ]);

        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        $store->menus()->delete();
        $store->categories()->delete();
        $store->items()->delete();

        $store->menus()->createMany($request->get('menus'));
        $store->categories()->createMany($request->get('categories'));
        $store->items()->createMany($request->get('items'));
    }
}
