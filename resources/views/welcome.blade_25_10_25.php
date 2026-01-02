<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Medical Content Writing by Licensed M.D.s | Healthcare Marketing Expert</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"></noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SVZMS2JP37"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-SVZMS2JP37');
    </script>

    <!-- LinkedIn Insight Tag -->
    <script type="text/javascript">
    _linkedin_partner_id = "7398036";
    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
    window._linkedin_data_partner_ids.push(_linkedin_partner_id);
    </script><script type="text/javascript">
    (function(l) {
    if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
    window.lintrk.q=[]}
    var s = document.getElementsByTagName("script")[0];
    var b = document.createElement("script");
    b.type = "text/javascript";b.async = true;
    b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
    s.parentNode.insertBefore(b, s);})(window.lintrk);
    </script>
    <noscript>
    <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=7398036&fmt=gif" />
    </noscript>

    <style>
/* TOPWRITER - OPTIMIZED SINGLE FILE CSS */
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

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

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

@keyframes fillBar {
    from { width: 0; }
}

@keyframes counterUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* UTILITY CLASSES */
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

.border-glow {
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
}

.border-glow:hover {
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
}

.highlight {
    background: linear-gradient(135deg, #FFD700, var(--secondary-orange));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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

.hero-visual-stat {
    margin: 2rem 0;
}

.comparison-chart {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: fadeInUp 1s ease 0.5s both;
}

.ai-side, .topwriter-side {
    text-align: center;
    flex: 1;
}

.comparison-chart .percentage {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    animation: counterUp 2s ease;
}

.ai-side .percentage {
    color: #ef4444;
}

.topwriter-side .percentage {
    background: linear-gradient(135deg, #FFD700, var(--secondary-orange));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.comparison-chart .label {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--text-white);
}

.bar {
    height: 10px;
    border-radius: 10px;
    margin-top: 1rem;
    position: relative;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.2);
}

.ai-bar {
    width: 20%;
    background: linear-gradient(90deg, #ef4444, #dc2626);
    animation: fillBar 2s ease-out 1s both;
}

.topwriter-bar {
    width: 90%;
    background: linear-gradient(90deg, var(--primary-blue), var(--secondary-orange));
    animation: fillBar 2s ease-out 1.5s both;
}

.vs-divider {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-white);
    background: linear-gradient(45deg, var(--primary-blue), var(--secondary-orange));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    padding: 0 1rem;
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

.hero-cta {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 3rem;
}

.guarantee-box {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: var(--blur);
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-stats {
    display: flex;
    gap: 2rem;
    justify-content: center;
    flex-wrap: wrap;
}

.stat-card {
    text-align: center;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: var(--blur);
    padding: 1.5rem 2rem;
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
    min-width: 160px;
}

.stat-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.25);
    box-shadow: var(--shadow-light);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #FFD700;
    line-height: 1;
    animation: counterUp 0.8s ease;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-top: 0.5rem;
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

/* Enhanced CTA Effects */
.cta-button {
    position: relative;
    overflow: hidden;
}

.cta-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s ease;
    z-index: 1;
}

.cta-button:hover::before {
    left: 100%;
}

.cta-button > * {
    position: relative;
    z-index: 2;
}

.cta-button:hover {
    transform: translateY(-3px) scale(1.02);
}

.cta-button.primary:hover {
    box-shadow: 0 15px 35px rgba(245, 158, 11, 0.5);
}

.cta-button.secondary:hover {
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.3);
}

/* Login button hover */
.hero a[href*="login"]:hover {
    background: rgba(255,255,255,0.2) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,255,255,0.2);
}

/* Load More Button Hover Effect */
#load-more-btn:hover {
    background: linear-gradient(135deg, #5a67d8, #667eea) !important;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4) !important;
}

/* SECTIONS */
.intro {
    padding: 6rem 0;
    background: var(--bg-light);
}

.problems {
    padding: 6rem 0;
    background: var(--bg-white);
}

.solutions {
    padding: 6rem 0;
    background: var(--bg-light);
}

.problems-grid, .solutions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.problem-card, .solution-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 2.5rem 2rem;
    border-radius: 25px;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(59, 130, 246, 0.1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.problem-card::before,
.solution-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.05), transparent);
    transition: left 0.5s ease;
    z-index: 1;
}

.problem-card:hover::before,
.solution-card:hover::before {
    left: 100%;
}

.problem-card:hover, .solution-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: rgba(59, 130, 246, 0.4);
    background: rgba(255, 255, 255, 1);
}

.problem-card > *,
.solution-card > * {
    position: relative;
    z-index: 2;
}

.problem-icon, .solution-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border-radius: 50%;
    position: relative;
    overflow: hidden;
}

.problem-icon {
    color: var(--primary-blue);
    background: rgba(59, 130, 246, 0.1);
}

.solution-icon {
    color: var(--success-green);
    background: rgba(16, 185, 129, 0.1);
}

.problem-card:hover .problem-icon,
.solution-card:hover .solution-icon {
    transform: scale(1.2) rotate(5deg);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.problem-card h3, .solution-card h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.problem-card p, .solution-card p {
    color: var(--text-light);
    line-height: 1.6;
}

.problems-cta, .solutions-cta {
    text-align: center;
    margin-top: 3rem;
}

/* SPECIALIZATION */
.specialization {
    padding: 6rem 0;
    background: var(--bg-white);
}

.specialization-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}

.spec-card {
    background: rgba(255, 255, 255, 0.9);
    padding: 2.5rem;
    border-radius: 25px;
    transition: var(--transition);
    border: 2px solid transparent;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
}

.spec-card.healthcare {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
    border-color: rgba(59, 130, 246, 0.2);
}

.spec-card:hover {
    transform: translateY(-15px) scale(1.03);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.18);
}

.spec-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2.5rem;
    color: var(--text-white);
    transition: var(--transition);
}

.spec-card.healthcare .spec-icon {
    background: linear-gradient(135deg, var(--primary-blue), #2563EB);
}

.spec-card:hover .spec-icon {
    transform: scale(1.1);
}

.spec-card h3 {
    font-size: 1.8rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}

.spec-card ul {
    list-style: none;
    margin-bottom: 2rem;
}

.spec-card li {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
    color: var(--text-light);
}

.spec-card li i {
    color: var(--success-green);
    font-size: 0.9rem;
}

.spec-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--success-green), #059669);
    color: var(--text-white);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.specialization-cta {
    text-align: center;
    margin-top: 3rem;
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
    margin-bottom: 3rem;
}

.testimonial-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 25px;
    transition: var(--transition);
    border: 1px solid rgba(59, 130, 246, 0.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.testimonial-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    border-color: rgba(59, 130, 246, 0.4);
    background: rgba(255, 255, 255, 1);
}

.testimonial-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
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
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-orange));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-white);
    font-size: 1.2rem;
    overflow: hidden;
    position: relative;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
    transition: all 0.3s ease;
}

.client-avatar img {
    width: 36px !important;
    height: 36px !important;
    border-radius: 50%;
    object-fit: contain;
    object-position: center;
}

.client-avatar {
    background: white !important;
    border: 2px solid rgba(59, 130, 246, 0.1);
}

.client-avatar:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    border-color: rgba(59, 130, 246, 0.3);
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
    margin: 0;
}

.rating {
    display: flex;
    gap: 0.2rem;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: flex-end;
    min-width: 110px;
}

.rating i {
    color: #FFD700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.testimonial-content {
    margin-bottom: 1.5rem;
}

.testimonial-content p {
    font-style: italic;
    color: var(--text-dark);
    line-height: 1.6;
    font-size: 1rem;
    margin: 0;
}

.testimonial-results {
    display: flex;
    gap: 2rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(59, 130, 246, 0.1);
}

.result-metric {
    text-align: center;
    flex: 1;
}

.result-metric .metric-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success-green);
    margin-bottom: 0.2rem;
}

.result-metric .metric-label {
    font-size: 0.8rem;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* PRICING */
.pricing {
    padding: 6rem 0;
    background: var(--bg-light);
}

.pricing-card {
    max-width: 500px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 3rem;
    border-radius: 30px;
    text-align: center;
    border: 2px solid rgba(59, 130, 246, 0.1);
    transition: var(--transition);
    position: relative;
    box-shadow: var(--shadow-light);
}

.pricing-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 30px 60px rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.5);
}

.pricing-header {
    margin-bottom: 2rem;
    position: relative;
}

.price {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.currency {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-light);
}

.amount {
    font-size: 4rem;
    font-weight: 900;
    color: var(--primary-blue);
}

.unit {
    font-size: 1.2rem;
    color: var(--text-light);
}

.pricing-badge {
    position: absolute;
    top: -1rem;
    right: -1rem;
    background: linear-gradient(135deg, var(--secondary-orange), #D97706);
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

.pricing-example {
    background: rgba(59, 130, 246, 0.05);
    padding: 1rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    font-size: 0.9rem;
    color: var(--text-dark);
}

/* FAQ */
.faq {
    padding: 6rem 0;
    background: var(--bg-white);
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

.faq-cta {
    text-align: center;
    margin-top: 3rem;
}

/* Journal logos styling */
.journal-logo {
    background: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    font-weight: bold;
    color: #1F2937;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.journal-logo:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.journal-logo::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
    transition: left 0.5s ease;
}

.journal-logo:hover::before {
    left: 100%;
}

.expert-highlight {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 15px;
    padding: 1.5rem;
    margin: 2rem 0;
    text-align: center;
}

.expert-highlight h4 {
    color: var(--primary-blue);
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 700;
}

.expert-highlight p {
    color: var(--text-dark);
    font-style: italic;
    line-height: 1.6;
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

.cta-buttons {
    margin-bottom: 3rem;
}

.trust-indicators {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
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
    .hero div[style*="position: absolute"] {
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        margin-bottom: 1rem;
        padding: 0.5rem 0;
    }

    .hero div[style*="position: absolute"] img {
        height: 32px !important;
    }

    .hero div[style*="position: absolute"] span {
        font-size: 1rem !important;
    }

    .hero a[href*="login"] {
        padding: 0.6rem 1rem !important;
        font-size: 0.9rem !important;
    }

    .hero-content {
        margin-top: 0.5rem !important;
    }

    .hero-title, .hero h1 {
        font-size: 2.2rem !important;
    }

    .hero-subtitle {
        font-size: 1rem !important;
    }

    .hero-content > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }

    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }

    .hero-cta {
        flex-direction: column;
        gap: 1rem;
    }

    .comparison-chart {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem !important;
    }

    .comparison-chart .percentage {
        font-size: 2rem !important;
    }

    .vs-divider {
        transform: rotate(90deg);
        margin: 0.5rem 0;
        font-size: 1rem !important;
    }

    .section-header h2 {
        font-size: 2.2rem;
    }

    .problems-grid,
    .solutions-grid {
        grid-template-columns: 1fr;
    }

    .specialization-grid {
        grid-template-columns: 1fr;
    }

    .trust-indicators {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 1rem;
    }

    .hero {
        padding: 5rem 1rem 2rem;
    }

    .hero-title, .hero h1 {
        font-size: 2rem;
    }

    .pricing-card {
        padding: 2rem;
    }

    .spec-card {
        padding: 2rem;
    }
}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- JSON-LD Structured Data for AI Understanding -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "MedicalBusiness",
        "name": "Topwriter - Medical Content Writing Service",
        "alternateName": ["Topwriter", "TopWriter Medical Content"],
        "url": "https://topwriter.co",
        "logo": "https://topwriter.co/logo.png",
        "image": "https://topwriter.co/logo.png",
        "description": "Premium medical content writing service by certified M.B.B.S. and M.D. doctors with published research on PubMed. Specializing in scientifically accurate healthcare content with proven SEO results for US healthcare market.",
        "foundingDate": "2020",
        "founder": {
            "@type": "Person",
            "name": "Medical Writing Team",
            "description": "Team of certified medical doctors and PhDs"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.95",
            "reviewCount": "847",
            "bestRating": "5",
            "worstRating": "4.2"
        },
        "priceRange": "$0.019 per word",
        "paymentAccepted": ["Credit Card", "PayPal", "Bank Transfer"],
        "currenciesAccepted": "USD",
        "serviceArea": {
            "@type": "Place",
            "name": "United States",
            "description": "Comprehensive medical content writing services for US healthcare market"
        },
        "areaServed": ["United States", "Canada", "United Kingdom", "Australia"],
        "availableLanguage": ["English"],
        "hasCredential": [
            {
                "@type": "EducationalOccupationalCredential",
                "name": "M.B.B.S. (Bachelor of Medicine, Bachelor of Surgery)",
                "credentialCategory": "Medical Degree",
                "recognizedBy": {
                    "@type": "Organization",
                    "name": "International Medical Councils"
                }
            },
            {
                "@type": "EducationalOccupationalCredential",
                "name": "M.D. (Doctor of Medicine)",
                "credentialCategory": "Medical Degree",
                "recognizedBy": {
                    "@type": "Organization",
                    "name": "American Medical Association"
                }
            }
        ],
        "speciality": [
            "Medical Content Writing",
            "Healthcare Communications",
            "Clinical Documentation",
            "Medical SEO",
            "Pharmaceutical Content",
            "Medical Device Documentation",
            "Patient Education Materials",
            "Medical Research Writing",
            "Healthcare Marketing Content",
            "Telemedicine Content"
        ],
        "medicalSpecialty": [
            "Internal Medicine",
            "Cardiology",
            "Neurology",
            "Oncology",
            "Pediatrics",
            "Women's Health",
            "Reproductive Health",
            "Public Health",
            "Infectious Diseases",
            "Respiratory Medicine"
        ],
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Medical Writing Services",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Medical Content Writing",
                        "description": "Professional medical content by certified US-licensed doctors"
                    },
                    "price": "0.019",
                    "priceCurrency": "USD",
                    "priceSpecification": {
                        "@type": "UnitPriceSpecification",
                        "price": "0.019",
                        "priceCurrency": "USD",
                        "unitText": "per word"
                    },
                    "availability": "InStock",
                    "deliveryTime": "48 hours",
                    "warranty": {
                        "@type": "WarrantyPromise",
                        "durationOfWarranty": "P30D",
                        "warrantee": {
                            "@type": "Organization",
                            "name": "Topwriter"
                        }
                    }
                }
            ]
        },
        "publishingPrinciples": "All medical content based on peer-reviewed research from PubMed, NIH, CDC, FDA, and other authoritative US medical sources. Content reviewed by licensed medical professionals before publication.",
        "ethicsPolicy": "Strict adherence to FDA regulations, HIPAA compliance, and US medical ethics guidelines. No misleading medical claims. All content fact-checked by medical professionals.",
        "correctionsPolicy": "5 free revisions included. 100% money-back guarantee if not satisfied.",
        "hasProductReturnPolicy": {
            "@type": "ProductReturnPolicy",
            "returnPolicyCategory": "MoneyBackGuarantee",
            "merchantReturnDays": 30,
            "returnFees": "Free"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer Service",
            "url": "https://topwriter.co/contact",
            "availableLanguage": ["English"]
        },
        "potentialAction": {
            "@type": "OrderAction",
            "target": "https://topwriter.co/try-writing?ref=expert-md-content",
            "name": "Order Medical Content Writing"
        },
        "inLanguage": "en-US",
        "datePublished": "2024-01-01",
        "dateModified": "2024-12-19",
        "additionalProperty": [
            {
                "@type": "PropertyValue",
                "name": "FDA Compliant",
                "value": "100% FDA regulation compliant content"
            },
            {
                "@type": "PropertyValue",
                "name": "HIPAA Compliant",
                "value": "Patient privacy protected per HIPAA guidelines"
            },
            {
                "@type": "PropertyValue",
                "name": "PubMed Publications",
                "value": "19+ peer-reviewed publications by team"
            },
            {
                "@type": "PropertyValue",
                "name": "Success Rate",
                "value": "98% client satisfaction rate"
            }
        ],
        "knowsAbout": [
            "FDA Regulations",
            "HIPAA Compliance",
            "Medical Writing",
            "Clinical Research",
            "Healthcare SEO",
            "Medical Ethics",
            "Patient Education",
            "Medical Device Documentation",
            "Pharmaceutical Content",
            "Telemedicine",
            "Digital Health",
            "Medical Marketing",
            "Evidence-Based Medicine",
            "Public Health",
            "Medical Communications"
        ]
    }
    </script>
    <!-- Font Awesome Kit Loader (Official) -->
    <script src="https://kit.fontawesome.com/2b8e1e1e8e.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero" style="min-height: 100vh; padding: 1.5rem 2rem 4rem;">
        <div class="container">
            <!-- Header with Logo and Login -->
            <div style="position: absolute; top: 0.5rem; left: 1.5rem; right: 1.5rem; display: flex; justify-content: space-between; align-items: center; z-index: 100;">
                <div style="display: flex; align-items: center; gap: 0.8rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); padding: 0.6rem 1.2rem; border-radius: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.3);">
                    <img src="{{ asset('logo.png') }}" alt="Topwriter Logo" style="height: 32px; width: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                    <span style="color: #1F2937; font-weight: 700; font-size: 1.1rem; text-shadow: none;">Topwriter</span>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); color: #1F2937; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(15px); color: #1F2937; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth
            </div>

            <div class="hero-content" style="margin-top: 2rem;">
                <div class="hero-badge shimmer-text" style="margin-bottom: 1rem;">
                    <i class="fas fa-star"></i>
                    Licensed M.D. & M.B.B.S. Team | Published on PubMed (NIH - US Government)
                </div>

                <h1 class="hero-title" style="font-size: 3.2rem; line-height: 1.1; margin-bottom: 0.8rem;">
                    Medical Content by <span class="highlight">Real Doctors</span><br>
                    Published in Oxford, Wiley & PubMed
                </h1>

                <p class="hero-subtitle" style="font-size: 1.1rem; margin-bottom: 1rem;">
                    When precision matters most in healthcare, choose our team of licensed physicians and medical doctors -
                    published researchers in Oxford Medical Case Reports, Wiley Clinical journals, and officially indexed on PubMed (NIH).
                </p>

                <!-- Compact Layout: CTA + Stats in one row -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; margin-bottom: 1rem;">
                    <!-- Left: CTA Buttons -->
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button primary large cta-sparkle">
                            <i class="fas fa-feather-alt" style="font-size: 1.2em; margin-right: 0.5rem;"></i>
                            GET YOUR PROFESSIONAL DEMO ARTICLE NOW
                        </a>
                        <div style="display: flex; gap: 1rem;">
                            <div class="guarantee-box border-glow" style="font-size: 0.8rem; padding: 0.6rem 1rem;">
                                <i class="fas fa-shield-alt"></i>
                                <span>100% Money Back</span>
                            </div>
                            <div class="guarantee-box border-glow" style="font-size: 0.8rem; padding: 0.6rem 1rem;">
                                <i class="fas fa-shipping-fast"></i>
                                <span>48H Delivery</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Stats -->
                    <div class="hero-stats" style="display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                        <div class="stat-card backdrop-blur border-glow" style="min-width: auto; padding: 1rem;">
                            <div class="stat-number" data-count="120" style="font-size: 2rem;">120+</div>
                            <div class="stat-label" style="font-size: 0.8rem;">Avg Reading Seconds</div>
                        </div>
                        <div style="display: flex; gap: 1rem;">
                            <div class="stat-card backdrop-blur border-glow" style="min-width: auto; padding: 1rem; flex: 1;">
                                <a href="#scientific-portfolio" style="text-decoration: none; color: inherit;">
                                    <div class="stat-number" data-count="19" style="font-size: 1.8rem;">19+</div>
                                    <div class="stat-label" style="font-size: 0.8rem;">PubMed</div>
                                </a>
                            </div>
                            <div class="stat-card backdrop-blur border-glow" style="min-width: auto; padding: 1rem; flex: 1;">
                                <a href="#case-studies" style="text-decoration: none; color: inherit;">
                                    <div class="stat-number" data-count="100" style="font-size: 1.8rem;">100+</div>
                                    <div class="stat-label" style="font-size: 0.8rem;">Success Stories</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compact Comparison Chart -->
                <div class="hero-visual-stat">
                    <div class="comparison-chart" style="padding: 1.5rem; margin: 0.5rem 0;">
                        <div class="ai-side">
                            <div class="percentage" style="font-size: 2.5rem;">AI</div>
                            <div class="label" style="font-size: 1rem;">AI-Generated Content</div>
                            <div style="font-size: 0.8rem; margin-top: 0.3rem; opacity: 0.8;">
                                ❌ AI Hallucinations<br>
                                ❌ No M.D. Credentials<br>
                                ❌ Serious Legal Risks
                            </div>
                        </div>
                        <div class="vs-divider" style="font-size: 1.2rem;">VS</div>
                        <div class="topwriter-side">
                            <div class="percentage highlight" style="font-size: 2.5rem;">M.D.</div>
                            <div class="label" style="font-size: 1rem;"><strong>Topwriter</strong> Doctors</div>
                            <div style="font-size: 0.8rem; margin-top: 0.3rem; opacity: 0.9;">
                                ✅ Published on PubMed (NIH)<br>
                                ✅ M.B.B.S., M.D. Credentials<br>
                                ✅ 100% Medical Law Compliant
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problems Section -->
    <section class="problems">
        <div class="container">
            <div class="section-header">
                <h2>6 Critical Problems Healthcare Businesses Face</h2>
                <p>Healthcare companies struggle with content that doesn't connect, doesn't convert, and doesn't meet industry standards - potentially leading to serious legal and compliance issues.</p>
            </div>

            <div class="problems-grid">
                <div class="problem-card backdrop-blur border-glow">
                    <div class="problem-icon">
                        <i class="fas fa-heart-broken"></i>
                    </div>
                    <h3>Cold, Disconnected Medical Content</h3>
                    <p>Generic content fails to create the emotional connection patients need when making critical healthcare decisions about their lives and families.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow">
                    <div class="problem-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3>Medically Inaccurate Information</h3>
                    <p>In healthcare, one wrong word can impact lives. You cannot trust AI or non-medical writers with content that affects patient safety and clinical decisions.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow">
                    <div class="problem-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Poor SEO & AI Search Readiness</h3>
                    <p>Content that doesn't rank, isn't found, and isn't prepared for the future when patients search for medical information through AI assistants.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow">
                    <div class="problem-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Brand-Audience Mismatch</h3>
                    <p>Content that doesn't align with your brand voice or speak directly to your target market's specific needs and healthcare concerns.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow">
                    <div class="problem-icon">
                        <i class="fas fa-copyright"></i>
                    </div>
                    <h3>Image Copyright Issues</h3>
                    <p>Using generic stock photos everyone else uses, or worse - facing legal issues from using unlicensed medical imagery and graphics.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow">
                    <div class="problem-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Trust & Credibility Crisis</h3>
                    <p>In healthcare, trust is everything. Poor quality content or medical misinformation destroys credibility instantly and can lead to severe legal consequences.</p>
                </div>
            </div>

            <div class="problems-cta">
                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button secondary">
                    SOLVE THESE PROBLEMS NOW
                </a>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section class="solutions">
        <div class="container">
            <div class="section-header">
                <h2>Our Comprehensive Solution System</h2>
                <p>Every piece of medical content we create is designed by our M.D. and M.B.B.S. team to solve specific industry challenges and deliver measurable results - without legal risks.</p>
            </div>

            <div class="solutions-grid">
                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Credentialed Medical Team</h3>
                    <p>Not just published - we've appeared on PubMed (NIH - US Government). Our team holds M.B.B.S., M.D., and specialized credentials in medicine, pharmacy, and healthcare.</p>
                </div>

                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Emotionally Engaging Medical Content</h3>
                    <p>Content that connects with patients on an emotional level, builds trust, and encourages action through empathy and understanding.</p>
                </div>

                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>7+ Years SEO Expertise</h3>
                    <p>Deep understanding of Google algorithms, comprehensive on-page/off-page optimization, advanced keyword research, and building sustainable SEO strategies for each specific industry.</p>
                </div>

                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI Search Ready + JSON Schema</h3>
                    <p>We write additional JSON Schema - the native language of AI. AI is most careful with medical information, sources with proper JSON are prioritized because they help AI provide accurate answers and avoid misinformation.</p>
                </div>

                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Bonus HTML Landing Page Monthly</h3>
                    <p>Beyond content, you receive 1 professionally designed HTML page like a landing page every month as a bonus.</p>
                </div>

                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Competitive Differentiation & Legal Safety</h3>
                    <p>Content that clearly differentiates you from competitors and highlights your unique value proposition. Most importantly: zero legal risk from medical misinformation.</p>
                </div>

                <div class="solution-card backdrop-blur border-glow">
                    <div class="solution-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Professional Licensed Images</h3>
                    <p>High-quality, licensed medical imagery that supports your content and enhances your brand's professional appearance.</p>
                </div>
            </div>

            <div class="solutions-cta">
                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button primary">
                    START YOUR SUCCESS STORY
                </a>
            </div>
        </div>
    </section>

    <!-- AI Future Section -->
    <section class="specialization">
        <div class="container">
            <div class="section-header">
                <h2>Not Just SEO Experts - Ready for the AI Search Era</h2>
                <p>With over 7 years of deep SEO expertise, we understand that while today is still the SEO era, the future belongs to AI Search. When patients search for medical information through AI, only sources with proper JSON Schema will be trusted and prioritized by AI systems.</p>
            </div>

            <div class="specialization-grid">
                <div class="spec-card healthcare backdrop-blur border-glow">
                    <div class="spec-icon">
                        <i class="fas fa-search-plus"></i>
                    </div>
                    <h3>Current: Google SEO Masters</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> 7+ years optimizing Google Search</li>
                        <li><i class="fas fa-check"></i> Deep algorithm & ranking factors understanding</li>
                        <li><i class="fas fa-check"></i> Advanced keyword research via Ahrefs Premium</li>
                        <li><i class="fas fa-check"></i> Google Ads VIP account with exclusive privileges</li>
                        <li><i class="fas fa-check"></i> Precise keyword planning for each industry</li>
                        <li><i class="fas fa-check"></i> Professional on-page & technical SEO</li>
                        <li><i class="fas fa-check"></i> <a href="#case-studies" style="color: var(--primary-blue); text-decoration: none;">🔗 See Real Case Studies Below</a></li>
                    </ul>
                    <div class="spec-badge">SEO Expert</div>
                </div>

                <div class="spec-card healthcare backdrop-blur border-glow">
                    <div class="spec-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Future: AI Search Ready</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> JSON is the native language of AI</li>
                        <li><i class="fas fa-check"></i> AI understands complete information in one reading</li>
                        <li><i class="fas fa-check"></i> No "guessing" or extracting like free text</li>
                        <li><i class="fas fa-check"></i> Reduces errors and data filtering steps</li>
                        <li><i class="fas fa-check"></i> AI trusts due to high accuracy</li>
                        <li><i class="fas fa-check"></i> Quickly becomes verified knowledge base</li>
                        <li><i class="fas fa-check"></i> Prepares your brand for AI medical search era</li>
                    </ul>
                    <div class="spec-badge">AI-Ready</div>
                </div>
            </div>

            <div class="specialization-cta">
                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button secondary">
                    PREPARE FOR AI FUTURE
                </a>
            </div>
        </div>
    </section>

    <!-- Healthcare Specialization -->
    <section class="intro">
        <div class="container">
            <div class="section-header">
                <h2>Scientifically Proven Medical Expertise</h2>
                <p>We focus 100% on Healthcare & Medical Industries - where we have real M.D. and M.B.B.S. doctors who have proven their capabilities through research published on PubMed.</p>
            </div>

            <div class="specialization-grid">
                <div class="spec-card healthcare backdrop-blur border-glow" style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto;">
                    <div class="spec-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Healthcare & Medical - Exclusive Expertise</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                        <ul style="margin: 0;">
                            <li><i class="fas fa-check"></i> Hospital & clinic content</li>
                            <li><i class="fas fa-check"></i> Patient education materials</li>
                            <li><i class="fas fa-check"></i> Medical technology & devices</li>
                            <li><i class="fas fa-check"></i> Aesthetic & cosmetic services</li>
                            <li><i class="fas fa-check"></i> Sports medicine & nutrition</li>
                        </ul>
                        <ul style="margin: 0;">
                            <li><i class="fas fa-check"></i> Pharmaceutical content</li>
                            <li><i class="fas fa-check"></i> Telemedicine platforms</li>
                            <li><i class="fas fa-check"></i> Clinical research</li>
                            <li><i class="fas fa-check"></i> Medical device documentation</li>
                            <li><i class="fas fa-check"></i> Healthcare compliance writing</li>
                        </ul>
                    </div>
                    <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <div class="spec-badge">FDA Compliant</div>
                        <div class="spec-badge" style="background: linear-gradient(135deg, var(--primary-blue), #2563EB);">M.D. & M.B.B.S. Certified</div>
                        <div class="spec-badge" style="background: linear-gradient(135deg, var(--secondary-orange), #D97706);">PubMed Published</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expert Team Section -->
    <section class="problems">
        <div class="container">
            <div class="section-header">
                <h2>Meet Our Licensed Medical Experts</h2>
                <p>When engaging in medical communications, we, as licensed physicians and PhDs with actual clinical experience and significant publication records in peer-reviewed journals, are confident in our ability to ensure scientific accuracy, clarity, and integrity in health communications.</p>
            </div>

            <div class="problems-grid">
                <div class="problem-card backdrop-blur border-glow" style="text-align: left; padding: 2rem;">
                    <div style="border-left: 4px solid var(--primary-blue); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem; color: var(--primary-blue);">Dr. Sandip Kuikel, M.B.B.S.</h3>
                        <div style="font-size: 0.9rem; color: var(--text-light); font-weight: 600;">Clinical Case Reports & Systematic Reviews Specialist</div>
                    </div>
                    <p>Licensed physician with actual clinical experience and significant publication record in peer-reviewed journals. Specialist in Clinical Case Reports and Systematic Reviews with 19+ PubMed publications.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow" style="text-align: left; padding: 2rem;">
                    <div style="border-left: 4px solid var(--success-green); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem; color: var(--success-green);">Dr. Omar Cisse Ochoa</h3>
                        <div style="font-size: 0.9rem; color: var(--text-light); font-weight: 600;">Ph.D. Biology | Evidence-Based Medical Writer</div>
                    </div>
                    <p>Ph.D. in Biology from Spain. Articles are always evidence-based from PubMed, WHO, CDC, and adjusted to make medical information clear, accessible, and trustworthy for patients.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow" style="text-align: left; padding: 2rem;">
                    <div style="border-left: 4px solid var(--secondary-orange); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem; color: var(--secondary-orange);">Dr. Promise Oladejo, M.B.B.S.</h3>
                        <div style="font-size: 0.9rem; color: var(--text-light); font-weight: 600;">Bachelor of Surgery | Clinical Medicine Expert</div>
                    </div>
                    <p>Bachelor of Surgery (MBBS) with deep expertise in clinical medicine. Multiple articles achieving high Google rankings for competitive medical keywords with proven patient engagement.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow" style="text-align: left; padding: 2rem;">
                    <div style="border-left: 4px solid var(--primary-blue); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem; color: var(--primary-blue);">Dr. Ijeoma Adiele</h3>
                        <div style="font-size: 0.9rem; color: var(--text-light); font-weight: 600;">Applied Biochemistry | Medical SEO Specialist</div>
                    </div>
                    <p>Applied Biochemistry degree with 3 years of medical content writing experience. Expert at combining medical expertise with proven SEO strategies, creating content that's both accurate and engaging.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow" style="text-align: left; padding: 2rem;">
                    <div style="border-left: 4px solid var(--success-green); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem; color: var(--success-green);">Dr. Luyando Moonze</h3>
                        <div style="font-size: 0.9rem; color: var(--text-light); font-weight: 600;">Women's Health & Reproductive Endocrinology</div>
                    </div>
                    <p>Medical expert focused on women's health and reproductive endocrinology. Multiple highly-rated articles on PCOS, cervical cancer, and maternal health with strong patient connection.</p>
                </div>

                <div class="problem-card backdrop-blur border-glow" style="text-align: left; padding: 2rem;">
                    <div style="border-left: 4px solid var(--secondary-orange); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem; color: var(--secondary-orange);">Dr. Naeem Bukhari</h3>
                        <div style="font-size: 0.9rem; color: var(--text-light); font-weight: 600;">PubMed Co-Author | Clinical Research</div>
                    </div>
                    <p>Co-author of medical research officially archived on PubMed - the medical research database of the U.S. National Library of Medicine (NIH).</p>
                </div>
            </div>

            <div class="problems-cta">
                <p style="text-align: center; font-style: italic; color: var(--text-light); margin-bottom: 2rem;">
                    "We take pride in helping bridge the gap between medical science and public understanding, ensuring that content shared with audiences is both trustworthy and engaging."
                </p>
                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button secondary">
                    MEET YOUR EXPERT
                </a>
            </div>
        </div>
    </section>

    <!-- Scientific Portfolio Section -->
    <section id="scientific-portfolio" class="intro">
        <div class="container">
            <div class="section-header">
                <h2>Internationally Recognized Scientific Portfolio</h2>
                <p>Throughout our careers, we have authored and co-authored numerous articles, research studies, and case reports. Many of these have been published in internationally recognized journals and cited by other leading publications. Here are some representative publications:</p>
            </div>

            <!-- Journal Logos -->
            <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 3rem; flex-wrap: wrap; align-items: center;">
                <div class="journal-logo">Oxford Medical Case Reports</div>
                <div class="journal-logo">Wiley Clinical Case Reports</div>
                <div class="journal-logo">American Journal of Infection Control</div>
                <div class="journal-logo">Annals of Medicine & Surgery</div>
                <div class="journal-logo">PubMed (NIH - USA Gov)</div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(15px); border-radius: 25px; padding: 2.5rem; margin-top: 3rem; border: 1px solid rgba(59, 130, 246, 0.1); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);">
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--primary-blue); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-flask"></i> Published Scientific Research</h3>

                <!-- Compact Research Showcase -->
                <div style="background: rgba(255, 255, 255, 0.95); border-radius: 20px; padding: 2rem; margin-top: 2rem; border: 1px solid rgba(59, 130, 246, 0.1);">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">

                        <!-- Systematic Reviews Column -->
                        <div style="border-left: 4px solid var(--success-green); padding-left: 1rem;">
                            <h4 style="color: var(--success-green); margin-bottom: 1rem; font-size: 1.1rem;">Systematic Reviews & Meta-Analyses</h4>
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div style="background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1016/j.ajic.2022.06.001" target="_blank" style="color: var(--text-dark);">MRSA Among Healthcare Workers in South Asia</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>American Journal of Infection Control</em> (IF: 4.4) | <a href="https://pubmed.ncbi.nlm.nih.gov/35697125/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 35697125</a><br>
                                        <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--success-green), #059669); border-radius: 50%; margin-right: 0.5rem; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);"></span>Meta-analysis of 12 studies, 8,247 healthcare workers
                                    </div>
                                </div>
                                <div style="background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1155/2021/9961610" target="_blank" style="color: var(--text-dark);">Stroke Prevalence in Asian Sickle Cell Patients</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Neurology Research International</em> | <a href="https://pubmed.ncbi.nlm.nih.gov/34150339/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 34150339</a><br>
                                        <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--success-green), #059669); border-radius: 50%; margin-right: 0.5rem; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);"></span>Comprehensive systematic review across 15 Asian countries
                                    </div>
                                </div>
                                <div style="background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1111/crj.13568" target="_blank" style="color: var(--text-dark);">COVID-19 Anticoagulation: RCT Systematic Review</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Clinical Respiratory Journal</em> (Wiley) | <a href="https://pubmed.ncbi.nlm.nih.gov/36572657/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 36572657</a><br>
                                        <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--success-green), #059669); border-radius: 50%; margin-right: 0.5rem; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);"></span>Evidence synthesis from 8 major randomized controlled trials
                                    </div>
                                </div>
                                <div style="background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1002/hsr2.630" target="_blank" style="color: var(--text-dark);">Neutrophil-Lymphocyte Ratio in Pneumonia</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Health Science Reports</em> (Wiley) | <a href="https://pubmed.ncbi.nlm.nih.gov/35509390/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 35509390</a><br>
                                        <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--success-green), #059669); border-radius: 50%; margin-right: 0.5rem; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);"></span>Prognostic biomarker validation across 2,847 patients
                                    </div>
                                </div>
                                <div style="background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1016/j.amsu.2022.104293" target="_blank" style="color: var(--text-dark);">Pregnancy with Heart Disease Meta-Analysis</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Annals of Medicine and Surgery</em> | <a href="https://pubmed.ncbi.nlm.nih.gov/36045771/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 36045771</a><br>
                                        <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--success-green), #059669); border-radius: 50%; margin-right: 0.5rem; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);"></span>Maternal outcomes analysis: 15 studies, 4,523 pregnancies
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clinical Cases & Trials Column -->
                        <div style="border-left: 4px solid var(--primary-blue); padding-left: 1rem;">
                            <h4 style="color: var(--primary-blue); margin-bottom: 1rem; font-size: 1.1rem;">Clinical Cases & Randomized Trials</h4>
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1093/omcr/omae055" target="_blank" style="color: var(--text-dark);">Severe Hypophosphatemia in Cannabis Hyperemesis</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Oxford Medical Case Reports</em> (IF: 1.1) | <a href="https://pubmed.ncbi.nlm.nih.gov/38860017/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 38860017</a><br>
                                        <i class="fas fa-file-medical" style="color: var(--primary-blue);"></i> Rare electrolyte complication - First documented case
                                    </div>
                                </div>
                                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1002/ccr3.8677" target="_blank" style="color: var(--text-dark);">Rowell's Syndrome: SLE + Erythema Multiforme</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Wiley Clinical Case Reports</em> (IF: 1.2) | <a href="https://pubmed.ncbi.nlm.nih.gov/38550727/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 38550727</a><br>
                                        <i class="fas fa-file-medical" style="color: var(--primary-blue);"></i> Autoimmune-dermatologic overlap syndrome documentation
                                    </div>
                                </div>
                                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1097/MS9.0000000000001919" target="_blank" style="color: var(--text-dark);">Pediatric Analgesia: Double-Blind RCT</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Annals of Medicine & Surgery</em> (IF: 1.8) | <a href="https://pubmed.ncbi.nlm.nih.gov/38576959/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 38576959</a><br>
                                        <i class="fas fa-file-medical" style="color: var(--primary-blue);"></i> Caudal ropivacaine + sedative agent efficacy study
                                    </div>
                                </div>
                                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1155/2022/8487737" target="_blank" style="color: var(--text-dark);">Pediatric Cushing from Topical Steroid Overuse</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Case Reports in Endocrinology</em> | <a href="https://pubmed.ncbi.nlm.nih.gov/35444835/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 35444835</a><br>
                                        <i class="fas fa-file-medical" style="color: var(--primary-blue);"></i> Clinical safety warning - Unsupervised steroid use
                                    </div>
                                </div>
                                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1159/000523770" target="_blank" style="color: var(--text-dark);">Delayed Visual Loss Post-Snake Bite</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Case Reports in Neurology</em> (Karger) | <a href="https://pubmed.ncbi.nlm.nih.gov/35530377/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 35530377</a><br>
                                        <i class="fas fa-file-medical" style="color: var(--primary-blue);"></i> Rare neuro-ophthalmic complication documentation
                                    </div>
                                </div>
                                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 10px;">
                                    <strong><a href="https://doi.org/10.1155/2022/3264002" target="_blank" style="color: var(--text-dark);">Eosinophilia in Systemic Lupus Erythematosus</a></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                        <em>Case Reports in Medicine</em> | <a href="https://pubmed.ncbi.nlm.nih.gov/35265137/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 35265137</a><br>
                                        <i class="fas fa-file-medical" style="color: var(--primary-blue);"></i> Uncommon hematologic manifestation in autoimmune disease
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Public Health Research - Horizontal layout -->
                    <div style="margin-top: 2rem; border-top: 1px solid rgba(59, 130, 246, 0.1); padding-top: 1.5rem;">
                        <h4 style="color: var(--secondary-orange); margin-bottom: 1rem; font-size: 1.1rem;"><span style="display: inline-block; width: 10px; height: 10px; background: linear-gradient(135deg, var(--secondary-orange), #D97706); margin-right: 0.5rem; transform: rotate(45deg); box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);"></span>Public Health & Epidemiological Research</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                            <div style="background: rgba(245, 158, 11, 0.05); padding: 1rem; border-radius: 10px;">
                                <strong><a href="https://doi.org/10.1002/hsr2.1371" target="_blank" style="color: var(--text-dark);">Anemia Prevalence in COPD Patients</a></strong>
                                <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                    <em>Health Science Reports</em> (Wiley) | <a href="https://pubmed.ncbi.nlm.nih.gov/37388270/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 37388270</a><br>
                                    <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--secondary-orange), #D97706); margin-right: 0.5rem; transform: rotate(45deg); box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);"></span>Cross-sectional study: 1,247 COPD patients analyzed
                                </div>
                            </div>
                            <div style="background: rgba(245, 158, 11, 0.05); padding: 1rem; border-radius: 10px;">
                                <strong><a href="https://doi.org/10.1155/2022/5787856" target="_blank" style="color: var(--text-dark);">Graphic Health Warnings & Smoking Behavior</a></strong>
                                <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                    <em>Journal of Smoking Cessation</em> | <a href="https://pubmed.ncbi.nlm.nih.gov/36159220/" target="_blank" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">PMID: 36159220</a><br>
                                    <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--secondary-orange), #D97706); margin-right: 0.5rem; transform: rotate(45deg); box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);"></span>Policy impact study across 2,156 smokers in Nepal
                                </div>
                            </div>
                            <div style="background: rgba(245, 158, 11, 0.05); padding: 1rem; border-radius: 10px;">
                                <strong><a href="https://www.jiomnepal.com.np/index.php/jiomnepal/article/view/201" target="_blank" style="color: var(--text-dark);">COVID-19 Critical Care Survival Analysis</a></strong>
                                <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                                    <em>Journal of the Institute of Medicine</em><br>
                                    <span style="display: inline-block; width: 8px; height: 8px; background: linear-gradient(135deg, var(--secondary-orange), #D97706); margin-right: 0.5rem; transform: rotate(45deg); box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);"></span>Single-center retrospective analysis: 387 ICU patients
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div style="text-align: center; margin-top: 2rem; padding: 1.5rem; background: rgba(59, 130, 246, 0.05); border-radius: 15px;">
                    <p style="font-style: italic; color: var(--text-dark); font-size: 1.1rem;">
                        <strong>Undeniable evidence:</strong> These publications reflect our ability to contribute high-quality, evidence-based content that can withstand rigorous peer review and support informed decision-making in medicine.
                    </p>
                </div>

                <div class="expert-highlight">
                    <h4>🏛️ Why PubMed Matters?</h4>
                    <p>
                        PubMed is the medical research database of the U.S. National Library of Medicine,
                        under the management of NIH – a federal agency of the United States government.
                        <strong>Being officially archived on PubMed means research has passed the world's most rigorous scientific standards.</strong>
                    </p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 3rem;">
                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button primary">
                    EXPERIENCE SCIENTIFIC QUALITY
                </a>
            </div>
        </div>
    </section>

    <!-- Real Case Studies Section -->
    <section id="case-studies" class="solutions">
        <div class="container">
            <div class="section-header">
                <h2>Case Studies: Real SEO Results Achieved</h2>
                <p>Our medical articles aren't just scientifically accurate - they also achieve high Google rankings for competitive keywords. Here are some representative examples from hundreds of successful projects.</p>
            </div>

            <div class="solutions-grid" id="case-studies-grid">
                <!-- Always visible -->
                <div class="solution-card backdrop-blur border-glow case-study-batch" data-batch="1">
                    <div class="solution-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3><a href="https://my.klarity.health/molecular-and-genetic-testing-for-ablepharon-macrostomia-syndrome" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Molecular And Genetic Testing For Ablepharon-Macrostomia Syndrome"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for specialized medical keywords. Article thoroughly researched with sources from PubMed, WHO, CDC and authoritative scientific journals.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://my.klarity.health/molecular-and-genetic-testing-for-ablepharon-macrostomia-syndrome" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <div class="solution-card backdrop-blur border-glow case-study-batch" data-batch="1">
                    <div class="solution-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3><a href="https://my.klarity.health/gerstmann-syndrome-in-neurodegenerative-diseases/" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Gerstmann Syndrome In Neurodegenerative Diseases"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for complex neurological keywords. Evidence-based content presented in accessible language for patients.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://my.klarity.health/gerstmann-syndrome-in-neurodegenerative-diseases/" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <div class="solution-card backdrop-blur border-glow case-study-batch" data-batch="1">
                    <div class="solution-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3><a href="https://my.klarity.health/long-term-outcomes-of-untreated-tennis-elbow/" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Long-term Outcomes of Untreated Tennis Elbow"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "outcome of untreated tennis elbow". Article combines medical expertise with effective SEO optimization.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://my.klarity.health/long-term-outcomes-of-untreated-tennis-elbow/" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <!-- Batch 2: Preview initially -->
                <div class="solution-card backdrop-blur border-glow case-study-batch case-study-preview" data-batch="2" style="height: 120px; overflow: hidden; opacity: 0.3; transform: translateY(20px); transition: all 0.5s ease;">
                    <div class="solution-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <h3><a href="https://www.datelinehealthafrica.org/tips-for-african-parents-on-caring-for-a-child-with-fever-at-home-safely" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Tips for African Parents on Caring for Child with Fever"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "African Parents fever". Practical, helpful content optimized for specific audience.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://www.datelinehealthafrica.org/tips-for-african-parents-on-caring-for-a-child-with-fever-at-home-safely" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <div class="solution-card backdrop-blur border-glow case-study-batch case-study-preview" data-batch="2" style="height: 120px; overflow: hidden; opacity: 0.3; transform: translateY(20px); transition: all 0.5s ease; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(to top, rgba(255,255,255,0.9), transparent); z-index: 10;"></div>
                    <div class="solution-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3><a href="https://www.datelinehealthafrica.org/adverse-childhood-experiences-in-nigeria-causes-effects-and-solutions" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Adverse Childhood Experiences in Nigeria"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "adverse childhood experiences in nigeria". Deep research on child psychology with culturally appropriate approach.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://www.datelinehealthafrica.org/adverse-childhood-experiences-in-nigeria-causes-effects-and-solutions" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <div class="solution-card backdrop-blur border-glow case-study-batch case-study-preview" data-batch="2" style="height: 120px; overflow: hidden; opacity: 0.3; transform: translateY(20px); transition: all 0.5s ease; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(to top, rgba(255,255,255,0.9), transparent); z-index: 10;"></div>
                    <div class="solution-icon">
                        <span style="display: inline-block; width: 12px; height: 12px; background: linear-gradient(135deg, var(--success-green), #059669); border-radius: 50%; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);"></span>
                    </div>
                    <h3><a href="https://ijeomaadiele.wordpress.com/2025/05/30/what-is-your-gut-trying-to-tell-you-heres-how-at-home-microbiome-tests-can-help/" target="_blank" style="color: var(--text-dark); text-decoration: none;">"At-Home Microbiome Tests"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "at-home microbiome tests". Combines scientific expertise with ability to simplify complex topics.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://ijeomaadiele.wordpress.com/2025/05/30/what-is-your-gut-trying-to-tell-you-heres-how-at-home-microbiome-tests-can-help/" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <!-- Hidden preview hint for batch 3 -->
                <div class="solution-card backdrop-blur border-glow case-study-batch case-study-preview" data-batch="3" style="height: 80px; overflow: hidden; opacity: 0.2; transform: translateY(30px); transition: all 0.3s ease; position: relative; display: block;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 60px; background: linear-gradient(to top, rgba(255,255,255,0.95), transparent); z-index: 10;"></div>
                    <div class="solution-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3><a href="https://medium.com/@ijeomaadiele/can-menopause-cause-depression-in-women-a0d93009ca33" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Can Menopause Cause Depression in Women"</a></h3>
                </div>

                <!-- Batch 3: Hidden initially -->
                <div class="solution-card backdrop-blur border-glow case-study-batch" data-batch="3" style="display: none;">
                    <div class="solution-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3><a href="https://medium.com/@ijeomaadiele/can-menopause-cause-depression-in-women-a0d93009ca33" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Can Menopause Cause Depression in Women"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "menopause and depression". Article by expert Ijeoma A. - Applied Biochemistry, combining medical expertise with deep emotional understanding of women's health issues during menopause.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://medium.com/@ijeomaadiele/can-menopause-cause-depression-in-women-a0d93009ca33" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <div class="solution-card backdrop-blur border-glow case-study-batch" data-batch="3" style="display: none;">
                    <div class="solution-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3><a href="https://my.klarity.health/cardiovascular-health-post-menopausal-women" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Cardiovascular Health in Post-Menopausal Women"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "cardiovascular health menopause". Comprehensive research on women's heart health with holistic approach.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://my.klarity.health/cardiovascular-health-post-menopausal-women" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>

                <div class="solution-card backdrop-blur border-glow case-study-batch" data-batch="3" style="display: none;">
                    <div class="solution-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3><a href="https://my.klarity.health/genetic-predisposition-diabetes-asian-populations" target="_blank" style="color: var(--text-dark); text-decoration: none;">"Genetic Predisposition to Diabetes in Asian Populations"</a></h3>
                    <p><strong>Results:</strong> Achieved top page 1 Google ranking for "genetic diabetes asian". Applied genetics analysis with research data from multiple Asian countries.</p>
                    <div style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 10px; color: var(--success-green); font-weight: bold;">
                        <i class="fab fa-google"></i> Page 1 Google | <a href="https://my.klarity.health/genetic-predisposition-diabetes-asian-populations" target="_blank" style="color: var(--success-green);">View Article</a>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div style="text-align: center; margin-top: 2rem;" id="load-more-container">
                <button id="load-more-btn" class="cta-button primary" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                    VIEW MORE SUCCESS STORIES
                </button>
            </div>

            <div style="text-align: center; margin-top: 3rem; padding: 2rem; background: rgba(255, 255, 255, 0.9); border-radius: 20px; border: 1px solid rgba(59, 130, 246, 0.1);">
                <h4 style="color: var(--primary-blue); margin-bottom: 1rem;">Real-World Experience</h4>
                <p style="font-style: italic; color: var(--text-dark);">
                    "Most healthcare content fails because it's either too technical for patients or too simplistic for search engines. We solve this by combining medical expertise with proven SEO strategies."
                </p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>Real Results from Healthcare Clients</h2>
                <p>Healthcare businesses share their success stories with Topwriter's professional content strategy - from startups to large corporations.</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card backdrop-blur border-glow">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/klarity-health.png') }}" alt="Klarity Health" style="width: 40px; height: 40px; object-fit: contain;">
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
                        <p>"Our article 'Molecular And Genetic Testing For Ablepharon-Macrostomia Syndrome' achieved #1 Google ranking for highly specialized keywords. The research quality and scientific accuracy far exceeded our competitors' capabilities."</p>
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

                <div class="testimonial-card backdrop-blur border-glow">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/within-health.png') }}" alt="Within Health" style="width: 40px; height: 40px; object-fit: contain;">
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
                        <p>"Topwriter's team of licensed physicians helps us establish credibility in the telemedicine space. Their scientific accuracy and regulatory compliance is unmatched."</p>
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

                <div class="testimonial-card backdrop-blur border-glow">
                    <div class="testimonial-header" style="flex-direction: column; align-items: flex-start; gap: 0.8rem;">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/NutritionAdvisor.com.png') }}" alt="NutritionAdvisor.com" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <div class="client-details">
                                <h4>NutritionAdvisor.com</h4>
                                <p>Nutrition Consulting Platform</p>
                            </div>
                        </div>
                        <div class="rating" style="justify-content: flex-start;">
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

                <div class="testimonial-card backdrop-blur border-glow">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/dateline-health-africa.png') }}" alt="Dateline Health Africa" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <div class="client-details">
                                <h4>Dateline Health Africa</h4>
                                <p>African Healthcare Platform</p>
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
                        <p>"Our articles on 'African Parents fever' and 'Adverse Childhood Experiences in Nigeria' both achieved top rankings. The content balances scientific rigor with culturally appropriate messaging perfectly."</p>
                    </div>
                    <div class="testimonial-results">
                        <div class="result-metric">
                            <span class="metric-number">TOP</span>
                            <span class="metric-label">Keywords</span>
                        </div>
                        <div class="result-metric">
                            <span class="metric-number">+340%</span>
                            <span class="metric-label">Organic Traffic</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card backdrop-blur border-glow">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <img src="{{ asset('img/logo_6web/AmeliaGenesis.png') }}" alt="AmeliaGenesis" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <div class="client-details">
                                <h4>AmeliaGenesis</h4>
                                <p>Reproductive Health Specialists</p>
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
                        <p>"Our articles 'Reproductive Endocrinologists' and 'How to Get Pregnant at 40' both rank highly and create powerful emotional connections with our audience. Consultation conversions increased 280%."</p>
                    </div>
                    <div class="testimonial-results">
                        <div class="result-metric">
                            <span class="metric-number">+280%</span>
                            <span class="metric-label">Consultations</span>
                        </div>
                        <div class="result-metric">
                            <span class="metric-number">4.8/5</span>
                            <span class="metric-label">Ratings</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card backdrop-blur border-glow">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <div class="client-avatar">
                                <span style="display: inline-block; width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-blue); font-weight: bold; font-size: 16px; border: 1px solid rgba(59, 130, 246, 0.2);">A</span>
                            </div>
                            <div class="client-details">
                                <h4>African Female Voices</h4>
                                <p>Women's Health Platform</p>
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
                        <p>"Our articles on 'Understanding PCOS', 'Breast Cancer Myths' and 'Uterine Fibroids' all achieved top rankings. The way they combine medical expertise with emotional connection is truly remarkable."</p>
                    </div>
                    <div class="testimonial-results">
                        <div class="result-metric">
                            <span class="metric-number">TOP 3</span>
                            <span class="metric-label">Multiple Keywords</span>
                        </div>
                        <div class="result-metric">
                            <span class="metric-number">+390%</span>
                            <span class="metric-label">Engagement</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing">
        <div class="container">
            <div class="section-header">
                <h2>Simple, Transparent Pricing</h2>
                <p>No hidden fees, no complex packages. Just professional content at an unbeatable price.</p>
            </div>

            <div class="pricing-card backdrop-blur border-glow">
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
                        <li><i class="fas fa-check"></i> Keyword research via Ahrefs Premium & Google Ads VIP</li>
                        <li><i class="fas fa-check"></i> Bonus 1 HTML Landing Page/month</li>
                    </ul>
                </div>

                <div class="pricing-example">
                    <strong>Example:</strong> 1,000-word article = $19 (Compare: Regular agency $200+ | AI + Medical review = $150+ | Legal risk = Priceless)
                </div>

                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button primary large">
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
                        <h3>What is JSON for AI and why is it important?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>JSON is AI's native language. When we include JSON Schema with your content, AI systems can fully understand your brand, services, and reviews instantly. This dramatically increases the chances of your healthcare business appearing in AI-powered search results when patients seek medical information.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How does the bonus HTML landing page work?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Every month, you receive 1 professionally designed HTML page like a landing page, which can be used for campaigns, new products, or service introduction pages. Completely free with your service package.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What does the $0.019 per word price include?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Real M.D. & M.B.B.S. team <a href="#scientific-portfolio" style="color: var(--primary-blue);">published on PubMed</a>, SEO & JSON optimization for AI, professional images, plagiarism check, 5 revisions, direct website posting, keyword research via Ahrefs Premium & Google Ads VIP, bonus Landing Page HTML, and most importantly - <strong>zero legal risk from medical misinformation</strong>.</p>
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

            <div class="faq-cta">
                <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button primary">
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

                <div class="cta-buttons">
                    <a href="https://topwriter.co/try-writing?ref=expert-md-content" class="cta-button primary large">
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

        // Load More Case Studies functionality
        const loadMoreBtn = document.getElementById('load-more-btn');
        const loadMoreContainer = document.getElementById('load-more-container');
        let currentBatch = 1;

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                if (currentBatch === 1) {
                    // First click: Show batch 2 fully
                    const batch2Previews = document.querySelectorAll('.case-study-preview[data-batch="2"]');
                    const batch2Full = document.querySelectorAll('.case-study-batch[data-batch="2"]:not(.case-study-preview)');

                    // Hide previews
                    batch2Previews.forEach(preview => {
                        preview.style.display = 'none';
                    });

                    // Show full batch 2
                    batch2Full.forEach(card => {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });

                    // Show batch 3 preview hint more prominently
                    const batch3Preview = document.querySelector('.case-study-preview[data-batch="3"]');
                    if (batch3Preview) {
                        batch3Preview.style.opacity = '0.4';
                        batch3Preview.style.transform = 'translateY(10px)';
                    }

                    currentBatch = 2;
                    loadMoreBtn.textContent = 'EXPLORE MORE STORIES';

                } else if (currentBatch === 2) {
                    // Second click: Show batch 3 fully
                    const batch3Preview = document.querySelector('.case-study-preview[data-batch="3"]');
                    const batch3Full = document.querySelectorAll('.case-study-batch[data-batch="3"]:not(.case-study-preview)');

                    // Hide preview
                    if (batch3Preview) {
                        batch3Preview.style.display = 'none';
                    }

                    // Show full batch 3
                    batch3Full.forEach(card => {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });

                    // Hide load more button
                    loadMoreContainer.style.display = 'none';
                    currentBatch = 3;
                }
            });
        }
    });
    </script>
</body>
</html>
