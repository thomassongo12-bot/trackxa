/**
 * TrackXa – Main JavaScript
 */
(function () {
  'use strict';

  // ── Page Loader ─────────────────────────────────────────────
  window.addEventListener('load', function () {
    var loader = document.getElementById('tx-loader');
    if (loader) {
      loader.style.opacity = '0';
      setTimeout(function () { loader.style.display = 'none'; }, 400);
    }
  });

  // ── Back to Top ──────────────────────────────────────────────
  var backTop = document.getElementById('tx-backtop');
  if (backTop) {
    window.addEventListener('scroll', function () {
      backTop.classList.toggle('visible', window.scrollY > 400);
    });
    backTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Tracking Form Submit ─────────────────────────────────────
  var trackForm = document.getElementById('tx-track-form');
  if (trackForm) {
    trackForm.addEventListener('submit', function (e) {
      var input = document.getElementById('tracking_number');
      var btn   = trackForm.querySelector('.tx-track-btn');
      var val   = input ? input.value.trim() : '';
      if (!val) {
        e.preventDefault();
        input && input.focus();
        showTrackError('Please enter a tracking number.');
        return;
      }
      if (btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Tracking...';
        btn.disabled  = true;
      }
    });
  }

  function showTrackError(msg) {
    var el = document.getElementById('tx-track-error');
    if (!el) {
      el = document.createElement('div');
      el.id = 'tx-track-error';
      el.className = 'alert alert-danger mt-2 mb-0';
      var form = document.getElementById('tx-track-form');
      if (form) form.appendChild(el);
    }
    el.textContent = msg;
    el.style.display = 'block';
  }

  // ── Counter Animation ────────────────────────────────────────
  function animateCounter(el) {
    var target = parseInt(el.getAttribute('data-target') || el.textContent, 10);
    if (isNaN(target)) return;
    var start    = 0;
    var duration = 2000;
    var step     = Math.ceil(target / (duration / 16));
    var current  = 0;
    var suffix   = el.getAttribute('data-suffix') || '';
    var timer    = setInterval(function () {
      current += step;
      if (current >= target) { current = target; clearInterval(timer); }
      el.textContent = current.toLocaleString() + suffix;
    }, 16);
  }

  var counters = document.querySelectorAll('[data-counter]');
  if (counters.length && 'IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          animateCounter(e.target);
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (c) { obs.observe(c); });
  }

  // ── AJAX Tracking (inline widget) ────────────────────────────
  var ajaxTrackBtn = document.getElementById('tx-ajax-track-btn');
  if (ajaxTrackBtn) {
    ajaxTrackBtn.addEventListener('click', function () {
      var input   = document.getElementById('tx-ajax-input');
      var result  = document.getElementById('tx-ajax-result');
      var number  = input ? input.value.trim() : '';
      if (!number || !result) return;
      result.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary"></div></div>';
      fetch(BASE_URL + '/api/v1/track/' + encodeURIComponent(number))
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (!data.success) {
            result.innerHTML = '<div class="alert alert-warning mb-0">' + (data.error || 'Not found') + '</div>';
            return;
          }
          var t = data.tracking;
          var statusClass = 'badge bg-' + (t.status_color || 'secondary');
          result.innerHTML =
            '<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">' +
              '<div><strong>' + t.tracking_number + '</strong><br>' +
              '<small class="text-muted">' + (t.origin || '') + ' → ' + (t.destination || '') + '</small></div>' +
              '<span class="' + statusClass + '">' + t.status_label + '</span>' +
            '</div>' +
            '<hr class="my-2">' +
            '<a href="' + BASE_URL + '/track/' + t.tracking_number + '" class="btn btn-sm btn-primary w-100">View Full Details</a>';
        })
        .catch(function () {
          result.innerHTML = '<div class="alert alert-danger mb-0">Error. Please try again.</div>';
        });
    });
    var ajaxInput = document.getElementById('tx-ajax-input');
    if (ajaxInput) {
      ajaxInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') ajaxTrackBtn.click();
      });
    }
  }

  // ── Smooth scroll for anchor links ───────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ── Navbar scroll effect ──────────────────────────────────────
  var navbar = document.querySelector('.tx-navbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    });
  }

  // ── Auto-dismiss alerts ───────────────────────────────────────
  document.querySelectorAll('.alert-auto-dismiss').forEach(function (el) {
    setTimeout(function () {
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 300);
    }, 4000);
  });

})();

// ── Hero Slider ───────────────────────────────────────────────
(function(){
  var slides = document.querySelectorAll('.tx-slide');
  var dots   = document.querySelectorAll('.tx-dot');
  if (!slides.length) return;
  var current  = 0;
  var interval = null;

  function goTo(n) {
    slides[current].classList.remove('active');
    dots[current] && dots[current].classList.remove('active');
    current = (n + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current] && dots[current].classList.add('active');
  }

  function next() { goTo(current + 1); }

  function start() { interval = setInterval(next, 5000); }
  function stop()  { clearInterval(interval); }

  // Dots click
  dots.forEach(function(dot, i){
    dot.addEventListener('click', function(){ stop(); goTo(i); start(); });
  });

  // Pause on hover
  var hero = document.querySelector('.tx-hero-slider');
  if (hero) {
    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
  }

  start();
})();
var BASE_URL = window.BASE_URL || '';
