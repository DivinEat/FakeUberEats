<?php


namespace App\Console\Commands;


use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        /** @var Store $store */
        $store = Store::all()->first();

        $store->orders()->create([
            "display_id" => Str::random(5),
            "external_reference_id" => "UberEatsOrder-" . Str::random(3),
            "current_state" => "CREATED",
            "type" => "DELIVERY_BY_UBER",
            "store" => [
                "id" => $store->store_id,
                "name" => $store->name,
                "external_reference_id" => "HARRY_123"
            ],
            "eater" => [
                "first_name" => 'El Morino',
                "phone" => "+33 06 35 49 48 85",
            ],
            "cart" => [
                //TODO : Récupérer nos items
                "items" => [
                    [
                        "id" => "Muffin",
                        "instance_id" => "Muffin-Instance",
                        "title" => "Fresh-baked muffin",
                        "external_data" => "External data for muffin",
                        "quantity" => 1,
                        "price" => 3
                    ]
                ]
            ],
            "payment" => [
                "charges" => [
                    "total" => [
                        "amount" => 650,
                        "currency_code" => "USD",
                        "formatted_amount" => "$6.50"
                    ],
                    "tax" => [
                        "amount" => 52,
                        "currency_code" => "USD",
                        "formatted_amount" => "$0.52"
                    ],
                    "total_fee" => [
                        "amount" => 697,
                        "currency_code" => "USD",
                        "formatted_amount" => "$6.97"
                    ]
                ]
            ],
            "placed_at" => Carbon::now()->toString(),
            "estimated_ready_for_pickup_at" => Carbon::now()->addMinutes(30)->toString(),
        ]);
    }
}