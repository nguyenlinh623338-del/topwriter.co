<?php

namespace Tests\Feature;

use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    /**
     * Test that pricing service extracts correct prices from welcome page
     */
    public function test_pricing_service_extracts_correct_prices(): void
    {
        $pricingService = app(PricingService::class);

        // Clear cache to ensure fresh parsing
        $pricingService->clearCache();

        $pricing = $pricingService->getSubscriptionPricing();

        $this->assertIsArray($pricing);
        $this->assertArrayHasKey('starter', $pricing);
        $this->assertArrayHasKey('professional', $pricing);
        $this->assertArrayHasKey('enterprise', $pricing);

        // Check that prices are numeric and reasonable
        $this->assertIsFloat($pricing['starter']);
        $this->assertIsFloat($pricing['professional']);
        $this->assertIsFloat($pricing['enterprise']);

        // Professional and Enterprise should be more expensive than Starter
        $this->assertGreaterThan($pricing['starter'], $pricing['professional']);
        $this->assertGreaterThan($pricing['professional'], $pricing['enterprise']);
    }

    /**
     * Test that pricing service returns formatted pricing correctly
     */
    public function test_pricing_service_returns_formatted_pricing(): void
    {
        $pricingService = app(PricingService::class);

        $formatted = $pricingService->getFormattedPricing();

        $this->assertIsArray($formatted);
        $this->assertArrayHasKey('starter', $formatted);
        $this->assertArrayHasKey('professional', $formatted);
        $this->assertArrayHasKey('enterprise', $formatted);

        // Check structure of formatted pricing
        foreach (['starter', 'professional', 'enterprise'] as $plan) {
            $this->assertArrayHasKey('price', $formatted[$plan]);
            $this->assertArrayHasKey('formatted', $formatted[$plan]);
            $this->assertArrayHasKey('period', $formatted[$plan]);
            $this->assertArrayHasKey('articles', $formatted[$plan]);

            $this->assertStringContainsString('$', $formatted[$plan]['formatted']);
            $this->assertEquals('month', $formatted[$plan]['period']);
            $this->assertIsInt($formatted[$plan]['articles']);
        }
    }

    /**
     * Test that pricing service caches results
     */
    public function test_pricing_service_caches_results(): void
    {
        $pricingService = app(PricingService::class);

        // Clear cache first
        $pricingService->clearCache();

        // First call should parse and cache
        $pricing1 = $pricingService->getSubscriptionPricing();

        // Second call should use cache
        $pricing2 = $pricingService->getSubscriptionPricing();

        $this->assertEquals($pricing1, $pricing2);

        // Verify cache exists
        $this->assertTrue(Cache::has('subscription_pricing'));
    }

    /**
     * Test that pricing service returns starter price correctly
     */
    public function test_pricing_service_returns_starter_price(): void
    {
        $pricingService = app(PricingService::class);

        $starterPrice = $pricingService->getStarterPrice();

        $this->assertIsFloat($starterPrice);
        $this->assertGreaterThan(0, $starterPrice);
    }

    /**
     * Test that pricing service can clear cache
     */
    public function test_pricing_service_can_clear_cache(): void
    {
        $pricingService = app(PricingService::class);

        // Ensure cache exists
        $pricingService->getSubscriptionPricing();
        $this->assertTrue(Cache::has('subscription_pricing'));

        // Clear cache
        $cleared = $pricingService->clearCache();
        $this->assertTrue($cleared);

        // Cache should be gone
        $this->assertFalse(Cache::has('subscription_pricing'));
    }
}
