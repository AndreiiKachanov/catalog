<?php

namespace Database\Factories\Admin\Cart;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Invoice;
use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Token;
use App\Models\Admin\Item\Item;
use App\UseCases\ApiService;
use App\UseCases\SendOrderService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Cart\Token>
 */
class TokenFactory extends Factory
{
    protected $model = Token::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $countryCode = $this->faker->countryCode;
        $ipAddress = $this->faker->randomElement([$this->faker->ipv4, $this->faker->ipv6]);

        return [
            'token' => $this->faker->unique()->md5(),
            'ip' => $ipAddress,
            'ip_info' => serialize([
                'type' => strpos($ipAddress, ':') === false ? 'IPv4' : 'IPv6',
                'city' => $this->faker->city,
                'flag' => [
                    'img' => 'https://cdn.ipwhois.io/flags/' . mb_strtolower($countryCode) . '.svg',
                ],
              'country' => $this->faker->country,
              'country_code' => $countryCode
            ]),
            'browser' => $this->faker->randomElement(['Safari', 'Chrome', 'Firefox', null]),
            'platform' => $this->faker->randomElement(['AndroidOS', 'OS X', 'iOS', 'Windows', null]),
            'device' => $this->faker->randomElement(['WebKit', 'Samsung', 'Pixel', null]),
            'device_version' => null,
            'is_mobile' => $this->faker->randomElement([true, false]),
            'is_tablet' => $this->faker->randomElement([true, false]),
            'is_desktop' => $this->faker->randomElement([true, false]),
            'is_robot' => $this->faker->randomElement([true, false])
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Token $token) {
            Invoice::factory()
                ->count(1)
                ->create(['token_id' => $token->id]);

            //if ($this->faker->boolean(30)) { // 30% вероятность создания CartItem
            if ($this->faker->boolean(30)) {
                $randomCount = random_int(1, 30);
                CartItem::factory()
                    ->count($randomCount)
                    ->create(['token_id' => $token->id]);

                // Случайным образом определяем, создавать ли Order
                if ($this->faker->boolean(50)) { // 50% вероятность создания Order
                    Order::factory()
                        ->count(1)
                        ->create(['token_id' => $token->id]);
                }
            }
        });
    }
}
