<?php


namespace App\Http\Controllers;


use App\Models\RestaurantStatus;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Class StoreController
 * @package App\Http\Controllers
 */
class StoreController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request): JsonResponse
    {
        $this->validate($request, [
            'limit' => 'sometimes|integer',
            'start_key' => 'sometimes|string'
        ]);

        return response()->json([
            'nex_key' => Str::random(5),
            'stores' => Store::all()
        ]);
    }

    /**
     * @param string $storeID
     *
     * @return JsonResponse
     */
    public function get(string $storeID): JsonResponse
    {
        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        return response()->json($store);
    }

    /**
     * @param string $storeID
     *
     * @return JsonResponse
     */
    public function getStatus(string $storeID): JsonResponse
    {
        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        return response()->json($store->restaurantStatus()->limit(1));
    }

    /**
     * @param string  $storeID
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setStatus(string $storeID, Request $request): JsonResponse
    {
        $this->validate($request, [
            'status' => [
                'required',
                Rule::in(['ONLINE', 'PAUSED']),
            ]
        ]);

        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        $store->restaurantStatus()->create([
            'status' => $request->get('status'),
            'offlineReason' => 'PAUSED_BY_RESTAURANT'
        ]);

        return response()->json([], 204);
    }

    /**
     * @param string $storeID
     *
     * @return JsonResponse
     */
    public function getHolidayHours(string $storeID)
    {
        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        return response()->json(['holiday_hours' => $store->holidayHours()]);
    }

    /**
     * @param string  $storeID
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setHolidayHours(string $storeID, Request $request)
    {
        $this->validate($request, [
            'holiday_hours' => 'required',
            'holiday_hours.*.open_time_periods.start_time' => 'required|string',
            'holiday_hours.*.open_time_periods.end_time' => 'required|string',
        ]);

        /** @var Store $store */
        $store = Store::find($storeID);

        if ($store === null)
            return response()->json(['error' => 'Store not found'], 404);

        foreach ($request->get('holiday_hours') as $item => $value)
            $store->holidayHours()->create([
                'content' => json_encode([$item => $value])
            ]);

        return response()->json([], 200);
    }
}
