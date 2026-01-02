<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topwriter - Medical Content Writing by Licensed M.D.s | Healthcare Marketing Expert</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"></noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <style>
/* TOPWRITER - OPTIMIZED CSS - Shortened for space */
:root {
    --primary-blue: #3B82F6;
    --secondary-orange: #F59E0B;
    --success-green: #10B981;
    --text-dark: #1F2937;
    --text-light: #6B7280;
    --text-white: #FFFFFF;
    --bg-light: #F9FAFB;
    --bg-white: #FFFFFF;
    --blur: blur(15px);
    --shadow-light: 0 4px 15px rgba(59, 130, 246, 0.15);
    --shadow-heavy: 0 15px 35px rgba(0, 0, 0, 0.1);
    --border-radius: 20px;
    --transition: all 0.2s ease;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    line-height: 1.6;
    color: var(--text-dark);
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* ANIMATIONS */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fillBar { from { width: 0; } }
@keyframes counterUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* HERO SECTION */
.hero {
    min-height: 100vh;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6rem 2rem 4rem;
    color: var(--text-white);
    position: relative;
    text-align: center;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    animation: fadeInUp 1s ease;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: var(--blur);
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-title, .hero h1 {
    font-size: 3.8rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
}

.hero-subtitle {
    font-size: 1.4rem;
    line-height: 1.6;
    margin-bottom: 3rem;
    opacity: 0.95;
}

/* CTA BUTTONS */
.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    padding: 1.2rem 2.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    border: none;
    cursor: pointer;
}

.cta-button.primary {
    background: linear-gradient(135deg, var(--secondary-orange), #FF8C00);
    color: var(--text-white);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
}

.cta-button.primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(245, 158, 11, 0.4);
}

.cta-button.secondary {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
    color: var(--primary-blue);
    border: 2px solid rgba(59, 130, 246, 0.3);
    backdrop-filter: var(--blur);
}

.cta-button.secondary:hover {
    background: linear-gradient(135deg, var(--primary-blue), #2563EB);
    color: var(--text-white);
    transform: translateY(-3px);
}

.cta-button.large {
    padding: 1.5rem 3rem;
    font-size: 1.3rem;
}

/* SECTIONS */
.section {
    padding: 6rem 0;
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header h2 {
    font-size: 2.8rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 1rem;
    line-height: 1.2;
}

.section-header p {
    font-size: 1.2rem;
    color: var(--text-light);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* TESTIMONIALS */
.testimonials {
    padding: 6rem 0;
    background: var(--bg-light);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.testimonial-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 25px;
    border: 1px solid rgba(59, 130, 246, 0.1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.testimonial-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: rgba(59, 130, 246, 0.4);
}

.testimonial-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.client-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.client-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-light);
}

.client-avatar img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.client-details h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.2rem;
}

.client-details p {
    font-size: 0.9rem;
    color: var(--text-light);
}

.rating {
    display: flex;
    gap: 0.2rem;
}

.rating i {
    color: #FFD700;
    font-size: 0.9rem;
}

.testimonial-content {
    margin-bottom: 1.5rem;
}

.testimonial-content p {
    color: var(--text-light);
    line-height: 1.6;
    font-style: italic;
}

.testimonial-results {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.result-metric {
    text-align: center;
}

.metric-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary-blue);
    line-height: 1;
}

.metric-label {
    font-size: 0.8rem;
    color: var(--text-light);
    margin-top: 0.2rem;
}

/* PRICING */
.pricing {
    padding: 6rem 0;
    background: var(--bg-white);
}

.pricing-card {
    max-width: 600px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 3rem;
    border-radius: 25px;
    border: 1px solid rgba(59, 130, 246, 0.1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
    position: relative;
}

.pricing-header {
    margin-bottom: 2rem;
}

.price {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.currency {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-blue);
}

.amount {
    font-size: 4rem;
    font-weight: 800;
    color: var(--primary-blue);
}

.unit {
    font-size: 1.2rem;
    color: var(--text-light);
}

.pricing-badge {
    position: absolute;
    top: -15px;
    right: 20px;
    background: linear-gradient(135deg, var(--secondary-orange), #FF8C00);
    color: var(--text-white);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    transform: rotate(15deg);
}

.pricing-features {
    margin-bottom: 2rem;
    text-align: left;
}

.pricing-features h3 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}

.pricing-features ul {
    list-style: none;
}

.pricing-features li {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
    color: var(--text-light);
}

.pricing-features li i {
    color: var(--success-green);
    font-size: 0.9rem;
}

/* FAQ */
.faq {
    padding: 6rem 0;
    background: var(--bg-light);
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    margin-bottom: 1rem;
    border: 1px solid rgba(59, 130, 246, 0.1);
    transition: var(--transition);
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.faq-item:hover {
    box-shadow: var(--shadow-light);
    border-color: rgba(59, 130, 246, 0.3);
}

.faq-question {
    padding: 1.5rem 2rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
    background: transparent;
}

.faq-question:hover {
    background: rgba(59, 130, 246, 0.05);
}

.faq-question h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.faq-question i {
    color: var(--primary-blue);
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    background: rgba(59, 130, 246, 0.02);
}

.faq-item.active .faq-answer {
    max-height: 300px;
    padding: 0 2rem 1.5rem;
}

.faq-answer p {
    color: var(--text-light);
    line-height: 1.6;
    margin: 0;
}

/* FINAL CTA */
.final-cta {
    padding: 6rem 0;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-orange));
    color: var(--text-white);
    text-align: center;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 3rem;
    opacity: 0.9;
}

.trust-indicators {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
    margin-top: 3rem;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.trust-item i {
    color: #FFD700;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero-title, .hero h1 { font-size: 2.2rem !important; }
    .hero-subtitle { font-size: 1rem !important; }
    .container { padding: 0 1rem; }
    .hero { padding: 5rem 1rem 2rem; }
    .section-header h2 { font-size: 2.2rem; }
    .testimonials-grid { grid-template-columns: 1fr; }
    .trust-indicators { flex-direction: column; gap: 1rem; }
}

@media (max-width: 480px) {
    .hero-title, .hero h1 { font-size: 2rem; }
    .pricing-card { padding: 2rem; }
}
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero">
        <!-- Top Navigation -->
        <div style="position: absolute; top: 0; left: 0; right: 0; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; z-index: 10;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('logo.png') }}" alt="Topwriter Logo" style="height: 40px; width: auto;">
                <span style="font-size: 1.5rem; font-weight: bold; color: white;">Topwriter</span>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" style="padding: 0.8rem 1.5rem; background: rgba(255,255,255,0.1); color: white; text-decoration: none; border-radius: 25px; font-weight: 600; transition: all 0.3s ease; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" style="padding: 0.8rem 1.5rem; background: rgba(255,255,255,0.1); color: white; text-decoration: none; border-radius: 25px; font-weight: 600; transition: all 0.3s ease; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    Login
                </a>
            @endauth
        </div>

        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-star" style="color: #FFD700;"></i>
                Licensed M.D. & M.B.B.S. Medical Writers
            </div>
            
            <h1 class="hero-title">
                Medical Content That <span style="background: linear-gradient(135deg, #FFD700, var(--secondary-orange)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Converts</span>
            </h1>
            
            <p class="hero-subtitle">
                The only content service written by certified doctors with published research on PubMed. Zero legal risk, maximum credibility, proven SEO results.
            </p>
            
            <div style="display: flex; gap: 1.5rem; align-items: center; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
                <a href="{{ route('try-writing') }}" class="cta-button primary large">
                    <i class="fas fa-rocket"></i>
                    GET FREE CREDITS
                </a>
                <a href="#pricing" class="cta-button secondary">
                    <i class="fas fa-play"></i>
                    View Pricing
                </a>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(255, 255, 255, 0.1); backdrop-filter: var(--blur); padding: 0.8rem 1.5rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.3); justify-content: center; margin-bottom: 3rem;">
                <i class="fas fa-shield-check" style="color: #FFD700;"></i>
                100% Money-Back Guarantee | 48-Hour Delivery | 5 Free Revisions
            </div>
            
            <!-- Stats -->
            <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
                <div style="text-align: center; background: rgba(255, 255, 255, 0.15); backdrop-filter: var(--blur); padding: 1.5rem 2rem; border-radius: var(--border-radius); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); min-width: 160px;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #FFD700; line-height: 1; animation: counterUp 0.8s ease;">19+</div>
                    <div style="font-size: 0.9rem; opacity: 0.9; margin-top: 0.5rem;">PubMed Publications</div>
                </div>
                <div style="text-align: center; background: rgba(255, 255, 255, 0.15); backdrop-filter: var(--blur); padding: 1.5rem 2rem; border-radius: var(--border-radius); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); min-width: 160px;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #FFD700; line-height: 1; animation: counterUp 0.8s ease;">98%</div>
                    <div style="font-size: 0.9rem; opacity: 0.9; margin-top: 0.5rem;">Client Satisfaction</div>
                </div>
                <div style="text-align: center; background: rgba(255, 255, 255, 0.15); backdrop-filter: var(--blur); padding: 1.5rem 2rem; border-radius: var(--border-radius); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); min-width: 160px;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #FFD700; line-height: 1; animation: counterUp 0.8s ease;">$0.019</div>
                    <div style="font-size: 0.9rem; opacity: 0.9; margin-top: 0.5rem;">Per Word</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>Real Results from Healthcare Clients</h2>
                <p>Healthcare businesses share their success stories with Topwriter's professional content strategy</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/klarity-health.png') }}" alt="Klarity Health">
                            </div>
                            <div class="client-details">
                                <h4>Klarity Health</h4>
                                <p>Telemedicine Platform</p>
                            </div>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Our article achieved #1 Google ranking for highly specialized keywords. The research quality and scientific accuracy far exceeded our competitors' capabilities."</p>
                    </div>
                    <div class="testimonial-results">
                        <div class="result-metric">
                            <span class="metric-number">#1</span>
                            <span class="metric-label">Google Ranking</span>
                        </div>
                        <div class="result-metric">
                            <span class="metric-number">TOP 1%</span>
                            <span class="metric-label">Scientific Quality</span>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/within-health.png') }}" alt="Within Health">
                            </div>
                            <div class="client-details">
                                <h4>Within Health</h4>
                                <p>Telemedicine Platform</p>
                            </div>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Topwriter's team of licensed physicians helps us establish credibility in telemedicine. Their scientific accuracy and regulatory compliance is unmatched."</p>
                    </div>
                    <div class="testimonial-results">
                        <div class="result-metric">
                            <span class="metric-number">98%</span>
                            <span class="metric-label">Compliance Rate</span>
                        </div>
                        <div class="result-metric">
                            <span class="metric-number">+220%</span>
                            <span class="metric-label">User Trust</span>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/NutritionAdvisor.com.png') }}" alt="NutritionAdvisor">
                            </div>
                            <div class="client-details">
                                <h4>NutritionAdvisor.com</h4>
                                <p>Nutrition Platform</p>
                            </div>
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Their nutritional expertise combined with SEO mastery delivers content that's both scientifically accurate and incredibly effective at attracting qualified leads."</p>
                    </div>
                    <div class="testimonial-results">
                        <div class="result-metric">
                            <span class="metric-number">+450%</span>
                            <span class="metric-label">Qualified Leads</span>
                        </div>
                        <div class="result-metric">
                            <span class="metric-number">120s</span>
                            <span class="metric-label">Avg. Read Time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <h2>Simple, Transparent Pricing</h2>
                <p>No hidden fees, no complex packages. Just professional content at an unbeatable price.</p>
            </div>
            
            <div class="pricing-card">
                <div class="pricing-header">
                    <div class="price">
                        <span class="currency">$</span>
                        <span class="amount">0.019</span>
                        <span class="unit">per word</span>
                    </div>
                    <div class="pricing-badge">M.D. QUALITY</div>
                </div>
                
                <div class="pricing-features">
                    <h3>Everything Included:</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Licensed expert writers</li>
                        <li><i class="fas fa-check"></i> SEO & JSON optimization for AI</li>
                        <li><i class="fas fa-check"></i> Professional medical images</li>
                        <li><i class="fas fa-check"></i> Plagiarism-free guarantee</li>
                        <li><i class="fas fa-check"></i> 5 free content revisions</li>
                        <li><i class="fas fa-check"></i> 48-hour delivery</li>
                        <li><i class="fas fa-check"></i> Direct website posting</li>
                        <li><i class="fas fa-check"></i> Keyword research via Ahrefs Premium</li>
                        <li><i class="fas fa-check"></i> Bonus 1 HTML Landing Page/month</li>
                    </ul>
                </div>
                
                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 15px; margin-bottom: 2rem; font-size: 0.9rem; color: var(--text-dark);">
                    <strong>Example:</strong> 1,000-word article = $19 (Compare: Regular agency $200+ | AI + Medical review = $150+ | Legal risk = Priceless)
                </div>
                
                <a href="{{ route('try-writing') }}" class="cta-button primary large">
                    GET FREE CREDITS
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Everything you need to know about our professional medical writing service.</p>
            </div>
            
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Why choose human writers over AI?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>AI can't grasp industry nuances, build genuine emotional connections, or ensure compliance with medical regulations. Our licensed physicians create high-converting content because they deeply understand healthcare, your patients, and specific business objectives.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do you ensure content quality?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Every physician undergoes medical credential verification, all content is reviewed by multiple licensed doctors, and we provide 5 revisions until you're completely satisfied. Plus, every article comes with a 100% money-back guarantee.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What does the $0.019 per word price include?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Real M.D. & M.B.B.S. team published on PubMed, SEO & JSON optimization for AI, professional images, plagiarism check, 5 revisions, direct website posting, keyword research via Ahrefs Premium, bonus Landing Page HTML, and most importantly - <strong>zero legal risk from medical misinformation</strong>.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do you only work with healthcare?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we focus 100% on Healthcare & Medical Industries. This focus allows us to deliver superior results because our M.D. and M.B.B.S. team are true experts who have published research on PubMed, not generalist writers or AI.</p>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 3rem;">
                <a href="{{ route('try-writing') }}" class="cta-button primary">
                    GET ANSWERS & FREE CREDITS
                </a>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="final-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Transform Your Content Strategy?</h2>
                <p>Create medical content that leverages your business strengths to drive conversions while building unshakeable scientific credibility.</p>
                
                <div style="margin-bottom: 3rem;">
                    <a href="{{ route('try-writing') }}" class="cta-button primary large">
                        START WITH FREE CREDITS
                    </a>
                </div>
                
                <div class="trust-indicators">
                    <div class="trust-item">
                        <i class="fas fa-shield-check"></i>
                        <span>100% Money-Back Guarantee</span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-clock"></i>
                        <span>48-Hour Delivery</span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-sync-alt"></i>
                        <span>5 Free Revisions</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    // FAQ functionality
    document.addEventListener('DOMContentLoaded', () => {
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all other FAQ items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                if (isActive) {
                    item.classList.remove('active');
                } else {
                    item.classList.add('active');
                }
            });
        });
    });
    </script>
</body>
</html> 