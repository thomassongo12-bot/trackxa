<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= Security::e($title ?? 'Admin Login – TrackXa') ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  body { font-family:'Inter',sans-serif; background: linear-gradient(135deg,#0f2647 0%,#1a3c6e 60%,#2563eb 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
  .auth-card { background:#fff; border-radius:20px; padding:3rem 2.5rem; width:100%; max-width:420px; box-shadow:0 30px 80px rgba(0,0,0,.3); }
  .auth-logo { text-align:center; margin-bottom:2rem; }
  .auth-logo .brand { font-size:2rem; font-weight:800; color:#1a3c6e; }
  .auth-logo .brand span { color:#e8a020; }
  .auth-logo p { color:#6c757d; font-size:.9rem; margin:0; }
  .auth-card h2 { font-size:1.4rem; font-weight:800; color:#1a3c6e; margin-bottom:.3rem; }
  .form-control { border:1.5px solid #e2e8f0; border-radius:10px; padding:.7rem 1rem; font-size:.9rem; }
  .form-control:focus { border-color:#1a3c6e; box-shadow:0 0 0 3px rgba(26,60,110,.12); }
  .form-label { font-size:.82rem; font-weight:600; color:#374151; }
  .btn-auth { background:#e8a020; color:#fff; border:none; border-radius:10px; padding:.8rem; font-weight:700; font-size:.95rem; width:100%; transition:all .2s; }
  .btn-auth:hover { background:#c8860a; transform:translateY(-1px); color:#fff; }
  .input-group-text { background:#f8f9fa; border:1.5px solid #e2e8f0; border-radius:0 10px 10px 0; cursor:pointer; }
  .pass-toggle { border-left:none; }
  .form-control.pass-input { border-right:none; border-radius:10px 0 0 10px; }
  .auth-footer { text-align:center; margin-top:1.5rem; font-size:.85rem; color:#6c757d; }
  .auth-footer a { color:#1a3c6e; font-weight:600; }
</style>
</head>
<body>
<?= $content ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.pass-toggle').forEach(function(btn){
  btn.addEventListener('click',function(){
    var inp = this.closest('.input-group').querySelector('input');
    var ico = this.querySelector('i');
    if(inp.type==='password'){inp.type='text';ico.className='fas fa-eye-slash';}
    else{inp.type='password';ico.className='fas fa-eye';}
  });
});
</script>
</body>
</html>
