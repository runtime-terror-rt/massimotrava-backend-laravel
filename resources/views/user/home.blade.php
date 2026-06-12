<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vyralabs | The World's Easiest Performance Lab Test</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root{
  --bg-main:#0b0f19; --bg:#0e1117; --surface:#111622; --surface-2:#1c2333;
  --surface-modal:#1e293b; --border:rgba(255,255,255,.06); --glass-bg:rgba(22,27,39,.7);
  --glass-border:rgba(255,255,255,.08); --glass-shadow:0 8px 32px rgba(0,0,0,.4);
  --text-main:#fff; --text:#e2e8f0; --text-muted:#94a3b8; --text-muted-dark:#64748b;
  --accent:#38bdf8; --accent-glow:rgba(56,189,248,.35); --accent-2:#34d399; --accent-3:#34d399; --accent-4:#f59e0b;
  --font-display:'Plus Jakarta Sans',sans-serif; --font-body:'Inter',sans-serif;
  --radius:14px; --radius-sm:8px; --transition:.3s cubic-bezier(.4,0,.2,1);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html{scroll-behavior:smooth;}
body{
  font-family:var(--font-body); background:var(--bg); color:var(--text);
  min-height:100vh; overflow-x:hidden;
}
body::before{
  content:''; position:fixed; inset:0;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
  pointer-events:none; z-index:0;
}
a{color:inherit; text-decoration:none;}
img{max-width:100%; display:block;}
.container{max-width:1240px; margin:0 auto; padding:0 32px;}

/* ===== ANNOUNCEMENT BAR ===== */
.announce{
  background:var(--surface-2); border-bottom:1px solid var(--border);
  text-align:center; padding:9px 16px; font-size:12px; color:var(--text-muted);
  letter-spacing:.5px;
}
.announce .pill{
  background:var(--accent); color:#fff; font-weight:700; font-size:11px;
  padding:3px 10px; border-radius:20px; margin:0 8px;
}

/* ===== NAVBAR ===== */
.navbar{
  position:sticky; top:0; z-index:900;
  background:var(--glass-bg); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
  border-bottom:1px solid var(--glass-border);
}
.nav-inner{
  display:flex; align-items:center; justify-content:space-between;
  height:72px;
}
.logo{
  font-family:var(--font-display); font-weight:700; font-size:24px;
  letter-spacing:-0.5px; display:flex; align-items:center; gap:10px;
}
.logo .dot{
  width:10px; height:10px; border-radius:50%;
  background:linear-gradient(135deg,var(--accent),var(--accent-2));
  box-shadow:0 0 16px var(--accent-glow);
}
.nav-links{ display:flex; align-items:center; gap:32px; }
.nav-links a{
  font-size:14px; font-weight:500; color:var(--text-muted);
  display:flex; align-items:center; gap:6px;
  transition:color var(--transition);
}
.nav-links a:hover{ color:var(--text); }
.nav-links a i{ font-size:11px; color:var(--text-muted-dark); }
.nav-actions{ display:flex; align-items:center; gap:12px; }

.btn{
  display:inline-flex; align-items:center; gap:8px; justify-content:center;
  padding:11px 24px; border-radius:var(--radius-sm);
  font-size:13px; font-weight:700; cursor:pointer; border:none;
  font-family:var(--font-body); transition:all var(--transition);
  white-space:nowrap;
}
.btn-primary{
  background:var(--accent); color:#fff; box-shadow:0 4px 20px rgba(56,189,248,.3);
}
.btn-primary:hover{ background:#0ea5e9; box-shadow:0 6px 24px rgba(56,189,248,.45); transform:translateY(-1px); }
.btn-ghost{ background:var(--surface-2); color:var(--text); border:1px solid var(--border); }
.btn-ghost:hover{ background:var(--surface); border-color:rgba(56,189,248,.3); }
.btn-lg{ padding:15px 32px; font-size:14px; border-radius:12px; }
.btn small-tag{font-size:11px;}

.nav-toggle{ display:none; background:none; border:1px solid var(--border); color:var(--text);
  width:40px; height:40px; border-radius:var(--radius-sm); font-size:16px; cursor:pointer; }

/* ===== HERO ===== */
.hero{
  position:relative; overflow:hidden;
  padding:88px 0 60px;
  background:
    radial-gradient(ellipse 70% 50% at 70% 0%, rgba(56,189,248,.18), transparent 60%),
    radial-gradient(ellipse 60% 50% at 0% 30%, rgba(52,211,153,.08), transparent 60%);
  border-bottom:1px solid var(--border);
}
.hero-grid{
  display:grid; grid-template-columns:1.1fr .9fr; gap:60px; align-items:center;
}
.eyebrow-row{ display:flex; gap:10px; margin-bottom:22px; flex-wrap:wrap; }
.eyebrow{
  display:inline-flex; align-items:center; gap:6px;
  font-size:11px; font-weight:700; letter-spacing:.6px; text-transform:uppercase;
  padding:6px 12px; border-radius:20px;
  background:var(--surface-2); border:1px solid var(--border); color:var(--text-muted);
}
.eyebrow i{ color:var(--accent-3); font-size:11px; }
.hero h1{
  font-family:var(--font-display); font-weight:700;
  font-size:56px; line-height:1.05; letter-spacing:-1.2px; color:#fff;
  margin-bottom:20px;
}
.hero h1 em{ font-style:normal; color:var(--accent); }
.hero p{ font-size:16px; color:var(--text-muted); line-height:1.7; max-width:480px; margin-bottom:28px; }
.hero-cta-row{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:28px; }
.hero-meta{ display:flex; flex-direction:column; gap:10px; font-size:13px; color:var(--text-muted); }
.hero-meta div{ display:flex; align-items:center; gap:8px; }
.hero-meta i{ color:var(--accent-3); width:16px; }
.stars{ color:var(--accent-4); font-size:13px; letter-spacing:2px; }

.hero-visual{ position:relative; display:flex; justify-content:center; align-items:center; }
.device-ring{
  width:300px; height:300px; border-radius:50%;
  background:linear-gradient(145deg,var(--surface-2),var(--surface));
  border:1px solid var(--glass-border);
  display:flex; align-items:center; justify-content:center;
  position:relative; box-shadow:var(--glass-shadow), inset 0 0 60px rgba(56,189,248,.08);
  animation:fadeUp .6s ease both;
}
.device-ring::before{
  content:''; position:absolute; inset:-1px; border-radius:50%;
  border:1px solid transparent;
  background:linear-gradient(135deg,var(--accent),transparent 40%,transparent 60%,var(--accent-2)) border-box;
  -webkit-mask:linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite:xor; mask-composite:exclude;
}
.device-core{
  width:170px; height:170px; border-radius:50%;
  background:linear-gradient(145deg,#1c2333,#0e1117);
  display:flex; align-items:center; justify-content:center;
  font-family:var(--font-display); font-weight:700; font-size:26px; letter-spacing:-0.5px; color:#fff;
  box-shadow:inset 0 0 30px rgba(0,0,0,.6), 0 8px 30px rgba(56,189,248,.25);
}
.device-stick{
  position:absolute; bottom:-70px; left:50%; transform:translateX(-50%) rotate(-8deg);
  width:38px; height:120px; border-radius:14px;
  background:linear-gradient(180deg,#1c2333,var(--accent-2) 80%);
  border:1px solid var(--glass-border);
  display:flex; align-items:flex-end; justify-content:center; padding-bottom:14px;
  font-family:var(--font-display); font-size:9px; font-weight:700; color:#fff; writing-mode:vertical-rl;
  box-shadow:var(--glass-shadow);
}
.float-card{
  position:absolute; background:var(--glass-bg); backdrop-filter:blur(20px);
  border:1px solid var(--glass-border); border-radius:var(--radius-sm);
  padding:10px 14px; font-size:12px; font-weight:600; color:var(--text);
  box-shadow:var(--glass-shadow); display:flex; align-items:center; gap:8px;
  animation:floatY 5s ease-in-out infinite;
}
.float-card i{ color:var(--accent-3); }
.float-1{ top:6%; left:0%; animation-delay:.2s; }
.float-2{ bottom:18%; right:0%; animation-delay:1s; }
@keyframes floatY{ 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }

/* trust strip */
.trust-strip{
  border-top:1px solid var(--border); margin-top:56px; padding-top:28px;
  display:flex; align-items:center; gap:40px; flex-wrap:wrap;
}
.trust-strip .label{ font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:var(--text-muted-dark); }
.trust-item{ display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:var(--text-muted); }
.trust-item i{ color:var(--accent); }

/* ===== SECTION COMMON ===== */
section{ padding:80px 0; position:relative; }
.section-head{ max-width:640px; margin-bottom:48px; }
.section-eyebrow{
  display:inline-flex; align-items:center; gap:8px;
  font-size:11px; font-weight:700; letter-spacing:1.4px; text-transform:uppercase;
  color:var(--accent); margin-bottom:14px;
}
.section-eyebrow::before{ content:''; width:18px; height:2px; background:var(--accent); border-radius:2px; }
.section-title{
  font-family:var(--font-display); font-weight:700; font-size:36px;
  letter-spacing:-0.6px; color:#fff; line-height:1.2;
}
.section-title strong{ color:var(--accent); }
.section-sub{ font-size:15px; color:var(--text-muted); margin-top:14px; line-height:1.7; }
.center{ text-align:center; margin-left:auto; margin-right:auto; }

/* ===== HOW IT WORKS ===== */
.how-section{ border-top:1px solid var(--border); }
.how-grid{ display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; }
.how-steps{ display:flex; flex-direction:column; }
.how-step{
  display:flex; gap:24px; align-items:flex-start; padding:24px 0;
  border-bottom:1px solid var(--border); position:relative;
}
.how-step:last-child{ border-bottom:none; }
.how-step .num{
  font-family:var(--font-display); font-weight:700; font-size:42px;
  color:transparent; -webkit-text-stroke:1.5px var(--surface-2);
  width:60px; flex-shrink:0; transition:color var(--transition), -webkit-text-stroke var(--transition);
}
.how-step:hover .num{ color:var(--accent); -webkit-text-stroke:1.5px var(--accent); }
.how-step-body .tag{ font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--accent-3); margin-bottom:6px; }
.how-step-body h3{ font-family:var(--font-display); font-size:20px; font-weight:700; color:#fff; margin-bottom:6px; }
.how-step-body p{ font-size:13px; color:var(--text-muted); line-height:1.6; }
.how-visual{
  border-radius:var(--radius); overflow:hidden; border:1px solid var(--glass-border);
  background:var(--surface); aspect-ratio:4/5; position:relative;
  display:flex; align-items:center; justify-content:center;
}
.how-visual .ring-big{
  width:140px; height:140px; border-radius:50%;
  background:linear-gradient(145deg,var(--surface-2),var(--bg));
  border:1px solid var(--glass-border); display:flex; align-items:center; justify-content:center;
  font-family:var(--font-display); font-weight:700; color:#fff; font-size:20px;
  box-shadow:0 0 60px rgba(56,189,248,.2);
}
.how-visual::before{
  content:''; position:absolute; inset:0;
  background:radial-gradient(circle at 50% 50%, rgba(56,189,248,.15), transparent 70%);
}

/* ===== COMPARISON (1ml vs 10ml) ===== */
.compare-section{ background:var(--surface); border-top:1px solid var(--border); border-bottom:1px solid var(--border); }
.compare-grid{ display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center; }
.vials{ display:flex; align-items:center; justify-content:center; gap:40px; }
.vial-group{ text-align:center; }
.vial-row{ display:flex; gap:8px; align-items:flex-end; justify-content:center; margin-bottom:14px; }
.vial{
  width:22px; border-radius:6px 6px 3px 3px;
  background:linear-gradient(180deg,#1c2333 0%,#1c2333 25%, #ef4444 25%, #b91c1c 100%);
  border:1px solid var(--glass-border);
}
.vial.big{ height:120px; }
.vial.small{ height:30px; width:18px; background:linear-gradient(180deg,#1c2333 0%,#1c2333 50%, var(--accent) 50%, #0ea5e9 100%); }
.vial-label{ font-size:12px; color:var(--text-muted); font-weight:600; }
.vial-amount{ font-family:var(--font-display); font-weight:700; font-size:20px; color:#fff; margin-top:6px; }
.vs-badge{
  width:46px; height:46px; border-radius:50%; background:var(--surface-2);
  border:1px solid var(--glass-border); display:flex; align-items:center; justify-content:center;
  font-family:var(--font-display); font-weight:700; font-size:13px; color:var(--text-muted);
  flex-shrink:0;
}

/* ===== INSIGHTS / MONTHLY TESTING SECTION ===== */
.insights-section{
  background:linear-gradient(115deg, var(--bg) 0%, var(--bg) 40%, #0c2a3a 75%, #0d3a4a 100%);
  border-top:1px solid var(--border); border-bottom:1px solid var(--border);
  overflow:hidden;
}
.insights-head{
  display:flex; justify-content:space-between; align-items:flex-start; gap:40px;
  flex-wrap:wrap; margin-bottom:60px;
}
.insights-head h2{
  font-family:var(--font-display); font-weight:700; font-size:34px;
  letter-spacing:-0.6px; color:#fff; line-height:1.25; max-width:520px;
}
.insights-head p{
  font-size:14px; color:var(--text-muted); line-height:1.7; max-width:300px; text-align:right;
}
.insights-stage{
  position:relative; min-height:480px; display:flex; align-items:center; justify-content:center;
  max-width:980px; margin:0 auto;
}
.insight-photo{
  position:relative; width:340px; height:420px; border-radius:var(--radius);
  overflow:hidden; border:1px solid var(--glass-border); box-shadow:var(--glass-shadow);
  flex-shrink:0;
}
.insight-photo img{ width:100%; height:100%; object-fit:cover; display:block; }

.insight-card{
  position:absolute; background:var(--glass-bg); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
  border:1px solid var(--glass-border); border-radius:var(--radius);
  box-shadow:var(--glass-shadow); padding:18px 20px;
}
.insight-card .ic-title{ font-size:14px; font-weight:600; color:#fff; }
.insight-card .ic-sub{ font-size:11px; color:var(--text-muted); margin-top:2px; }

.ic-badge{
  display:inline-flex; align-items:center; font-size:11px; font-weight:700;
  padding:4px 12px; border-radius:20px; background:rgba(56,189,248,.18); color:var(--accent);
}

/* Ferritin trend card */
.card-ferritin{
  top:42%; left:-4%; width:230px; z-index:2;
  animation:floatY 6s ease-in-out infinite;
}
.card-ferritin .ferritin-head{ display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
.ferritin-chart{ position:relative; height:70px; display:flex; align-items:flex-end; }
.ferritin-chart svg{ width:100%; height:100%; overflow:visible; }
.ferritin-months{ display:flex; justify-content:space-between; margin-top:8px; font-size:10px; color:var(--text-muted-dark); }

/* Score gauge card */
.card-score{
  top:8%; left:32%; width:220px; z-index:3;
  animation:floatY 7s ease-in-out infinite; animation-delay:.3s;
}
.card-score .score-head{ display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px; }
.card-score .score-head .logo-mini{ font-family:var(--font-display); font-weight:700; font-size:15px; color:#fff; }
.card-score .score-head .score-lbl{ font-size:11px; color:var(--text-muted); margin-top:2px; }
.score-gauge{ position:relative; width:160px; height:160px; margin:0 auto; }
.score-gauge svg{ width:100%; height:100%; transform:rotate(-90deg); }
.score-gauge .gauge-pct{
  position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
  font-family:var(--font-display); font-weight:700; font-size:34px; color:#fff;
}
.score-arrow{
  position:absolute; top:0; right:0; width:30px; height:30px; border-radius:50%;
  background:var(--surface-2); border:1px solid var(--glass-border);
  display:flex; align-items:center; justify-content:center; color:var(--accent-3); font-size:12px;
}

/* Biomarkers card */
.card-biomarkers{
  top:58%; left:53%; width:230px; z-index:2;
  animation:floatY 6.5s ease-in-out infinite; animation-delay:.6s;
}
.card-biomarkers .bm-head{ display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
.bm-bar-track{ height:6px; border-radius:4px; background:var(--surface-2); overflow:hidden; display:flex; }
.bm-bar-track .seg{ height:100%; }
.bm-name{ font-size:13px; color:var(--text); font-weight:500; margin-top:14px; }
.bm-sub{ font-size:11px; color:var(--text-muted); margin-top:2px; }

/* Insight card */
.card-insight{
  top:32%; left:78%; width:230px; z-index:1;
  animation:floatY 7.5s ease-in-out infinite; animation-delay:.9s;
}
.card-insight .insight-tag{
  display:flex; align-items:center; gap:6px; font-size:11px; font-family:monospace;
  color:var(--accent-3); margin-bottom:10px; letter-spacing:.5px;
}
.card-insight p{ font-size:12px; color:var(--text-muted); line-height:1.6; margin-bottom:6px; }
.card-insight .read-more{ font-size:11px; color:var(--accent); font-weight:600; }
.card-insight .ask-chat{
  margin-top:14px; border:1px solid var(--glass-border); border-radius:20px;
  padding:7px 14px; font-size:11px; color:var(--text-muted); display:flex; align-items:center; gap:8px;
}
.card-insight .ask-chat i{ color:var(--accent); }

/* Supplements/Goals card */
.card-supplements{
  top:5%; left:78%; width:200px; z-index:1;
  animation:floatY 6.8s ease-in-out infinite; animation-delay:1.2s;
}
.supp-section-lbl{ font-size:13px; font-weight:600; color:#fff; margin-bottom:10px; }
.supp-row{ display:flex; justify-content:space-between; align-items:center; font-size:11.5px; color:var(--text-muted); margin-bottom:8px; }
.supp-row .freq{ background:var(--surface-2); border-radius:10px; padding:2px 8px; font-size:10px; color:var(--text); }
.card-supplements .divider{ height:1px; background:var(--border); margin:14px 0; }

@media (max-width:1080px){
  .insights-stage{ min-height:0; flex-direction:column; align-items:center; gap:24px; padding:40px 0; }
  .insight-photo{ position:relative; width:100%; max-width:340px; }
  .insight-card{ position:relative; top:auto !important; left:auto !important; width:100% !important; max-width:340px; margin:0 auto; animation:none !important; }
  .insights-head{ margin-bottom:32px; }
  .insights-head p{ text-align:left; max-width:none; }
}

/* ===== USE CASES ===== */
.usecase-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:18px; }
.usecase-card{
  background:var(--glass-bg); backdrop-filter:blur(20px);
  border:1px solid var(--glass-border); border-radius:var(--radius);
  overflow:hidden; transition:all var(--transition); position:relative;
}
.usecase-card:hover{ transform:translateY(-4px); border-color:rgba(56,189,248,.35); }
.usecase-img{
  aspect-ratio:4/3; background:linear-gradient(145deg,var(--surface-2),var(--bg));
  display:flex; align-items:center; justify-content:center; font-size:28px; color:var(--accent);
  position:relative; overflow:hidden;
}
.usecase-img::after{
  content:''; position:absolute; inset:0;
  background:radial-gradient(circle at 30% 30%, rgba(56,189,248,.2), transparent 60%);
}
.usecase-body{ padding:18px 20px 22px; }
.usecase-body h4{ font-family:var(--font-display); font-size:16px; font-weight:700; color:#fff; margin-bottom:6px; }
.usecase-body p{ font-size:12.5px; color:var(--text-muted); line-height:1.6; }

/* ===== SECURITY ===== */
.security-section{
  background:linear-gradient(180deg,var(--bg) 0%, var(--surface) 100%);
  border-top:1px solid var(--border); border-bottom:1px solid var(--border);
}
.security-grid{ display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center; }
.security-list{ display:flex; flex-direction:column; gap:16px; }
.security-item{
  display:flex; gap:16px; align-items:flex-start;
  background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:var(--radius);
  padding:18px 20px; backdrop-filter:blur(20px);
}
.security-item .icon{
  width:40px; height:40px; border-radius:10px; flex-shrink:0;
  background:rgba(56,189,248,.15); color:var(--accent);
  display:flex; align-items:center; justify-content:center; font-size:15px;
}
.security-item h4{ font-family:var(--font-display); font-size:15px; font-weight:700; color:#fff; margin-bottom:4px; }
.security-item p{ font-size:12.5px; color:var(--text-muted); line-height:1.6; }
.security-stats{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.security-stat{
  background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:var(--radius);
  padding:22px; text-align:center; backdrop-filter:blur(20px);
}
.security-stat .pct{ font-family:var(--font-display); font-weight:700; font-size:34px; color:#fff; letter-spacing:-0.5px; }
.security-stat .pct.acc{ color:var(--accent); }
.security-stat .pct.acc2{ color:var(--accent-2); }
.security-stat .pct.acc3{ color:var(--accent-3); }
.security-stat .pct.acc4{ color:var(--accent-4); }
.security-stat .lbl{ font-size:12px; color:var(--text-muted); margin-top:6px; }

/* ===== REVIEWS ===== */
.reviews-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
.review-card{
  background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:var(--radius);
  padding:22px; backdrop-filter:blur(20px);
}
.review-card .stars{ margin-bottom:10px; }
.review-card p{ font-size:13px; color:var(--text); line-height:1.7; margin-bottom:14px; }
.review-author{ display:flex; align-items:center; gap:10px; }
.review-avatar{
  width:32px; height:32px; border-radius:50%;
  background:linear-gradient(135deg,var(--accent),var(--accent-2));
  display:flex; align-items:center; justify-content:center;
  font-size:12px; font-weight:700; color:#fff; font-family:var(--font-display);
}
.review-name{ font-size:13px; font-weight:600; color:#fff; }
.review-verified{ font-size:11px; color:var(--accent-3); display:flex; align-items:center; gap:4px; }

/* ===== PRICING / OFFER ===== */
.pricing-section{ border-top:1px solid var(--border); }
.pricing-grid{ display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:center; }
.product-shot{
  border-radius:var(--radius); overflow:hidden; border:1px solid var(--glass-border);
  background:linear-gradient(145deg,var(--surface-2),var(--bg)); aspect-ratio:4/5;
  display:flex; align-items:center; justify-content:center; position:relative;
}
.product-shot .ring-big{
  width:160px; height:160px; border-radius:50%;
  background:linear-gradient(145deg,var(--surface),var(--bg));
  border:1px solid var(--glass-border); display:flex; align-items:center; justify-content:center;
  font-family:var(--font-display); font-weight:700; color:#fff; font-size:22px;
  box-shadow:0 0 60px rgba(56,189,248,.25);
}
.pricing-card{
  background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:var(--radius);
  padding:32px; backdrop-filter:blur(20px);
}
.pricing-card .ribbon{
  display:inline-flex; background:rgba(52,211,153,.15); color:var(--accent-3);
  font-size:11px; font-weight:700; letter-spacing:.6px; text-transform:uppercase;
  padding:5px 12px; border-radius:20px; margin-bottom:16px;
}
.pricing-card h3{ font-family:var(--font-display); font-weight:700; font-size:24px; color:#fff; margin-bottom:6px; }
.price-row{ display:flex; align-items:baseline; gap:10px; margin:14px 0 20px; }
.price-now{ font-family:var(--font-display); font-weight:700; font-size:32px; color:#fff; }
.price-old{ font-size:15px; color:var(--text-muted-dark); text-decoration:line-through; }
.price-period{ font-size:13px; color:var(--text-muted); }
.feature-list{ display:flex; flex-direction:column; gap:10px; margin-bottom:24px; }
.feature-list li{ list-style:none; display:flex; gap:10px; align-items:flex-start; font-size:13.5px; color:var(--text); }
.feature-list i{ color:var(--accent-3); margin-top:2px; }
.pricing-card .btn{ width:100%; }
.pricing-note{ font-size:11.5px; color:var(--text-muted-dark); text-align:center; margin-top:14px; }

/* ===== FAQ ===== */
.faq-section{ border-top:1px solid var(--border); }
.faq-list{ display:flex; flex-direction:column; gap:1px; max-width:760px; margin:0 auto; }
.faq-item{
  background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:var(--radius-sm);
  margin-bottom:10px; overflow:hidden; backdrop-filter:blur(20px);
}
.faq-q{
  display:flex; align-items:center; justify-content:space-between;
  padding:18px 22px; cursor:pointer; font-size:14px; font-weight:600; color:#fff;
}
.faq-q i{ color:var(--text-muted); transition:transform var(--transition); font-size:13px; }
.faq-item.open .faq-q i{ transform:rotate(45deg); color:var(--accent); }
.faq-a{
  max-height:0; overflow:hidden; transition:max-height var(--transition);
  font-size:13px; color:var(--text-muted); line-height:1.7;
}
.faq-item.open .faq-a{ max-height:200px; }
.faq-a-inner{ padding:0 22px 18px; }

/* ===== FINAL CTA ===== */
.cta-section{
  background:radial-gradient(ellipse 80% 80% at 50% 0%, rgba(56,189,248,.2), transparent 60%);
  text-align:center; border-top:1px solid var(--border);
}
.cta-section h2{
  font-family:var(--font-display); font-weight:700; font-size:42px;
  letter-spacing:-0.8px; color:#fff; max-width:680px; margin:0 auto 18px;
}
.cta-section p{ font-size:15px; color:var(--text-muted); max-width:520px; margin:0 auto 32px; }

/* ===== FOOTER ===== */
footer{ background:var(--surface); border-top:1px solid var(--border); padding-top:60px; }
.footer-top{ display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr; gap:40px; padding-bottom:48px; border-bottom:1px solid var(--border); }
.footer-brand .logo{ margin-bottom:14px; }
.footer-brand p{ font-size:13px; color:var(--text-muted); line-height:1.7; max-width:280px; margin-bottom:18px; }
.social-row{ display:flex; gap:10px; }
.social-row a{
  width:36px; height:36px; border-radius:var(--radius-sm); border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center; color:var(--text-muted);
  transition:all var(--transition); font-size:13px;
}
.social-row a:hover{ background:var(--surface-2); color:var(--accent); border-color:rgba(56,189,248,.4); }
.footer-col h4{ font-family:var(--font-display); font-size:13px; font-weight:700; color:#fff; letter-spacing:1px; text-transform:uppercase; margin-bottom:18px; }
.footer-col ul{ list-style:none; display:flex; flex-direction:column; gap:12px; }
.footer-col a{ font-size:13.5px; color:var(--text-muted); transition:color var(--transition); }
.footer-col a:hover{ color:var(--accent); }
.footer-bottom{
  display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap;
  padding:24px 0; font-size:12px; color:var(--text-muted-dark);
}
.footer-legal{ display:flex; gap:18px; flex-wrap:wrap; }
.footer-legal a:hover{ color:var(--text-muted); }
.disclaimer{
  font-size:10.5px; color:var(--text-muted-dark); line-height:1.7; padding:20px 0 30px;
  border-top:1px solid var(--border); margin-top:8px;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeUp{ from{opacity:0; transform:translateY(16px);} to{opacity:1; transform:translateY(0);} }
.fade-up{ animation:fadeUp .6s cubic-bezier(.4,0,.2,1) both; }
.fade-up.d1{ animation-delay:.05s; }
.fade-up.d2{ animation-delay:.1s; }
.fade-up.d3{ animation-delay:.15s; }
[data-animate]{ opacity:0; transform:translateY(24px); transition:opacity .7s cubic-bezier(.4,0,.2,1), transform .7s cubic-bezier(.4,0,.2,1); }
[data-animate].in-view{ opacity:1; transform:translateY(0); }

/* ===== RESPONSIVE ===== */
@media (max-width:1080px){
  .hero-grid, .how-grid, .compare-grid, .security-grid, .pricing-grid{ grid-template-columns:1fr; }
  .hero-visual{ order:-1; }
  .usecase-grid{ grid-template-columns:repeat(2,1fr); }
  .reviews-grid{ grid-template-columns:1fr; }
  .security-stats{ margin-top:10px; }
  .footer-top{ grid-template-columns:1fr 1fr; }
}
@media (max-width:768px){
  .container{ padding:0 20px; }
  .nav-links{ display:none; }
  .nav-toggle{ display:flex; align-items:center; justify-content:center; }
  .hero h1{ font-size:38px; }
  .hero{ padding:48px 0 40px; }
  .device-ring{ width:220px; height:220px; }
  .device-core{ width:130px; height:130px; font-size:20px; }
  .section-title{ font-size:28px; }
  .usecase-grid{ grid-template-columns:1fr 1fr; }
  .vials{ gap:20px; }
  .footer-top{ grid-template-columns:1fr; gap:32px; }
  .footer-bottom{ flex-direction:column; align-items:flex-start; }
  .cta-section h2{ font-size:30px; }
  .trust-strip{ gap:18px; }
}
</style>
</head>
<body>

<!-- ===== ANNOUNCEMENT BAR ===== -->
<div class="announce">
  LIMITED TIME ONLY <span class="pill">Take 20% off</span> on your first Vyralabs kit
</div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
  <div class="container nav-inner">
    {{-- <div class="logo"><span class="dot"></span> vyralabs</div> --}}
    <div class="logo">
        <img src="{{ asset('images/logo.avif') }}" alt="Massimo Logo" 
         style="position: absolute !important; top: 15px !important; left: 21% !important; transform: translateX(-50%) !important; height: 38px !important; width: auto !important; object-fit: contain !important; max-width: 85% !important;">
  
    </div>
    <div class="nav-links">
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

<!-- ===== HERO ===== -->
@php
    $bannerPath = asset('images/dna-banner.png');
@endphp
<header class="hero" style="
    background-image: 
        radial-gradient(ellipse 70% 50% at 70% 0%, rgba(56, 189, 248, 0.18), transparent 60%),
        radial-gradient(ellipse 60% 50% at 0% 30%, rgba(52, 211, 153, 0.08), transparent 60%),
        /* ফিক্স ১: গ্রাডিয়েন্ট মাস্কের দিক পরিবর্তন (বাম থেকে ডানে কালো শেড) */
        linear-gradient(270deg, rgba(11, 15, 25, 0) 0%, rgba(11, 15, 25, 0.8) 60%, var(--bg-main) 100%),
        url('{{ asset('images/dna-banner.png') }}');
    /* ফিক্স ২: প্রতিটি লেয়ারের সাইজ আলাদাভাবে পুশ করা */
    background-size: 100% 100%, 100% 100%, 100% 100%, cover;
    /* ফিক্স ৩: ইমেজটিকে রাইট-সেন্টার পজিশন দেওয়া */
    background-position: center, center, center, right center;
    background-repeat: no-repeat;
">
  <div class="container hero-grid">
    <div class="hero-copy">
      <div class="eyebrow-row">
        <span class="eyebrow"><i class="fa-solid fa-circle-check"></i> Clinical Accuracy Guarantee</span>
        <span class="eyebrow"><i class="fa-solid fa-circle-check"></i> CLIA Certified &amp; CAP Accredited</span>
      </div>
      <h1>The World's Easiest <em>Performance Test</em></h1>
      <p>Painless at-home blood testing built for creators, athletes, and anyone optimizing their body. Actionable results in less than 72 hours.</p>
      <div class="hero-cta-row">
        <a href="#pricing" class="btn btn-primary btn-lg">Try now with 20% off <span style="opacity:.7; font-style:italic; font-weight:500;">risk free</span></a>
      </div>
      <div class="hero-meta">
        <div><i class="fa-solid fa-check"></i> HSA / FSA Eligible</div>
        <div><i class="fa-solid fa-droplet"></i> Millions of results delivered</div>
        <div><span class="stars">★★★★★</span> 4.8/5 from verified customers</div>
      </div>

      <div class="trust-strip">
        <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> HIPAA Aligned</div>
        <div class="trust-item"><i class="fa-solid fa-globe"></i> CLIA Certified</div>
        <div class="trust-item"><i class="fa-solid fa-award"></i> CAP Accredited</div>
        <div class="trust-item"><i class="fa-solid fa-location-dot"></i> FDA Cleared</div>
      </div>
    </div>

    {{-- <div class="hero-visual">
      <div class="float-card float-1"><i class="fa-solid fa-shield"></i> Results in 72hrs</div>
      <div class="float-card float-2"><i class="fa-solid fa-droplet"></i> Painless · 1ml sample</div>
      <div class="device-ring">
        <div class="device-core">vyralabs</div>
        <div class="device-stick">VYRALABS</div>
      </div>
    </div> --}}
  </div>
</header>

<!-- ===== HOW IT WORKS ===== -->
<section class="how-section" id="how">
  <div class="container how-grid">
    <div class="how-visual" data-animate>
      <div class="ring-big">vyralabs</div>
    </div>
    <div class="how-steps">
      <div class="section-eyebrow">How it works</div>
      <div class="how-step" data-animate>
        <div class="num">1</div>
        <div class="how-step-body">
          <div class="tag">Painless</div>
          <h3>At-Home Test</h3>
          <p>A single, near-painless finger prick collects everything we need — no needles, no lab visits, no appointments.</p>
        </div>
      </div>
      <div class="how-step" data-animate>
        <div class="num">2</div>
        <div class="how-step-body">
          <div class="tag">Free</div>
          <h3>Send (Mail or Get Picked Up)</h3>
          <p>Drop your kit in any mailbox or schedule a free pickup from your home — whichever fits your schedule.</p>
        </div>
      </div>
      <div class="how-step" data-animate>
        <div class="num">3</div>
        <div class="how-step-body">
          <div class="tag">Results within</div>
          <h3>3 Days</h3>
          <p>Get clinically validated results and personalized, AI-powered insights delivered straight to your dashboard.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== COMPARISON ===== -->
<section class="compare-section">
  <div class="container compare-grid">
    <div data-animate>
      <div class="section-eyebrow">Clinical results, a fraction of the blood</div>
      <h2 class="section-title">Clinical results with a <strong>tenth</strong> of the blood</h2>
      <p class="section-sub">Traditional labs need a full vial draw across multiple tubes. Vyralabs gets the same clinical-grade insight from a single 1ml sample — no needles, no clinic, no waiting room.</p>
    </div>
    <div class="vials" data-animate>
      <img src="{{ asset('images/bloodtube.png') }}" alt="">
    </div>
  </div>
</section>

<!-- ===== MONTHLY TESTING / INSIGHTS DASHBOARD ===== -->
<section class="insights-section">
  <div class="container">
    <div class="insights-head" data-animate>
      <h2>Monthly testing for more personalized insights.</h2>
      <p>Your AI-powered dashboard surfaces real trends, so the insights you get reflect your goals.</p>
    </div>

    <div class="insights-stage" data-animate>
      <div class="insight-photo">
        <img src="https://images.unsplash.com/photo-1571008887538-b36bb32f4571?w=700&q=80&auto=format&fit=crop" alt="Athlete running outdoors" />
      </div>

      <!-- Ferritin trend -->
      <div class="insight-card card-ferritin">
        <div class="ferritin-head">
          <span class="ic-title">Ferritin</span>
          <span class="ic-badge">Optimal</span>
        </div>
        <div class="ferritin-chart">
          <svg viewBox="0 0 200 70" preserveAspectRatio="none">
            <defs>
              <linearGradient id="ferritinLine" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%" stop-color="#f59e0b"/>
                <stop offset="35%" stop-color="#f59e0b"/>
                <stop offset="55%" stop-color="#38bdf8"/>
                <stop offset="100%" stop-color="#38bdf8"/>
              </linearGradient>
            </defs>
            <line x1="0" y1="35" x2="200" y2="35" stroke="var(--border)" stroke-width="1"/>
            <polyline points="0,58 40,52 80,30 120,24 160,18 200,14" fill="none" stroke="url(#ferritinLine)" stroke-width="2.5"/>
            <circle cx="80" cy="30" r="3.5" fill="#38bdf8"/>
            <circle cx="120" cy="24" r="3.5" fill="#38bdf8"/>
            <circle cx="160" cy="18" r="3.5" fill="#38bdf8"/>
            <circle cx="200" cy="14" r="3.5" fill="#38bdf8"/>
          </svg>
        </div>
        <div class="ferritin-months">
          <span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
        </div>
      </div>

      <!-- Score gauge -->
      <div class="insight-card card-score">
        <div class="score-head">
          <div>
            <div class="logo-mini">vyralabs</div>
            <div class="score-lbl">Longevity Score</div>
          </div>
          <span class="ic-badge">Optimal</span>
        </div>
        <div class="score-gauge">
          <svg viewBox="0 0 160 160">
            <circle cx="80" cy="80" r="68" fill="none" stroke="var(--surface-2)" stroke-width="12"/>
            <circle cx="80" cy="80" r="68" fill="none" stroke="#f59e0b" stroke-width="12" stroke-dasharray="427" stroke-dashoffset="80" stroke-linecap="round"/>
            <circle cx="80" cy="80" r="68" fill="none" stroke="#38bdf8" stroke-width="12" stroke-dasharray="427" stroke-dashoffset="372" stroke-linecap="round"/>
          </svg>
          <div class="gauge-pct">87%</div>
          <div class="score-arrow"><i class="fa-solid fa-arrow-up"></i></div>
        </div>
      </div>

      <!-- Biomarkers -->
      <div class="insight-card card-biomarkers">
        <div class="bm-head">
          <span class="ic-title">Biomarkers</span>
          <span class="ic-badge" style="background:rgba(52,211,153,.18); color:var(--accent-3);">+12%</span>
        </div>
        <div class="bm-name">Total Testosterone</div>
        <div class="bm-sub">Vitality Indicator</div>
        <div class="bm-bar-track" style="margin-top:10px;">
          <div class="seg" style="width:25%; background:#f59e0b;"></div>
          <div class="seg" style="width:35%; background:var(--surface-2);"></div>
          <div class="seg" style="width:30%; background:var(--accent);"></div>
          <div class="seg" style="width:10%; background:var(--accent-3);"></div>
        </div>
      </div>

      <!-- Insight of the month -->
      <div class="insight-card card-insight">
        <div class="insight-tag"><i class="fa-regular fa-clock"></i> INSIGHT FOR THIS MONTH</div>
        <p>Your ferritin fell by 22%, a trend often seen with increased training volume during marathon preparation.</p>
        <p>This dip usually appears when your body adjusts to marathon training. <span class="read-more">Read more</span></p>
        <div class="ask-chat"><i class="fa-regular fa-circle"></i> Ask more in the chat</div>
      </div>

      <!-- Supplements & Goals -->
      <div class="insight-card card-supplements">
        <div class="supp-section-lbl">Supplements</div>
        <div class="supp-row"><span>Magnesium</span><span class="freq">2x Day</span></div>
        <div class="supp-row"><span>Vitamin D</span><span class="freq">1x Week</span></div>
        <div class="divider"></div>
        <div class="supp-section-lbl">Goals</div>
        <div class="supp-row" style="margin-bottom:0;"><span>Marathon Training</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ===== USE CASES ===== -->
<section id="about">
  <div class="container">
    <div class="section-head center">
      <div class="section-eyebrow">For every part of life</div>
      <h2 class="section-title">Vyralabs' all-in-one panel is built for every part of your life.</h2>
      <p class="section-sub">Whether you're chasing performance, planning ahead, or just want clarity — Vyralabs is your roadmap to better health.</p>
    </div>
    <div class="usecase-grid">
      <div class="usecase-card" data-animate>
        <div class="usecase-img"><i class="fa-solid fa-person-running"></i></div>
        <div class="usecase-body">
          <h4>Athletic Performance</h4>
          <p>See how your body produces energy and adapts to training — and train smarter.</p>
        </div>
      </div>
      <div class="usecase-card" data-animate>
        <div class="usecase-img"><i class="fa-solid fa-heart-pulse"></i></div>
        <div class="usecase-body">
          <h4>Women's Health</h4>
          <p>Navigate changes in your body with clarity so you can adapt, stay balanced, and feel your best.</p>
        </div>
      </div>
      <div class="usecase-card" data-animate>
        <div class="usecase-img"><i class="fa-solid fa-vial-circle-check"></i></div>
        <div class="usecase-body">
          <h4>Hormone Optimization</h4>
          <p>Continuous tracking on TRT, HRT, GLP-1s and more — stay informed and in control.</p>
        </div>
      </div>
      <div class="usecase-card" data-animate>
        <div class="usecase-img"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
        <div class="usecase-body">
          <h4>Preventative Tracking</h4>
          <p>Stay ahead of potential health concerns by tracking instead of reacting to symptoms.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== SECURITY ===== -->
<section class="security-section">
  <div class="container security-grid">
    <div data-animate>
      <div class="section-eyebrow">Private &amp; Secure</div>
      <h2 class="section-title">It's easy to forget the infrastructure that makes fast, accurate testing possible.</h2>
      <div class="security-list" style="margin-top:24px;">
        <div class="security-item">
          <div class="icon"><i class="fa-solid fa-lock"></i></div>
          <div>
            <h4>Military-grade encryption</h4>
            <p>Advanced encryption protocols keep your health data protected and private at every point of the system.</p>
          </div>
        </div>
        <div class="security-item">
          <div class="icon"><i class="fa-solid fa-flask"></i></div>
          <div>
            <h4>In-house testing, no third parties</h4>
            <p>We handle every sample ourselves to guarantee privacy and consistent lab-grade results.</p>
          </div>
        </div>
        <div class="security-item">
          <div class="icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <h4>Verified chain of custody</h4>
            <p>Our closed-loop chain of custody system tracks and protects your sample from the moment it's collected.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="security-stats" data-animate>
      <div class="security-stat"><div class="pct acc3">97%</div><div class="lbl">Enjoyed the collection and would do it again</div></div>
      <div class="security-stat"><div class="pct acc">91%</div><div class="lbl">Learned something new about their health</div></div>
      <div class="security-stat"><div class="pct acc4">85%</div><div class="lbl">Made changes to their diet, exercise & lifestyle</div></div>
      <div class="security-stat"><div class="pct acc2">93%</div><div class="lbl">Have recommended Vyralabs to friends & family</div></div>
    </div>
  </div>
</section>

<!-- ===== REVIEWS ===== -->
<section>
  <div class="container">
    <div class="section-head center">
      <div class="section-eyebrow">Thousands of 5-star reviews</div>
      <h2 class="section-title">This is what people say about Vyralabs</h2>
      <p class="section-sub">Real reviews from real customers who've seen health improvements in less than 90 days.</p>
    </div>
    <div class="reviews-grid">
      <div class="review-card" data-animate>
        <div class="stars">★★★★★</div>
        <p>I was the most skeptical, as this sounded too good to be true. I ordered my kit, got informed every step of the way, and got my results within days.</p>
        <div class="review-author">
          <div class="review-avatar">M</div>
          <div>
            <div class="review-name">Manny A.</div>
            <div class="review-verified"><i class="fa-solid fa-circle-check"></i> Verified Buyer</div>
          </div>
        </div>
      </div>
      <div class="review-card" data-animate>
        <div class="stars">★★★★★</div>
        <p>It's really that easy. The simplicity is not just talk — it's the reality. Scheduled a pickup, completed it Friday, results were ready by the weekend.</p>
        <div class="review-author">
          <div class="review-avatar">D</div>
          <div>
            <div class="review-name">Dominic</div>
            <div class="review-verified"><i class="fa-solid fa-circle-check"></i> Verified Buyer</div>
          </div>
        </div>
      </div>
      <div class="review-card" data-animate>
        <div class="stars">★★★★★</div>
        <p>If you have any hesitation, don't. I'm in great health, train 4-5 days a week, and the insights gave me real clarity on where to focus next.</p>
        <div class="review-author">
          <div class="review-avatar">J</div>
          <div>
            <div class="review-name">John P.</div>
            <div class="review-verified"><i class="fa-solid fa-circle-check"></i> Verified Buyer</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PRICING ===== -->
<section class="pricing-section" id="pricing">
  <div class="container pricing-grid">
    <div class="product-shot" data-animate>
      <div class="ring-big">vyralabs</div>
    </div>
    <div class="pricing-card" data-animate>
      <span class="ribbon">Save 20% on your first order</span>
      <h3>Vyralabs At-Home Performance Test</h3>
      <p style="font-size:13px; color:var(--text-muted);">Ships every 30 days · Flexible subscription · Skip anytime</p>
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

<!-- ===== FAQ ===== -->
<section class="faq-section">
  <div class="container">
    <div class="section-head center">
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

<!-- ===== FINAL CTA ===== -->
<section class="cta-section">
  <div class="container">
    <h2>Start tracking what actually moves the needle.</h2>
    <p>Join thousands using Vyralabs to turn guesswork into a real, data-backed plan for their health and performance.</p>
    <a href="#pricing" class="btn btn-primary btn-lg">Try now with 20% off <span style="opacity:.7; font-style:italic; font-weight:500;">risk free</span></a>
  </div>
</section>

<!-- ===== FOOTER ===== -->
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
          <li><a href="#">Login</a></li>
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
      DISCLAIMER: Vyralabs does not recommend or refer you to any healthcare providers, and you are free to choose any healthcare provider and to continue to use Vyralabs' services. Vyralabs does not offer medical advice, a diagnosis, medical treatment, or any form of medical opinion through our services or otherwise. We recommend that you discuss those questions with your primary care physician or other licensed provider. All material, information, data, and content that Vyralabs provides is strictly for general information purposes. Vyralabs' membership pricing includes technology and service fees charged by Vyralabs, as well as access to prepaid laboratory and other services. Certain items and services require additional payment that are not included in standard membership pricing. For other important information regarding the services provided by Vyralabs, please see our Terms of Service.
    </div>
  </div>
</footer>

<script>
// Profile-dropdown-style FAQ accordion
document.querySelectorAll('.faq-item').forEach(item => {
  item.querySelector('.faq-q').addEventListener('click', () => {
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  });
});

// Scroll reveal animations
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) entry.target.classList.add('in-view');
  });
}, { threshold: 0.15 });
document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

// Mobile nav toggle (simple show/hide)
const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');
navToggle?.addEventListener('click', () => {
  navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
  navLinks.style.flexDirection = 'column';
  navLinks.style.position = 'absolute';
  navLinks.style.top = '72px';
  navLinks.style.left = '0';
  navLinks.style.right = '0';
  navLinks.style.background = 'var(--surface)';
  navLinks.style.padding = '20px';
  navLinks.style.borderBottom = '1px solid var(--border)';
});
</script>
</body>
</html>
