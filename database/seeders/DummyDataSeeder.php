<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Create Stores
        $stores = [];
        for ($i = 0; $i < 3; $i++) {
            $stores[] = Store::create([
                'store_name' => $faker->company . ' Salon',
                'phone' => substr($faker->phoneNumber, 0, 20),
                'email' => $faker->companyEmail,
                'address' => $faker->address,
                'status' => 1
            ]);
        }

        // 2. Create Service Categories & Services
        $serviceCategories = [];
        $categoryNames = ['Haircut & Styling', 'Coloring', 'Spa & Massage', 'Facial', 'Nail Care'];
        foreach ($categoryNames as $name) {
            $category = ServiceCategory::create(['name' => $name, 'status' => 1]);
            $serviceCategories[] = $category;

            // Create 3-5 services per category
            for ($j = 0; $j < rand(3, 5); $j++) {
                Service::create([
                    'category_id' => $category->id,
                    'service_name' => $name . ' - ' . $faker->word,
                    'duration' => $faker->randomElement([30, 45, 60, 90, 120]), // minutes
                    'price' => $faker->randomFloat(2, 20, 150),
                    'status' => 1
                ]);
            }
        }
        $services = Service::all();

        // 3. Create Expense Categories
        $expenseCategories = [];
        $expCatNames = ['Rent', 'Utilities', 'Inventory', 'Marketing', 'Maintenance'];
        foreach ($expCatNames as $name) {
            $expenseCategories[] = ExpenseCategory::create(['name' => $name, 'status' => 1]);
        }

        // 4. Create Users and Staff
        $staffMembers = [];
        foreach ($stores as $store) {
            for ($i = 0; $i < 4; $i++) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $user = User::create([
                    'store_id' => $store->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'full_name' => $firstName . ' ' . $lastName,
                    'slug' => Str::slug($firstName . ' ' . $lastName . ' ' . rand(100, 999)),
                    'email' => $faker->unique()->safeEmail,
                    'phone' => substr($faker->phoneNumber, 0, 20),
                    'password' => Hash::make('password'),
                    'address' => substr($faker->address, 0, 255),
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'country' => $faker->country,
                    'country_code' => "1",
                    'zipcode' => substr($faker->postcode, 0, 10),
                    'role' => 'user',
                    'status' => 'active'
                ]);
                // Using Spatie permission if available
                if (method_exists($user, 'assignRole')) {
                    try {
                        $user->assignRole('staff');
                    } catch (\Exception $e) {
                        // ignore if role doesn't exist yet
                    }
                }

                $staff = Staff::create([
                    'store_id' => $store->id,
                    'user_id' => $user->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'phone' => substr($faker->phoneNumber, 0, 20),
                    'address' => $faker->address,
                    'position' => $faker->randomElement(['Stylist', 'Therapist', 'Receptionist', 'Manager']),
                    'salary' => $faker->randomFloat(2, 1000, 4000),
                    'shift_time' => '09:00 AM - 05:00 PM',
                    'working_hours' => 8,
                    'joining_date' => $faker->date(),
                    'status' => 1
                ]);
                $staffMembers[] = $staff;
            }
        }

        // 5. Create Customers
        $customers = [];
        for ($i = 0; $i < 20; $i++) {
            $customers[] = Customer::create([
                'name' => $faker->name,
                'mobile' => substr($faker->phoneNumber, 0, 20),
                'email' => $faker->unique()->safeEmail,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'dob' => $faker->date('Y-m-d', '2005-01-01'),
                'address' => $faker->address,
                'notes' => substr($faker->sentence, 0, 255),
                'loyalty_points' => rand(0, 500)
            ]);
        }

        // 6. Create Appointments (Past and Future)
        foreach ($stores as $store) {
            $storeStaff = collect($staffMembers)->where('store_id', $store->id)->values();
            
            if ($storeStaff->isEmpty()) continue;

            for ($i = 0; $i < 30; $i++) { // 30 appointments per store
                $customer = $faker->randomElement($customers);
                $staff = $faker->randomElement($storeStaff);
                $service = $faker->randomElement($services);
                
                $isPast = $faker->boolean(70); // 70% past, 30% future
                
                if ($isPast) {
                    $appointmentDate = Carbon::instance($faker->dateTimeBetween('-2 months', 'now'));
                    $status = 'completed';
                    $paymentStatus = 'paid';
                } else {
                    $appointmentDate = Carbon::instance($faker->dateTimeBetween('now', '+1 month'));
                    $status = $faker->randomElement(['pending', 'confirmed']);
                    $paymentStatus = 'unpaid';
                }
                
                // Add time to date
                $appointmentDate->setTime(rand(9, 17), $faker->randomElement([0, 15, 30, 45]));
                $endTime = (clone $appointmentDate)->addMinutes($service->duration);

                Appointment::create([
                    'store_id' => $store->id,
                    'customer_id' => $customer->id,
                    'staff_id' => $staff->id,
                    'service_id' => $service->id,
                    'appointment_date' => $appointmentDate,
                    'end_time' => $endTime,
                    'check_in_time' => $isPast ? (clone $appointmentDate)->subMinutes(rand(0, 15)) : null,
                    'completed_time' => $isPast ? $endTime : null,
                    'status' => $status,
                    'notes' => substr($faker->sentence, 0, 255),
                    'total_amount' => $service->price,
                    'discount' => 0,
                    'final_amount' => $service->price,
                    'payment_status' => $paymentStatus,
                    'payment_type' => $isPast ? $faker->randomElement(['cash', 'card', 'upi']) : null,
                    'upi_reference' => null
                ]);
            }
        }

        // 7. Create Expenses
        foreach ($stores as $store) {
            for ($i = 0; $i < 15; $i++) {
                Expense::create([
                    'store_id' => $store->id,
                    'category_id' => $faker->randomElement($expenseCategories)->id,
                    'amount' => $faker->randomFloat(2, 50, 1000),
                    'date' => $faker->dateTimeBetween('-3 months', 'now'),
                    'remarks' => substr($faker->sentence, 0, 255),
                    'attachment' => null
                ]);
            }
        }
    }
}
