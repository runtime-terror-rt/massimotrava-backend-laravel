<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vyralabs | The World's Easiest Performance Lab Test</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root {
  --bg:#070b12;
  --bg2:#0d1117;
  --surface:#111827;
  --surface2:#1a2235;
  --surface3:#1e2d42;
  --border:rgba(255,255,255,.06);
  --border2:rgba(255,255,255,.1);
  --glass:rgba(13,17,23,.75);
  --glass2:rgba(17,24,39,.6);
  --c:#22d3ee;
  --c-glow:rgba(34,211,238,.3);
  --c2:#a78bfa;
  --c2-glow:rgba(167,139,250,.25);
  --c3:#34d399;
  --amber:#f59e0b;
  --text:#f1f5f9;
  --muted:#94a3b8;
  --muted2:#64748b;
  --font:'Plus Jakarta Sans',sans-serif;
  --body:'Inter',sans-serif;
  --r:14px;
  --r-sm:8px;
  --ease:cubic-bezier(.4,0,.2,1);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  font-family:var(--body);
  background:var(--bg);
  color:var(--text);
  min-height:100vh;
  overflow-x:hidden;
}

/* ========= NOISE + GRID BACKGROUND ========= */
body::before {
  content:'';
  position:fixed;
  inset:0;
  background-image:
    linear-gradient(rgba(34,211,238,.03) 1px,transparent 1px),
    linear-gradient(90deg,rgba(34,211,238,.03) 1px,transparent 1px);
  background-size:60px 60px;
  pointer-events:none;
  z-index:0;
}
body::after {
  content:'';
  position:fixed;
  inset:0;
  background:radial-gradient(ellipse 80% 60% at 50% -20%,rgba(34,211,238,.07),transparent 70%);
  pointer-events:none;
  z-index:0;
}

a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
.container{max-width:1240px;margin:0 auto;padding:0 32px;position:relative;z-index:1}

/* ========= ANNOUNCE BAR ========= */
.announce {
  background:linear-gradient(90deg,var(--surface) 0%,#0f1a2e 50%,var(--surface) 100%);
  border-bottom:1px solid var(--border);
  text-align:center;
  padding:10px 16px;
  font-size:12px;
  color:var(--muted);
  letter-spacing:.5px;
  overflow:hidden;
  position:relative;
}
.announce::before,.announce::after {
  content:'';
  position:absolute;
  top:0;bottom:0;
  width:60px;
  z-index:2;
}
.announce::before{left:0;background:linear-gradient(90deg,var(--surface),transparent)}
.announce::after{right:0;background:linear-gradient(-90deg,var(--surface),transparent)}
.announce .pill {
  background:linear-gradient(135deg,var(--c),var(--c2));
  color:#fff;
  font-weight:700;
  font-size:11px;
  padding:3px 10px;
  border-radius:20px;
  margin:0 8px;
  display:inline-block;
  animation:pillPulse 2s ease-in-out infinite;
}
@keyframes pillPulse{0%,100%{box-shadow:0 0 0 0 rgba(34,211,238,.4)}50%{box-shadow:0 0 0 6px rgba(34,211,238,0)}}

/* ========= NAVBAR ========= */
.navbar {
  position:sticky;top:0;z-index:900;
  background:rgba(7,11,18,.85);
  backdrop-filter:blur(24px);
  -webkit-backdrop-filter:blur(24px);
  border-bottom:1px solid var(--border);
  transition:background .3s var(--ease);
}
.navbar.scrolled{background:rgba(7,11,18,.95)}
.nav-inner{display:flex;align-items:center;justify-content:space-between;height:72px}
.logo{
  font-family:var(--font);
  font-weight:800;
  font-size:22px;
  letter-spacing:-.5px;
  display:flex;
  align-items:center;
  gap:10px;
  background:linear-gradient(135deg,#fff 0%,var(--c) 100%);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
}
.logo .dot{
  width:10px;height:10px;border-radius:50%;
  background:linear-gradient(135deg,var(--c),var(--c2));
  box-shadow:0 0 20px var(--c-glow);
  animation:dotPulse 3s ease-in-out infinite;
}
@keyframes dotPulse{0%,100%{box-shadow:0 0 10px var(--c-glow)}50%{box-shadow:0 0 25px var(--c-glow),0 0 40px rgba(34,211,238,.15)}}
.nav-links{display:flex;align-items:center;gap:32px}
.nav-links a{
  font-size:13.5px;font-weight:500;color:var(--muted);
  display:flex;align-items:center;gap:6px;
  transition:color .25s;
  position:relative;
}
.nav-links a::after{
  content:'';position:absolute;bottom:-4px;left:0;right:0;
  height:1px;background:var(--c);
  transform:scaleX(0);
  transition:transform .25s var(--ease);
}
.nav-links a:hover{color:#fff}
.nav-links a:hover::after{transform:scaleX(1)}
.nav-links a i{font-size:10px;color:var(--muted2)}
.nav-actions{display:flex;align-items:center;gap:12px}
.btn {
  display:inline-flex;align-items:center;gap:8px;justify-content:center;
  padding:10px 22px;border-radius:var(--r-sm);
  font-size:13px;font-weight:700;cursor:pointer;border:none;
  font-family:var(--body);transition:all .25s var(--ease);
  white-space:nowrap;letter-spacing:.2px;
}
.btn-primary {
  background:linear-gradient(135deg,var(--c),#0ea5e9);
  color:#000;
  font-weight:800;
  box-shadow:0 4px 20px rgba(34,211,238,.3),inset 0 1px 0 rgba(255,255,255,.2);
  position:relative;overflow:hidden;
}
.btn-primary::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);
  transform:translateX(-100%);
  transition:transform .4s var(--ease);
}
.btn-primary:hover::before{transform:translateX(100%)}
.btn-primary:hover{
  box-shadow:0 8px 30px rgba(34,211,238,.5),inset 0 1px 0 rgba(255,255,255,.2);
  transform:translateY(-1px);
}
.btn-ghost{
  background:rgba(255,255,255,.05);
  color:var(--text);
  border:1px solid var(--border2);
  transition:all .25s;
}
.btn-ghost:hover{background:rgba(255,255,255,.1);border-color:rgba(34,211,238,.4)}
.btn-lg{padding:14px 32px;font-size:14px;border-radius:12px}
.nav-toggle{
  display:none;background:none;border:1px solid var(--border);
  color:var(--text);width:40px;height:40px;border-radius:var(--r-sm);
  font-size:16px;cursor:pointer;
}

/* ========= HERO ========= */
.hero {
  position:relative;overflow:hidden;
  padding:100px 0 70px;
  border-bottom:1px solid var(--border);
}
.hero-bg-orb {
  position:absolute;
  top:-200px;right:-100px;
  width:700px;height:700px;
  border-radius:50%;
  background:radial-gradient(circle,rgba(34,211,238,.08) 0%,rgba(167,139,250,.04) 50%,transparent 70%);
  animation:orbDrift 15s ease-in-out infinite alternate;
  pointer-events:none;
}
@keyframes orbDrift{
  0%{transform:translate(0,0) scale(1)}
  100%{transform:translate(-60px,40px) scale(1.1)}
}
.hero-grid{
  display:grid;
  grid-template-columns:1.15fr .85fr;
  gap:64px;
  align-items:center;
}
.badge-row{display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap}
.badge{
  display:inline-flex;align-items:center;gap:6px;
  font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;
  padding:6px 12px;border-radius:20px;
  background:rgba(34,211,238,.08);
  border:1px solid rgba(34,211,238,.2);
  color:var(--c);
}
.badge i{color:var(--c3)}
.hero h1{
  font-family:var(--font);
  font-weight:800;
  font-size:60px;
  line-height:1.03;
  letter-spacing:-1.5px;
  color:#fff;
  margin-bottom:22px;
}
.hero h1 .grad{
  background:linear-gradient(135deg,var(--c) 0%,var(--c2) 100%);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
  background-size:200% 100%;
  animation:gradShift 4s ease-in-out infinite alternate;
}
@keyframes gradShift{0%{background-position:0% 50%}100%{background-position:100% 50%}}
.hero p{font-size:16px;color:var(--muted);line-height:1.75;max-width:500px;margin-bottom:30px}
.hero-cta-row{display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:30px}
.hero-trustline{display:flex;flex-direction:column;gap:10px;font-size:13px;color:var(--muted)}
.hero-trustline div{display:flex;align-items:center;gap:8px}
.hero-trustline i{color:var(--c3);width:16px}
.stars{color:var(--amber);letter-spacing:2px}

/* Hero visual — animated sphere */
.hero-visual{position:relative;display:flex;justify-content:center;align-items:center;height:420px}
.sphere-wrap{
  position:relative;
  width:280px;height:280px;
  display:flex;align-items:center;justify-content:center;
}
.sphere-core {
  width:180px;height:180px;
  border-radius:50%;
  background:radial-gradient(circle at 35% 35%,rgba(34,211,238,.25),rgba(167,139,250,.15) 50%,rgba(7,11,18,.9) 80%);
  border:1px solid rgba(34,211,238,.3);
  display:flex;align-items:center;justify-content:center;
  font-family:var(--font);font-weight:800;font-size:18px;color:#fff;
  box-shadow:
    0 0 60px rgba(34,211,238,.2),
    0 0 120px rgba(34,211,238,.08),
    inset 0 0 40px rgba(34,211,238,.1);
  position:relative;z-index:3;
  animation:spherePulse 4s ease-in-out infinite;
}
@keyframes spherePulse{
  0%,100%{box-shadow:0 0 40px rgba(34,211,238,.2),0 0 80px rgba(34,211,238,.06),inset 0 0 30px rgba(34,211,238,.08)}
  50%{box-shadow:0 0 80px rgba(34,211,238,.35),0 0 160px rgba(34,211,238,.12),inset 0 0 50px rgba(34,211,238,.15)}
}
.sphere-ring {
  position:absolute;
  border-radius:50%;
  border:1px solid rgba(34,211,238,.15);
  animation:ringExpand 4s ease-out infinite;
}
.ring-1{width:220px;height:220px;animation-delay:0s}
.ring-2{width:260px;height:260px;animation-delay:1.3s}
.ring-3{width:300px;height:300px;animation-delay:2.6s;border-color:rgba(167,139,250,.1)}
@keyframes ringExpand{
  0%{opacity:.8;transform:scale(.9)}
  100%{opacity:0;transform:scale(1.15)}
}
.orb-orbit {
  position:absolute;
  width:320px;height:320px;
  border-radius:50%;
  border:1px dashed rgba(34,211,238,.12);
  animation:orbitSpin 20s linear infinite;
}
.orb-dot {
  position:absolute;
  width:10px;height:10px;border-radius:50%;
  background:var(--c);
  box-shadow:0 0 12px var(--c-glow);
  top:-5px;left:50%;transform:translateX(-50%);
}
@keyframes orbitSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}

.float-card {
  position:absolute;
  background:rgba(13,17,30,.85);
  backdrop-filter:blur(20px);
  border:1px solid rgba(34,211,238,.2);
  border-radius:12px;
  padding:12px 16px;
  font-size:12px;font-weight:600;color:var(--text);
  box-shadow:0 8px 32px rgba(0,0,0,.4),0 0 20px rgba(34,211,238,.06);
  display:flex;align-items:center;gap:8px;
  animation:floatY 5s ease-in-out infinite;
  z-index:5;
}
.float-card i{color:var(--c3)}
.float-1{top:8%;left:-5%;animation-delay:.2s}
.float-2{bottom:20%;right:-8%;animation-delay:1.1s}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}

/* trust strip */
.trust-strip {
  border-top:1px solid var(--border);
  margin-top:60px;
  padding-top:30px;
  display:flex;align-items:center;gap:40px;flex-wrap:wrap;
}
.trust-item{
  display:flex;align-items:center;gap:8px;
  font-size:13px;font-weight:500;color:var(--muted);
  transition:color .2s;
}
.trust-item:hover{color:var(--c)}
.trust-item i{color:var(--c)}

/* ========= SECTION COMMONS ========= */
section{padding:88px 0;position:relative}
.section-eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  font-size:11px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;
  color:var(--c);margin-bottom:14px;
}
.section-eyebrow::before{content:'';width:20px;height:2px;background:linear-gradient(90deg,var(--c),var(--c2));border-radius:2px}
.section-title{
  font-family:var(--font);font-weight:800;font-size:38px;
  letter-spacing:-.8px;color:#fff;line-height:1.18;
}
.section-title strong{
  background:linear-gradient(135deg,var(--c),var(--c2));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.section-sub{font-size:15px;color:var(--muted);margin-top:14px;line-height:1.75}
.center{text-align:center;margin-left:auto;margin-right:auto}

/* ========= HOW IT WORKS ========= */
.how-section{border-top:1px solid var(--border)}
.how-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.how-visual {
  border-radius:var(--r);
  overflow:hidden;
  border:1px solid rgba(34,211,238,.15);
  background:linear-gradient(145deg,var(--surface2),var(--bg));
  aspect-ratio:4/5;
  position:relative;
  display:flex;align-items:center;justify-content:center;
}
.how-visual::before{
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at 50% 50%,rgba(34,211,238,.12),transparent 65%);
}
.how-visual-orb {
  width:160px;height:160px;border-radius:50%;
  background:radial-gradient(circle at 35% 35%,rgba(34,211,238,.2),rgba(7,11,18,.95));
  border:1px solid rgba(34,211,238,.3);
  display:flex;align-items:center;justify-content:center;
  font-family:var(--font);font-weight:800;color:#fff;font-size:18px;
  box-shadow:0 0 60px rgba(34,211,238,.2);
  position:relative;z-index:2;
}
.how-steps{display:flex;flex-direction:column}
.how-step{
  display:flex;gap:24px;align-items:flex-start;
  padding:28px 0;
  border-bottom:1px solid var(--border);
  position:relative;
  transition:all .3s;
}
.how-step:last-child{border-bottom:none}
.how-step::before{
  content:'';
  position:absolute;
  left:-20px;top:0;bottom:0;
  width:2px;
  background:linear-gradient(180deg,transparent,var(--c),transparent);
  opacity:0;
  transition:opacity .3s;
}
.how-step:hover::before{opacity:1}
.how-num{
  font-family:var(--font);font-weight:800;font-size:48px;
  color:transparent;
  -webkit-text-stroke:1.5px rgba(255,255,255,.1);
  width:64px;flex-shrink:0;
  transition:all .3s;
  line-height:1;
}
.how-step:hover .how-num{
  -webkit-text-stroke:1.5px var(--c);
  color:rgba(34,211,238,.1);
}
.how-step-body .tag{
  font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
  color:var(--c3);margin-bottom:6px;
}
.how-step-body h3{font-family:var(--font);font-size:21px;font-weight:700;color:#fff;margin-bottom:6px}
.how-step-body p{font-size:13.5px;color:var(--muted);line-height:1.65}

/* ========= COMPARE ========= */
.compare-section{
  background:var(--surface);
  border-top:1px solid var(--border);
  border-bottom:1px solid var(--border);
}
.compare-grid{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.compare-img-wrap{
  display:flex;align-items:center;justify-content:center;
  padding:20px;
}

/* ========= INSIGHTS ========= */
.insights-section {
  background:linear-gradient(135deg,var(--bg) 0%,#080e1a 40%,#091626 70%,#0c2035 100%);
  border-top:1px solid var(--border);
  border-bottom:1px solid var(--border);
  overflow:hidden;
}
.insights-head{
  display:flex;justify-content:space-between;align-items:flex-start;gap:40px;flex-wrap:wrap;
  margin-bottom:64px;
}
.insights-head h2{
  font-family:var(--font);font-weight:800;font-size:38px;
  letter-spacing:-.8px;color:#fff;line-height:1.2;max-width:520px;
}
.insights-head p{font-size:14px;color:var(--muted);line-height:1.75;max-width:300px;text-align:right}
.insights-cards-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:20px;
  max-width:1000px;margin:0 auto;
}

/* Glassmorphic insight cards */
.i-card {
  background:rgba(13,17,30,.7);
  backdrop-filter:blur(20px);
  border:1px solid rgba(255,255,255,.08);
  border-radius:16px;
  padding:22px;
  transition:all .35s var(--ease);
  position:relative;
  overflow:hidden;
}
.i-card::before{
  content:'';
  position:absolute;
  inset:0;
  background:linear-gradient(135deg,rgba(34,211,238,.03),transparent 50%);
  opacity:0;
  transition:opacity .35s;
}
.i-card:hover{
  border-color:rgba(34,211,238,.25);
  transform:translateY(-4px);
  box-shadow:0 20px 60px rgba(0,0,0,.4),0 0 30px rgba(34,211,238,.06);
}
.i-card:hover::before{opacity:1}

.photo-card {
  grid-row:span 2;
  padding:0;overflow:hidden;
}
.photo-card img{width:100%;height:100%;object-fit:cover}

.ic-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.ic-title{font-size:14px;font-weight:600;color:#fff}
.ic-badge{
  font-size:11px;font-weight:700;
  padding:4px 12px;border-radius:20px;
}
.ic-badge.cyan{background:rgba(34,211,238,.15);color:var(--c)}
.ic-badge.amber{background:rgba(245,158,11,.15);color:var(--amber)}
.ic-badge.green{background:rgba(52,211,153,.15);color:var(--c3)}
.ic-badge.purple{background:rgba(167,139,250,.15);color:var(--c2)}

/* ferritin chart */
.ferritin-mini-chart{height:72px;margin:12px 0 8px}
.ferritin-mini-chart svg{width:100%;height:100%;overflow:visible}
.chart-months{display:flex;justify-content:space-between;font-size:10px;color:var(--muted2)}

/* score gauge */
.gauge-wrap{position:relative;width:140px;height:140px;margin:8px auto}
.gauge-wrap svg{width:100%;height:100%}
.gauge-center{
  position:absolute;inset:0;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  font-family:var(--font);
}
.gauge-num{font-weight:800;font-size:32px;color:#fff;line-height:1}
.gauge-arrow{font-size:13px;color:var(--c3);margin-top:2px}

/* biomarker bar */
.bm-bar-track{
  height:8px;border-radius:6px;
  background:rgba(255,255,255,.06);
  overflow:hidden;display:flex;margin-top:14px;
}
.bm-bar-track .seg{height:100%;transition:width 1.2s var(--ease)}

/* insight text card */
.insight-tag{
  display:flex;align-items:center;gap:6px;
  font-size:11px;font-weight:700;color:var(--amber);
  margin-bottom:12px;letter-spacing:.5px;text-transform:uppercase;
}
.read-more{color:var(--c);font-weight:600;cursor:pointer}
.read-more:hover{text-decoration:underline}
.ask-chat{
  margin-top:16px;padding-top:12px;
  border-top:1px solid rgba(255,255,255,.06);
  font-size:11.5px;color:var(--c);
  display:flex;align-items:center;gap:8px;cursor:pointer;
  font-weight:500;
}
.ask-chat:hover{color:var(--c2)}

/* supplement card */
.supp-lbl{font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--muted2);margin-bottom:10px}
.supp-row{
  display:flex;justify-content:space-between;align-items:center;
  background:rgba(255,255,255,.04);
  border-radius:8px;
  padding:9px 12px;
  margin-bottom:8px;
  font-size:12.5px;
}
.supp-row span:first-child{color:var(--text);font-weight:500}
.supp-freq{
  font-size:10.5px;font-weight:700;
  padding:2px 9px;border-radius:10px;
}
.freq-amber{background:rgba(245,158,11,.12);color:var(--amber)}
.freq-cyan{background:rgba(34,211,238,.12);color:var(--c)}
.freq-green{background:rgba(52,211,153,.12);color:var(--c3)}
.card-divider{height:1px;background:var(--border);margin:14px 0}

/* ========= USE CASES ========= */
.usecase-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.usecase-card {
  background:rgba(13,17,30,.7);
  border:1px solid var(--border);
  border-radius:var(--r);
  overflow:hidden;
  transition:all .3s var(--ease);
  cursor:pointer;
}
.usecase-card:hover{
  transform:translateY(-6px);
  border-color:rgba(34,211,238,.35);
  box-shadow:0 24px 48px rgba(0,0,0,.4),0 0 30px rgba(34,211,238,.06);
}
.usecase-icon-wrap {
  aspect-ratio:4/3;
  background:linear-gradient(145deg,var(--surface2),var(--bg));
  display:flex;align-items:center;justify-content:center;
  font-size:32px;color:var(--c);
  position:relative;overflow:hidden;
  transition:all .3s;
}
.usecase-icon-wrap::after{
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at 30% 40%,rgba(34,211,238,.15),transparent 60%);
}
.usecase-card:hover .usecase-icon-wrap{background:linear-gradient(145deg,#1a2a40,var(--surface2))}
.usecase-body{padding:20px 22px 24px}
.usecase-body h4{font-family:var(--font);font-size:16px;font-weight:700;color:#fff;margin-bottom:6px}
.usecase-body p{font-size:12.5px;color:var(--muted);line-height:1.65}

/* ========= SECURITY ========= */
.security-section{
  background:linear-gradient(180deg,var(--bg) 0%,var(--surface) 100%);
  border-top:1px solid var(--border);
  border-bottom:1px solid var(--border);
}
.security-grid{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.security-items{display:flex;flex-direction:column;gap:14px}
.security-item {
  display:flex;gap:16px;align-items:flex-start;
  background:rgba(13,17,30,.7);
  border:1px solid var(--border);
  border-radius:var(--r);
  padding:20px 22px;
  transition:all .3s;
}
.security-item:hover{border-color:rgba(34,211,238,.25)}
.security-item .icon{
  width:42px;height:42px;border-radius:10px;flex-shrink:0;
  background:rgba(34,211,238,.1);color:var(--c);
  display:flex;align-items:center;justify-content:center;font-size:16px;
}
.security-item h4{font-family:var(--font);font-size:15px;font-weight:700;color:#fff;margin-bottom:4px}
.security-item p{font-size:12.5px;color:var(--muted);line-height:1.65}
.security-stats{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.stat-card {
  background:rgba(13,17,30,.7);
  border:1px solid var(--border);
  border-radius:var(--r);
  padding:24px;text-align:center;
  transition:all .35s;
  position:relative;overflow:hidden;
}
.stat-card::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(34,211,238,.03),transparent 60%);
  opacity:0;transition:opacity .35s;
}
.stat-card:hover{border-color:rgba(34,211,238,.25);transform:translateY(-3px)}
.stat-card:hover::after{opacity:1}
.stat-pct{
  font-family:var(--font);font-weight:800;font-size:38px;
  letter-spacing:-.5px;line-height:1;
  display:block;margin-bottom:8px;
}
.pct-green{background:linear-gradient(135deg,var(--c3),#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.pct-cyan{background:linear-gradient(135deg,var(--c),#0ea5e9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.pct-amber{background:linear-gradient(135deg,var(--amber),#ef4444);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.pct-purple{background:linear-gradient(135deg,var(--c2),#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.stat-lbl{font-size:12px;color:var(--muted);line-height:1.5}

/* ========= REVIEWS ========= */
.reviews-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.review-card {
  background:rgba(13,17,30,.7);
  border:1px solid var(--border);
  border-radius:var(--r);
  padding:24px;
  transition:all .3s;
  position:relative;overflow:hidden;
}
.review-card::before{
  content:'"';
  position:absolute;top:-10px;right:20px;
  font-size:120px;font-family:Georgia,serif;
  color:rgba(34,211,238,.05);
  line-height:1;
}
.review-card:hover{border-color:rgba(34,211,238,.2);transform:translateY(-4px)}
.review-stars{color:var(--amber);font-size:14px;letter-spacing:2px;margin-bottom:12px}
.review-card p{font-size:13.5px;color:var(--text);line-height:1.75;margin-bottom:16px}
.review-author{display:flex;align-items:center;gap:10px}
.review-av{
  width:36px;height:36px;border-radius:50%;
  background:linear-gradient(135deg,var(--c),var(--c2));
  display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:800;color:#fff;font-family:var(--font);
  box-shadow:0 0 14px rgba(34,211,238,.3);
}
.review-name{font-size:13px;font-weight:600;color:#fff}
.review-tag{font-size:11px;color:var(--c3);display:flex;align-items:center;gap:4px;margin-top:2px}

/* ========= PRICING ========= */
.pricing-section{border-top:1px solid var(--border)}
.pricing-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
.product-visual {
  border-radius:var(--r);overflow:hidden;
  border:1px solid rgba(34,211,238,.15);
  background:linear-gradient(145deg,var(--surface2),var(--bg));
  aspect-ratio:4/5;
  display:flex;align-items:center;justify-content:center;
  position:relative;
}
.product-visual::before{
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at 50% 50%,rgba(34,211,238,.1),transparent 60%);
}
.product-orb{
  width:180px;height:180px;border-radius:50%;
  background:radial-gradient(circle at 35% 35%,rgba(34,211,238,.2),rgba(7,11,18,.9));
  border:1px solid rgba(34,211,238,.3);
  display:flex;align-items:center;justify-content:center;
  font-family:var(--font);font-weight:800;color:#fff;font-size:20px;
  box-shadow:0 0 80px rgba(34,211,238,.25);
  animation:spherePulse 4s ease-in-out infinite;
  position:relative;z-index:2;
}
.pricing-card {
  background:rgba(13,17,30,.8);
  border:1px solid rgba(34,211,238,.2);
  border-radius:20px;
  padding:36px;
  position:relative;overflow:hidden;
}
.pricing-card::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(34,211,238,.04) 0%,transparent 50%,rgba(167,139,250,.03) 100%);
  pointer-events:none;
}
/* animated border */
.pricing-card::after{
  content:'';
  position:absolute;
  inset:-1px;
  border-radius:21px;
  background:linear-gradient(135deg,var(--c),var(--c2),var(--c));
  background-size:300% 300%;
  animation:borderGlow 4s ease infinite;
  z-index:-1;
  opacity:.4;
}
@keyframes borderGlow{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}
.ribbon{
  display:inline-flex;
  background:rgba(52,211,153,.12);color:var(--c3);
  font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;
  padding:5px 12px;border-radius:20px;margin-bottom:18px;
}
.pricing-card h3{font-family:var(--font);font-weight:800;font-size:26px;color:#fff;margin-bottom:8px}
.pricing-card .sub{font-size:13px;color:var(--muted)}
.price-row{display:flex;align-items:baseline;gap:10px;margin:16px 0 22px}
.price-now{font-family:var(--font);font-weight:800;font-size:36px;color:#fff}
.price-old{font-size:15px;color:var(--muted2);text-decoration:line-through}
.price-period{font-size:13px;color:var(--muted)}
.feature-list{display:flex;flex-direction:column;gap:12px;margin-bottom:26px}
.feature-list li{
  list-style:none;
  display:flex;gap:10px;align-items:flex-start;
  font-size:13.5px;color:var(--text);
}
.feature-list i{color:var(--c3);margin-top:2px;flex-shrink:0}
.pricing-card .btn{width:100%;margin-top:4px}
.pricing-note{font-size:11.5px;color:var(--muted2);text-align:center;margin-top:14px}

/* ========= FAQ ========= */
.faq-section{border-top:1px solid var(--border)}
.faq-list{display:flex;flex-direction:column;gap:8px;max-width:780px;margin:0 auto}
.faq-item {
  background:rgba(13,17,30,.7);
  border:1px solid var(--border);
  border-radius:var(--r-sm);
  overflow:hidden;
  transition:border-color .25s;
}
.faq-item.open{border-color:rgba(34,211,238,.25)}
.faq-q{
  display:flex;align-items:center;justify-content:space-between;
  padding:18px 22px;cursor:pointer;
  font-size:14px;font-weight:600;color:#fff;
  gap:12px;
  user-select:none;
}
.faq-q i{
  color:var(--muted);transition:all .3s;font-size:13px;flex-shrink:0;
  width:20px;height:20px;
  background:rgba(255,255,255,.05);border-radius:50%;
  display:flex;align-items:center;justify-content:center;
}
.faq-item.open .faq-q i{transform:rotate(45deg);color:var(--c);background:rgba(34,211,238,.1)}
.faq-a{max-height:0;overflow:hidden;transition:max-height .4s var(--ease)}
.faq-item.open .faq-a{max-height:200px}
.faq-a-inner{padding:0 22px 18px;font-size:13.5px;color:var(--muted);line-height:1.7}

/* ========= CTA ========= */
.cta-section {
  background:radial-gradient(ellipse 80% 70% at 50% -10%,rgba(34,211,238,.15),transparent 60%);
  text-align:center;border-top:1px solid var(--border);
}
.cta-section h2{
  font-family:var(--font);font-weight:800;font-size:46px;
  letter-spacing:-1px;color:#fff;max-width:680px;margin:0 auto 20px;
  line-height:1.1;
}
.cta-section p{font-size:16px;color:var(--muted);max-width:520px;margin:0 auto 36px;line-height:1.75}

/* ========= FOOTER ========= */
footer{background:var(--surface);border-top:1px solid var(--border);padding-top:60px}
.footer-top{
  display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:48px;
  padding-bottom:48px;border-bottom:1px solid var(--border);
}
.footer-brand p{font-size:13px;color:var(--muted);line-height:1.75;max-width:280px;margin:14px 0 18px}
.social-row{display:flex;gap:10px}
.social-row a{
  width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border);
  display:flex;align-items:center;justify-content:center;
  color:var(--muted);transition:all .25s;font-size:13px;
}
.social-row a:hover{background:rgba(34,211,238,.1);color:var(--c);border-color:rgba(34,211,238,.4)}
.footer-col h4{
  font-family:var(--font);font-size:12px;font-weight:700;
  color:#fff;letter-spacing:1.4px;text-transform:uppercase;margin-bottom:18px;
}
.footer-col ul{list-style:none;display:flex;flex-direction:column;gap:12px}
.footer-col a{font-size:13.5px;color:var(--muted);transition:color .2s}
.footer-col a:hover{color:var(--c)}
.footer-bottom{
  display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;
  padding:22px 0;font-size:12px;color:var(--muted2);
}
.footer-legal{display:flex;gap:18px;flex-wrap:wrap}
.footer-legal a:hover{color:var(--muted)}
.disclaimer{
  font-size:10.5px;color:var(--muted2);line-height:1.75;
  padding:20px 0 30px;border-top:1px solid var(--border);margin-top:8px;
}

/* ========= ANIMATIONS / REVEAL ========= */
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
[data-reveal]{opacity:0;transform:translateY(28px);transition:opacity .7s var(--ease),transform .7s var(--ease)}
[data-reveal].revealed{opacity:1;transform:translateY(0)}
[data-reveal].delay-1{transition-delay:.1s}
[data-reveal].delay-2{transition-delay:.2s}
[data-reveal].delay-3{transition-delay:.3s}
[data-reveal].delay-4{transition-delay:.4s}

/* count-up animation */
.count-num{display:inline-block}

/* shimmer on hover */
.shimmer{position:relative;overflow:hidden}
.shimmer::before{
  content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.04),transparent);
  animation:shimmerSlide 3s ease-in-out infinite;
}
@keyframes shimmerSlide{0%{left:-100%}100%{left:200%}}

/* ========= RESPONSIVE ========= */
@media(max-width:1080px){
  .hero-grid,.how-grid,.compare-grid,.security-grid,.pricing-grid{grid-template-columns:1fr}
  .hero-visual{order:-1}
  .usecase-grid{grid-template-columns:repeat(2,1fr)}
  .reviews-grid{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr 1fr}
  .insights-cards-grid{grid-template-columns:1fr 1fr}
  .photo-card{grid-row:span 1;aspect-ratio:16/9}
}
@media(max-width:768px){
  .container{padding:0 20px}
  .nav-links{display:none}
  .nav-toggle{display:flex;align-items:center;justify-content:center}
  .hero h1{font-size:38px}
  .hero{padding:56px 0 48px}
  .section-title{font-size:28px}
  .usecase-grid{grid-template-columns:1fr 1fr}
  .footer-top{grid-template-columns:1fr;gap:32px}
  .footer-bottom{flex-direction:column;align-items:flex-start}
  .cta-section h2{font-size:30px}
  .trust-strip{gap:16px}
  .insights-cards-grid{grid-template-columns:1fr}
}

/* ========= BIOMARKERS MOVING SECTION CSS ========= */
.biomarkers-moving-section {
  background: var(--bg);
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  padding: 100px 0;
  overflow: hidden;
}

.biomarkers-moving-grid {
  display: grid;
  grid-template-columns: 0.8fr 1.4fr 0.8fr;
  gap: 40px;
  align-items: center;
}

/* Left Column Styling */
.bm-left-col {
  display: flex;
  flex-direction: column;
  gap: 60px;
}
.bm-title-wrap h2 {
  font-size: 34px;
  margin-bottom: 10px;
}
.bm-counter-box {
  display: flex;
  flex-direction: column;
}
.bm-lbl {
  font-size: 14px;
  color: var(--muted);
  font-weight: 500;
  letter-spacing: 0.5px;
}
.bm-count {
  font-family: var(--font);
  font-size: 64px;
  font-weight: 800;
  color: #fff;
  line-height: 1.1;
}

/* Center Card Layout */
.bm-card-wrapper {
  display: grid;
  grid-template-columns: 1fr 1fr;
  background: rgba(13, 17, 30, 0.4);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 30px 60px rgba(0,0,0,0.4);
}

.bm-image-side {
  position: relative;
  aspect-ratio: 1/1;
  height: 420px;
}
.bm-main-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.bm-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent 50%, rgba(13, 17, 30, 0.95) 100%);
}

/* Infinite Vertical Moving Marquee Logic */
.bm-list-side {
  background: #0d111e;
  padding: 30px 24px;
  display: flex;
  align-items: center;
  position: relative;
}

/* Fade effects for top and bottom of the list */
.bm-list-side::before,
.bm-list-side::after {
  content: '';
  position: absolute;
  left: 0; right: 0; height: 50px;
  z-index: 5;
  pointer-events: none;
}
.bm-list-side::before {
  top: 0; background: linear-gradient(180deg, #0d111e 0%, transparent 100%);
}
.bm-list-side::after {
  bottom: 0; background: linear-gradient(0deg, #0d111e 0%, transparent 100%);
}

.marquee-vertical-container {
  overflow: hidden;
  height: 340px; /* Limits visible items */
  width: 100%;
}

.marquee-vertical-track {
  display: flex;
  flex-direction: column;
  gap: 16px;
  animation: scrollVertical 22s linear infinite;
}

/* Pause animation on hover so users can inspect */
.marquee-vertical-track:hover {
  animation-play-state: paused;
}

@keyframes scrollVertical {
  0% { transform: translateY(0); }
  100% { transform: translateY(-50%); } /* Perfect infinite loop trick */
}

.bm-item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  font-size: 14px;
  color: var(--muted);
  font-weight: 500;
  transition: color 0.2s;
}
.bm-item-row:hover {
  color: #fff;
}
.bm-item-row span:first-child {
  font-size: 15px;
}

/* Right Column Action Button */
.view-all-bm-btn {
  display: inline-flex;
  align-items: center;
  gap: 14px;
  padding: 18px 28px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.08);
  background: rgba(255, 255, 255, 0.02);
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.3s var(--ease);
}
.view-all-bm-btn:hover {
  background: rgba(34, 211, 238, 0.08);
  border-color: var(--c);
  transform: translateX(4px);
  box-shadow: 0 0 20px rgba(34, 211, 238, 0.1);
}
.view-all-bm-btn i {
  font-size: 12px;
  color: var(--c);
}

/* Responsive adjustments */
@media(max-width: 1140px) {
  .biomarkers-moving-grid {
    grid-template-columns: 1fr;
    gap: 50px;
  }
  .bm-left-col {
    gap: 24px;
    text-align: center;
  }
  .bm-right-col {
    display: flex;
    justify-content: center;
  }
}
@media(max-width: 580px) {
  .bm-card-wrapper {
    grid-template-columns: 1fr;
  }
  .bm-image-side {
    height: 240px;
  }
  .bm-img-overlay {
    background: linear-gradient(180deg, transparent 40%, rgba(13, 17, 30, 0.95) 100%);
  }
}
</style>
</head>
<body>

<!-- ANNOUNCE BAR -->
{{-- <div class="announce">
  LIMITED TIME ONLY &nbsp;<span class="pill">Save 20%</span>&nbsp; on your first Vyralabs kit — results in under 72 hours
</div> --}}

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="container nav-inner">

    <div class="logo">
        <img src="{{ asset('images/logo.avif') }}" alt="Massimo Logo" 
         style="position: absolute !important; top: 15px !important; left: 6% !important; transform: translateX(-50%) !important; height: 38px !important; width: auto !important; object-fit: contain !important; max-width: 85% !important;">
  
    </div>    <div class="nav-links">
      <a href="#how">What We Test <i class="fa-solid fa-chevron-down"></i></a>
      <a href="#pricing">Pricing</a>
      <a href="#about">About Us</a>
      <a href="#partners">Creator Partnerships</a>
    </div>
    <div class="nav-actions">
      <a href="{{route('login')}}" class="btn btn-ghost">Login <i class="fa-solid fa-arrow-right-to-bracket"></i></a>
      <a href="#pricing" class="btn btn-primary">Get Vyralabs</a>
      <button class="nav-toggle"><i class="fa-solid fa-bars"></i></button>
    </div>
  </div>
</nav>

<!-- HERO -->
<header class="hero" style="
  background-image:
    linear-gradient(270deg,rgba(7,11,18,0) 0%,rgba(7,11,18,.75) 50%,rgba(7,11,18,1) 100%),
    url('images/dna-banner.png');
  background-size:100% 100%,cover;
  background-position:center,right center;
  background-repeat:no-repeat;
">
  <div class="hero-bg-orb"></div>
  <div class="container hero-grid">
    <div class="hero-copy">
      <div class="badge-row" style="animation:fadeUp .5s ease both">
        <span class="badge"><i class="fa-solid fa-circle-check"></i> Clinical Accuracy Guarantee</span>
        <span class="badge"><i class="fa-solid fa-shield-halved"></i> CLIA Certified &amp; CAP Accredited</span>
      </div>
      <h1 style="animation:fadeUp .55s ease both .08s">
        The World's Easiest<br><span class="grad">Performance Test</span>
      </h1>
      <p style="animation:fadeUp .55s ease both .16s">Painless at-home blood testing built for creators, athletes, and anyone optimizing their body. Actionable results in less than 72 hours.</p>
      <div class="hero-cta-row" style="animation:fadeUp .55s ease both .22s">
        <a href="#pricing" class="btn btn-primary btn-lg">Try now with 20% off <span style="opacity:.65;font-style:italic;font-weight:500">risk free</span></a>
      </div>
      <div class="hero-trustline" style="animation:fadeUp .55s ease both .28s">
        <div><i class="fa-solid fa-check"></i> HSA / FSA Eligible</div>
        <div><i class="fa-solid fa-droplet"></i> Millions of results delivered worldwide</div>
        <div><span class="stars">★★★★★</span>&nbsp; 4.8 / 5 from verified customers</div>
      </div>
      <div class="trust-strip" style="animation:fadeUp .55s ease both .36s">
        <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> HIPAA Aligned</div>
        <div class="trust-item"><i class="fa-solid fa-globe"></i> CLIA Certified</div>
        <div class="trust-item"><i class="fa-solid fa-award"></i> CAP Accredited</div>
        <div class="trust-item"><i class="fa-solid fa-location-dot"></i> FDA Cleared</div>
      </div>
    </div>

    <div class="hero-visual" style="animation:fadeUp .7s ease both .1s">
      <div class="float-card float-1"><i class="fa-solid fa-shield"></i> Results in 72hrs</div>
      <div class="float-card float-2"><i class="fa-solid fa-droplet"></i> Painless · 1ml sample</div>
      <div class="sphere-wrap">
        <div class="sphere-ring ring-1"></div>
        <div class="sphere-ring ring-2"></div>
        <div class="sphere-ring ring-3"></div>
        <div class="orb-orbit"><div class="orb-dot"></div></div>
        <div class="sphere-core">vyralabs</div>
      </div>
    </div>
  </div>
</header>

<!-- HOW IT WORKS -->
<section class="how-section" id="how">
  <div class="container how-grid">
    <div class="how-visual" data-reveal>
      <div class="how-visual-orb">vyralabs</div>
    </div>
    <div class="how-steps">
      <div class="section-eyebrow">How it works</div>
      <div class="how-step" data-reveal>
        <div class="how-num">1</div>
        <div class="how-step-body">
          <div class="tag">Painless</div>
          <h3>At-Home Test</h3>
          <p>A single, near-painless finger prick collects everything we need — no needles, no lab visits, no appointments.</p>
        </div>
      </div>
      <div class="how-step" data-reveal delay-1>
        <div class="how-num">2</div>
        <div class="how-step-body">
          <div class="tag">Free</div>
          <h3>Send or Get Picked Up</h3>
          <p>Drop your kit in any mailbox or schedule a free pickup from your home — whichever fits your schedule.</p>
        </div>
      </div>
      <div class="how-step" data-reveal delay-2>
        <div class="how-num">3</div>
        <div class="how-step-body">
          <div class="tag">Results within</div>
          <h3>3 Days</h3>
          <p>Get clinically validated results and personalized, AI-powered insights delivered straight to your dashboard.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- COMPARE -->
<section class="compare-section">
  <div class="container compare-grid">
    <div data-reveal>
      <div class="section-eyebrow">Clinical results, a fraction of the blood</div>
      <h2 class="section-title">Clinical results with a <strong>tenth</strong> of the blood</h2>
      <p class="section-sub">Traditional labs need a full vial draw across multiple tubes. Vyralabs gets the same clinical-grade insight from a single 1ml sample — no needles, no clinic, no waiting room.</p>
    </div>
    <div class="compare-img-wrap" data-reveal delay-1>
      <img src="images/bloodtube.png" alt="Blood tube comparison" style="max-width:340px;border-radius:var(--r);filter:drop-shadow(0 0 30px rgba(34,211,238,.15))">
    </div>
  </div>
</section>

<!-- INSIGHTS -->
<section class="insights-section" id="insights">
  <div class="container">
    <div class="insights-head" data-reveal>
      <h2>Monthly testing for more<br>personalized insights.</h2>
      <p>Your AI-powered dashboard surfaces real trends, so the insights you get actually reflect your goals — and adapt over time.</p>
    </div>

    <div class="insights-cards-grid">
      <!-- Photo -->
      <div class="i-card photo-card" data-reveal>
        <img src="images/1.webp" alt="Athlete">
      </div>

      <!-- Ferritin trend -->
      <div class="i-card" data-reveal delay-1>
        <div class="ic-header">
          <span class="ic-title">Ferritin</span>
          <span class="ic-badge cyan">Optimal</span>
        </div>
        <div class="ferritin-mini-chart">
          <svg viewBox="0 0 200 70" preserveAspectRatio="none">
            <defs>
              <linearGradient id="fl" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%" stop-color="#f59e0b"/>
                <stop offset="40%" stop-color="#f59e0b"/>
                <stop offset="60%" stop-color="#22d3ee"/>
                <stop offset="100%" stop-color="#22d3ee"/>
              </linearGradient>
              <filter id="glow"><feGaussianBlur stdDeviation="2" result="blur"/><feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
            </defs>
            <line x1="0" y1="35" x2="200" y2="35" stroke="rgba(255,255,255,.08)" stroke-width="1"/>
            <polyline points="0,58 40,52 80,30 120,24 160,18 200,14" fill="none" stroke="url(#fl)" stroke-width="2.5" stroke-linecap="round" filter="url(#glow)"/>
            <circle cx="80" cy="30" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
            <circle cx="120" cy="24" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
            <circle cx="160" cy="18" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
            <circle cx="200" cy="14" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
          </svg>
        </div>
        <div class="chart-months"><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span></div>
      </div>

      <!-- Score gauge -->
      <div class="i-card" data-reveal delay-2>
        <div class="ic-header">
          <div>
            <div style="font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--muted2);margin-bottom:3px">vyralabs</div>
            <span class="ic-title">Longevity Score</span>
          </div>
          <span class="ic-badge amber">Optimal</span>
        </div>
        <div class="gauge-wrap">
          <svg viewBox="0 0 160 160" style="transform:rotate(-90deg)">
            <circle cx="80" cy="80" r="64" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="12"/>
            <circle cx="80" cy="80" r="64" fill="none" stroke="#f59e0b" stroke-width="12" stroke-dasharray="402" stroke-dashoffset="76" stroke-linecap="round"/>
            <circle cx="80" cy="80" r="64" fill="none" stroke="#22d3ee" stroke-width="12" stroke-dasharray="402" stroke-dashoffset="360" stroke-linecap="round"/>
          </svg>
          <div class="gauge-center">
            <span class="gauge-num">87%</span>
            <span class="gauge-arrow"><i class="fa-solid fa-arrow-up"></i></span>
          </div>
        </div>
      </div>

      <!-- Biomarkers -->
      <div class="i-card" data-reveal delay-3>
        <div class="ic-header">
          <span class="ic-title">Biomarkers</span>
          <span class="ic-badge green">+12%</span>
        </div>
        <div style="font-size:16px;font-weight:600;color:#fff;margin-top:6px">Total Testosterone</div>
        <div style="font-size:12px;color:var(--muted);margin-top:3px">Vitality Indicator</div>
        <div class="bm-bar-track">
          <div class="seg" style="width:25%;background:#f59e0b"></div>
          <div class="seg" style="width:35%;background:rgba(255,255,255,.06)"></div>
          <div class="seg" style="width:30%;background:var(--c)"></div>
          <div class="seg" style="width:10%;background:var(--c3)"></div>
        </div>
      </div>

      <!-- Insight text -->
      <div class="i-card" data-reveal delay-1>
        <div class="insight-tag"><i class="fa-regular fa-clock"></i> INSIGHT FOR THIS MONTH</div>
        <p style="font-size:13.5px;color:var(--text);line-height:1.7;margin-bottom:8px">
          Your ferritin fell by 22%, a trend often seen with increased training volume during marathon preparation.
        </p>
        <p style="font-size:13px;color:var(--muted);line-height:1.7">
          This dip usually appears when your body adjusts to marathon training. 
          <span class="read-more">Read more</span>
        </p>
        <div class="ask-chat"><i class="fa-regular fa-comment-dots"></i> Ask more in the chat</div>
      </div>

      <!-- Supplements -->
      {{-- <div class="i-card" data-reveal delay-2>
        <div class="supp-lbl">Supplements</div>
        <div class="supp-row">
          <span>Magnesium</span>
          <span class="supp-freq freq-amber">2x Day</span>
        </div>
        <div class="supp-row">
          <span>Vitamin D</span>
          <span class="supp-freq freq-cyan">1x Week</span>
        </div>
        <div class="card-divider"></div>
        <div class="supp-lbl">Goals</div>
        <div class="supp-row">
          <span>Marathon Training</span>
          <span class="supp-freq freq-green"><i class="fa-solid fa-circle-check"></i></span>
        </div>
      </div> --}}
    </div>
  </div>
</section>


<!-- BIOMARKERS INFINITE MOVING SECTION -->
<section class="biomarkers-moving-section">
  <div class="container biomarkers-moving-grid">
    
    <!-- Left Column: Title & Count -->
    <div class="bm-left-col" data-reveal>
      <div class="bm-title-wrap">
        <h2 class="section-title"><i class="fa-solid fa-circle-check" style="color: var(--c3); font-size: 28px;"></i> <strong>One test</strong> optimized for daily impact.</h2>
        <p class="section-sub">Track core markers for whole-body health.</p>
      </div>
      
      <div class="bm-counter-box">
        <span class="bm-lbl">Biomarkers</span>
        <h3 class="bm-count">25+</h3>
      </div>
    </div>

    <!-- Center Column: Visual & Auto Moving List -->
    <div class="bm-center-col" data-reveal delay-1>
      <div class="bm-card-wrapper">
        <!-- Background Device Image Placeolder -->
        <div class="bm-image-side">
          <img src="images/3dbiomarker.jpg" alt="Testing Device" class="bm-main-img">
          <!-- Gradient overlay matching the mockup vibe -->
          <div class="bm-img-overlay"></div>
        </div>
        
        <!-- Right Content inside card: Auto Moving Marquee -->
        <div class="bm-list-side">
          <div class="marquee-vertical-container">
            <div class="marquee-vertical-track">
              
              <!-- Original List -->
              <div class="bm-item-row"><span>Vitamin D</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Testosterone</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>TSH</span><span class="ic-badge amber">Out of Range</span></div>
              <div class="bm-item-row"><span>Free T3</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Creatinine</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Estrogen</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Ferritin</span><span class="ic-badge amber">Out of Range</span></div>
              <div class="bm-item-row"><span>Progesterone</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Cortisol</span><span class="ic-badge green">Normal</span></div>
              <div class="bm-item-row"><span>HbA1c</span><span class="ic-badge green">Normal</span></div>

              <!-- Duplicate List for Seamless Infinite Loop -->
              <div class="bm-item-row"><span>Vitamin D</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Testosterone</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>TSH</span><span class="ic-badge amber">Out of Range</span></div>
              <div class="bm-item-row"><span>Free T3</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Creatinine</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Estrogen</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Ferritin</span><span class="ic-badge amber">Out of Range</span></div>
              <div class="bm-item-row"><span>Progesterone</span><span class="ic-badge cyan">Optimal</span></div>
              <div class="bm-item-row"><span>Cortisol</span><span class="ic-badge green">Normal</span></div>
              <div class="bm-item-row"><span>HbA1c</span><span class="ic-badge green">Normal</span></div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: View All CTA -->
    <div class="bm-right-col" data-reveal delay-2>
      <a href="#pricing" class="view-all-bm-btn">
        <span>View All Biomarkers</span>
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      </a>
    </div>

  </div>
</section>
<!-- USE CASES -->
<section id="about">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="section-eyebrow">For every part of life</div>
      <h2 class="section-title">Vyralabs' all-in-one panel is built<br>for every part of your life.</h2>
      <p class="section-sub">Whether you're chasing performance, planning ahead, or just want clarity — Vyralabs is your roadmap to better health.</p>
    </div>
    <div class="usecase-grid">
      <div class="usecase-card" data-reveal>
        <div class="usecase-icon-wrap"><i class="fa-solid fa-person-running"></i></div>
        <div class="usecase-body">
          <h4>Athletic Performance</h4>
          <p>See how your body produces energy and adapts to training — and train smarter, not harder.</p>
        </div>
      </div>
      <div class="usecase-card" data-reveal delay-1>
        <div class="usecase-icon-wrap" style="color:var(--c2)"><i class="fa-solid fa-heart-pulse"></i></div>
        <div class="usecase-body">
          <h4>Women's Health</h4>
          <p>Navigate changes in your body with clarity so you can adapt, stay balanced, and feel your best.</p>
        </div>
      </div>
      <div class="usecase-card" data-reveal delay-2>
        <div class="usecase-icon-wrap" style="color:var(--c3)"><i class="fa-solid fa-vial-circle-check"></i></div>
        <div class="usecase-body">
          <h4>Hormone Optimization</h4>
          <p>Continuous tracking on TRT, HRT, GLP-1s and more — stay informed and in control.</p>
        </div>
      </div>
      <div class="usecase-card" data-reveal delay-3>
        <div class="usecase-icon-wrap" style="color:var(--amber)"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
        <div class="usecase-body">
          <h4>Preventative Tracking</h4>
          <p>Stay ahead of potential health concerns by tracking trends instead of reacting to symptoms.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECURITY -->
<section class="security-section">
  <div class="container security-grid">
    <div data-reveal>
      <div class="section-eyebrow">Private &amp; Secure</div>
      <h2 class="section-title">The infrastructure that makes fast, accurate testing possible.</h2>
      <div class="security-items" style="margin-top:28px">
        <div class="security-item">
          <div class="icon"><i class="fa-solid fa-lock"></i></div>
          <div>
            <h4>Military-grade encryption</h4>
            <p>Advanced encryption protocols keep your health data protected and private at every point of the system.</p>
          </div>
        </div>
        <div class="security-item">
          <div class="icon" style="background:rgba(167,139,250,.1);color:var(--c2)"><i class="fa-solid fa-flask"></i></div>
          <div>
            <h4>In-house testing, no third parties</h4>
            <p>We handle every sample ourselves to guarantee privacy and consistent lab-grade results.</p>
          </div>
        </div>
        <div class="security-item">
          <div class="icon" style="background:rgba(52,211,153,.1);color:var(--c3)"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <h4>Verified chain of custody</h4>
            <p>Our closed-loop system tracks and protects your sample from the moment it's collected.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="security-stats" data-reveal delay-2>
      <div class="stat-card shimmer">
        <span class="stat-pct pct-green" data-count="97">0%</span>
        <div class="stat-lbl">Enjoyed the collection and would do it again</div>
      </div>
      <div class="stat-card shimmer">
        <span class="stat-pct pct-cyan" data-count="91">0%</span>
        <div class="stat-lbl">Learned something new about their health</div>
      </div>
      <div class="stat-card shimmer">
        <span class="stat-pct pct-amber" data-count="85">0%</span>
        <div class="stat-lbl">Made changes to their diet, exercise &amp; lifestyle</div>
      </div>
      <div class="stat-card shimmer">
        <span class="stat-pct pct-purple" data-count="93">0%</span>
        <div class="stat-lbl">Have recommended Vyralabs to friends &amp; family</div>
      </div>
    </div>
  </div>
</section>

<!-- REVIEWS -->
<section>
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="section-eyebrow">Thousands of 5-star reviews</div>
      <h2 class="section-title">This is what people say about Vyralabs</h2>
      <p class="section-sub">Real reviews from real customers who've seen health improvements in less than 90 days.</p>
    </div>
    <div class="reviews-grid">
      <div class="review-card" data-reveal>
        <div class="review-stars">★★★★★</div>
        <p>I was the most skeptical, as this sounded too good to be true. I ordered my kit, got informed every step of the way, and got my results within days.</p>
        <div class="review-author">
          <div class="review-av">M</div>
          <div>
            <div class="review-name">Manny A.</div>
            <div class="review-tag"><i class="fa-solid fa-circle-check"></i> Verified Buyer</div>
          </div>
        </div>
      </div>
      <div class="review-card" data-reveal delay-1>
        <div class="review-stars">★★★★★</div>
        <p>It's really that easy. The simplicity is not just talk — it's the reality. Scheduled a pickup, completed it Friday, results were ready by the weekend.</p>
        <div class="review-author">
          <div class="review-av" style="background:linear-gradient(135deg,var(--c2),#ec4899)">D</div>
          <div>
            <div class="review-name">Dominic</div>
            <div class="review-tag"><i class="fa-solid fa-circle-check"></i> Verified Buyer</div>
          </div>
        </div>
      </div>
      <div class="review-card" data-reveal delay-2>
        <div class="review-stars">★★★★★</div>
        <p>If you have any hesitation, don't. I'm in great health, train 4-5 days a week, and the insights gave me real clarity on where to focus next.</p>
        <div class="review-author">
          <div class="review-av" style="background:linear-gradient(135deg,var(--c3),#10b981)">J</div>
          <div>
            <div class="review-name">John P.</div>
            <div class="review-tag"><i class="fa-solid fa-circle-check"></i> Verified Buyer</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="pricing-section" id="pricing">
  <div class="container pricing-grid">
    <div class="product-visual" data-reveal>
      <div class="product-orb">vyralabs</div>
    </div>
    <div class="pricing-card" data-reveal delay-1>
      <span class="ribbon">Save 20% on your first order</span>
      <h3>Vyralabs At-Home Performance Test</h3>
      <p class="sub">Ships every 30 days · Flexible subscription · Skip anytime</p>
      <div class="price-row">
        <span class="price-old">$79/mo</span>
        <span class="price-now">$63.20</span>
        <span class="price-period">first month + shipping</span>
      </div>
      <ul class="feature-list">
        <li><i class="fa-solid fa-circle-check"></i> Easy and painless at-home collection</li>
        <li><i class="fa-solid fa-circle-check"></i> Free pickup from your home</li>
        <li><i class="fa-solid fa-circle-check"></i> Results ready in &lt;72hrs</li>
        <li><i class="fa-solid fa-circle-check"></i> Unlimited personalized AI insights</li>
        <li><i class="fa-solid fa-circle-check"></i> Unlimited AI coaching</li>
      </ul>
      <a href="#" class="btn btn-primary btn-lg"><i class="fa-solid fa-lock"></i> Secure Checkout</a>
      <div class="pricing-note">HSA / FSA Eligible · Cancel anytime, no fees</div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="faq-section">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="section-eyebrow">FAQ</div>
      <h2 class="section-title">Questions? We've got answers.</h2>
    </div>
    <div class="faq-list">
      <div class="faq-item">
        <div class="faq-q">How is Vyralabs different? <i class="fa-solid fa-plus"></i></div>
        <div class="faq-a"><div class="faq-a-inner">We run testing in-house with no third-party labs, giving you faster results, tighter privacy, and lower cost — all from a single drop of blood.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Does Vyralabs help me improve? <i class="fa-solid fa-plus"></i></div>
        <div class="faq-a"><div class="faq-a-inner">Yes. Every result comes with AI-powered, personalized insights and recommendations tailored to your goals and trends over time.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Is Vyralabs HIPAA aligned? <i class="fa-solid fa-plus"></i></div>
        <div class="faq-a"><div class="faq-a-inner">Yes, your data is encrypted and protected at every step, following HIPAA-aligned privacy and security practices.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Can I cancel anytime without fees? <i class="fa-solid fa-plus"></i></div>
        <div class="faq-a"><div class="faq-a-inner">Absolutely — your subscription is flexible. Skip a month or cancel anytime with no hidden fees.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">Does Vyralabs accept HSA/FSA payments? <i class="fa-solid fa-plus"></i></div>
        <div class="faq-a"><div class="faq-a-inner">Yes, all Vyralabs kits and subscriptions are HSA/FSA eligible.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">How long does it take to get my results? <i class="fa-solid fa-plus"></i></div>
        <div class="faq-a"><div class="faq-a-inner">Most customers receive their full results and insights dashboard within 72 hours of their sample being processed.</div></div>
      </div>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="cta-section">
  <div class="container">
    <h2 data-reveal>Start tracking what actually moves the needle.</h2>
    <p data-reveal delay-1>Join thousands using Vyralabs to turn guesswork into a real, data-backed plan for their health and performance.</p>
    <a href="#pricing" class="btn btn-primary btn-lg" data-reveal delay-2>Try now with 20% off <span style="opacity:.65;font-style:italic;font-weight:500">risk free</span></a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <div class="logo"><span class="dot"></span> vyralabs</div>
        <p>Painless at-home blood testing for everyone. Clinical-grade insights, delivered to your dashboard in under 72 hours.</p>
        <div class="social-row">
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
          <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Get in Vyralabs</h4>
        <ul>
          <li><a href="#pricing">Start Testing</a></li>
          <li><a href="{{route('login')}}">Login</a></li>
          <li><a href="#">Careers</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Explore</h4>
        <ul>
          <li><a href="#how">Biomarkers We Test</a></li>
          <li><a href="#">Customer Reviews</a></li>
          <li><a href="#about">About Us</a></li>
        </ul>
      </div>
      <div class="footer-col" id="partners">
        <h4>Partnerships</h4>
        <ul>
          <li><a href="#">Creator Partnerships</a></li>
          <li><a href="#">Affiliate Programs</a></li>
          <li><a href="#">Chat With Us Now</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div>&copy; 2026 Vyralabs. All rights reserved.</div>
      <div class="footer-legal">
        <a href="#">Terms &amp; Conditions</a>
        <a href="#">Refund Policy</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Consumer Health Data Privacy Policy</a>
        <a href="#">Laboratory Services Consent</a>
      </div>
    </div>
    <div class="disclaimer">
      DISCLAIMER: Vyralabs does not recommend or refer you to any healthcare providers, and you are free to choose any healthcare provider and to continue to use Vyralabs' services. Vyralabs does not offer medical advice, a diagnosis, medical treatment, or any form of medical opinion through our services or otherwise. We recommend that you discuss those questions with your primary care physician or other licensed provider. All material, information, data, and content that Vyralabs provides is strictly for general information purposes. Vyralabs' membership pricing includes technology and service fees charged by Vyralabs, as well as access to prepaid laboratory and other services. Certain items and services require additional payment that are not included in standard membership pricing.
    </div>
  </div>
</footer>

<script>
// ── Navbar scroll
const navbar = document.getElementById('navbar');
window.addEventListener('scroll',()=>{
  navbar.classList.toggle('scrolled',window.scrollY>60);
});

// ── FAQ accordion
document.querySelectorAll('.faq-item').forEach(item=>{
  item.querySelector('.faq-q').addEventListener('click',()=>{
    const open=item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i=>i.classList.remove('open'));
    if(!open) item.classList.add('open');
  });
});

// ── Scroll reveal
const revealObs = new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      e.target.classList.add('revealed');
      revealObs.unobserve(e.target);
    }
  });
},{threshold:.12});
document.querySelectorAll('[data-reveal]').forEach(el=>revealObs.observe(el));

// ── Count-up animation for stats
function countUp(el,target,duration=1600){
  let start=0;
  const step=target/duration*16;
  const timer=setInterval(()=>{
    start=Math.min(start+step,target);
    el.textContent=Math.floor(start)+'%';
    if(start>=target) clearInterval(timer);
  },16);
}
const countObs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      const target=parseInt(e.target.dataset.count);
      countUp(e.target,target);
      countObs.unobserve(e.target);
    }
  });
},{threshold:.5});
document.querySelectorAll('[data-count]').forEach(el=>countObs.observe(el));

// ── Mobile nav
const toggle=document.querySelector('.nav-toggle');
const navLinks=document.querySelector('.nav-links');
toggle?.addEventListener('click',()=>{
  const vis=navLinks.style.display==='flex';
  navLinks.style.cssText=vis?'':`
    display:flex;flex-direction:column;position:fixed;
    top:72px;left:0;right:0;background:rgba(7,11,18,.97);
    backdrop-filter:blur(20px);padding:24px 32px;gap:20px;
    border-bottom:1px solid rgba(255,255,255,.06);z-index:899;
  `;
});

// ── Biomarker bar animated fill on scroll
const barObs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      e.target.querySelectorAll('.seg').forEach((seg,i)=>{
        const targets=['25%','35%','30%','10%'];
        seg.style.width='0%';
        setTimeout(()=>{ seg.style.width=targets[i]||'25%'; },i*80+200);
      });
      barObs.unobserve(e.target);
    }
  });
},{threshold:.3});
document.querySelectorAll('.bm-bar-track').forEach(el=>barObs.observe(el));
</script>
</body>
</html>