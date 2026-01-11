<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PricingService
{
    /**
     * Get subscription pricing from welcome.blade.php
     *
     * @return array
     */
    public function getSubscriptionPricing()
    {
        return Cache::remember('subscription_pricing', 3600, function () { // Cache for 1 hour
            return $this->parsePricingFromWelcomePage();
        });
    }

    /**
     * Parse pricing information from welcome.blade.php file
     *
     * @return array
     */
    private function parsePricingFromWelcomePage()
    {
        try {
            $welcomeFile = resource_path('views/welcome.blade.php');

            if (!file_exists($welcomeFile)) {
                Log::error('Welcome page file not found', ['path' => $welcomeFile]);
                return $this->getDefaultPricing();
            }

            $content = file_get_contents($welcomeFile);

            $pricing = [
                'starter' => $this->extractPrice($content, 'Starter'),
                'professional' => $this->extractPrice($content, 'Professional'),
                'enterprise' => $this->extractPrice($content, 'Enterprise'),
            ];

            // Validate extracted prices
            foreach ($pricing as $plan => $price) {
                if (!$price || !is_numeric($price)) {
                    Log::warning("Invalid price extracted for {$plan} plan", ['price' => $price]);
                    $pricing[$plan] = $this->getDefaultPricing()[$plan];
                }
            }

            Log::info('Subscription pricing parsed successfully', $pricing);

            return $pricing;

        } catch (\Exception $e) {
            Log::error('Error parsing pricing from welcome page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->getDefaultPricing();
        }
    }

    /**
     * Extract price for a specific plan from HTML content
     *
     * @param string $content
     * @param string $planName
     * @return float|null
     */
    private function extractPrice($content, $planName)
    {
        try {
            // Split content by plan sections to ensure we get the right price for each plan
            $sections = explode('<div class="plan-name">' . $planName . '</div>', $content);

            if (count($sections) < 2) {
                Log::warning("Plan section not found for {$planName}");
                return null;
            }

            // Look for the price in the section that follows the plan name
            $planSection = $sections[1];

            // Find the next plan section or end of content to limit our search
            $nextPlans = ['Starter', 'Professional', 'Enterprise'];
            $nextPlanIndex = null;
            foreach ($nextPlans as $nextPlan) {
                if ($nextPlan !== $planName) {
                    $nextIndex = strpos($planSection, '<div class="plan-name">' . $nextPlan . '</div>');
                    if ($nextIndex !== false && ($nextPlanIndex === null || $nextIndex < $nextPlanIndex)) {
                        $nextPlanIndex = $nextIndex;
                    }
                }
            }

            // Extract the relevant section
            if ($nextPlanIndex !== null) {
                $planSection = substr($planSection, 0, $nextPlanIndex);
            }

            // Look for the amount span in this specific section
            if (preg_match('/<span class="amount">([0-9,]+)<\/span>/', $planSection, $matches)) {
                // Remove commas and convert to float
                $price = str_replace(',', '', $matches[1]);
                return (float) $price;
            }

            Log::warning("Price amount not found for {$planName} plan in its section");
            return null;

        } catch (\Exception $e) {
            Log::error("Error extracting price for {$planName}", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get default pricing as fallback
     *
     * @return array
     */
    private function getDefaultPricing()
    {
        return [
            'starter' => 1500.00,
            'professional' => 2800.00,
            'enterprise' => 5000.00,
        ];
    }

    /**
     * Get pricing for a specific plan
     *
     * @param string $plan
     * @return float
     */
    public function getPlanPrice($plan)
    {
        $pricing = $this->getSubscriptionPricing();

        return $pricing[strtolower($plan)] ?? $pricing['starter'];
    }

    /**
     * Get starter plan price (most commonly used for trials)
     *
     * @return float
     */
    public function getStarterPrice()
    {
        return $this->getPlanPrice('starter');
    }

    /**
     * Clear pricing cache (useful after updating welcome page)
     *
     * @return bool
     */
    public function clearCache()
    {
        return Cache::forget('subscription_pricing');
    }

    /**
     * Get all pricing information with formatted display
     *
     * @return array
     */
    public function getFormattedPricing()
    {
        $pricing = $this->getSubscriptionPricing();

        return [
            'starter' => [
                'price' => $pricing['starter'],
                'formatted' => '$' . number_format($pricing['starter']),
                'period' => 'month',
                'articles' => 5,
            ],
            'professional' => [
                'price' => $pricing['professional'],
                'formatted' => '$' . number_format($pricing['professional']),
                'period' => 'month',
                'articles' => 10,
            ],
            'enterprise' => [
                'price' => $pricing['enterprise'],
                'formatted' => '$' . number_format($pricing['enterprise']),
                'period' => 'month',
                'articles' => 20,
            ],
        ];
    }
}

