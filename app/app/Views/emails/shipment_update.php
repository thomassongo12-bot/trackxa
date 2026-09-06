<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{font-family:'Segoe UI',Arial,sans-serif;background:#f4f6f9;margin:0;padding:0;}
  .wrap{max-width:600px;margin:30px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
  .header{background:linear-gradient(135deg,#0f2647,#1a3c6e);padding:32px;text-align:center;}
  .header h1{color:#fff;font-size:24px;margin:0;font-weight:800;}
  .status-box{background:#e8f4fd;border-left:5px solid #1a3c6e;border-radius:0 12px 12px 0;padding:20px 24px;margin:24px 0;}
  .status-label{font-size:12px;color:#888;text-transform:uppercase;letter-spacing:.08em;}
  .status-value{font-size:20px;font-weight:800;color:#1a3c6e;margin-top:4px;}
  .btn{display:inline-block;background:#e8a020;color:#fff;text-decoration:none;border-radius:8px;padding:12px 28px;font-weight:700;font-size:14px;margin-top:16px;}
  .footer{background:#f8f9fa;padding:20px;text-align:center;font-size:12px;color:#aaa;border-top:1px solid #e2e8f0;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>Track<span style="color:#e8a020">Xa</span></h1>
    <p style="color:rgba(255,255,255,.7);font-size:14px;margin:6px 0 0">Shipment Status Update</p>
  </div>
  <div style="padding:36px 32px">
    <p style="font-size:16px;color:#2d3748">Hello <strong><?= htmlspecialchars($recipient_name) ?></strong>,</p>
    <p style="color:#555;line-height:1.7">There's an update on your shipment <strong><?= htmlspecialchars($tracking_number) ?></strong>.</p>
    <div class="status-box">
      <div class="status-label">Current Status</div>
      <div class="status-value"><?= htmlspecialchars($status_label) ?></div>
    </div>
    <div style="text-align:center;margin-top:24px">
      <a href="<?= htmlspecialchars($tracking_url) ?>" class="btn">View Full Tracking</a>
    </div>
    <p style="color:#888;font-size:13px;margin-top:28px">Tracking number: <strong><?= htmlspecialchars($tracking_number) ?></strong></p>
  </div>
  <div class="footer">
    &copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?> &nbsp;|&nbsp; Automated notification.
  </div>
</div>
</body>
</html>
