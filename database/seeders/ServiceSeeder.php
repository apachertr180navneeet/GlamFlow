<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesData = [
            [
                'name' => 'Female Styling',
                'description' => 'Women\'s hair cutting, styling, coloring, and hair treatment services.',
                'services' => [
                    ['service_name' => 'Basic Hair Cut', 'description' => 'Basic haircut for women', 'price' => 600, 'duration' => 30],
                    ['service_name' => 'Hair Cut Advance', 'description' => 'Advanced haircut styling', 'price' => 800, 'duration' => 45],
                    ['service_name' => 'Deep Conditioning', 'description' => 'Deep hair conditioning treatment', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Blow Dry Curls', 'description' => 'Blow dry with curls', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Blow Dry Straight', 'description' => 'Blow dry straight styling', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Ironing Straight / Curls', 'description' => 'Hair ironing according to length', 'price' => 700, 'duration' => 45],
                    ['service_name' => 'Basic Hair Spa', 'description' => 'Nourishing hair spa', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'L\'Oreal Hair Spa', 'description' => 'L\'Oreal hair spa treatment', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Wella Hair Spa', 'description' => 'Wella hair spa treatment', 'price' => 1800, 'duration' => 45],
                    ['service_name' => 'Schwarzkopf Hair Spa', 'description' => 'Schwarzkopf hair spa', 'price' => 2000, 'duration' => 45],
                    ['service_name' => 'Anti Dandruff Hair Spa', 'description' => 'Anti-dandruff treatment', 'price' => 2500, 'duration' => 45],
                    ['service_name' => 'Anti Hair Fall Spa', 'description' => 'Anti hair fall treatment', 'price' => 3500, 'duration' => 45],
                    ['service_name' => 'L\'Oreal Majirel Root Touchup', 'description' => 'Root touch-up coloring', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'Innova Root Touchup', 'description' => 'Root touch-up coloring', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'L\'Oreal Majirel Global', 'description' => 'Global hair color', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Innova Global', 'description' => 'Premium global color', 'price' => 4000, 'duration' => 60],
                    ['service_name' => 'Highlights (Per Strip)', 'description' => 'Hair highlight per strip', 'price' => 350, 'duration' => 30],
                    ['service_name' => 'Global Highlights', 'description' => 'Full head highlights', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Ombre', 'description' => 'Ombre coloring', 'price' => 4000, 'duration' => 90],
                    ['service_name' => 'Balayage', 'description' => 'Balayage hair coloring', 'price' => 3500, 'duration' => 90],
                    ['service_name' => 'Rebonding', 'description' => 'Hair rebonding', 'price' => 3500, 'duration' => 120],
                    ['service_name' => 'Smoothening', 'description' => 'Hair smoothening', 'price' => 4000, 'duration' => 120],
                    ['service_name' => 'Keratin', 'description' => 'Keratin treatment', 'price' => 4000, 'duration' => 120],
                    ['service_name' => 'Cysteine', 'description' => 'Cysteine treatment', 'price' => 4500, 'duration' => 120],
                    ['service_name' => 'Botox Treatment', 'description' => 'Hair botox', 'price' => 4500, 'duration' => 120],
                    ['service_name' => 'No Plaster Treatment', 'description' => 'Hair treatment', 'price' => 4500, 'duration' => 120],
                    ['service_name' => 'Hair Extensions', 'description' => 'Hair extensions (according to length)', 'price' => 20000, 'duration' => 120],
                ]
            ],
            [
                'name' => 'Male Grooming',
                'description' => 'Men\'s haircut, beard styling, coloring, and grooming treatments.',
                'services' => [
                    ['service_name' => 'Hair Cut', 'description' => 'Men\'s haircut', 'price' => 300, 'duration' => 30],
                    ['service_name' => 'Deep Conditioning', 'description' => 'Hair conditioning', 'price' => 300, 'duration' => 30],
                    ['service_name' => 'Kids Hair Cut', 'description' => 'Kids haircut', 'price' => 200, 'duration' => 20],
                    ['service_name' => 'Clean Shaving', 'description' => 'Clean shave', 'price' => 100, 'duration' => 20],
                    ['service_name' => 'Beard Trim', 'description' => 'Beard trimming', 'price' => 200, 'duration' => 20],
                    ['service_name' => 'Beard Color', 'description' => 'Beard coloring', 'price' => 700, 'duration' => 30],
                    ['service_name' => 'L\'Oreal Hair Spa', 'description' => 'Hair spa', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'Wella Hair Spa', 'description' => 'Hair spa', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Schwarzkopf Hair Spa', 'description' => 'Hair spa', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Anti Dandruff Treatment', 'description' => 'Anti dandruff', 'price' => 2000, 'duration' => 45],
                    ['service_name' => 'Anti Hairfall Treatment', 'description' => 'Hairfall treatment', 'price' => 2500, 'duration' => 45],
                    ['service_name' => 'Body Waxing Hands', 'description' => 'Waxing', 'price' => 700, 'duration' => 45],
                    ['service_name' => 'Body Waxing Front', 'description' => 'Waxing', 'price' => 700, 'duration' => 45],
                    ['service_name' => 'Body Waxing Back', 'description' => 'Waxing', 'price' => 700, 'duration' => 45],
                    ['service_name' => 'Body Waxing Legs', 'description' => 'Waxing', 'price' => 1000, 'duration' => 60],
                    ['service_name' => 'Complete Body Waxing', 'description' => 'Full body waxing', 'price' => 6000, 'duration' => 120],
                    ['service_name' => 'Body Trimming Hands', 'description' => 'Body trimming', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Body Trimming Chest', 'description' => 'Body trimming', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Body Trimming Back', 'description' => 'Body trimming', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Body Trimming Legs', 'description' => 'Body trimming', 'price' => 1000, 'duration' => 45],
                    ['service_name' => 'Neck D-Tan', 'description' => 'Neck tan removal', 'price' => 300, 'duration' => 20],
                    ['service_name' => 'Face D-Tan', 'description' => 'Face tan removal', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Hand D-Tan', 'description' => 'Hand tan removal', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'Back D-Tan', 'description' => 'Back tan removal', 'price' => 700, 'duration' => 30],
                    ['service_name' => 'Full Leg D-Tan', 'description' => 'Leg tan removal', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'Highlights Per Strip', 'description' => 'Hair highlights', 'price' => 200, 'duration' => 20],
                    ['service_name' => 'Global Color', 'description' => 'Global hair color', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Innova Color', 'description' => 'Hair coloring', 'price' => 2500, 'duration' => 45],
                    ['service_name' => 'Global Highlights', 'description' => 'Hair highlights', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Rebonding', 'description' => 'Hair rebonding', 'price' => 3000, 'duration' => 90],
                    ['service_name' => 'Smoothening', 'description' => 'Hair smoothening', 'price' => 3000, 'duration' => 90],
                    ['service_name' => 'Keratin', 'description' => 'Keratin treatment', 'price' => 3500, 'duration' => 90],
                    ['service_name' => 'Cysteine', 'description' => 'Cysteine treatment', 'price' => 4000, 'duration' => 90],
                ]
            ],
            [
                'name' => 'Skin Care',
                'description' => 'Facials, clean-ups, skin brightening, and rejuvenation therapies.',
                'services' => [
                    ['service_name' => 'Fruit Facial', 'description' => 'Fruit facial', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Kaya Pearl Whitening', 'description' => 'Whitening facial', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Aroma Vitamin C Facial', 'description' => 'Vitamin C facial', 'price' => 2000, 'duration' => 60],
                    ['service_name' => 'Organic Facial', 'description' => 'Organic facial', 'price' => 2000, 'duration' => 60],
                    ['service_name' => 'Lotus Whitening', 'description' => 'Whitening facial', 'price' => 2000, 'duration' => 60],
                    ['service_name' => 'O3+ Whitening with Mask', 'description' => 'O3+ facial', 'price' => 2500, 'duration' => 60],
                    ['service_name' => 'O3+ Diamond with Mask', 'description' => 'Premium O3+ facial', 'price' => 3500, 'duration' => 60],
                    ['service_name' => 'Peel Off Mask', 'description' => 'Peel-off treatment', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Kampeki Facial', 'description' => 'Japanese facial', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Hydra Facial', 'description' => 'Hydra facial', 'price' => 3500, 'duration' => 60],
                    ['service_name' => 'Fruit Clean-Up', 'description' => 'Clean-up', 'price' => 800, 'duration' => 30],
                    ['service_name' => 'Kaya Pearl Clean-Up', 'description' => 'Clean-up', 'price' => 800, 'duration' => 30],
                    ['service_name' => 'Lotus Whitening Clean-Up', 'description' => 'Clean-up', 'price' => 1000, 'duration' => 30],
                    ['service_name' => 'Organic Clean-Up', 'description' => 'Clean-up', 'price' => 1200, 'duration' => 30],
                    ['service_name' => 'Kanpeki Clean-Up', 'description' => 'Clean-up', 'price' => 1500, 'duration' => 30],
                    ['service_name' => 'O3+ Whitening Clean-Up', 'description' => 'Clean-up', 'price' => 1500, 'duration' => 30],
                    ['service_name' => 'O3+ Diamond Clean-Up', 'description' => 'Clean-up', 'price' => 2000, 'duration' => 30],
                    ['service_name' => 'Customized Mini Facial', 'description' => 'Express facial (30 min)', 'price' => 1800, 'duration' => 30],
                    ['service_name' => 'Men\'s Skin Treatment', 'description' => 'Men\'s facial treatment', 'price' => 2500, 'duration' => 45],
                    ['service_name' => 'Ultimate Hydrating Treatment', 'description' => 'Hydrating facial', 'price' => 2600, 'duration' => 45],
                    ['service_name' => 'MediBac Acne Treatment', 'description' => 'Acne treatment', 'price' => 2800, 'duration' => 45],
                    ['service_name' => 'Multivitamin Skin Brightening', 'description' => 'Brightening facial', 'price' => 3200, 'duration' => 60],
                    ['service_name' => 'Multivitamin Anti-ageing', 'description' => 'Anti-ageing facial', 'price' => 3500, 'duration' => 60],
                ]
            ],
            [
                'name' => 'Waxing & Threading',
                'description' => 'Hair removal, threading, and Rica waxing services.',
                'services' => [
                    ['service_name' => 'Arms Wax', 'description' => 'Rica waxing', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Half Hand Wax', 'description' => 'Rica waxing', 'price' => 300, 'duration' => 20],
                    ['service_name' => 'Legs Wax', 'description' => 'Rica waxing', 'price' => 600, 'duration' => 30],
                    ['service_name' => 'Half Legs Wax', 'description' => 'Rica waxing', 'price' => 300, 'duration' => 20],
                    ['service_name' => 'Under Arms Wax', 'description' => 'Rica waxing', 'price' => 150, 'duration' => 15],
                    ['service_name' => 'Tummy Wax', 'description' => 'Rica waxing', 'price' => 300, 'duration' => 20],
                    ['service_name' => 'Front Wax', 'description' => 'Rica waxing', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Back Wax', 'description' => 'Rica waxing', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Bikini Wax', 'description' => 'Rica waxing', 'price' => 2000, 'duration' => 45],
                    ['service_name' => 'Full Body Wax', 'description' => 'Rica waxing', 'price' => 2000, 'duration' => 90],
                    ['service_name' => 'Eyebrows Threading', 'description' => 'Threading', 'price' => 70, 'duration' => 15],
                    ['service_name' => 'Upper Lip Threading', 'description' => 'Threading', 'price' => 30, 'duration' => 10],
                    ['service_name' => 'Chin Threading', 'description' => 'Threading', 'price' => 30, 'duration' => 10],
                    ['service_name' => 'Forehead Threading', 'description' => 'Threading', 'price' => 30, 'duration' => 10],
                    ['service_name' => 'Face Threading', 'description' => 'Threading', 'price' => 300, 'duration' => 30],
                    ['service_name' => 'Eyebrows Face Wax', 'description' => 'Face wax', 'price' => 150, 'duration' => 15],
                    ['service_name' => 'Upper Lip Face Wax', 'description' => 'Face wax', 'price' => 100, 'duration' => 10],
                    ['service_name' => 'Chin Face Wax', 'description' => 'Face wax', 'price' => 100, 'duration' => 10],
                    ['service_name' => 'Forehead Face Wax', 'description' => 'Face wax', 'price' => 100, 'duration' => 10],
                    ['service_name' => 'Side Locks Wax', 'description' => 'Face wax', 'price' => 300, 'duration' => 20],
                    ['service_name' => 'Full Face Wax', 'description' => 'Face wax', 'price' => 500, 'duration' => 30],
                ]
            ],
            [
                'name' => 'Manicure & Pedicure',
                'description' => 'Spa manicure, pedicure, nail extensions, and nail art services.',
                'services' => [
                    ['service_name' => 'Hand Spa Manicure', 'description' => 'Spa manicure', 'price' => 1000, 'duration' => 45],
                    ['service_name' => 'Foot Spa Pedicure', 'description' => 'Spa pedicure', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'Acrylic Nails', 'description' => 'Acrylic nail extension', 'price' => 2500, 'duration' => 60],
                    ['service_name' => 'Nail Extension', 'description' => 'Nail extension', 'price' => 2200, 'duration' => 60],
                    ['service_name' => 'Gel Extension', 'description' => 'Gel extension', 'price' => 1200, 'duration' => 45],
                    ['service_name' => 'Basic Nail Paint Hands', 'description' => 'Nail paint', 'price' => 300, 'duration' => 15],
                    ['service_name' => 'Basic Nail Paint Feet', 'description' => 'Nail paint', 'price' => 200, 'duration' => 15],
                    ['service_name' => 'Gel Nail Paint Hands', 'description' => 'Gel polish', 'price' => 1000, 'duration' => 30],
                    ['service_name' => 'Gel Nail Paint Feet', 'description' => 'Gel polish', 'price' => 500, 'duration' => 30],
                ]
            ],
            [
                'name' => 'Make-Up',
                'description' => 'Party, occasion, and HD bridal makeup services.',
                'services' => [
                    ['service_name' => 'Basic Make-Up', 'description' => 'Basic makeup', 'price' => 1500, 'duration' => 45],
                    ['service_name' => 'Advance Make-Up', 'description' => 'Advanced makeup', 'price' => 2500, 'duration' => 60],
                    ['service_name' => 'Light Party Make-Up', 'description' => 'Party makeup', 'price' => 2500, 'duration' => 60],
                    ['service_name' => 'Sangeet / Haldi Make-Up', 'description' => 'Occasion makeup', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Reception Make-Up', 'description' => 'Reception makeup', 'price' => 5000, 'duration' => 90],
                    ['service_name' => 'Eye Make-Up', 'description' => 'Eye makeup', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Groom Make-Up', 'description' => 'Groom makeup', 'price' => 2000, 'duration' => 45],
                    ['service_name' => 'HD Bridal Make-Up', 'description' => 'HD bridal makeup', 'price' => 11000, 'duration' => 120],
                    ['service_name' => 'Saree Draping', 'description' => 'Saree draping', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Hair Styling', 'description' => 'Hairstyling', 'price' => 500, 'duration' => 30],
                    ['service_name' => 'Air Brush Make-Up', 'description' => 'Airbrush bridal makeup', 'price' => 18000, 'duration' => 150],
                ]
            ],
            [
                'name' => 'Wellness Treatment',
                'description' => 'Body therapies, Swedish, deep tissue, and hot stone massages.',
                'services' => [
                    ['service_name' => 'Swedish Therapy', 'description' => 'Relaxation massage', 'price' => 2000, 'duration' => 60],
                    ['service_name' => 'Deep Tissue Therapy', 'description' => 'Deep tissue massage', 'price' => 2000, 'duration' => 60],
                    ['service_name' => 'Balinese Therapy', 'description' => 'Balinese massage', 'price' => 2000, 'duration' => 60],
                    ['service_name' => 'Aroma Therapy', 'description' => 'Aromatherapy massage', 'price' => 1800, 'duration' => 60],
                    ['service_name' => 'Hot Stone Therapy', 'description' => 'Hot stone massage', 'price' => 2500, 'duration' => 75],
                    ['service_name' => 'Shiatsu Therapy', 'description' => 'Shiatsu massage', 'price' => 3000, 'duration' => 60],
                ]
            ],
            [
                'name' => 'Body Rituals',
                'description' => 'Head massage, back soother, and reflexology rituals.',
                'services' => [
                    ['service_name' => 'Traditional Head Massage', 'description' => 'Head massage', 'price' => 600, 'duration' => 30],
                    ['service_name' => 'Back Soother', 'description' => 'Back massage', 'price' => 800, 'duration' => 30],
                    ['service_name' => 'Foot Reflexology', 'description' => 'Foot massage', 'price' => 800, 'duration' => 30],
                    ['service_name' => 'Back / Shoulder / Hands Massage', 'description' => 'Upper body massage', 'price' => 1000, 'duration' => 45],
                ]
            ],
            [
                'name' => 'Express Massage',
                'description' => '30-minute quick relief massages.',
                'services' => [
                    ['service_name' => 'Back Soother', 'description' => '30-minute back massage', 'price' => 800, 'duration' => 30],
                    ['service_name' => 'Traditional Head Massage', 'description' => '30-minute head massage', 'price' => 600, 'duration' => 30],
                    ['service_name' => 'Foot Reflexology', 'description' => 'Foot massage 30 min', 'price' => 800, 'duration' => 30],
                ]
            ],
            [
                'name' => 'Body Scrub',
                'description' => 'Herbal, rose, lavender, and saffron body scrubs.',
                'services' => [
                    ['service_name' => 'Glow Scrub', 'description' => 'Herbal glow body scrub', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Rose Scrub', 'description' => 'Rose body scrub', 'price' => 3500, 'duration' => 60],
                    ['service_name' => 'Exfoliating Splendor', 'description' => 'Premium exfoliation', 'price' => 3500, 'duration' => 60],
                    ['service_name' => 'Lavender Scrub', 'description' => 'Lavender body scrub', 'price' => 3500, 'duration' => 60],
                    ['service_name' => 'Zafaran Scrub', 'description' => 'Saffron body scrub', 'price' => 3000, 'duration' => 60],
                    ['service_name' => 'Thaishi Body Scrub', 'description' => 'Thai body scrub', 'price' => 3000, 'duration' => 60],
                ]
            ]
        ];

        foreach ($categoriesData as $catData) {
            $category = ServiceCategory::updateOrCreate(
                ['name' => $catData['name']],
                [
                    'description' => $catData['description'],
                    'status'      => true,
                ]
            );

            foreach ($catData['services'] as $svcData) {
                Service::updateOrCreate(
                    [
                        'category_id'  => $category->id,
                        'service_name' => $svcData['service_name'],
                    ],
                    [
                        'description'  => $svcData['description'],
                        'price'        => $svcData['price'],
                        'duration'     => $svcData['duration'],
                        'status'       => true,
                    ]
                );
            }
        }
    }
}
