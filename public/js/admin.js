/**
 * TrackXa – Admin Panel JavaScript
 */
(function () {
  'use strict';

  // ── Sidebar Toggle ────────────────────────────────────────────
  var sidebar  = document.querySelector('.adm-sidebar');
  var toggle   = document.querySelector('.adm-menu-toggle');
  var overlay  = document.getElementById('adm-overlay');

  function openSidebar() {
    if (sidebar) sidebar.classList.add('open');
    if (overlay) overlay.classList.add('active');
  }
  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('active');
  }

  if (toggle) {
    toggle.addEventListener('click', function () {
      sidebar && sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
  }
  if (overlay) {
    overlay.addEventListener('click', closeSidebar);
  }

  // Close sidebar on nav link click (mobile)
  document.querySelectorAll('.adm-nav-link:not(.has-sub)').forEach(function (link) {
    link.addEventListener('click', function () {
      if (window.innerWidth < 992) closeSidebar();
    });
  });

  // ── Submenu toggle ────────────────────────────────────────────
  document.querySelectorAll('.adm-nav-link.has-sub').forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      this.classList.toggle('open');
      var sub = this.nextElementSibling;
      if (sub && sub.classList.contains('adm-submenu')) {
        sub.classList.toggle('open');
      }
    });
  });

  // ── Confirm delete ────────────────────────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm(this.getAttribute('data-confirm') || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });

  // ── Auto-dismiss flash messages ───────────────────────────────
  document.querySelectorAll('.adm-flash').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .3s';
      el.style.opacity    = '0';
      setTimeout(function () { el.remove(); }, 300);
    }, 4500);
  });

  // ── Tracking status add modal ─────────────────────────────────
  var addStatusForm = document.getElementById('adm-add-status-form');
  if (addStatusForm) {
    // Auto-fill occurred_at with current datetime
    var dtField = addStatusForm.querySelector('[name="occurred_at"]');
    if (dtField && !dtField.value) {
      var now = new Date();
      var local = new Date(now - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
      dtField.value = local;
    }
  }

  // ── Image preview ─────────────────────────────────────────────
  document.querySelectorAll('[data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var previewId = this.getAttribute('data-preview');
      var preview   = document.getElementById(previewId);
      if (!preview) return;
      var file = this.files[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) { alert('Please select an image file.'); return; }
      if (file.size > 5 * 1024 * 1024) { alert('Image must be under 5MB.'); return; }
      var reader = new FileReader();
      reader.onload = function (e) { preview.src = e.target.result; preview.style.display = 'block'; };
      reader.readAsDataURL(file);
    });
  });

  // ── Tracking number copy ──────────────────────────────────────
  document.querySelectorAll('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = this.getAttribute('data-copy');
      navigator.clipboard && navigator.clipboard.writeText(text).then(function () {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(function () { btn.innerHTML = orig; }, 1500);
      });
    });
  });

  // ── Datatable search ─────────────────────────────────────────
  var tableSearch = document.getElementById('adm-table-search');
  if (tableSearch) {
    var table = document.querySelector('.adm-table tbody');
    tableSearch.addEventListener('input', function () {
      var q = this.value.toLowerCase();
      if (!table) return;
      Array.from(table.querySelectorAll('tr')).forEach(function (row) {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

  // ── Chart.js init (dashboard) ─────────────────────────────────
  var visitorCtx = document.getElementById('chart-visitors');
  if (visitorCtx && typeof Chart !== 'undefined' && window.VISITOR_DATA) {
    new Chart(visitorCtx, {
      type: 'line',
      data: {
        labels: window.VISITOR_DATA.labels,
        datasets: [{
          label: 'Visitors',
          data: window.VISITOR_DATA.values,
          borderColor: '#1a3c6e',
          backgroundColor: 'rgba(26,60,110,.08)',
          borderWidth: 2,
          pointBackgroundColor: '#1a3c6e',
          fill: true,
          tension: .4,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  var statusCtx = document.getElementById('chart-statuses');
  if (statusCtx && typeof Chart !== 'undefined' && window.STATUS_DATA) {
    new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: window.STATUS_DATA.labels,
        datasets: [{
          data: window.STATUS_DATA.values,
          backgroundColor: ['#1a3c6e','#28a745','#e8a020','#dc3545','#17a2b8','#6c757d','#ffc107'],
          borderWidth: 0,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'right', labels: { font: { size: 11 } } } },
        cutout: '70%',
      }
    });
  }

  // ── Slug auto-generator (blog) ────────────────────────────────
  var titleInput = document.getElementById('adm-post-title');
  var slugInput  = document.getElementById('adm-post-slug');
  if (titleInput && slugInput && !slugInput.value) {
    titleInput.addEventListener('input', function () {
      slugInput.value = this.value
        .toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    });
  }

  // ── Select all checkbox ───────────────────────────────────────
  var selectAll = document.getElementById('adm-select-all');
  if (selectAll) {
    selectAll.addEventListener('change', function () {
      document.querySelectorAll('.adm-row-check').forEach(function (c) { c.checked = selectAll.checked; });
    });
  }

})();
