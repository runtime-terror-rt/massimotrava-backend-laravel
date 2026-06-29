<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ফরেন কি চেক এরর এড়াতে এবং নতুন করে ডেটা ইনসার্ট করতে টেবিল ক্লিয়ার করা
        SubscriptionPlan::truncate();

        // user_id এর জন্য প্রথম উপলব্ধ ইউজার আইডি নেওয়া হলো
        $adminUser = User::where('role', 'admin')->first() ?? User::first();
        $userId = $adminUser ? $adminUser->id : 1; 

        $plans = [
            [
                'name'              => 'Free',
                'billing_cycle'     => 'monthly',
                'price'             => 0.00,
                'duration'          => 30,
                'stripe_product_id' => null,
                'stripe_price_id'   => null,
                'features'          => [
                    'kits_per_year'                => '1',
                    'longevity_score'              => 'Basic',
                    'biomarker_insights'           => 'Limited',
                    'personalized_recommendations' => 'No',
                    'home_delivery_courier'        => 'No',
                    'support'                      => 'Community',
                    'pdf_reports_export'           => 'No',
                    'family_sharing'               => 'No'
                ],
                'status'            => true,
            ],
            [
                'name'              => 'Basic',
                'billing_cycle'     => 'monthly',
                'price'             => 19.00,
                'duration'          => 30,
                'stripe_product_id' => 'prod_UmQ3TbqgWleKzq', 
                'stripe_price_id'   => 'price_1TmrE0PFSvdpw7414rmFIRwk', 
                'features'          => [
                    'kits_per_year'                => '4',
                    'longevity_score'              => 'Full',
                    'biomarker_insights'           => 'Standard',
                    'personalized_recommendations' => 'Basic',
                    'home_delivery_courier'        => 'Yes',
                    'support'                      => 'Email',
                    'pdf_reports_export'           => 'Yes',
                    'family_sharing'               => 'No'
                ],
                'status'            => true,
            ],
            [
                'name'              => 'Premium',
                'billing_cycle'     => 'monthly',
                'price'             => 39.00,
                'duration'          => 30,
                'stripe_product_id' => 'prod_UmQ5rKlnilczHj',
                'stripe_price_id'   => 'price_1TmrErPFSvdpw741ndhhsVXc', 
                'features'          => [
                    'kits_per_year'                => 'Unlimited',
                    'longevity_score'              => 'Full + History',
                    'biomarker_insights'           => 'Advanced',
                    'personalized_recommendations' => 'Advanced',
                    'home_delivery_courier'        => 'Priority',
                    'support'                      => 'Chat',
                    'pdf_reports_export'           => 'Yes',
                    'family_sharing'               => 'Up to 2'
                ],
                'status'            => true,
            ],
            [
                'name'              => 'Ultimate',
                'billing_cycle'     => 'monthly',
                'price'             => 69.00,
                'duration'          => 30,
                'stripe_product_id' => 'prod_UmQ5NNzQsQ420r',
                'stripe_price_id'   => 'price_1TmrF6PFSvdpw7419FZ3mOBW', 
                'features'          => [
                    'kits_per_year'                => 'Unlimited',
                    'longevity_score'              => 'Full + AI',
                    'biomarker_insights'           => 'Personalized AI',
                    'personalized_recommendations' => 'Doctor-level',
                    'home_delivery_courier'        => 'Free + Priority',
                    'support'                      => 'Dedicated',
                    'pdf_reports_export'           => 'Advanced',
                    'family_sharing'               => 'Up to 4'
                ],
                'status'            => true,
            ]
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::create(array_merge($planData, [
                'user_id' => $userId,
            ]));
        }
    }
}