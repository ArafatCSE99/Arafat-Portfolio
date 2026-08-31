<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'delete' && $id) {
        db()->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
        flash_set('success', 'Message deleted.');
    } elseif ($action === 'mark_read' && $id) {
        db()->prepare('UPDATE messages SET is_read = 1 WHERE id = ?')->execute([$id]);
        flash_set('success', 'Marked as read.');
    }
    redirect($adminBase . '/messages.php');
}

$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();

$pageTitle = 'Messages';
$activeAdmin = 'messages';
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar"><h1>Contact Messages</h1></div>

<?php if (!$messages): ?>
  <div class="empty-state"><?= icon('mail') ?><p>No messages received yet.</p></div>
<?php else: ?>
<div style="display:flex;flex-direction:column;gap:16px;">
  <?php foreach ($messages as $m): ?>
    <div class="card" style="padding:22px;">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
        <div>
          <strong><?= e($m['name']) ?></strong>
          <span class="pill <?= $m['is_read'] ? 'pill-muted' : 'pill-success' ?>" style="margin-left:8px;"><?= $m['is_read'] ? 'Read' : 'New' ?></span>
          <div style="color:var(--text-muted);font-size:.85rem;margin-top:4px;">
            <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a> · <?= e(format_date($m['created_at'], 'M j, Y g:i A')) ?>
          </div>
        </div>
        <div class="row-actions">
          <?php if (!$m['is_read']): ?>
          <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="mark_read"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button type="submit" class="icon-btn" title="Mark read"><?= icon('check') ?></button></form>
          <?php endif; ?>
          <form method="post" onsubmit="return confirm('Delete this message?');"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button type="submit" class="icon-btn danger" title="Delete"><?= icon('trash') ?></button></form>
        </div>
      </div>
      <?php if ($m['subject']): ?><p style="font-weight:600;margin-bottom:6px;"><?= e($m['subject']) ?></p><?php endif; ?>
      <p style="color:var(--text-muted);white-space:pre-line;"><?= e($m['message']) ?></p>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require __DIR__ . '/layout-bottom.php'; ?>
