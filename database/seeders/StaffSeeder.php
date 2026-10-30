<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Store;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storeId = Store::first()?->id;

        $staffData = [
            [
                'first_name' => 'Prince',
                'last_name'  => '',
                'phone'      => '9119202171',
                'position'   => 'Hairdresser',
                'salary'     => 20000,
            ],
            [
                'first_name' => 'Twinkle',
                'last_name'  => '',
                'phone'      => '9351890310',
                'position'   => 'Beautician',
                'salary'     => 12000,
            ],
            [
                'first_name' => 'Tanvi',
                'last_name'  => '',
                'phone'      => '9351096060',
                'position'   => 'Beautician',
                'salary'     => 10000,
            ],
            [
                'first_name' => 'Lolo',
                'last_name'  => '',
                'phone'      => '9863033852',
                'position'   => 'Therapist',
                'salary'     => 23000,
            ],
            [
                'first_name' => 'Raj',
                'last_name'  => '',
                'phone'      => '9863431564',
                'position'   => 'Therapist',
                'salary'     => 28000,
            ],
            [
                'first_name' => 'Sita',
                'last_name'  => '',
                'phone'      => '9875727662',
                'position'   => 'Housekeeping',
                'salary'     => 8000,
            ],
            [
                'first_name' => 'Naveen',
                'last_name'  => '',
                'phone'      => '7023580075',
                'position'   => 'Hairdresser unisex',
                'salary'     => 0,
            ],
            [
                'first_name' => 'Jagdish',
                'last_name'  => '',
                'phone'      => '9588949851',
                'position'   => 'Hairdresser unisex',
                'salary'     => 18000,
            ],
            [
                'first_name' => 'Vishnu',
                'last_name'  => '',
                'phone'      => '9521732217',
                'position'   => 'Hairdresser unisex',
                'salary'     => 0,
            ],
            [
                'first_name' => 'Deep',
                'last_name'  => '',
                'phone'      => null,
                'position'   => 'Hairdresser',
                'salary'     => 10000,
            ],
            [
                'first_name' => 'Rinku',
                'last_name'  => '',
                'phone'      => '9664376142',
                'position'   => 'Beautician',
                'salary'     => 22000,
            ],
            [
                'first_name' => 'Deepa',
                'last_name'  => '',
                'phone'      => '9549971160',
                'position'   => 'Housekeeping',
                'salary'     => 0,
            ],
            [
                'first_name' => 'Dimple',
                'last_name'  => '',
                'phone'      => '7014786089',
                'position'   => 'Beautician',
                'salary'     => 0,
            ],
            [
                'first_name' => 'Anu',
                'last_name'  => '',
                'phone'      => '8560904087',
                'position'   => 'Beautician',
                'salary'     => 17000,
            ],
            [
                'first_name' => 'Lori',
                'last_name'  => '',
                'phone'      => '7877951276',
                'position'   => 'Therapist',
                'salary'     => 25000,
            ],
            [
                'first_name' => 'Hoti',
                'last_name'  => '',
                'phone'      => '7877196405',
                'position'   => 'Therapist',
                'salary'     => 0,
            ],
        ];

        foreach ($staffData as $data) {
            Staff::updateOrCreate(
                [
                    'first_name' => $data['first_name'],
                    'phone'      => $data['phone'],
                ],
                [
                    'store_id'   => $storeId,
                    'last_name'  => $data['last_name'],
                    'position'   => $data['position'],
                    'salary'     => $data['salary'],
                    'status'     => true,
                ]
            );
        }
    }
}
