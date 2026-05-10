<?php
session_start();
require_once __DIR__ . '/../includes/contact_db.php';
require_once __DIR__ . '/../includes/smtp_mailer.php';

/* ── CSRF token ── */
if (empty($_SESSION['admin_csrf'])) {
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
}

$notice = null;
$action = $_POST['action'] ?? '';
$msgId  = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

/* ── Handle POST actions ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['admin_csrf'], $_POST['csrf_token'] ?? '')) {
        $notice = ['type' => 'error', 'text' => 'Invalid CSRF token. Please try again.'];

    } elseif ($action === 'mark_read' && $msgId > 0) {
        $pdo->prepare("UPDATE contact_messages SET status='read' WHERE id=:id AND status='unread'")
            ->execute([':id' => $msgId]);
        header("Location: messages.php?id={$msgId}&notice=marked_read");
        exit;

    } elseif ($action === 'mark_replied' && $msgId > 0) {
        $pdo->prepare("UPDATE contact_messages SET status='replied' WHERE id=:id")
            ->execute([':id' => $msgId]);
        header("Location: messages.php?id={$msgId}&notice=marked_replied");
        exit;

    } elseif ($action === 'reply' && $msgId > 0) {
        $replyText = trim($_POST['reply_body'] ?? '');

        if ($replyText === '') {
            $notice = ['type' => 'error', 'text' => 'Reply cannot be empty.'];
        } else {
            $row = $pdo->prepare("SELECT * FROM contact_messages WHERE id=:id");
            $row->execute([':id' => $msgId]);
            $msg = $row->fetch(PDO::FETCH_ASSOC);

            if ($msg) {
                $date = date('D, d M Y \a\t H:i', strtotime($msg['created_at']));

                /* Build quoted body */
                $quoted = '';
                foreach (explode("\n", str_replace("\r\n", "\n", $msg['message'])) as $line) {
                    $quoted .= "> {$line}\n";
                }

                $body = "{$replyText}\n\n"
                      . "-- \n"
                      . "MensaHost Team | https://mensahost.tech\n\n"
                      . "-------- Original Message --------\n"
                      . "From:    {$msg['name']} <{$msg['email']}>\n"
                      . "Date:    {$date}\n"
                      . "Subject: {$msg['subject']}\n\n"
                      . $quoted;

                $mailer  = new SmtpMailer('festo@mensahost.tech', 'MensaHost');
                $subject = (str_starts_with($msg['subject'], 'Re: ')
                    ? $msg['subject']
                    : "Re: {$msg['subject']}");

                $sent = $mailer->send($msg['email'], $msg['name'], $subject, $body);

                if (!$sent) {
                    $notice = ['type' => 'error', 'text' => 'Mail error: ' . $mailer->getLastError()];
                } else {
                    $pdo->prepare("UPDATE contact_messages SET status='replied' WHERE id=:id")
                        ->execute([':id' => $msgId]);
                    $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
                    header("Location: messages.php?id={$msgId}&notice=reply_sent");
                    exit;
                }
            }
        }
    }
}

/* ── Fetch notice from redirect ── */
if ($notice === null && isset($_GET['notice'])) {
    $noticeMap = [
        'marked_read'    => ['type' => 'success', 'text' => 'Message marked as read.'],
        'marked_replied' => ['type' => 'success', 'text' => 'Message marked as replied.'],
        'reply_sent'     => ['type' => 'success', 'text' => 'Reply sent successfully.'],
    ];
    $notice = $noticeMap[$_GET['notice']] ?? null;
}

/* ── Load single message (detail view) ── */
$detail = null;
if ($msgId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id=:id");
    $stmt->execute([':id' => $msgId]);
    $detail = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Auto-mark as read when opened */
    if ($detail && $detail['status'] === 'unread') {
        $pdo->prepare("UPDATE contact_messages SET status='read' WHERE id=:id")
            ->execute([':id' => $msgId]);
        $detail['status'] = 'read';
    }
}

/* ── Load message list ── */
$messages = $pdo->query(
    "SELECT id, name, email, subject, status, created_at FROM contact_messages ORDER BY created_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$unreadCount = count(array_filter($messages, fn($m) => $m['status'] === 'unread'));

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function statusBadge(string $s): string {
    return match($s) {
        'unread'  => '<span class="badge bg-danger">Unread</span>',
        'read'    => '<span class="badge bg-secondary">Read</span>',
        'replied' => '<span class="badge bg-success">Replied</span>',
        default   => '<span class="badge bg-light text-dark">' . h($s) . '</span>',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — MensaHost Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:       #4F46E5;
            --primary-dark:  #3730a3;
            --secondary:     #10B981;
            --secondary-dark:#059669;
            --dark:          #0f172a;
            --text:          #1e293b;
            --muted:         #64748b;
            --light-bg:      #f8fafc;
            --card-bg:       #ffffff;
            --border:        #e2e8f0;
            --radius:        12px;
            --shadow:        0 4px 20px rgba(0,0,0,0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text);
            background: var(--light-bg);
        }

        /* ===== TOP BAR ===== */
        .topbar {
            background: var(--dark);
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .topbar-brand { font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
        .topbar-brand span { color: var(--secondary); }
        .topbar-label { font-size: 0.78rem; color: rgba(255,255,255,0.4); font-weight: 500; letter-spacing: 1px; text-transform: uppercase; }
        .back-link { color: rgba(255,255,255,0.55); text-decoration: none; font-size: 0.85rem; transition: color 0.2s; }
        .back-link:hover { color: #fff; }

        /* ===== LAYOUT ===== */
        .admin-wrap { padding: 32px 0; min-height: calc(100vh - 60px); }
        .sidebar { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
        .sidebar-head { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .sidebar-title { font-size: 0.88rem; font-weight: 700; color: var(--dark); }

        /* ===== MESSAGE LIST ===== */
        .msg-item {
            display: block;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
            transition: background 0.15s;
        }
        .msg-item:hover { background: var(--light-bg); }
        .msg-item.active { background: rgba(79,70,229,0.06); border-left: 3px solid var(--primary); }
        .msg-item.unread .msg-name { font-weight: 700; }
        .msg-name { font-size: 0.88rem; color: var(--dark); margin-bottom: 2px; }
        .msg-subject { font-size: 0.82rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .msg-meta { font-size: 0.75rem; color: var(--muted); margin-top: 4px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .empty-list { padding: 40px 20px; text-align: center; color: var(--muted); font-size: 0.9rem; }

        /* ===== DETAIL PANEL ===== */
        .detail-panel { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); }
        .detail-head { padding: 24px 28px; border-bottom: 1px solid var(--border); }
        .detail-subject { font-size: 1.2rem; font-weight: 700; color: var(--dark); margin-bottom: 10px; }
        .detail-meta { font-size: 0.85rem; color: var(--muted); display: flex; gap: 16px; flex-wrap: wrap; align-items: center; }
        .detail-meta strong { color: var(--text); }
        .detail-body { padding: 28px; border-bottom: 1px solid var(--border); }
        .detail-message {
            font-size: 0.92rem;
            line-height: 1.8;
            color: var(--text);
            white-space: pre-wrap;
            background: var(--light-bg);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 20px;
        }
        .detail-actions { padding: 20px 28px; border-bottom: 1px solid var(--border); display: flex; gap: 10px; flex-wrap: wrap; }
        .detail-reply { padding: 28px; }
        .reply-title { font-size: 0.95rem; font-weight: 700; color: var(--dark); margin-bottom: 16px; }

        /* ===== EMPTY STATE ===== */
        .no-selection {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 80px 40px;
            text-align: center;
            color: var(--muted);
        }
        .no-selection-icon { font-size: 3rem; margin-bottom: 16px; }
        .no-selection-text { font-size: 0.95rem; }

        /* ===== FORM CONTROLS ===== */
        .form-control {
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
            outline: none;
        }
        textarea.form-control { min-height: 120px; resize: vertical; }
        .form-label { font-size: 0.83rem; font-weight: 600; color: var(--text); margin-bottom: 6px; }

        /* ===== BUTTONS ===== */
        .btn-primary-sm {
            background: var(--primary); color: #fff; border: none;
            border-radius: 8px; padding: 9px 20px; font-size: 0.85rem;
            font-weight: 600; font-family: 'Poppins', sans-serif;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-primary-sm:hover { background: var(--primary-dark); }
        .btn-outline-sm {
            background: transparent; color: var(--text);
            border: 1px solid var(--border);
            border-radius: 8px; padding: 8px 18px; font-size: 0.85rem;
            font-weight: 500; font-family: 'Poppins', sans-serif;
            cursor: pointer; transition: border-color 0.2s, background 0.2s;
            text-decoration: none; display: inline-block;
        }
        .btn-outline-sm:hover { border-color: var(--primary); color: var(--primary); }
        .btn-success-sm {
            background: var(--secondary); color: #fff; border: none;
            border-radius: 8px; padding: 9px 20px; font-size: 0.85rem;
            font-weight: 600; font-family: 'Poppins', sans-serif;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-success-sm:hover { background: var(--secondary-dark); }

        /* ===== ALERT ===== */
        .notice-box {
            border-radius: 9px; padding: 12px 16px;
            font-size: 0.87rem; font-weight: 500; margin-bottom: 20px;
        }
        .notice-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #065f46; }
        .notice-error   { background: rgba(239,68,68,0.07); border: 1px solid rgba(239,68,68,0.25); color: #991b1b; }

        @media (max-width: 991px) {
            .admin-wrap { padding: 20px 0; }
        }
    </style>
</head>
<body>

<!-- ===== TOP BAR ===== -->
<div class="topbar">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="../index.php" class="topbar-brand">Mensa<span>Host</span></a>
            <span class="topbar-label">Admin</span>
        </div>
        <a href="../index.php" class="back-link">← Back to Site</a>
    </div>
</div>

<!-- ===== ADMIN LAYOUT ===== -->
<div class="admin-wrap">
    <div class="container">

        <!-- Header row -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 style="font-size:1.4rem;font-weight:700;color:var(--dark);margin-bottom:2px;">
                    📬 Contact Messages
                </h1>
                <p style="font-size:0.85rem;color:var(--muted);margin:0;">
                    <?= count($messages) ?> total &nbsp;·&nbsp;
                    <strong style="color:<?= $unreadCount > 0 ? '#dc3545' : 'var(--muted)' ?>;"><?= $unreadCount ?> unread</strong>
                </p>
            </div>
        </div>

        <?php if ($notice): ?>
            <div class="notice-box notice-<?= h($notice['type']) ?>">
                <?= $notice['type'] === 'success' ? '✅' : '⚠️' ?> <?= h($notice['text']) ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- ===== SIDEBAR: message list ===== -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="sidebar-head">
                        <span class="sidebar-title">All Messages</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge bg-danger"><?= $unreadCount ?> new</span>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($messages)): ?>
                        <div class="empty-list">No messages yet.</div>
                    <?php else: ?>
                        <?php foreach ($messages as $m): ?>
                            <?php
                                $isActive = ($detail && $detail['id'] == $m['id']);
                                $cls      = ($m['status'] === 'unread') ? 'msg-item unread' : 'msg-item';
                                if ($isActive) $cls .= ' active';
                                $ts = date('M j, g:ia', strtotime($m['created_at']));
                            ?>
                            <a href="messages.php?id=<?= (int)$m['id'] ?>" class="<?= $cls ?>">
                                <div class="msg-name"><?= h($m['name']) ?></div>
                                <div class="msg-subject"><?= h($m['subject']) ?></div>
                                <div class="msg-meta">
                                    <?= statusBadge($m['status']) ?>
                                    <span><?= h($ts) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== DETAIL PANEL ===== -->
            <div class="col-lg-8">
                <?php if (!$detail): ?>
                    <div class="no-selection">
                        <div class="no-selection-icon">✉️</div>
                        <div class="no-selection-text">Select a message from the list to read it.</div>
                    </div>

                <?php else: ?>
                    <div class="detail-panel">

                        <!-- Header -->
                        <div class="detail-head">
                            <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                                <div class="detail-subject"><?= h($detail['subject']) ?></div>
                                <?= statusBadge($detail['status']) ?>
                            </div>
                            <div class="detail-meta">
                                <div><strong>From:</strong> <?= h($detail['name']) ?> &lt;<?= h($detail['email']) ?>&gt;</div>
                                <div><strong>Received:</strong> <?= h(date('D, d M Y \a\t H:i', strtotime($detail['created_at']))) ?></div>
                            </div>
                        </div>

                        <!-- Message body -->
                        <div class="detail-body">
                            <div class="detail-message"><?= h($detail['message']) ?></div>
                        </div>

                        <!-- Action buttons -->
                        <div class="detail-actions">
                            <?php if ($detail['status'] === 'unread'): ?>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= h($_SESSION['admin_csrf']) ?>">
                                    <input type="hidden" name="action" value="mark_read">
                                    <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
                                    <button type="submit" class="btn-outline-sm">✓ Mark as Read</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($detail['status'] !== 'replied'): ?>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= h($_SESSION['admin_csrf']) ?>">
                                    <input type="hidden" name="action" value="mark_replied">
                                    <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
                                    <button type="submit" class="btn-outline-sm">✓ Mark as Replied</button>
                                </form>
                            <?php endif; ?>

                            <a href="messages.php" class="btn-outline-sm">← Back to List</a>
                        </div>

                        <!-- Reply form -->
                        <div class="detail-reply">
                            <div class="reply-title">↩ Reply to <?= h($detail['name']) ?></div>

                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?= h($_SESSION['admin_csrf']) ?>">
                                <input type="hidden" name="action" value="reply">
                                <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">To</label>
                                    <input type="text" class="form-control" value="<?= h($detail['name']) ?> &lt;<?= h($detail['email']) ?>&gt;" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" value="Re: <?= h($detail['subject']) ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="reply_body">Your Reply</label>
                                    <textarea class="form-control" id="reply_body" name="reply_body"
                                              placeholder="Write your reply here…" required></textarea>
                                    <div style="margin-top:8px;padding:12px;background:var(--light-bg);border:1px solid var(--border);border-radius:8px;font-size:0.78rem;color:var(--muted);">
                                        The original message will be quoted below your reply automatically.
                                    </div>
                                </div>
                                <button type="submit" class="btn-primary-sm">Send Reply</button>
                            </form>
                        </div>

                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
