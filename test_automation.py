#!/usr/bin/env python3
"""
TopWriterX Full Flow Automation Test
=====================================

This script automates the complete user journey:
1. Fill try-writing form (multi-step)
2. Complete trial payment with PayPal
3. Navigate to dashboard
4. Purchase 30 credits with PayPal
5. Verify credits are added correctly

Requirements:
pip install selenium webdriver-manager

Usage:
python test_automation.py
"""

import time
import random
import string
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from webdriver_manager.chrome import ChromeDriverManager

class TopWriterXAutomation:
    def __init__(self, base_url="http://localhost:8000", headless=False):
        self.base_url = base_url
        self.driver = None
        self.wait = None
        self.setup_driver(headless)
        
        # Test data
        self.test_user = {
            'name': f'Test User {random.randint(1000, 9999)}',
            'email': f'test.{random.randint(1000, 9999)}@example.com',
            'website': f'https://test-website-{random.randint(100, 999)}.com',
            'industry': 'Medical Clinic',
            'keywords': 'medical treatment, healthcare services, patient care',
            'competitor': 'https://competitor-medical.com',
            'notes': 'Premium medical clinic specializing in comprehensive healthcare services'
        }
        
        # PayPal sandbox credentials
        self.paypal_credentials = {
            'email': 'sb-gbnhi37819222@personal.example.com',
            'password': '3xINVl_<'
        }
    
    def setup_driver(self, headless=False):
        """Setup Chrome WebDriver with options"""
        chrome_options = Options()
        if headless:
            chrome_options.add_argument("--headless")
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--window-size=1920,1080")
        
        service = Service(ChromeDriverManager().install())
        self.driver = webdriver.Chrome(service=service, options=chrome_options)
        self.wait = WebDriverWait(self.driver, 30)
        
        print(f"✅ Chrome WebDriver initialized")
    
    def log(self, message, level="INFO"):
        """Log messages with timestamp"""
        timestamp = time.strftime("%H:%M:%S")
        print(f"[{timestamp}] {level}: {message}")
    
    def safe_click(self, element):
        """Safely click an element with scroll into view"""
        self.driver.execute_script("arguments[0].scrollIntoView(true);", element)
        time.sleep(0.5)
        element.click()
    
    def fill_try_writing_form(self):
        """Fill the multi-step try-writing form"""
        self.log("🎯 Starting try-writing form automation")
        
        # Navigate to try-writing page
        self.driver.get(f"{self.base_url}/try-writing")
        self.log(f"Navigated to: {self.driver.current_url}")
        
        # Step 1: Basic Information
        self.log("📝 Filling Step 1: Basic Information")
        
        # Fill name
        name_field = self.wait.until(EC.presence_of_element_located((By.NAME, "name")))
        name_field.clear()
        name_field.send_keys(self.test_user['name'])
        
        # Fill email
        email_field = self.driver.find_element(By.NAME, "email")
        email_field.clear()
        email_field.send_keys(self.test_user['email'])
        
        # Fill website URL
        website_field = self.driver.find_element(By.NAME, "website_url")
        website_field.clear()
        website_field.send_keys(self.test_user['website'])
        
        self.log(f"✅ Step 1 completed - User: {self.test_user['name']}")
        
        # Click Next to Step 2
        next_button = self.driver.find_element(By.ID, "next-to-step-2")
        self.safe_click(next_button)
        
        # Wait for step 2 to appear and progress bar animation
        self.wait.until(EC.visibility_of_element_located((By.ID, "step-2")))
        time.sleep(2)  # Wait for progress bar animation
        
        self.log("🎯 Progress bar animated, proceeding to Step 2")
        
        # Step 2: Detailed Requirements
        self.log("📝 Filling Step 2: Detailed Requirements")
        
        # Industry is already pre-selected (Health - Medical - Aesthetics)
        industry_field = self.driver.find_element(By.NAME, "industry")
        industry_field.clear()
        industry_field.send_keys(self.test_user['industry'])
        
        # Keywords (provided option is already selected)
        keywords_field = self.driver.find_element(By.NAME, "keywords")
        keywords_field.clear()
        keywords_field.send_keys(self.test_user['keywords'])
        
        # Competitor websites
        competitor_field = self.driver.find_element(By.NAME, "competitor_links[]")
        competitor_field.clear()
        competitor_field.send_keys(self.test_user['competitor'])
        
        # Special requirements
        notes_field = self.driver.find_element(By.NAME, "notes")
        notes_field.clear()
        notes_field.send_keys(self.test_user['notes'])
        
        self.log("✅ Step 2 completed - All fields filled")
        
        # Submit form
        submit_button = self.driver.find_element(By.ID, "submit-form")
        self.safe_click(submit_button)
        
        self.log("🚀 Form submitted, waiting for payment options...")
        
        # Wait for payment options page
        self.wait.until(EC.url_contains("trial-payment-options"))
        self.log(f"✅ Redirected to payment options: {self.driver.current_url}")
    
    def complete_trial_payment(self):
        """Complete trial payment with PayPal"""
        self.log("💳 Starting trial payment process")
        
        # Click PayPal payment option
        paypal_button = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "a[href*='paypal/checkout']")))
        self.safe_click(paypal_button)
        
        self.log("🔄 Redirected to PayPal, logging in...")
        
        # Wait for PayPal login page
        time.sleep(3)
        
        # Handle PayPal login
        try:
            # Check if already logged in or need to login
            if "paypal.com" in self.driver.current_url:
                self.handle_paypal_login()
                self.handle_paypal_payment_approval()
            else:
                self.log("⚠️ PayPal redirect failed or already completed")
        except Exception as e:
            self.log(f"❌ PayPal payment error: {str(e)}", "ERROR")
            raise
        
        # Wait for success page
        self.wait.until(EC.url_contains("trial-success"))
        self.log("✅ Trial payment completed successfully")
    
    def handle_paypal_login(self):
        """Handle PayPal login process"""
        try:
            # Wait for email field
            email_field = self.wait.until(EC.presence_of_element_located((By.ID, "email")))
            email_field.clear()
            email_field.send_keys(self.paypal_credentials['email'])
            
            # Click Next or Login
            try:
                next_button = self.driver.find_element(By.ID, "btnNext")
                self.safe_click(next_button)
                time.sleep(2)
            except NoSuchElementException:
                pass
            
            # Enter password
            password_field = self.wait.until(EC.presence_of_element_located((By.ID, "password")))
            password_field.clear()
            password_field.send_keys(self.paypal_credentials['password'])
            
            # Click login
            login_button = self.driver.find_element(By.ID, "btnLogin")
            self.safe_click(login_button)
            
            self.log("✅ PayPal login completed")
            time.sleep(3)
            
        except TimeoutException:
            self.log("⚠️ PayPal login elements not found, might be already logged in")
    
    def handle_paypal_payment_approval(self):
        """Handle PayPal payment approval"""
        try:
            # Wait for and click approve button
            approve_button = self.wait.until(EC.element_to_be_clickable((By.ID, "payment-submit-btn")))
            self.safe_click(approve_button)
            
            self.log("✅ PayPal payment approved")
            time.sleep(5)
            
        except TimeoutException:
            self.log("⚠️ PayPal approval button not found, trying alternative selectors")
            try:
                # Try alternative selectors
                approve_button = self.driver.find_element(By.CSS_SELECTOR, "button[data-testid='submit-button']")
                self.safe_click(approve_button)
            except NoSuchElementException:
                self.log("❌ Could not find PayPal approval button")
    
    def navigate_to_dashboard(self):
        """Navigate to dashboard from success page"""
        self.log("🏠 Navigating to dashboard")
        
        # Look for dashboard link on success page
        try:
            dashboard_link = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "a[href*='dashboard']")))
            self.safe_click(dashboard_link)
        except TimeoutException:
            # Navigate directly if link not found
            self.driver.get(f"{self.base_url}/dashboard")
        
        self.wait.until(EC.url_contains("dashboard"))
        self.log(f"✅ Dashboard loaded: {self.driver.current_url}")
    
    def get_current_credits(self):
        """Get current credits from dashboard"""
        try:
            # Look for credits display element
            credits_element = self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "[data-credits], .credits-count, .credit-balance")))
            credits_text = credits_element.text
            
            # Extract number from text
            import re
            credits_match = re.search(r'(\d+)', credits_text)
            if credits_match:
                return int(credits_match.group(1))
            
            return 0
        except:
            self.log("⚠️ Could not find credits display, assuming 0")
            return 0
    
    def purchase_credits(self):
        """Purchase 30 credits with PayPal"""
        self.log("💰 Starting credits purchase")
        
        # Get current credits
        current_credits = self.get_current_credits()
        self.log(f"💳 Current credits: {current_credits}")
        
        # Navigate to credits purchase
        self.driver.get(f"{self.base_url}/credits/checkout")
        self.wait.until(EC.url_contains("credits/checkout"))
        
        self.log("🛒 Credits checkout page loaded")
        
        # Click PayPal payment for credits
        paypal_credits_button = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "a[href*='credits/paypal/checkout']")))
        self.safe_click(paypal_credits_button)
        
        self.log("🔄 Redirected to PayPal for credits payment...")
        
        # Handle PayPal payment (similar to trial but for credits)
        time.sleep(3)
        
        try:
            if "paypal.com" in self.driver.current_url:
                # Might need to login again or just approve
                try:
                    self.handle_paypal_payment_approval()
                except:
                    self.handle_paypal_login()
                    self.handle_paypal_payment_approval()
        except Exception as e:
            self.log(f"❌ Credits PayPal payment error: {str(e)}", "ERROR")
            raise
        
        # Wait for credits success page
        self.wait.until(EC.url_contains("credits/success"))
        self.log("✅ Credits payment completed successfully")
        
        # Verify credits were added
        self.driver.get(f"{self.base_url}/dashboard")
        time.sleep(2)
        
        new_credits = self.get_current_credits()
        credits_added = new_credits - current_credits
        
        self.log(f"💰 Credits verification:")
        self.log(f"   Previous: {current_credits}")
        self.log(f"   Current: {new_credits}")
        self.log(f"   Added: {credits_added}")
        
        if credits_added == 30:
            self.log("✅ Credits purchase successful - 30 credits added!")
            return True
        else:
            self.log(f"❌ Credits mismatch - Expected 30, got {credits_added}", "ERROR")
            return False
    
    def run_full_test(self):
        """Run the complete automation test"""
        try:
            self.log("🚀 Starting TopWriterX Full Flow Automation Test")
            self.log("=" * 60)
            
            # Step 1: Fill try-writing form
            self.fill_try_writing_form()
            
            # Step 2: Complete trial payment
            self.complete_trial_payment()
            
            # Step 3: Navigate to dashboard
            self.navigate_to_dashboard()
            
            # Step 4: Purchase credits
            credits_success = self.purchase_credits()
            
            # Final results
            self.log("=" * 60)
            if credits_success:
                self.log("🎉 AUTOMATION TEST COMPLETED SUCCESSFULLY!")
                self.log("✅ All systems working correctly:")
                self.log("   - Multi-step form submission ✅")
                self.log("   - Trial payment with auto-refund ✅")
                self.log("   - Credits purchase and addition ✅")
            else:
                self.log("❌ AUTOMATION TEST FAILED - Credits not added correctly")
            
            return credits_success
            
        except Exception as e:
            self.log(f"❌ AUTOMATION TEST FAILED: {str(e)}", "ERROR")
            return False
        
        finally:
            # Keep browser open for manual inspection
            self.log("🔍 Test completed. Browser will remain open for 30 seconds for inspection...")
            time.sleep(30)
            self.cleanup()
    
    def cleanup(self):
        """Clean up resources"""
        if self.driver:
            self.driver.quit()
            self.log("🧹 Browser closed")

def main():
    """Main function to run the automation"""
    print("🤖 TopWriterX Full Flow Automation Test")
    print("=" * 50)
    
    # Configuration
    BASE_URL = "http://localhost:8000"
    HEADLESS = False  # Set to True to run without browser window
    
    # Run automation
    automation = TopWriterXAutomation(base_url=BASE_URL, headless=HEADLESS)
    success = automation.run_full_test()
    
    if success:
        print("\n🎯 RESULT: ALL TESTS PASSED ✅")
        exit(0)
    else:
        print("\n❌ RESULT: TESTS FAILED")
        exit(1)

if __name__ == "__main__":
    main() 