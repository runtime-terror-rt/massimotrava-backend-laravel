// ===== SIDEBAR TOGGLE =====
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

const isMobile = () => window.innerWidth <= 768;

function toggleSidebar() {
  if (isMobile()) {
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('active');
  } else {
    sidebar.classList.toggle('collapsed');
  }
}

window.addEventListener('resize', () => {
  if (!isMobile()) {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
  }
});

// ===== NAV ITEM ACTIVE =====
document.querySelectorAll('.nav-item').forEach(item => {
  item.addEventListener('click', () => {
    // Active state is handled server-side via Blade,
    // but we keep this for instant visual feedback on click.
    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
    item.classList.add('active');
    if (isMobile()) toggleSidebar();
  });
});

// ===== FIXED PROFILE DROPDOWN =====
const profileBtn    = document.getElementById('profileBtn');
const dropdownMenu  = document.getElementById('dropdownMenu');

function toggleDropdown() {
  if (profileBtn && dropdownMenu) {
    profileBtn.classList.toggle('open');
    dropdownMenu.classList.toggle('show-menu'); // <-- FIXED: Synced with your layout layout style sheet class
  }
}

// Close dropdown when clicking outside
document.addEventListener('click', e => {
  if (!e.target.closest('.profile-dropdown')) {
    profileBtn?.classList.remove('open');
    dropdownMenu?.classList.remove('show-menu'); // <-- FIXED
  }
});

// ===== TAB BUTTONS =====
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.closest('.tab-group').querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});