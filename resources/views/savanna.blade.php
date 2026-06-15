<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="twinasafaris - Award-winning luxury safari, Kilimanjaro trekking and Zanzibar beach packages. Book your African adventure with Tanzania's top safari operator.">
        <meta property="og:title" content="twinasafaris | Luxury Safari & Kilimanjaro Treks">
        <meta property="og:description" content="Discover Africa's wild heart with Tanzania's premier safari operator. Serengeti, Ngorongoro, Kilimanjaro, Zanzibar.">
        <meta property="og:type" content="website">
        <title>twinasafaris | Luxury Safari, Kilimanjaro & Zanzibar Packages</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Savanna Trails Tanzania",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+255-767-000-888",
    "contactType": "customer service"
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "What is the best time for a safari in Tanzania?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "The best time for a safari is during the dry season from late June to October."
    }
  }]
}
</script>
<style>
/* =============================================
   CSS CUSTOM PROPERTIES - GREEN PALETTE
   ============================================= */
:root {
  --g900: #052010;
  --g800: #0a3d1f;
  --g700: #0f5a2e;
  --g600: #147a3e;
  --g500: #1a9b50;
  --g400: #28c268;
  --g300: #5dd98a;
  --g200: #9aecb8;
  --g100: #c8f5da;
  --g50:  #edfaf3;

  --accent: #2ecc71;
  --accent-dark: #27ae60;
  --accent-light: #a8e6c1;

  --dark: #060f09;
  --dark2: #0c1a10;
  --dark3: #112917;

  --white: #ffffff;
  --off-white: #f4fdf7;
  --cream: #eaf7ef;

  --text-dark: #0a1f10;
  --text-mid: #3a6348;
  --text-light: #7ab890;
  --text-muted: #a8c9b3;

  --border: rgba(42, 180, 100, 0.18);
  --border-strong: rgba(42, 180, 100, 0.35);

  --shadow-sm: 0 2px 12px rgba(10, 60, 30, 0.08);
  --shadow-md: 0 8px 40px rgba(10, 60, 30, 0.14);
  --shadow-lg: 0 20px 80px rgba(10, 60, 30, 0.22);

  --radius-sm: 6px;
  --radius-md: 12px;
  --radius-lg: 20px;
  --radius-xl: 32px;
  --radius-full: 9999px;

  --font-display: 'Cormorant Garamond', Georgia, serif;
  --font-body: 'Outfit', system-ui, sans-serif;

  --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  --transition-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* =============================================
   RESET & BASE
   ============================================= */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; font-size: 16px; }
body {
  font-family: var(--font-body);
  background: var(--off-white);
  color: var(--text-dark);
  overflow-x: hidden;
  line-height: 1.6;
}
img { max-width: 100%; display: block; }
a { text-decoration: none; color: inherit; }
button { cursor: pointer; font-family: var(--font-body); }
ul { list-style: none; }

/* =============================================
   TYPOGRAPHY
   ============================================= */
.display-xl {
  font-family: var(--font-display);
  font-size: clamp(48px, 7vw, 88px);
  font-weight: 700;
  line-height: 1.0;
  letter-spacing: -0.02em;
}
.display-lg {
  font-family: var(--font-display);
  font-size: clamp(36px, 5vw, 64px);
  font-weight: 700;
  line-height: 1.08;
  letter-spacing: -0.02em;
}
.display-md {
  font-family: var(--font-display);
  font-size: clamp(28px, 3.5vw, 44px);
  font-weight: 600;
  line-height: 1.15;
}
.display-sm {
  font-family: var(--font-display);
  font-size: clamp(20px, 2.5vw, 28px);
  font-weight: 600;
  line-height: 1.25;
}
.body-lg { font-size: 18px; line-height: 1.75; font-weight: 300; }
.body-md { font-size: 16px; line-height: 1.7; }
.body-sm { font-size: 14px; line-height: 1.65; }
.label {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}
.italic { font-style: italic; }
.green { color: var(--accent); }
.green-dark { color: var(--g700); }

/* =============================================
   UTILITY CLASSES
   ============================================= */
.container { max-width: 1280px; margin: 0 auto; padding: 0 40px; }
.container-wide { max-width: 1440px; margin: 0 auto; padding: 0 40px; }
.section-pad { padding: 100px 0; }
.section-pad-sm { padding: 60px 0; }
.text-center { text-align: center; }
.flex { display: flex; }
.flex-center { display: flex; align-items: center; justify-content: center; }
.flex-between { display: flex; align-items: center; justify-content: space-between; }
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }

/* Section Header */
.section-header { margin-bottom: 60px; }
.section-label {
  display: inline-block;
  color: var(--g600);
  margin-bottom: 12px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  padding: 6px 14px;
  background: var(--g100);
  border-radius: var(--radius-full);
}
.section-header h2 { color: var(--text-dark); }
.section-header p {
  font-size: 17px;
  color: var(--text-mid);
  line-height: 1.75;
  max-width: 560px;
  font-weight: 300;
  margin-top: 14px;
}
.section-header.center { text-align: center; }
.section-header.center p { margin: 14px auto 0; }

/* =============================================
   BUTTONS
   ============================================= */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 15px 32px;
  border-radius: var(--radius-sm);
  font-family: var(--font-body);
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.03em;
  border: none;
  transition: all var(--transition);
  white-space: nowrap;
}
.btn-primary {
  background: var(--g600);
  color: var(--white);
}
.btn-primary:hover {
  background: var(--g700);
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(20, 122, 62, 0.4);
}
.btn-secondary {
  background: var(--white);
  color: var(--g700);
  border: 2px solid var(--g600);
}
.btn-secondary:hover {
  background: var(--g50);
  transform: translateY(-2px);
}
.btn-ghost {
  background: rgba(255,255,255,0.12);
  color: var(--white);
  border: 1.5px solid rgba(255,255,255,0.35);
  backdrop-filter: blur(8px);
}
.btn-ghost:hover {
  background: rgba(255,255,255,0.22);
  border-color: rgba(255,255,255,0.6);
  transform: translateY(-2px);
}
.btn-sm {
  padding: 10px 22px;
  font-size: 13px;
}
.btn-lg {
  padding: 18px 40px;
  font-size: 16px;
  border-radius: var(--radius-md);
}
.btn-full { width: 100%; justify-content: center; }

/* =============================================
   NAVBAR
   ============================================= */
#navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  padding: 0;
  transition: all var(--transition);
}
#navbar.scrolled {
  background: rgba(5, 32, 16, 0.97);
  backdrop-filter: blur(24px);
  border-bottom: 1px solid rgba(42, 180, 100, 0.12);
}
.nav-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 22px 40px;
  max-width: 1440px;
  margin: 0 auto;
}
.nav-logo {
  display: flex;
  flex-direction: column;
  gap: 1px;
}
.nav-logo-text {
  font-family: var(--font-display);
  font-size: 26px;
  font-weight: 700;
  color: var(--white);
  letter-spacing: -0.01em;
  line-height: 1;
}
.nav-logo-sub {
  font-size: 9px;
  font-weight: 600;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--g300);
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 6px;
}
.nav-links a {
  padding: 8px 16px;
  font-size: 14px;
  font-weight: 500;
  color: rgba(255,255,255,0.75);
  border-radius: var(--radius-sm);
  transition: all var(--transition);
}
.nav-links a:hover {
  color: var(--white);
  background: rgba(255,255,255,0.08);
}
.nav-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}
.nav-phone {
  font-size: 13px;
  font-weight: 500;
  color: var(--g200);
}
.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 6px;
}
.hamburger span {
  display: block;
  width: 24px;
  height: 2px;
  background: var(--white);
  border-radius: 2px;
  transition: all var(--transition);
}
.mobile-menu {
  display: none;
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: var(--g900);
  z-index: 999;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 32px;
}
.mobile-menu.open { display: flex; }
.mobile-menu a {
  font-family: var(--font-display);
  font-size: 36px;
  font-weight: 600;
  color: var(--white);
  opacity: 0.8;
  transition: opacity var(--transition);
}
.mobile-menu a:hover { opacity: 1; color: var(--accent); }
.mobile-close {
  position: absolute;
  top: 24px; right: 32px;
  background: none;
  border: none;
  color: var(--white);
  font-size: 32px;
  cursor: pointer;
  line-height: 1;
}

/* =============================================
   HERO SECTION
   ============================================= */
#hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  overflow: hidden;
  background: var(--g900);
}
.hero-video-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}
.hero-video-bg video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.hero-video-bg::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, rgba(5,32,16,0.3) 0%, rgba(5,32,16,0.6) 60%, rgba(5,32,16,0.95) 100%);
}
.hero-pattern {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.04;
  background-image:
    repeating-linear-gradient(0deg, transparent, transparent 60px, rgba(42,180,100,0.6) 60px, rgba(42,180,100,0.6) 61px),
    repeating-linear-gradient(90deg, transparent, transparent 60px, rgba(42,180,100,0.6) 60px, rgba(42,180,100,0.6) 61px);
}
.hero-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  z-index: 0;
  pointer-events: none;
}
.hero-orb-1 {
  width: 700px; height: 700px;
  background: radial-gradient(circle, rgba(26,155,80,0.25) 0%, transparent 70%);
  top: -200px; right: -100px;
  animation: orbFloat1 12s ease-in-out infinite;
}
.hero-orb-2 {
  width: 500px; height: 500px;
  background: radial-gradient(circle, rgba(42,180,100,0.15) 0%, transparent 70%);
  bottom: 100px; left: -100px;
  animation: orbFloat2 15s ease-in-out infinite;
}
@keyframes orbFloat1 {
  0%,100% { transform: translate(0,0) scale(1); }
  33% { transform: translate(40px,-30px) scale(1.05); }
  66% { transform: translate(-20px,20px) scale(0.97); }
}
@keyframes orbFloat2 {
  0%,100% { transform: translate(0,0) scale(1); }
  50% { transform: translate(30px,40px) scale(1.08); }
}
.hero-content {
  position: relative;
  z-index: 2;
  padding: 0 40px 80px;
  max-width: 1440px;
  margin: 0 auto;
  width: 100%;
}
.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: rgba(42, 180, 100, 0.12);
  border: 1px solid rgba(42, 180, 100, 0.3);
  border-radius: var(--radius-full);
  padding: 8px 20px;
  margin-bottom: 28px;
}
.hero-eyebrow-dot {
  width: 7px; height: 7px;
  background: var(--accent);
  border-radius: 50%;
  animation: blink 2.2s ease-in-out infinite;
}
@keyframes blink {
  0%,100% { opacity: 1; }
  50% { opacity: 0.3; }
}
.hero-eyebrow span {
  font-size: 12px;
  font-weight: 600;
  color: var(--g200);
  letter-spacing: 0.08em;
  text-transform: uppercase;
}
.hero-title {
  font-family: var(--font-display);
  font-size: clamp(52px, 8vw, 100px);
  font-weight: 700;
  color: var(--white);
  line-height: 1.0;
  letter-spacing: -0.03em;
  max-width: 820px;
  margin-bottom: 24px;
}
.hero-title .italic-green {
  font-style: italic;
  color: var(--g300);
}
.hero-sub {
  font-size: 18px;
  color: rgba(255,255,255,0.6);
  font-weight: 300;
  max-width: 520px;
  line-height: 1.75;
  margin-bottom: 40px;
}
.hero-cta-group {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-bottom: 60px;
}
.hero-scroll-hint {
  display: flex;
  align-items: center;
  gap: 12px;
  color: rgba(255,255,255,0.4);
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}
.scroll-line {
  width: 40px;
  height: 1px;
  background: rgba(255,255,255,0.3);
}
.hero-floating-card {
  position: absolute;
  right: 40px;
  bottom: 120px;
  z-index: 3;
  background: rgba(255,255,255,0.06);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: var(--radius-lg);
  padding: 24px 28px;
  min-width: 220px;
  animation: cardFloat 6s ease-in-out infinite;
}
@keyframes cardFloat {
  0%,100% { transform: translateY(0); }
  50% { transform: translateY(-12px); }
}
.float-label {
  font-size: 11px;
  color: var(--g200);
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 8px;
}
.float-value {
  font-family: var(--font-display);
  font-size: 36px;
  font-weight: 700;
  color: var(--white);
  line-height: 1;
  margin-bottom: 4px;
}
.float-sub { font-size: 13px; color: rgba(255,255,255,0.5); }
.hero-bottom-strip {
  position: relative;
  z-index: 2;
  background: rgba(255,255,255,0.04);
  border-top: 1px solid rgba(255,255,255,0.08);
}
.hero-stats-row {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 40px;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
}
.hero-stat {
  padding: 24px 0;
  border-right: 1px solid rgba(255,255,255,0.08);
  padding-left: 40px;
}
.hero-stat:first-child { padding-left: 0; }
.hero-stat:last-child { border-right: none; }
.hero-stat-num {
  font-family: var(--font-display);
  font-size: 30px;
  font-weight: 700;
  color: var(--white);
  line-height: 1;
}
.hero-stat-label {
  font-size: 12px;
  color: rgba(255,255,255,0.45);
  font-weight: 500;
  margin-top: 4px;
  letter-spacing: 0.04em;
}

/* =============================================
   SEARCH SECTION
   ============================================= */
#search {
  background: var(--white);
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 72px;
  z-index: 100;
  box-shadow: var(--shadow-sm);
}
.search-inner {
  max-width: 1440px;
  margin: 0 auto;
  padding: 28px 40px;
}
.search-title {
  font-family: var(--font-display);
  font-size: 20px;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 18px;
}
.search-form {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1fr auto;
  gap: 12px;
  align-items: flex-end;
}
.search-field { display: flex; flex-direction: column; gap: 6px; }
.search-field label {
  font-size: 11px;
  font-weight: 700;
  color: var(--text-light);
  letter-spacing: 0.1em;
  text-transform: uppercase;
}
.search-field select,
.search-field input[type="date"],
.search-field input[type="number"] {
  padding: 13px 16px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: var(--font-body);
  font-size: 14px;
  color: var(--text-dark);
  background: var(--off-white);
  outline: none;
  transition: border-color var(--transition);
  appearance: none;
  -webkit-appearance: none;
}
.search-field select:focus,
.search-field input:focus {
  border-color: var(--g500);
  background: var(--white);
}

/* =============================================
   DESTINATIONS SECTION
   ============================================= */
#destinations { padding: 100px 0; background: var(--off-white); }
.destinations-grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr;
  grid-template-rows: 240px 240px;
  gap: 16px;
}
.dest-card {
  position: relative;
  border-radius: var(--radius-lg);
  overflow: hidden;
  cursor: pointer;
  group: true;
}
.dest-card:first-child { grid-row: 1 / 3; }
.dest-card-bg {
  position: absolute;
  inset: 0;
  transition: transform 0.6s ease;
}
.dest-card:hover .dest-card-bg { transform: scale(1.06); }
.dest-serengeti { background: linear-gradient(135deg, #0a3d1f 0%, #1a6b3a 40%, #2d9455 70%, #3ab068 100%); }
.dest-kili { background: linear-gradient(160deg, #0c1a10 0%, #1a3a22 50%, #2d5e38 100%); }
.dest-zanzibar { background: linear-gradient(135deg, #0a2a3d 0%, #0f4a6b 50%, #1a7aaa 100%); }
.dest-ngoro { background: linear-gradient(135deg, #1f1a0a 0%, #3d3010 50%, #6b5520 100%); }
.dest-tarangire { background: linear-gradient(135deg, #1a0f0a 0%, #3d2010 50%, #7a3f1a 100%); }
.dest-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(5,32,16,0.9) 0%, rgba(5,32,16,0.2) 60%, transparent 100%);
}
.dest-overlay-top {
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 50%;
  background: linear-gradient(to bottom, rgba(5,32,16,0.4) 0%, transparent 100%);
}
.dest-tag {
  position: absolute;
  top: 16px;
  right: 16px;
  background: rgba(42, 180, 100, 0.85);
  backdrop-filter: blur(8px);
  color: var(--white);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 5px 12px;
  border-radius: var(--radius-full);
  z-index: 2;
}
.dest-info {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  padding: 24px;
  z-index: 2;
}
.dest-card:first-child .dest-info { padding: 32px; }
.dest-name {
  font-family: var(--font-display);
  font-size: 22px;
  font-weight: 700;
  color: var(--white);
  line-height: 1.1;
  margin-bottom: 6px;
}
.dest-card:first-child .dest-name { font-size: 38px; margin-bottom: 10px; }
.dest-detail {
  font-size: 13px;
  color: rgba(255,255,255,0.65);
}
.dest-explore {
  display: inline-block;
  margin-top: 16px;
  padding: 10px 24px;
  background: rgba(255,255,255,0.12);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: var(--radius-sm);
  color: var(--white);
  font-size: 13px;
  font-weight: 600;
  transition: all var(--transition);
}
.dest-explore:hover {
  background: rgba(42,180,100,0.6);
  border-color: var(--accent);
}

/* =============================================
   PACKAGES SECTION
   ============================================= */
#packages { padding: 100px 0; background: var(--white); }
.packages-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 48px;
  flex-wrap: wrap;
}
.pkg-tab {
  padding: 10px 24px;
  border-radius: var(--radius-full);
  border: 1.5px solid var(--border);
  background: transparent;
  font-size: 14px;
  font-weight: 500;
  color: var(--text-mid);
  cursor: pointer;
  transition: all var(--transition);
}
.pkg-tab:hover { border-color: var(--g500); color: var(--g700); }
.pkg-tab.active {
  background: var(--g600);
  border-color: var(--g600);
  color: var(--white);
}
.packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
.pkg-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  overflow: hidden;
  transition: all var(--transition);
  cursor: pointer;
}
.pkg-card:hover {
  transform: translateY(-6px);
  box-shadow: var(--shadow-lg);
  border-color: var(--g400);
}
.pkg-card.featured { border-color: var(--g500); box-shadow: 0 0 0 3px rgba(42, 180, 100, 0.12); }
.pkg-image {
  height: 220px;
  position: relative;
  overflow: hidden;
}
.pkg-img-bg {
  position: absolute;
  inset: 0;
  transition: transform 0.6s ease;
}
.pkg-card:hover .pkg-img-bg { transform: scale(1.08); }
.pkg-safari-bg { background: linear-gradient(135deg, #0a3d1f 0%, #1a7a3a 40%, #2db860 70%, #3acc70 100%); }
.pkg-kili-bg { background: linear-gradient(160deg, #090e0a 0%, #121f15 40%, #1e3d25 70%, #2a5a35 100%); }
.pkg-zanzibar-bg { background: linear-gradient(135deg, #050d12 0%, #0a2a3d 50%, #0f4f7a 80%, #1a7ab0 100%); }
.pkg-ngoro-bg { background: linear-gradient(135deg, #0a0800 0%, #1f1800 40%, #3d3000 70%, #5a4a10 100%); }
.pkg-tara-bg { background: linear-gradient(135deg, #120800 0%, #2a1200 40%, #5a2a00 70%, #8a4a10 100%); }
.pkg-combo-bg { background: linear-gradient(135deg, #051208 0%, #0a3020 40%, #1a6040 70%, #2a9060 100%); }
.pkg-badge {
  position: absolute;
  top: 14px;
  left: 14px;
  z-index: 2;
  padding: 5px 12px;
  border-radius: var(--radius-full);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}
.badge-bestseller { background: var(--g600); color: var(--white); }
.badge-popular { background: rgba(255,255,255,0.9); color: var(--g700); }
.badge-new { background: #1a6bb0; color: var(--white); }
.badge-limited { background: #8B1A1A; color: var(--white); }
.pkg-duration {
  position: absolute;
  bottom: 14px;
  right: 14px;
  z-index: 2;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(6px);
  color: var(--white);
  font-size: 12px;
  font-weight: 600;
  padding: 5px 12px;
  border-radius: var(--radius-full);
}
.pkg-body { padding: 24px; }
.pkg-category {
  font-size: 11px;
  font-weight: 700;
  color: var(--g600);
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 8px;
}
.pkg-name {
  font-family: var(--font-display);
  font-size: 20px;
  font-weight: 700;
  color: var(--text-dark);
  line-height: 1.25;
  margin-bottom: 12px;
}
.pkg-highlights {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 16px;
}
.pkg-highlight {
  font-size: 12px;
  color: var(--text-mid);
  background: var(--g50);
  padding: 4px 10px;
  border-radius: var(--radius-full);
  border: 1px solid var(--g100);
  font-weight: 500;
}
.pkg-meta {
  display: flex;
  gap: 20px;
  padding: 14px 0;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  margin-bottom: 18px;
}
.pkg-meta-item {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.meta-label { font-size: 10px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.08em; text-transform: uppercase; }
.meta-value { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.pkg-footer { display: flex; align-items: flex-end; justify-content: space-between; }
.pkg-price .from-text { font-size: 11px; color: var(--text-light); display: block; margin-bottom: 2px; font-weight: 500; }
.pkg-price .price-amount {
  font-family: var(--font-display);
  font-size: 30px;
  font-weight: 700;
  color: var(--g700);
  line-height: 1;
}
.pkg-price .price-per { font-size: 13px; color: var(--text-light); font-weight: 400; }
.pkg-stars {
  display: flex;
  align-items: center;
  gap: 4px;
  margin-top: 4px;
}
.star-filled { color: #f0a500; font-size: 13px; }
.star-count { font-size: 12px; color: var(--text-light); margin-left: 4px; }

/* =============================================
   KILIMANJARO SECTION
   ============================================= */
#kilimanjaro { padding: 100px 0; background: var(--dark); }
.kili-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
.kili-left h2 { color: var(--white); margin-bottom: 16px; }
.kili-left p { color: rgba(255,255,255,0.55); margin-bottom: 32px; line-height: 1.8; font-weight: 300; font-size: 17px; }
.kili-routes { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 32px; }
.kili-route {
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(42,180,100,0.15);
  border-radius: var(--radius-md);
  padding: 18px;
  cursor: pointer;
  transition: all var(--transition);
}
.kili-route:hover, .kili-route.active {
  background: rgba(42,180,100,0.1);
  border-color: rgba(42,180,100,0.4);
}
.route-name {
  font-family: var(--font-display);
  font-size: 16px;
  font-weight: 600;
  color: var(--white);
  margin-bottom: 4px;
}
.route-detail { font-size: 12px; color: rgba(255,255,255,0.45); }
.route-price { font-size: 13px; font-weight: 700; color: var(--g300); margin-top: 8px; }
.kili-visual {
  position: relative;
  height: 500px;
  border-radius: var(--radius-xl);
  overflow: hidden;
  background: linear-gradient(160deg, #060e08 0%, #0c1a10 30%, #142a1c 60%, #1e4028 85%, #2a5a38 100%);
}
.kili-mountain {
  position: absolute;
  bottom: 0;
  left: 0; right: 0;
  height: 85%;
}
.kili-mountain svg { width: 100%; height: 100%; }
.kili-stats-overlay {
  position: absolute;
  bottom: 24px;
  left: 24px;
  right: 24px;
  display: flex;
  gap: 12px;
}
.kili-stat-chip {
  flex: 1;
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: var(--radius-md);
  padding: 14px 16px;
  text-align: center;
}
.ksc-num {
  font-family: var(--font-display);
  font-size: 22px;
  font-weight: 700;
  color: var(--white);
  line-height: 1;
}
.ksc-label { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 3px; }

/* =============================================
   ZANZIBAR SECTION
   ============================================= */
#zanzibar { padding: 100px 0; background: var(--off-white); }
.zanzibar-intro {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 64px;
  align-items: center;
  margin-bottom: 60px;
}
.zanzibar-visual {
  height: 400px;
  border-radius: var(--radius-xl);
  background: linear-gradient(135deg, #061218 0%, #0a2840 40%, #0e4a78 75%, #1270b0 100%);
  position: relative;
  overflow: hidden;
}
.zanzibar-waves {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 60%;
}
.zanzibar-waves svg { width: 100%; height: 100%; }
.zanzibar-packages { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.z-pkg-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  border: 1px solid var(--border);
  transition: all var(--transition);
}
.z-pkg-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
.z-pkg-img {
  height: 160px;
  position: relative;
}
.z-ocean { background: linear-gradient(135deg, #061622 0%, #0a2a42 50%, #0f4a7a 100%); }
.z-sunset { background: linear-gradient(135deg, #120806 0%, #2a1010 40%, #5a2020 70%, #9a4422 100%); }
.z-stone { background: linear-gradient(135deg, #0e1208 0%, #1a2210 40%, #2e3e20 70%, #4a5a30 100%); }
.z-pkg-body { padding: 18px; }
.z-pkg-name {
  font-family: var(--font-display);
  font-size: 18px;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 6px;
}
.z-pkg-detail { font-size: 13px; color: var(--text-mid); margin-bottom: 12px; line-height: 1.5; }
.z-pkg-footer { display: flex; align-items: center; justify-content: space-between; }
.z-price {
  font-family: var(--font-display);
  font-size: 22px;
  font-weight: 700;
  color: var(--g700);
}

/* =============================================
   WHY CHOOSE US
   ============================================= */
#why { padding: 100px 0; background: var(--g800); }
.why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; margin-top: 60px; }
.why-card {
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(42,180,100,0.15);
  border-radius: var(--radius-lg);
  padding: 36px 28px;
  text-align: center;
  transition: all var(--transition);
}
.why-card:hover {
  background: rgba(42,180,100,0.08);
  border-color: rgba(42,180,100,0.35);
  transform: translateY(-4px);
}
.why-num {
  font-family: var(--font-display);
  font-size: 52px;
  font-weight: 700;
  color: var(--g400);
  opacity: 0.35;
  line-height: 1;
  margin-bottom: 16px;
}
.why-title {
  font-family: var(--font-display);
  font-size: 20px;
  font-weight: 600;
  color: var(--white);
  margin-bottom: 12px;
}
.why-desc { font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.75; }
.trust-badges {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 32px;
  margin-top: 60px;
  padding-top: 48px;
  border-top: 1px solid rgba(255,255,255,0.06);
  flex-wrap: wrap;
}
.trust-badge {
  text-align: center;
  padding: 20px 28px;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: var(--radius-md);
}
.trust-badge-title { font-family: var(--font-display); font-size: 18px; font-weight: 700; color: var(--g300); margin-bottom: 4px; }
.trust-badge-sub { font-size: 12px; color: rgba(255,255,255,0.4); }

/* =============================================
   PROCESS / HOW IT WORKS
   ============================================= */
#process { padding: 100px 0; background: var(--white); }
.process-steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; margin-top: 60px; position: relative; }
.process-steps::before {
  content: '';
  position: absolute;
  top: 32px;
  left: 12.5%;
  right: 12.5%;
  height: 1px;
  background: linear-gradient(to right, transparent, var(--g300), transparent);
}
.process-step { padding: 0 20px; text-align: center; }
.step-num {
  width: 64px; height: 64px;
  border-radius: 50%;
  background: var(--g600);
  color: var(--white);
  font-family: var(--font-display);
  font-size: 24px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  position: relative;
  z-index: 1;
  border: 4px solid var(--white);
  box-shadow: 0 0 0 2px var(--g300);
}
.step-title {
  font-family: var(--font-display);
  font-size: 18px;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 10px;
}
.step-desc { font-size: 14px; color: var(--text-mid); line-height: 1.7; }

/* =============================================
   REVIEWS SECTION
   ============================================= */
#reviews { padding: 100px 0; background: var(--g50); }
.reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.review-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  padding: 32px;
  border: 1px solid var(--border);
  transition: all var(--transition);
}
.review-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }
.review-card.featured-review {
  background: var(--g800);
  border-color: transparent;
}
.review-stars { display: flex; gap: 3px; margin-bottom: 16px; }
.rv-star { color: #f0a500; font-size: 14px; }
.review-text {
  font-family: var(--font-display);
  font-size: 17px;
  font-weight: 400;
  line-height: 1.7;
  font-style: italic;
  color: var(--text-dark);
  margin-bottom: 24px;
}
.featured-review .review-text { color: rgba(255,255,255,0.8); }
.review-author { display: flex; align-items: center; gap: 14px; }
.reviewer-avatar {
  width: 44px; height: 44px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--font-display);
  font-size: 16px;
  font-weight: 700;
  color: var(--white);
  flex-shrink: 0;
}
.av-1 { background: var(--g600); }
.av-2 { background: var(--g700); }
.av-3 { background: var(--g500); }
.av-4 { background: #1a4a7a; }
.av-5 { background: #7a3a10; }
.av-6 { background: var(--g800); }
.reviewer-name { font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
.featured-review .reviewer-name { color: var(--white); }
.reviewer-loc { font-size: 12px; color: var(--text-light); }
.featured-review .reviewer-loc { color: rgba(255,255,255,0.45); }
.reviewer-trip { font-size: 11px; color: var(--g600); font-weight: 600; margin-top: 3px; }
.featured-review .reviewer-trip { color: var(--g300); }
.reviews-trust-bar {
  margin-top: 48px;
  padding: 28px 36px;
  background: var(--white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 24px;
}
.rtb-stat { text-align: center; }
.rtb-num { font-family: var(--font-display); font-size: 32px; font-weight: 700; color: var(--g700); line-height: 1; }
.rtb-label { font-size: 12px; color: var(--text-light); margin-top: 4px; font-weight: 500; }
.rtb-divider { width: 1px; height: 40px; background: var(--border); }

/* =============================================
   BLOG SECTION
   ============================================= */
#blog { padding: 100px 0; background: var(--white); }
.blog-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 24px; }
.blog-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  border: 1px solid var(--border);
  cursor: pointer;
  transition: all var(--transition);
}
.blog-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
.blog-img { height: 200px; position: relative; overflow: hidden; }
.blog-card:first-child .blog-img { height: 260px; }
.blog-img-bg { position: absolute; inset: 0; transition: transform 0.6s ease; }
.blog-card:hover .blog-img-bg { transform: scale(1.06); }
.blog-b1 { background: linear-gradient(135deg, #0a3d1f 0%, #1a7a3a 60%, #3ab060 100%); }
.blog-b2 { background: linear-gradient(135deg, #060e08 0%, #0c1e14 50%, #1a3820 100%); }
.blog-b3 { background: linear-gradient(135deg, #0a0a1a 0%, #1a1a3a 50%, #2a2a5a 100%); }
.blog-category-tag {
  position: absolute;
  top: 14px;
  left: 14px;
  z-index: 2;
  background: rgba(42, 180, 100, 0.85);
  backdrop-filter: blur(6px);
  color: var(--white);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 5px 12px;
  border-radius: var(--radius-full);
}
.blog-body { padding: 24px; }
.blog-date { font-size: 12px; color: var(--text-light); margin-bottom: 10px; font-weight: 500; }
.blog-title {
  font-family: var(--font-display);
  font-size: 20px;
  font-weight: 600;
  color: var(--text-dark);
  line-height: 1.35;
  margin-bottom: 12px;
}
.blog-card:first-child .blog-title { font-size: 24px; }
.blog-excerpt { font-size: 14px; color: var(--text-mid); line-height: 1.7; margin-bottom: 16px; }
.blog-read-more {
  font-size: 13px;
  font-weight: 700;
  color: var(--g600);
  letter-spacing: 0.04em;
  transition: color var(--transition);
}
.blog-card:hover .blog-read-more { color: var(--g400); }

/* =============================================
   NEWSLETTER SECTION
   ============================================= */
#newsletter { padding: 80px 0; background: var(--g700); }
.newsletter-inner {
  max-width: 680px;
  margin: 0 auto;
  text-align: center;
  padding: 0 40px;
}
#newsletter h2 { color: var(--white); margin-bottom: 12px; }
#newsletter p { color: rgba(255,255,255,0.65); margin-bottom: 36px; font-size: 17px; font-weight: 300; }
.newsletter-form {
  display: flex;
  gap: 12px;
}
.newsletter-form input[type="email"] {
  flex: 1;
  padding: 16px 22px;
  border-radius: var(--radius-sm);
  border: 1.5px solid rgba(255,255,255,0.2);
  background: rgba(255,255,255,0.1);
  color: var(--white);
  font-family: var(--font-body);
  font-size: 15px;
  outline: none;
  transition: border-color var(--transition);
}
.newsletter-form input[type="email"]::placeholder { color: rgba(255,255,255,0.4); }
.newsletter-form input[type="email"]:focus { border-color: rgba(255,255,255,0.5); }
.newsletter-form button {
  background: var(--white);
  color: var(--g700);
  padding: 16px 32px;
  border-radius: var(--radius-sm);
  border: none;
  font-weight: 700;
  font-size: 14px;
  font-family: var(--font-body);
  cursor: pointer;
  transition: all var(--transition);
  white-space: nowrap;
}
.newsletter-form button:hover { background: var(--g100); transform: translateY(-2px); }
.newsletter-guarantee { margin-top: 16px; font-size: 12px; color: rgba(255,255,255,0.45); }

/* =============================================
   PARTNERS / CERTIFICATIONS
   ============================================= */
#partners { padding: 60px 0; background: var(--off-white); border-top: 1px solid var(--border); }
.partners-label { text-align: center; font-size: 12px; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--text-light); margin-bottom: 36px; }
.partners-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 48px;
  flex-wrap: wrap;
}
.partner-badge {
  padding: 14px 28px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  font-family: var(--font-display);
  font-size: 16px;
  font-weight: 700;
  color: var(--text-mid);
  opacity: 0.7;
  transition: opacity var(--transition);
}
.partner-badge:hover { opacity: 1; }

/* =============================================
   INSTAGRAM / GALLERY
   ============================================= */
#gallery { padding: 100px 0; background: var(--dark); }
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  grid-template-rows: 200px 200px;
  gap: 8px;
  margin-top: 48px;
}
.gallery-item {
  border-radius: var(--radius-md);
  overflow: hidden;
  position: relative;
  cursor: pointer;
}
.gallery-item:first-child { grid-column: 1 / 3; grid-row: 1 / 3; border-radius: var(--radius-xl); }
.gallery-item:nth-child(4) { grid-column: 4 / 6; }
.gallery-item-bg {
  position: absolute;
  inset: 0;
  transition: transform 0.5s ease;
}
.gallery-item:hover .gallery-item-bg { transform: scale(1.08); }
.g1 { background: linear-gradient(135deg, #0a3a1f 0%, #1a7040 60%, #2aa060 100%); }
.g2 { background: linear-gradient(135deg, #0a0810 0%, #1a1030 50%, #2a2050 100%); }
.g3 { background: linear-gradient(135deg, #1a0a00 0%, #3a1800 50%, #6a3010 100%); }
.g4 { background: linear-gradient(135deg, #001018 0%, #002040 50%, #004080 100%); }
.g5 { background: linear-gradient(135deg, #081810 0%, #103020 50%, #205040 100%); }
.g6 { background: linear-gradient(135deg, #180a00 0%, #380e00 50%, #702010 100%); }
.g7 { background: linear-gradient(135deg, #060e08 0%, #0e2010 50%, #1e4020 100%); }
.g8 { background: linear-gradient(135deg, #100a00 0%, #281400 50%, #503000 100%); }
.gallery-hover-overlay {
  position: absolute;
  inset: 0;
  background: rgba(5,32,16,0.6);
  opacity: 0;
  transition: opacity var(--transition);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2;
}
.gallery-item:hover .gallery-hover-overlay { opacity: 1; }
.gallery-hover-text {
  color: var(--white);
  font-family: var(--font-display);
  font-size: 15px;
  font-weight: 600;
}

/* =============================================
   INQUIRY / CONTACT FORM
   ============================================= */
#inquiry { padding: 100px 0; background: var(--white); }
.inquiry-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 80px; align-items: start; }
.inquiry-left h2 { color: var(--text-dark); margin-bottom: 16px; }
.inquiry-left p { color: var(--text-mid); margin-bottom: 36px; font-size: 17px; font-weight: 300; line-height: 1.8; }
.contact-methods { display: flex; flex-direction: column; gap: 16px; }
.contact-method {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 18px 20px;
  background: var(--off-white);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  transition: all var(--transition);
  cursor: pointer;
}
.contact-method:hover { border-color: var(--g500); background: var(--g50); }
.cm-icon-box {
  width: 44px; height: 44px;
  border-radius: var(--radius-sm);
  background: var(--g100);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.cm-label { font-size: 11px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 2px; }
.cm-value { font-size: 14px; font-weight: 600; color: var(--text-dark); }
.inquiry-form {
  background: var(--off-white);
  border-radius: var(--radius-xl);
  padding: 40px;
  border: 1px solid var(--border);
}
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 18px; }
.form-group.full { grid-column: 1 / -1; }
.form-group label {
  font-size: 12px;
  font-weight: 700;
  color: var(--text-mid);
  letter-spacing: 0.06em;
  text-transform: uppercase;
}
.form-group input,
.form-group select,
.form-group textarea {
  padding: 14px 18px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: var(--font-body);
  font-size: 15px;
  color: var(--text-dark);
  background: var(--white);
  outline: none;
  transition: border-color var(--transition);
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { border-color: var(--g500); }
.form-group textarea { resize: vertical; min-height: 110px; line-height: 1.6; }
.form-submit-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 8px;
  flex-wrap: wrap;
  gap: 12px;
}
.form-privacy { font-size: 12px; color: var(--text-light); }

/* =============================================
   FOOTER
   ============================================= */
footer {
  background: var(--dark);
  padding: 80px 0 0;
  border-top: 1px solid rgba(255,255,255,0.06);
}
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 48px;
  margin-bottom: 60px;
}
.footer-brand h3 {
  font-family: var(--font-display);
  font-size: 28px;
  font-weight: 700;
  color: var(--white);
  margin-bottom: 8px;
}
.footer-brand-sub { font-size: 11px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: var(--g400); margin-bottom: 16px; }
.footer-brand p { font-size: 14px; color: rgba(255,255,255,0.45); line-height: 1.8; max-width: 280px; margin-bottom: 24px; font-weight: 300; }
.footer-social { display: flex; gap: 10px; }
.footer-social-link {
  width: 38px; height: 38px;
  border-radius: var(--radius-sm);
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  display: flex; align-items: center; justify-content: center;
  color: rgba(255,255,255,0.5);
  font-size: 12px;
  font-weight: 700;
  font-family: var(--font-display);
  transition: all var(--transition);
}
.footer-social-link:hover { background: var(--g700); border-color: var(--g600); color: var(--white); }
.footer-col h4 {
  font-size: 13px;
  font-weight: 700;
  color: var(--white);
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 20px;
}
.footer-col ul { display: flex; flex-direction: column; gap: 10px; }
.footer-col ul li a {
  font-size: 14px;
  color: rgba(255,255,255,0.45);
  font-weight: 300;
  transition: color var(--transition);
}
.footer-col ul li a:hover { color: var(--g300); }
.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.06);
  padding: 24px 0;
}
.footer-bottom-inner {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}
.footer-copy { font-size: 13px; color: rgba(255,255,255,0.3); font-weight: 300; }
.footer-legal { display: flex; gap: 24px; }
.footer-legal a { font-size: 12px; color: rgba(255,255,255,0.3); transition: color var(--transition); }
.footer-legal a:hover { color: var(--g300); }

/* =============================================
   WHATSAPP FLOATING BUTTON
   ============================================= */
.whatsapp-btn {
  position: fixed;
  bottom: 32px;
  right: 32px;
  z-index: 999;
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: #25D366;
  border: none;
  box-shadow: 0 8px 30px rgba(37, 211, 102, 0.4);
  transition: all var(--transition);
  display: flex;
  align-items: center;
  justify-content: center;
}
.whatsapp-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(37, 211, 102, 0.5);
}
.whatsapp-btn svg {
  width: 32px;
  height: 32px;
  fill: white;
}
.wa-tooltip {
  position: absolute;
  right: 80px;
  background: var(--dark);
  color: white;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
  white-space: nowrap;
  opacity: 0;
  transform: translateY(10px);
  transition: all var(--transition);
  pointer-events: none;
}
.whatsapp-btn:hover .wa-tooltip {
  opacity: 1;
  transform: translateY(0);
}

/* =============================================
   EXIT INTENT POPUP
   ============================================= */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(5, 32, 16, 0.85);
  backdrop-filter: blur(12px);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  opacity: 0;
  visibility: hidden;
  transition: all var(--transition-slow);
}
.popup-overlay.open {
  opacity: 1;
  visibility: visible;
}
.popup-box {
  background: var(--white);
  border-radius: var(--radius-xl);
  padding: 48px;
  max-width: 520px;
  width: 100%;
  text-align: center;
  position: relative;
  transform: scale(0.9) translateY(20px);
  transition: all var(--transition-slow);
}
.popup-overlay.open .popup-box {
  transform: scale(1) translateY(0);
}
.popup-close {
  position: absolute;
  top: 16px;
  right: 16px;
  background: none;
  border: none;
  font-size: 24px;
  color: var(--text-muted);
  cursor: pointer;
  transition: color var(--transition);
}
.popup-close:hover { color: var(--text-dark); }
.popup-label {
  display: inline-block;
  color: var(--g600);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  padding: 6px 14px;
  background: var(--g100);
  border-radius: var(--radius-full);
  margin-bottom: 16px;
}
.popup-title {
  font-family: var(--font-display);
  font-size: 32px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 12px;
}
.popup-sub {
  font-size: 16px;
  color: var(--text-mid);
  margin-bottom: 28px;
  line-height: 1.7;
}
.popup-offer {
  background: var(--g50);
  border: 2px dashed var(--g300);
  border-radius: var(--radius-lg);
  padding: 24px;
  margin-bottom: 28px;
}
.popup-offer-code {
  font-family: var(--font-display);
  font-size: 28px;
  font-weight: 700;
  color: var(--g600);
  letter-spacing: 0.1em;
}
.popup-offer-desc {
  font-size: 14px;
  color: var(--text-mid);
  margin-top: 8px;
}
.popup-decline {
  margin-top: 16px;
  font-size: 13px;
  color: var(--text-light);
  cursor: pointer;
  text-decoration: underline;
  display: inline-block;
}

/* =============================================
   USER DASHBOARD
   ============================================= */
#user-dashboard {
  position: fixed;
  inset: 0;
  background: var(--off-white);
  z-index: 9998;
  display: none;
  flex-direction: column;
}
#user-dashboard.active-view { display: flex; }
.dash-header {
  background: var(--g900);
  padding: 24px 40px;
}
.dash-header-inner {
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
}
.dash-user-info {
  display: flex;
  align-items: center;
  gap: 16px;
}
.dash-avatar {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: var(--g600);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--font-display);
  font-size: 24px;
  font-weight: 700;
  color: var(--white);
}
.dash-name {
  font-family: var(--font-display);
  font-size: 24px;
  font-weight: 700;
  color: var(--white);
}
.dash-role {
  font-size: 13px;
  color: var(--g300);
}
.dash-nav {
  background: var(--white);
  border-bottom: 1px solid var(--border);
}
.dash-nav-inner {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 40px;
  display: flex;
  gap: 8px;
}
.dash-nav-item {
  padding: 18px 24px;
  font-size: 14px;
  font-weight: 500;
  color: var(--text-mid);
  border-bottom: 3px solid transparent;
  cursor: pointer;
  transition: all var(--transition);
}
.dash-nav-item:hover {
  color: var(--g700);
  background: var(--g50);
}
.dash-nav-item.active {
  color: var(--g700);
  border-bottom-color: var(--g600);
  background: var(--g50);
}
.dash-content {
  flex: 1;
  padding: 40px;
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
}
.dash-quick-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 32px;
}
.dash-quick-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  padding: 24px;
}
.dqc-number {
  font-family: var(--font-display);
  font-size: 36px;
  font-weight: 700;
  color: var(--g700);
  line-height: 1;
  margin-bottom: 8px;
}
.dqc-label {
  font-size: 13px;
  font-weight: 500;
  color: var(--text-mid);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  margin-bottom: 4px;
}
.dqc-sub {
  font-size: 13px;
  color: var(--text-light);
}
.dash-2col {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}
.dash-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  padding: 24px;
}
.dash-card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}
.dash-card-header h3 {
  font-family: var(--font-display);
  font-size: 20px;
  font-weight: 700;
  color: var(--text-dark);
}
.dash-card-header span {
  font-size: 13px;
  color: var(--g600);
  font-weight: 500;
  cursor: pointer;
}
.dash-booking-row {
  display: grid;
  grid-template-columns: auto 1fr auto auto auto;
  gap: 20px;
  align-items: center;
  padding: 20px 0;
  border-bottom: 1px solid var(--border);
}
.dash-booking-row:last-child { border-bottom: none; }
.dbr-img {
  width: 80px;
  height: 60px;
  border-radius: var(--radius-sm);
}
.dbr-safari { background: linear-gradient(135deg, #0a3d1f 0%, #2db860 100%); }
.dbr-zanzi { background: linear-gradient(135deg, #0a2a3d 0%, #1a7aaa 100%); }
.dbr-kili { background: linear-gradient(160deg, #0c1a10 0%, #2d5e38 100%); }
.dbr-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.dbr-name {
  font-size: 16px;
  font-weight: 600;
  color: var(--text-dark);
}
.dbr-detail {
  font-size: 13px;
  color: var(--text-mid);
}
.status-badge {
  padding: 6px 12px;
  border-radius: var(--radius-full);
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}
.status-confirmed {
  background: var(--g100);
  color: var(--g700);
}
.status-pending {
  background: #fff3e0;
  color: #e65100;
}
.status-processing {
  background: #e3f2fd;
  color: #1565c0;
}
.dbr-price {
  font-family: var(--font-display);
  font-size: 20px;
  font-weight: 700;
  color: var(--g700);
}
.dbr-action-btn {
  padding: 10px 20px;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--white);
  font-size: 13px;
  font-weight: 600;
  color: var(--text-dark);
  cursor: pointer;
  transition: all var(--transition);
}
.dbr-action-btn:hover {
  border-color: var(--g500);
  background: var(--g50);
  color: var(--g700);
}

/* =============================================
   ADMIN DASHBOARD
   ============================================= */
#admin-dashboard {
  position: fixed;
  inset: 0;
  background: var(--off-white);
  z-index: 9998;
  display: none;
}
#admin-dashboard.active-view { display: flex; }
.admin-sidebar {
  width: 280px;
  background: var(--g900);
  padding: 32px 24px;
  display: flex;
  flex-direction: column;
  gap: 32px;
}
.adm-logo {
  border-bottom: 1px solid rgba(255,255,255,0.08);
  padding-bottom: 24px;
}
.adm-logo-name {
  font-family: var(--font-display);
  font-size: 22px;
  font-weight: 700;
  color: var(--white);
}
.adm-logo-sub {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--g400);
}
.adm-nav {
  display: flex;
  flex-direction: column;
  gap: 24px;
  flex: 1;
}
.adm-nav-section {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.adm-nav-label {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.4);
  margin-bottom: 8px;
  padding-left: 12px;
}
.adm-nav-item {
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 500;
  color: rgba(255,255,255,0.7);
  cursor: pointer;
  transition: all var(--transition);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.adm-nav-item:hover {
  background: rgba(255,255,255,0.08);
  color: var(--white);
}
.adm-nav-item.active {
  background: var(--g800);
  color: var(--white);
  border-left: 3px solid var(--g400);
}
.adm-badge {
  background: var(--g600);
  color: var(--white);
  font-size: 11px;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: var(--radius-full);
}
.admin-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.admin-topbar {
  background: var(--white);
  border-bottom: 1px solid var(--border);
  padding: 24px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.admin-topbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}
.admin-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--g600);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--font-display);
  font-size: 16px;
  font-weight: 700;
  color: var(--white);
}
.admin-content {
  flex: 1;
  padding: 32px 40px;
  overflow-y: auto;
}
.admin-metrics {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 32px;
}
.admin-metric-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  padding: 24px;
}
.amc-label {
  font-size: 12px;
  font-weight: 500;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  margin-bottom: 8px;
}
.amc-value {
  font-family: var(--font-display);
  font-size: 36px;
  font-weight: 700;
  color: var(--text-dark);
  line-height: 1;
  margin-bottom: 8px;
}
.amc-change {
  font-size: 13px;
  color: var(--g600);
  font-weight: 600;
}
.admin-grid-2 {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
  margin-bottom: 32px;
}
.admin-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  padding: 24px;
}
.admin-card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}
.admin-card-header h3 {
  font-family: var(--font-display);
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
}
.admin-card-header span {
  font-size: 13px;
  color: var(--g600);
  font-weight: 500;
  cursor: pointer;
}
.chart-area {
  display: flex;
  align-items: end;
  gap: 12px;
  height: 200px;
  padding: 0 12px;
}
.chart-bar-wrap {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}
.chart-bar {
  width: 100%;
  background: linear-gradient(to top, var(--g600), var(--g400));
  border-radius: var(--radius-sm) var(--radius-sm) 0 0;
  transition: all var(--transition);
}
.chart-bar.current {
  background: linear-gradient(to top, var(--g700), var(--g500));
}
.chart-month {
  font-size: 12px;
  color: var(--text-light);
}
.booking-table {
  width: 100%;
  border-collapse: collapse;
}
.booking-table thead {
  background: var(--off-white);
}
.booking-table th {
  padding: 14px 16px;
  text-align: left;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text-light);
}
.booking-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
}
.td-name {
  font-weight: 600;
  color: var(--text-dark);
}
.td-ref {
  font-family: monospace;
  font-size: 13px;
  color: var(--text-mid);
}
.td-amount {
  font-family: var(--font-display);
  font-size: 18px;
  font-weight: 700;
  color: var(--g700);
}

/* =============================================
   RESPONSIVE
   ============================================= */
@media (max-width: 1200px) {
  .why-grid { grid-template-columns: repeat(2, 1fr); }
  .packages-grid { grid-template-columns: repeat(2, 1fr); }
  .zanzibar-packages { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 992px) {
  .search-form { grid-template-columns: 1fr 1fr; }
  .destinations-grid { grid-template-columns: 1fr 1fr; }
  .dest-card:first-child { grid-column: 1 / -1; grid-row: auto; }
  .kili-grid { grid-template-columns: 1fr; }
  .zanzibar-intro { grid-template-columns: 1fr; }
  .gallery-grid { grid-template-columns: repeat(3, 1fr); }
  .gallery-item:first-child { grid-column: 1 / 3; }
  .inquiry-grid { grid-template-columns: 1fr; }
  .footer-grid { grid-template-columns: 1fr 1fr; }
  .dash-quick-grid { grid-template-columns: repeat(2, 1fr); }
  .dash-2col { grid-template-columns: 1fr; }
  .admin-metrics { grid-template-columns: repeat(2, 1fr); }
  .admin-grid-2 { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
  .nav-links { display: none; }
  .hamburger { display: flex; }
  .hero-stats-row { grid-template-columns: repeat(2, 1fr); }
  .search-form { grid-template-columns: 1fr; }
  .packages-grid { grid-template-columns: 1fr; }
  .zanzibar-packages { grid-template-columns: 1fr; }
  .why-grid { grid-template-columns: 1fr; }
  .process-steps { grid-template-columns: 1fr; }
  .process-steps::before { display: none; }
  .reviews-grid { grid-template-columns: 1fr; }
  .blog-grid { grid-template-columns: 1fr; }
  .gallery-grid { grid-template-columns: repeat(2, 1fr); }
  .gallery-item:first-child { grid-column: 1 / -1; }
  .footer-grid { grid-template-columns: 1fr; }
  .dash-quick-grid { grid-template-columns: 1fr; }
  .dash-booking-row { grid-template-columns: 1fr; gap: 12px; }
  .admin-sidebar { display: none; }
}

/* BREADCRUMBS */
.breadcrumbs { padding: 12px 0; font-size: 13px; color: var(--text-light); display: flex; gap: 8px; align-items: center; }
.breadcrumbs a { color: var(--g600); font-weight: 500; }
.breadcrumb-sep { color: var(--text-muted); font-size: 10px; }
</style>
</head>
<body>
<!-- =============================================
     NAVBAR
     ============================================= -->
<nav id="navbar">
  <div class="nav-inner">
    <div class="nav-logo" style="display: flex; flex-direction: row; align-items: center; gap: 12px;">
      <div style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--g400), var(--g600)); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <svg style="width: 28px; height: 28px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
      </div>
      <div style="display: flex; flex-direction: column; gap: 1px;">
        <div class="nav-logo-text">Savanna Trails</div>
        <div class="nav-logo-sub">Tanzania</div>
      </div>
    </div>
    <div class="nav-links">
      <a href="#hero">Home</a>
      <a href="#destinations">Destinations</a>
      <a href="#packages">Packages</a>
      <a href="#kilimanjaro">Kilimanjaro</a>
      <a href="#zanzibar">Zanzibar</a>
      <a href="#reviews">Reviews</a>
      <a href="#inquiry">Contact</a>
    </div>
    <div class="nav-actions">
      <div class="nav-phone">+255 767 000 888</div>
      <button class="btn btn-ghost btn-sm">Book Now</button>
    </div>
    <button class="hamburger" onclick="toggleMobile()">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <button class="mobile-close" onclick="toggleMobile()">×</button>
  <a href="#hero" onclick="toggleMobile()">Home</a>
  <a href="#destinations" onclick="toggleMobile()">Destinations</a>
  <a href="#packages" onclick="toggleMobile()">Packages</a>
  <a href="#kilimanjaro" onclick="toggleMobile()">Kilimanjaro</a>
  <a href="#zanzibar" onclick="toggleMobile()">Zanzibar</a>
  <a href="#reviews" onclick="toggleMobile()">Reviews</a>
  <a href="#inquiry" onclick="toggleMobile()">Contact</a>
</div>

<!-- =============================================
     HERO SECTION
     ============================================= -->
<section id="hero">
  <div class="hero-video-bg">
    <video autoplay muted loop playsinline>
      <source src="videos/tanzania-hero-cinematic.mp4" type="video/mp4">
      <!-- Drone video of Serengeti, Kilimanjaro sunrise, and wildlife cinematic footage -->
    </video>
  </div>
  <div class="hero-pattern"></div>
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>

  <div class="hero-content">
    <div class="hero-eyebrow">
      <div class="hero-eyebrow-dot"></div>
      <span>Tanzania's #1 Boutique Safari Operator</span>
    </div>
    <h1 class="hero-title">Explore Tanzania <span class="italic-green">Beyond Expectations</span></h1>
    <p class="hero-sub">Award-winning luxury safaris, Kilimanjaro trekking and Zanzibar beach escapes tailored to your dreams.</p>

    <div class="hero-cta-group">
      <button class="btn btn-primary btn-lg" onclick="document.querySelector('#packages').scrollIntoView({behavior:'smooth'})">Plan Your Safari</button>
      <button class="btn btn-ghost btn-lg" onclick="document.querySelector('#kilimanjaro').scrollIntoView({behavior:'smooth'})">Climb Kilimanjaro</button>
    </div>

    <div class="hero-scroll-hint">
      <div class="scroll-line"></div>
      <span>Scroll to explore</span>
    </div>
  </div>

  <div class="hero-floating-card">
    <div class="float-label">SINCE</div>
    <div class="float-value">2005</div>
    <div class="float-sub">19+ Years of Excellence</div>
  </div>

  <div class="hero-bottom-strip">
    <div class="hero-stats-row">
      <div class="hero-stat">
        <div class="hero-stat-num" data-target="15000">0</div>
        <div class="hero-stat-label">Happy Travelers</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num" data-target="98">0</div>
        <div class="hero-stat-label">% Satisfaction</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num" data-target="250">0</div>
        <div class="hero-stat-label">Expert Guides</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num" data-target="50">0</div>
        <div class="hero-stat-label">Unique Packages</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num" data-target="12">0</div>
        <div class="hero-stat-label">Destinations</div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     SEARCH SECTION
     ============================================= -->
<section id="search">
  <div class="search-inner">
    <h3 class="search-title">Find Your Perfect Adventure</h3>
    <div class="search-form">
      <div class="search-field">
        <label>Destination</label>
        <select>
          <option>All Destinations</option>
          <option>Serengeti</option>
          <option>Kilimanjaro</option>
          <option>Zanzibar</option>
          <option>Ngorongoro</option>
          <option>Tarangire</option>
        </select>
      </div>
      <div class="search-field">
        <label>Travel Date</label>
        <input type="date">
      </div>
      <div class="search-field">
        <label>Travelers</label>
        <input type="number" placeholder="2" min="1" max="20">
      </div>
      <div class="search-field">
        <label>Budget</label>
        <select>
          <option>Any Budget</option>
          <option>$1000 - $3000</option>
          <option>$3000 - $5000</option>
          <option>$5000 - $10000</option>
          <option>$10000+</option>
        </select>
      </div>
      <button class="btn btn-primary">Search</button>
    </div>
  </div>
</section>

<!-- =============================================
     DESTINATIONS SECTION
     ============================================= -->
<section id="destinations" class="section-pad">
  <div class="container">
    <div class="section-header">
      <div class="section-label">EXPLORE</div>
      <h2 class="display-lg">Top Destinations</h2>
      <p>From the vast plains of the Serengeti to the pristine beaches of Zanzibar, discover Tanzania's most breathtaking locations.</p>
    </div>

    <div class="destinations-grid">
      <!-- Serengeti - Featured -->
      <div class="dest-card">
        <div class="dest-card-bg dest-serengeti"></div>
        <div class="dest-overlay"></div>
        <div class="dest-overlay-top"></div>
        <div class="dest-tag">MOST POPULAR</div>
        <div class="dest-info">
          <div class="dest-name">Serengeti National Park</div>
          <div class="dest-detail">Witness the Great Migration, home to millions of wildebeest and predators.</div>
          <button class="dest-explore">Explore Tours →</button>
        </div>
      </div>

      <!-- Kilimanjaro -->
      <div class="dest-card">
        <div class="dest-card-bg dest-kili"></div>
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-name">Mount Kilimanjaro</div>
          <div class="dest-detail">Africa's highest peak at 5,895m.</div>
        </div>
      </div>

      <!-- Zanzibar -->
      <div class="dest-card">
        <div class="dest-card-bg dest-zanzibar"></div>
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-name">Zanzibar</div>
          <div class="dest-detail">Paradise beaches and historic Stone Town.</div>
        </div>
      </div>

      <!-- Ngorongoro -->
      <div class="dest-card">
        <div class="dest-card-bg dest-ngoro"></div>
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-name">Ngorongoro Crater</div>
          <div class="dest-detail">The Garden of Eden.</div>
        </div>
      </div>

      <!-- Tarangire -->
      <div class="dest-card">
        <div class="dest-card-bg dest-tarangire"></div>
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-name">Tarangire</div>
          <div class="dest-detail">Famous for tree-climbing lions.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     PACKAGES SECTION
     ============================================= -->
<section id="packages" class="section-pad">
  <div class="container">
    <div class="section-header">
      <div class="section-label">ADVENTURES</div>
      <h2 class="display-lg">Our Safari Packages</h2>
      <p>Choose from our carefully curated selection of safari experiences, trekking adventures, and beach getaways.</p>
    </div>

    <div class="packages-tabs">
      <button class="pkg-tab active" onclick="filterPkgs(this, 'all')">All Packages</button>
      <button class="pkg-tab" onclick="filterPkgs(this, 'safari')">Safari</button>
      <button class="pkg-tab" onclick="filterPkgs(this, 'kilimanjaro')">Kilimanjaro</button>
      <button class="pkg-tab" onclick="filterPkgs(this, 'zanzibar')">Zanzibar</button>
      <button class="pkg-tab" onclick="filterPkgs(this, 'combo')">Combo</button>
    </div>

    <div class="packages-grid">
      <!-- Package 1 - Featured -->
      <div class="pkg-card featured" data-category="safari">
        <div class="pkg-image">
          <div class="pkg-img-bg pkg-safari-bg"></div>
          <div class="pkg-badge badge-bestseller">BESTSELLER</div>
          <div class="pkg-duration">7 DAYS</div>
        </div>
        <div class="pkg-body">
          <div class="pkg-category">SAFARI</div>
          <h3 class="pkg-name">Serengeti & Ngorongoro Classic</h3>
          <div class="pkg-highlights">
            <span class="pkg-highlight">Serengeti</span>
            <span class="pkg-highlight">Ngorongoro</span>
            <span class="pkg-highlight">Tarangire</span>
          </div>
          <div class="pkg-meta">
            <div class="pkg-meta-item">
              <span class="meta-label">Duration</span>
              <span class="meta-value">7 Days</span>
            </div>
            <div class="pkg-meta-item">
              <span class="meta-label">Group Size</span>
              <span class="meta-value">Up to 8</span>
            </div>
          </div>
          <div class="pkg-footer">
            <div class="pkg-price">
              <span class="from-text">From</span>
              <span class="price-amount">$5,700</span>
              <span class="price-per">/ person</span>
            </div>
            <div class="pkg-stars">
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-count">(248)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Package 2 -->
      <div class="pkg-card" data-category="kilimanjaro">
        <div class="pkg-image">
          <div class="pkg-img-bg pkg-kili-bg"></div>
          <div class="pkg-badge badge-popular">POPULAR</div>
          <div class="pkg-duration">8 DAYS</div>
        </div>
        <div class="pkg-body">
          <div class="pkg-category">KILIMANJARO</div>
          <h3 class="pkg-name">Machame Route Trek</h3>
          <div class="pkg-highlights">
            <span class="pkg-highlight">Scenic Route</span>
            <span class="pkg-highlight">High Success</span>
          </div>
          <div class="pkg-meta">
            <div class="pkg-meta-item">
              <span class="meta-label">Duration</span>
              <span class="meta-value">8 Days</span>
            </div>
            <div class="pkg-meta-item">
              <span class="meta-label">Difficulty</span>
              <span class="meta-value">Challenging</span>
            </div>
          </div>
          <div class="pkg-footer">
            <div class="pkg-price">
              <span class="from-text">From</span>
              <span class="price-amount">$3,990</span>
              <span class="price-per">/ person</span>
            </div>
            <div class="pkg-stars">
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-count">(187)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Package 3 -->
      <div class="pkg-card" data-category="combo">
        <div class="pkg-image">
          <div class="pkg-img-bg pkg-combo-bg"></div>
          <div class="pkg-badge badge-new">NEW</div>
          <div class="pkg-duration">12 DAYS</div>
        </div>
        <div class="pkg-body">
          <div class="pkg-category">COMBO</div>
          <h3 class="pkg-name">Ultimate Safari & Beach</h3>
          <div class="pkg-highlights">
            <span class="pkg-highlight">Safari</span>
            <span class="pkg-highlight">Zanzibar</span>
          </div>
          <div class="pkg-meta">
            <div class="pkg-meta-item">
              <span class="meta-label">Duration</span>
              <span class="meta-value">12 Days</span>
            </div>
            <div class="pkg-meta-item">
              <span class="meta-label">Type</span>
              <span class="meta-value">All-Inclusive</span>
            </div>
          </div>
          <div class="pkg-footer">
            <div class="pkg-price">
              <span class="from-text">From</span>
              <span class="price-amount">$8,900</span>
              <span class="price-per">/ person</span>
            </div>
            <div class="pkg-stars">
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-count">(142)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Package 4 -->
      <div class="pkg-card" data-category="zanzibar">
        <div class="pkg-image">
          <div class="pkg-img-bg pkg-zanzibar-bg"></div>
          <div class="pkg-duration">5 DAYS</div>
        </div>
        <div class="pkg-body">
          <div class="pkg-category">ZANZIBAR</div>
          <h3 class="pkg-name">Zanzibar Luxury Escape</h3>
          <div class="pkg-highlights">
            <span class="pkg-highlight">Beach</span>
            <span class="pkg-highlight">Spa</span>
          </div>
          <div class="pkg-meta">
            <div class="pkg-meta-item">
              <span class="meta-label">Duration</span>
              <span class="meta-value">5 Days</span>
            </div>
            <div class="pkg-meta-item">
              <span class="meta-label">Stay</span>
              <span class="meta-value">5★ Resort</span>
            </div>
          </div>
          <div class="pkg-footer">
            <div class="pkg-price">
              <span class="from-text">From</span>
              <span class="price-amount">$2,950</span>
              <span class="price-per">/ person</span>
            </div>
            <div class="pkg-stars">
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-count">(312)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Package 5 -->
      <div class="pkg-card" data-category="safari">
        <div class="pkg-image">
          <div class="pkg-img-bg pkg-ngoro-bg"></div>
          <div class="pkg-duration">4 DAYS</div>
        </div>
        <div class="pkg-body">
          <div class="pkg-category">SAFARI</div>
          <h3 class="pkg-name">Northern Circuit Highlights</h3>
          <div class="pkg-highlights">
            <span class="pkg-highlight">Ngorongoro</span>
            <span class="pkg-highlight">Tarangire</span>
          </div>
          <div class="pkg-meta">
            <div class="pkg-meta-item">
              <span class="meta-label">Duration</span>
              <span class="meta-value">4 Days</span>
            </div>
            <div class="pkg-meta-item">
              <span class="meta-label">Type</span>
              <span class="meta-value">Private</span>
            </div>
          </div>
          <div class="pkg-footer">
            <div class="pkg-price">
              <span class="from-text">From</span>
              <span class="price-amount">$3,200</span>
              <span class="price-per">/ person</span>
            </div>
            <div class="pkg-stars">
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-count">(95)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Package 6 -->
      <div class="pkg-card" data-category="kilimanjaro">
        <div class="pkg-image">
          <div class="pkg-img-bg pkg-tara-bg"></div>
          <div class="pkg-badge badge-limited">LIMITED</div>
          <div class="pkg-duration">6 DAYS</div>
        </div>
        <div class="pkg-body">
          <div class="pkg-category">KILIMANJARO</div>
          <h3 class="pkg-name">Marangu Route Trek</h3>
          <div class="pkg-highlights">
            <span class="pkg-highlight">Hut Stay</span>
            <span class="pkg-highlight">Coca-Cola</span>
          </div>
          <div class="pkg-meta">
            <div class="pkg-meta-item">
              <span class="meta-label">Duration</span>
              <span class="meta-value">6 Days</span>
            </div>
            <div class="pkg-meta-item">
              <span class="meta-label">Difficulty</span>
              <span class="meta-value">Moderate</span>
            </div>
          </div>
          <div class="pkg-footer">
            <div class="pkg-price">
              <span class="from-text">From</span>
              <span class="price-amount">$3,150</span>
              <span class="price-per">/ person</span>
            </div>
            <div class="pkg-stars">
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-filled">★</span>
              <span class="star-count">(156)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     KILIMANJARO SECTION
     ============================================= -->
<section id="kilimanjaro" class="section-pad">
  <div class="container">
    <div class="kili-grid">
      <div class="kili-left">
        <div class="section-header">
          <div class="section-label">TREK</div>
          <h2 class="display-lg">Conquer Kilimanjaro</h2>
          <p>Stand on the roof of Africa. Choose from our expert-guided treks with the highest success rates on the mountain.</p>
        </div>

        <div class="kili-routes">
          <div class="kili-route active" onclick="setRoute(this)">
            <div class="route-name">Machame Route</div>
            <div class="route-detail">Whiskey Route • 7 Days • Scenic</div>
            <div class="route-price">From $3,990</div>
          </div>
          <div class="kili-route" onclick="setRoute(this)">
            <div class="route-name">Lemosho Route</div>
            <div class="route-detail">Most Scenic • 8 Days</div>
            <div class="route-price">From $4,450</div>
          </div>
          <div class="kili-route" onclick="setRoute(this)">
            <div class="route-name">Marangu Route</div>
            <div class="route-detail">Coca-Cola Route • 6 Days</div>
            <div class="route-price">From $3,150</div>
          </div>
          <div class="kili-route" onclick="setRoute(this)">
            <div class="route-name">Umbwe Route</div>
            <div class="route-detail">Direct • 6 Days • Challenging</div>
            <div class="route-price">From $2,950</div>
          </div>
        </div>

        <button class="btn btn-primary btn-lg">View All Kilimanjaro Treks</button>
      </div>

      <div class="kili-visual">
        <div class="kili-mountain">
          <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 400L200 80L400 400H0Z" fill="url(#paint0_linear)"/>
            <path d="M140 240L200 80L260 240L140 240Z" fill="url(#paint1_linear)"/>
            <defs>
              <linearGradient id="paint0_linear" x1="200" y1="80" x2="200" y2="400" gradientUnits="userSpaceOnUse">
                <stop stop-color="#0c1a10"/>
                <stop offset="1" stop-color="#1e4028"/>
              </linearGradient>
              <linearGradient id="paint1_linear" x1="200" y1="80" x2="200" y2="240" gradientUnits="userSpaceOnUse">
                <stop stop-color="#ffffff" stop-opacity="0.9"/>
                <stop offset="1" stop-color="#ffffff" stop-opacity="0.1"/>
              </linearGradient>
            </defs>
          </svg>
        </div>
        <div class="kili-stats-overlay">
          <div class="kili-stat-chip">
            <div class="ksc-num">5,895m</div>
            <div class="ksc-label">Summit</div>
          </div>
          <div class="kili-stat-chip">
            <div class="ksc-num">85%</div>
            <div class="ksc-label">Success Rate</div>
          </div>
          <div class="kili-stat-chip">
            <div class="ksc-num">6-8 Days</div>
            <div class="ksc-label">Duration</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     ZANZIBAR SECTION
     ============================================= -->
<section id="zanzibar" class="section-pad">
  <div class="container">
    <div class="zanzibar-intro">
      <div class="zanzibar-visual">
        <div class="zanzibar-waves">
          <svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 300V180C40 150 80 200 120 170C160 140 200 190 240 160C280 130 320 180 360 150C400 120 440 170 480 140L480 300H0Z" fill="url(#paint0_linear_waves)" opacity="0.6"/>
            <path d="M0 300V220C50 190 100 240 150 210C200 180 250 230 300 200C350 170 400 220 450 190L480 300H0Z" fill="url(#paint1_linear_waves)" opacity="0.8"/>
            <defs>
              <linearGradient id="paint0_linear_waves" x1="200" y1="140" x2="200" y2="300" gradientUnits="userSpaceOnUse">
                <stop stop-color="#1a7aaa"/>
                <stop offset="1" stop-color="#0a2a3d"/>
              </linearGradient>
              <linearGradient id="paint1_linear_waves" x1="240" y1="180" x2="240" y2="300" gradientUnits="userSpaceOnUse">
                <stop stop-color="#0f4a78"/>
                <stop offset="1" stop-color="#061218"/>
              </linearGradient>
            </defs>
          </svg>
        </div>
      </div>
      <div>
        <div class="section-header">
          <div class="section-label">RELAX</div>
          <h2 class="display-lg">Zanzibar Beach Paradise</h2>
          <p>After your safari, unwind on pristine white-sand beaches, explore historic Stone Town, and dive into crystal-clear waters.</p>
        </div>
        <button class="btn btn-primary btn-lg">Explore Zanzibar Packages</button>
      </div>
    </div>

    <div class="zanzibar-packages">
      <div class="z-pkg-card">
        <div class="z-pkg-img z-ocean"></div>
        <div class="z-pkg-body">
          <h3 class="z-pkg-name">Beach & Spa Retreat</h3>
          <p class="z-pkg-detail">7 nights at luxury resort with daily spa treatments.</p>
          <div class="z-pkg-footer">
            <div class="z-price">$3,450</div>
            <button class="btn btn-primary btn-sm">View</button>
          </div>
        </div>
      </div>
      <div class="z-pkg-card">
        <div class="z-pkg-img z-sunset"></div>
        <div class="z-pkg-body">
          <h3 class="z-pkg-name">Romantic Honeymoon</h3>
          <p class="z-pkg-detail">Private villas, sunset dinners, and couples spa.</p>
          <div class="z-pkg-footer">
            <div class="z-price">$4,950</div>
            <button class="btn btn-primary btn-sm">View</button>
          </div>
        </div>
      </div>
      <div class="z-pkg-card">
        <div class="z-pkg-img z-stone"></div>
        <div class="z-pkg-body">
          <h3 class="z-pkg-name">Culture & History Tour</h3>
          <p class="z-pkg-detail">Stone Town, spice tours, and Jozani Forest.</p>
          <div class="z-pkg-footer">
            <div class="z-price">$2,100</div>
            <button class="btn btn-primary btn-sm">View</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     WHY CHOOSE US
     ============================================= -->
<section id="why" class="section-pad">
  <div class="container">
    <div class="section-header center">
      <div class="section-label">WHY US</div>
      <h2 class="display-lg">Why Travel With Savanna Trails?</h2>
      <p>We've been crafting unforgettable African adventures for over 19 years.</p>
    </div>

    <div class="why-grid">
      <div class="why-card">
        <div class="why-num">01</div>
        <h3 class="why-title">Expert Local Guides</h3>
        <p class="why-desc">Our team of 250+ expert guides know Tanzania like no other.</p>
      </div>
      <div class="why-card">
        <div class="why-num">02</div>
        <h3 class="why-title">98% Satisfaction Rate</h3>
        <p class="why-desc">Don't just take our word for it - read our 15,000+ reviews.</p>
      </div>
      <div class="why-card">
        <div class="why-num">03</div>
        <h3 class="why-title">Sustainable Tourism</h3>
        <p class="why-desc">Committed to conservation and supporting local communities.</p>
      </div>
      <div class="why-card">
        <div class="why-num">04</div>
        <h3 class="why-title">100% Financial Protection</h3>
        <p class="why-desc">Your booking is fully protected and secure.</p>
      </div>
    </div>

    <div class="trust-badges">
      <div class="trust-badge">
        <div class="trust-badge-title">TATO Certified</div>
        <div class="trust-badge-sub">Tanzania Association of Tour Operators</div>
      </div>
      <div class="trust-badge">
        <div class="trust-badge-title">Kilimanjaro Porters</div>
        <div class="trust-badge-sub">Fair Treatment Certified</div>
      </div>
      <div class="trust-badge">
        <div class="trust-badge-title">Eco-Tourism</div>
        <div class="trust-badge-sub">Sustainable Travel Partner</div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     PROCESS / HOW IT WORKS
     ============================================= -->
<section id="process" class="section-pad">
  <div class="container">
    <div class="section-header center">
      <div class="section-label">HOW IT WORKS</div>
      <h2 class="display-lg">Plan Your Dream Safari</h2>
      <p>From first inquiry to farewell, we're with you every step of the way.</p>
    </div>

    <div class="process-steps">
      <div class="process-step">
        <div class="step-num">01</div>
        <h3 class="step-title">Tell Us Your Dream</h3>
        <p class="step-desc">Fill out our inquiry form or give us a call. What does your ideal African adventure look like?</p>
      </div>
      <div class="process-step">
        <div class="step-num">02</div>
        <h3 class="step-title">Custom Itinerary</h3>
        <p class="step-desc">Our safari experts craft a personalized itinerary tailored perfectly to your wishes.</p>
      </div>
      <div class="process-step">
        <div class="step-num">03</div>
        <h3 class="step-title">Book & Prepare</h3>
        <p class="step-desc">Secure your booking with a deposit. We send you a detailed preparation guide.</p>
      </div>
      <div class="process-step">
        <div class="step-num">04</div>
        <h3 class="step-title">Enjoy Your Adventure</h3>
        <p class="step-desc">Your dream safari becomes reality! Our team ensures everything runs smoothly.</p>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     REVIEWS SECTION
     ============================================= -->
<section id="reviews" class="section-pad">
  <div class="container">
    <div class="section-header center">
      <div class="section-label">TESTIMONIALS</div>
      <h2 class="display-lg">What Our Travelers Say</h2>
      <p>Real experiences from real travelers who've explored Tanzania with us.</p>
    </div>

    <div class="reviews-grid">
      <!-- Featured Review -->
      <div class="review-card featured-review">
        <div class="review-stars">
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
        </div>
        <p class="review-text">"Absolutely life-changing experience! Our guide knew every animal's behavior, we saw all the Big Five, and the Kilimanjaro summit was unforgettable. Already planning our return!"</p>
        <div class="review-author">
          <div class="reviewer-avatar av-1">SM</div>
          <div>
            <div class="reviewer-name">Sarah Mitchell</div>
            <div class="reviewer-loc">New York, USA</div>
            <div class="reviewer-trip">12-Day Safari & Kilimanjaro</div>
          </div>
        </div>
      </div>

      <!-- Review 2 -->
      <div class="review-card">
        <div class="review-stars">
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
        </div>
        <p class="review-text">"Perfect honeymoon! Safari followed by Zanzibar beach time. The lodges were incredible, service impeccable, and the memories priceless."</p>
        <div class="review-author">
          <div class="reviewer-avatar av-2">MJ</div>
          <div>
            <div class="reviewer-name">Marco & Julia</div>
            <div class="reviewer-loc">Milan, Italy</div>
            <div class="reviewer-trip">Honeymoon Package</div>
          </div>
        </div>
      </div>

      <!-- Review 3 -->
      <div class="review-card">
        <div class="review-stars">
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
          <span class="rv-star">★</span>
        </div>
        <p class="review-text">"Family-friendly, professional, and so much fun! Kids loved every minute, especially the wildebeest migration. Thank you!"</p>
        <div class="review-author">
          <div class="reviewer-avatar av-3">KT</div>
          <div>
            <div class="reviewer-name">Kim & Family</div>
            <div class="reviewer-loc">London, UK</div>
            <div class="reviewer-trip">Family Safari</div>
          </div>
        </div>
      </div>
    </div>

    <div class="reviews-trust-bar">
      <div class="rtb-stat">
        <div class="rtb-num">4.9/5</div>
        <div class="rtb-label">Average Rating</div>
      </div>
      <div class="rtb-divider"></div>
      <div class="rtb-stat">
        <div class="rtb-num">15,000+</div>
        <div class="rtb-label">Happy Travelers</div>
      </div>
      <div class="rtb-divider"></div>
      <div class="rtb-stat">
        <div class="rtb-num">85%</div>
        <div class="rtb-label">Repeat Clients</div>
      </div>
      <div class="rtb-divider"></div>
      <div class="
