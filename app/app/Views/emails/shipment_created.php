<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{font-family:'Segoe UI',Arial,sans-serif;background:#f4f6f9;margin:0;padding:0;}
  .wrap{max-width:600px;margin:30px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
  .header{background:linear-gradient(135deg,#0f2647,#1a3c6e);padding:32px;text-align:center;}
  .header h1{color:#fff;font-size:24px;margin:0;font-weight:800;}
  .header p{color:rgba(255,255,255,.7);margin:6px 0 0;font-size:14px;}
  .body{padding:36px 32px;}
  .tracking-box{background:#f0f4ff;border:2px dashed #1a3c6e;border-radius:12px;padding:20px;text-align:center;margin:24px 0;}
  .tracking-number{font-size:22px;font-weight:800;color:#1a3c6e;letter-spacing:.06em;font-family:monospace;}
  .tracking-label{font-size:12px;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;}
  .btn{display:inline-block;background:#e8a020;color:#fff;text-decoration:none;border-radius:8px;padding:12px 28px;font-weight:700;font-size:15px;margin-top:16px;}
  .info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:14px;}
  .info-row .label{color:#888;font-size:13px;}
  .info-row .value{font-weight:600;color:#2d3748;}
  .footer{background:#f8f9fa;padding:20px;text-align:center;font-size:12px;color:#aaa;border-top:1px solid #e2e8f0;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Track<span style="color:#e8a020">Xa</span></h1>
    <p>Professional Shipment Tracking</p>
  </div>
  <div class="body">
    <p style="font-size:16px;color:#2d3748">Hello <strong><?= htmlspecialchars($recipient_name) ?></strong>,</p>
    <p style="color:#555;line-height:1.7">Your shipment has been successfully created. You can track your package in real time using the tracking number below.</p>
    <div class="tracking-box">
      <div class="tracking-label">Your Tracking Number</div>
      <div class="tracking-number"><?= htmlspecialchars($tracking_number) ?></div>
      <a href="<?= htmlspecialchars($tracking_url) ?>" class="btn">Track My Shipment</a>
    </div>
    <div style="margin-top:20px">
      <div class="info-row"><span class="label">Estimated Delivery</span><span class="value"><?= htmlspecialchars($estimated_delivery ?? 'To be confirmed') ?></span></div>
      <div class="info-row"><span class="label">Tracking URL</span><span class="value" style="word-break:break-all;font-size:12px"><?= htmlspecialchars($tracking_url) ?></span></div>
    </div>
    <p style="color:#888;font-size:13px;margin-top:24px">If you have any questions, please contact our support team.</p>
  </div>
  <div class="footer">
    &copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?> &nbsp;|&nbsp; This is an automated message.
  </div>
</div>
</body>
</html>
