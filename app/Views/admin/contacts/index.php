<div class="adm-page-header">
  <div><h1 class="adm-page-title"><i class="fas fa-envelope me-2 text-warning"></i>Contact Messages</h1></div>
</div>
<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Name</th><th>Email</th><th class="hide-mobile">Subject</th><th>Status</th><th class="hide-mobile">Date</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($msgs as $m): ?>
      <tr style="<?= !$m['is_read']?'font-weight:700':'' ?>">
        <td><?= Security::e($m['name']) ?></td>
        <td><?= Security::e($m['email']) ?></td>
        <td><?= Security::e(substr($m['subject']??'(no subject)',0,60)) ?></td>
        <td>
          <?php if ($m['replied_at']): ?>
          <span class="adm-badge adm-badge-success">Replied</span>
          <?php elseif ($m['is_read']): ?>
          <span class="adm-badge adm-badge-secondary">Read</span>
          <?php else: ?>
          <span class="adm-badge adm-badge-warning">New</span>
          <?php endif; ?>
        </td>
        <td style="font-size:.78rem;color:#888"><?= date('M d, Y', strtotime($m['created_at'])) ?></td>
        <td><a href="<?= BASE_URL ?>/admin/contacts/<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="fas fa-eye"></i></a></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
