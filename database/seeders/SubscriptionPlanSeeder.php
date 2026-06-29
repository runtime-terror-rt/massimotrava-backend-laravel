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
        $user = User::first();
        $userId = $user ? $user->id : 1; 

        $plans = [
            [
                'name'              => 'Free',
                'billing_cycle'     => 'monthly',
                'price'             => 0.00,
                'duration'          => 30,
                'stripe_product_id' => null,
                'stripe_price_id'   => null,
                'features'          => [
                    'kits_per_year'                => '1 Kit Per Year Included',
                    'longevity_score'              => 'Basic Longevity Score & Trends',
                    'biomarker_insights'           => 'Limited Biomarker Insights',
                    'personalized_recommendations' => 'No Personalized Recommendations',
                    'home_delivery_courier'        => 'No Home Kit Delivery + Courier',
                    'support'                      => 'Community Support',
                    'pdf_reports_export'           => 'No PDF Reports & Data Export',
                    'family_sharing'               => 'No Family Sharing Access'
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
                    'kits_per_year'                => '4 Kits Per Year Included',
                    'longevity_score'              => 'Full Longevity Score & Trends',
                    'biomarker_insights'           => 'Standard Biomarker Insights',
                    'personalized_recommendations' => 'Basic Personalized Recommendations',
                    'home_delivery_courier'        => 'Home Kit Delivery + Courier',
                    'support'                      => 'Standard Email Support',
                    'pdf_reports_export'           => 'PDF Reports & Data Export',
                    'family_sharing'               => 'No Family Sharing Access'
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
                    'kits_per_year'                => 'Unlimited Kits Per Year',
                    'longevity_score'              => 'Full Longevity Score + Deep History',
                    'biomarker_insights'           => 'Advanced Biomarker Insights',
                    'personalized_recommendations' => 'Advanced Recommendations',
                    'home_delivery_courier'        => 'Priority Home Delivery & Courier',
                    'support'                      => '24/7 Live Chat Support',
                    'pdf_reports_export'           => 'PDF Reports & Data Export',
                    'family_sharing'               => 'Family Sharing (Up to 2 Members)'
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
                    'kits_per_year'                => 'Unlimited Kits Per Year',
                    'longevity_score'              => 'Full Longevity Score + AI Trends',
                    'biomarker_insights'           => 'Personalized AI Biomarker Insights',
                    'personalized_recommendations' => 'Doctor-Level Recommendations',
                    'home_delivery_courier'        => 'Free + Priority Home Delivery',
                    'support'                      => 'Dedicated Support Concierge',
                    'pdf_reports_export'           => 'Advanced PDF Reports & Export',
                    'family_sharing'               => 'Family Sharing (Up to 4 Members)'
                ],
                'status'            => true,
            ]
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::updateOrCreate(
                [
                    'name'          => $planData['name'], 
                    'billing_cycle' => $planData['billing_cycle']
                ],
                array_merge($planData, [
                    'user_id' => $userId
                ])
            );
        }
    }
}