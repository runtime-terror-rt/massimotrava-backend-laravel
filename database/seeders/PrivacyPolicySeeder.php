<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrivacyPolicy;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrivacyPolicy::truncate();

        PrivacyPolicy::create([
            'title'     => 'Vyralabs Privacy Policy',
            'is_active' => true,
            'content'   => [
                [
                    'heading' => '1. Data We Collect',
                    'content' => "• Personal Data: Name, email, phone number, shipping address, date of birth, gender.\n• Health Data: Biomarker results, longevity score, blood samples (processed by labs).\n• Technical Data: IP address, device information, app usage data.\n• Payment Data: Processed securely by Stripe (we do not store full card details)."
                ],
                [
                    'heading' => '2. How We Use Your Data',
                    'content' => "• To process orders and deliver test kits and results.\n• To generate personalized longevity insights and recommendations.\n• For customer support and service improvement.\n• With your consent, for marketing communications."
                ],
                [
                    'heading' => '3. Legal Basis for Processing (GDPR)',
                    'content' => "• Performance of a contract\n• Legitimate interest\n• Your explicit consent (especially for health data)"
                ],
                [
                    'heading' => '4. Data Sharing',
                    'content' => "• Accredited partner laboratories for sample analysis\n• Payment processors (Stripe)\n• Shipping and logistics partners\n• Legal authorities when required by law\n\nWe never sell your personal or health data to third parties."
                ],
                [
                    'heading' => '5. Your GDPR Rights',
                    'content' => "You have the right to:\n• Access, rectify, or erase your data\n• Restrict or object to processing\n• Data portability\n• Withdraw consent at any time\n\nTo exercise these rights, email: privacy@vyralabs.health"
                ],
                [
                    'heading' => '6. Data Security',
                    'content' => 'We implement appropriate technical and organizational measures to protect your data, including encryption.'
                ],
                [
                    'heading' => '7. Data Retention',
                    'content' => 'We retain your data only as long as necessary to provide the service or as required by law.'
                ],
                [
                    'heading' => '8. International Data Transfers',
                    'content' => 'Any transfers outside the EU are protected by Standard Contractual Clauses or equivalent safeguards.'
                ],
            ]
        ]);
    }
}