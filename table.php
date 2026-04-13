<?php
session_start();
if (!isset($_SESSION['inventory_logged_in']) || !$_SESSION['inventory_logged_in']) {
    header("Location: index.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>InvenTrack — Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink: #0d0d0d;
            --paper: #f5f2eb;
            --cream: #ede9df;
            --accent: #d4601a;
            --accent-soft: #fff0e8;
            --muted: #7a7265;
            --white: #ffffff;
            --border: #ddd8cf;
            --sidebar-w: 280px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 32px 24px;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 22px;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 40px;
        }
        .logo-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; }

        .nav-label {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #4a4540;
            margin-bottom: 10px;
            margin-top: 24px;
            padding-left: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #8a8076;
            font-size: 14px;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: #1e1e1c; color: var(--white); }
        .nav-item.active { background: var(--accent); color: var(--white); }
        .nav-icon { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid #1e1e1c;
        }
        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4a4540;
            text-decoration: none;
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: color 0.15s, background 0.15s;
        }
        .sidebar-bottom a:hover { color: #e05555; background: #1e1e1c; }

        /* MAIN */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 36px;
            background: var(--paper);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 700;
        }
        .topbar-left p { font-size: 13px; color: var(--muted); margin-top: 2px; }

        /* CONTENT */
        .content { padding: 32px 36px; flex: 1; }

        /* STATS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 14px;
            padding: 24px;
            border: 1px solid var(--border);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.07); }

        .stat-icon { font-size: 22px; margin-bottom: 12px; }
        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-label { font-size: 13px; color: var(--muted); }

        /* ADD ITEM FORM */
        .add-section {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 28px;
            margin-bottom: 28px;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-row {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .form-group { flex: 1; min-width: 160px; }

        label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            background: var(--cream);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212,96,26,0.1);
        }

        .btn {
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            transition: all 0.15s;
        }
        .btn-primary { background: var(--ink); color: var(--white); }
        .btn-primary:hover { background: #222; }
        .btn-danger { background: #fce8e6; color: #c62828; }
        .btn-danger:hover { background: #f4d0cd; }
        .btn-warning { background: #fff3e0; color: #c26a00; }
        .btn-warning:hover { background: #ffe4b5; }

        /* TABLE */
        .table-section {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
        }

        .table-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
        }
        .table-count {
            font-size: 12px;
            color: var(--muted);
            background: var(--cream);
            padding: 3px 10px;
            border-radius: 20px;
            margin-left: 10px;
        }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            text-align: left;
            background: var(--paper);
            border-bottom: 1px solid var(--border);
        }
        tbody tr { border-bottom: 1px solid #f0ece4; transition: background 0.1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #faf8f4; }

        td {
            padding: 14px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        .product-name { font-weight: 500; }
        .price-val { font-family: 'Syne', sans-serif; font-weight: 600; }

        .qty-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .qty-low { background: #fce8e6; color: #c62828; }
        .qty-mid { background: #fff3e0; color: #c26a00; }
        .qty-ok  { background: #e6f4ea; color: #2d7a3a; }

        .actions { display: flex; gap: 6px; }

        .row-num {
            font-family: 'Syne', sans-serif;
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 64px 32px;
            color: var(--muted);
        }
        .empty-state .empty-icon { font-size: 48px; margin-bottom: 16px; }
        .empty-state h3 { font-family: 'Syne', sans-serif; font-size: 18px; color: var(--ink); margin-bottom: 8px; }

        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .content { padding: 20px 16px; }
            .topbar { padding: 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .form-row { flex-direction: column; }
        }
    </style>
</head>
<body>

<?php
$conn = new mysqli("localhost", "root", "", "inventorymanagement");
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$total_items = count($products);
$total_qty = array_sum(array_column($products, 'quantity'));
$total_value = array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $products));
?>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <span class="logo-dot"></span> InvenTrack
    </div>

    <span class="nav-label">Main Menu</span>
    <a href="table.php" class="nav-item active">
        <span class="nav-icon">📦</span> Dashboard
    </a>

    <div class="sidebar-bottom">
        <a href="index.php">⏻ &nbsp;Logout</a>
    </div>
</aside>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <h1>Inventory Dashboard</h1>
            <p><?= date('l, F j, Y') ?></p>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-num"><?= $total_items ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔢</div>
                <div class="stat-num"><?= $total_qty ?></div>
                <div class="stat-label">Total Stock Qty</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-num">₱<?= number_format($total_value, 2) ?></div>
                <div class="stat-label">Total Inventory Value</div>
            </div>
        </div>

        <!-- ADD ITEM FORM -->
        <div class="add-section">
            <div class="section-title">➕ Add New Product</div>
            <form method="POST" action="additem.php">
                <div class="form-row">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="product_name" placeholder="e.g., Laptop" required>
                    </div>
                    <div class="form-group">
                        <label>Price (₱)</label>
                        <input type="text" name="price" placeholder="e.g., 1500.00" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quant" id="quant" min="1" placeholder="e.g., 10" required>
                    </div>
                    <div>
                        <button type="submit" name="add" class="btn btn-primary">+ Add Item</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="table-section">
            <div class="table-header">
                <div style="display:flex;align-items:center;">
                    <span class="table-title">Product Inventory</span>
                    <span class="table-count"><?= $total_items ?> items</span>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>No products yet</h3>
                    <p>Add your first product using the form above.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 0; foreach ($products as $row): $count++; ?>
                        <tr>
                            <td><span class="row-num"><?= $count ?></span></td>
                            <td><span class="product-name"><?= htmlspecialchars($row['product_name']) ?></span></td>
                            <td><span class="price-val">₱<?= number_format($row['price'], 2) ?></span></td>
                            <td>
                                <?php
                                $qty = (int)$row['quantity'];
                                $cls = $qty <= 5 ? 'qty-low' : ($qty <= 20 ? 'qty-mid' : 'qty-ok');
                                ?>
                                <span class="qty-badge <?= $cls ?>"><?= $qty ?></span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?= $row['product_id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $row['product_id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this product?')">🗑 Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div><!-- /content -->
</div><!-- /main -->

</body>
</html>
