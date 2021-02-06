<?php


namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function get(string $orderID): JsonResponse
    {
        /** @var Order $order */
        $order = Order::find($orderID);

        if ($order === null)
            return response()->json(['error' => 'Order not found'], 404);

        return response()->json($order);
    }

    public function cancel(string $orderID, Request $request): JsonResponse
    {
        $this->validate($request, [
            'reason' => [
                'required',
                Rule::in([
                    'OUT_OF_ITEMS',
                    'KITCHEN_CLOSED',
                    'CUSTOMER_CALLED_TO_CANCEL',
                    'RESTAURANT_TOO_BUSY',
                    'CANNOT_COMPLETE_CUSTOMER_NOTE',
                    'OTHER'
                ])
            ],
            'details' => 'sometimes|string'
        ]);

        /** @var Order $order */
        $order = Order::where('current_state', 'CREATED')->find($orderID);

        if ($order === null)
            return response()->json(['error' => 'Order not found'], 404);

        $order->update([
            'current_state' => 'CANCELED',
            'reason' => $request->get('reason'),
            'details' => $request->get('details') ?? null,
            'cancelling_party' => 'MERCHANT'
        ]);

        return response()->json();
    }

    public function accept(string $orderID, Request $request): JsonResponse
    {
        $this->validate($request, ['reason' => 'required|string']);

        /** @var Order $order */
        $order = Order::where('current_state', 'CREATED')->find($orderID);

        if ($order === null)
            return response()->json(['error' => 'Order not found'], 404);

        $order->update([
            'current_state' => 'ACCEPTED',
            'reason' => $request->get('reason')
        ]);

        return response()->json([], 204);
    }

    public function deny(string $orderID, Request $request): JsonResponse
    {
        $this->validate($request, [
            'reason' => 'required|array',
            'reason.explanation' => 'required|string',
            'reason.out_of_stock_items' => 'sometimes|array',
            'reason.invalid_items' => 'sometimes|array',
        ]);

        /** @var Order $order */
        $order = Order::where('current_state', 'CREATED')->find($orderID);

        if ($order === null)
            return response()->json(['error' => 'Order not found'], 404);

        $order->update([
            'current_state' => 'DENIED',
            'reason' => $request->get('reason')
        ]);

        return response()->json([], 204);
    }

    public function created(string $storeID, Request $request): JsonResponse
    {
        $this->validate($request, ['limit' => 'sometimes|integer']);

        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        return response()->json($store->orders()->where('current_state', 'CREATED')->get());
    }

    public function canceled(string $storeID): JsonResponse
    {
        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        return response()->json($store->orders()->where('current_state', 'CANCELED')->get());
    }
}
