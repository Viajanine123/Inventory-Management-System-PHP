<?php
$item_name = "";
$item_price = "";

$db = mysqli_connect('localhost', 'root', '', 'inventorymanagement');
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

$success = false;
$error = false;

if (isset($_POST['add'])) {
    $item_name  = mysqli_real_escape_string($db, $_POST['product_name']);
    $item_price = mysqli_real_escape_string($db, $_POST['price']);
    $quant      = mysqli_real_escape_string($db, $_POST['quant']);

    $query = "INSERT INTO product (product_name, price, quantity) VALUES('$item_name', '$item_price', '$quant')";
    if (mysqli_query($db, $query)) {
        header("Location: table.php");
        exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvenTrack — Add Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0d0d0d; --paper: #f5f2eb; --cream: #ede9df;
            --accent: #d4601a; --muted: #7a7265; --white: #ffffff;
            --border: #ddd8cf; --sidebar-w: 280px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--paper); color: var(--ink); min-height: 100vh; display: flex; }

        .sidebar { width: var(--sidebar-w); background: var(--ink); min-height: 100vh; display: flex; flex-direction: column; padding: 32px 24px; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 22px; color: var(--white); display: flex; align-items: center; gap: 8px; margin-bottom: 40px; }
        .logo-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; }
        .nav-label { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #4a4540; margin-bottom: 10px; margin-top: 24px; padding-left: 4px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #8a8076; font-size: 14px; text-decoration: none; transition: background 0.15s, color 0.15s; margin-bottom: 2px; }
        .nav-item:hover { background: #1e1e1c; color: var(--white); }
        .nav-item.active { background: var(--accent); color: var(--white); }
        .nav-icon { font-size: 16px; width: 20px; text-align: center; }
        .sidebar-bottom { margin-top: auto; padding-top: 24px; border-top: 1px solid #1e1e1c; }
        .sidebar-bottom a { display: flex; align-items: center; gap: 8px; color: #4a4540; text-decoration: none; font-size: 13px; padding: 8px 12px; border-radius: 8px; transition: color 0.15s, background 0.15s; }
        .sidebar-bottom a:hover { color: #e05555; background: #1e1e1c; }

        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { display: flex; align-items: center; justify-content: space-between; padding: 24px 36px; background: var(--paper); border-bottom: 1px solid var(--border); }
        .topbar-left h1 { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 700; }
        .topbar-left p { font-size: 13px; color: var(--muted); margin-top: 2px; }

        .content { padding: 40px 36px; flex: 1; display: flex; justify-content: center; align-items: flex-start; }

        .card { background: var(--white); border-radius: 20px; border: 1px solid var(--border); padding: 40px; width: 100%; max-width: 520px; box-shadow: 0 8px 32px rgba(0,0,0,0.06); }
        .card-icon { font-size: 32px; margin-bottom: 12px; }
        .card-title { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 700; margin-bottom: 6px; }
        .card-sub { font-size: 13px; color: var(--muted); margin-bottom: 32px; }

        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 24px; }
        .alert-error { background: #fce8e6; color: #c62828; border: 1px solid #f4c0bc; }

        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 13px 16px; border: 1.5px solid var(--border); background: var(--cream); border-radius: 8px; font-size: 15px; font-family: 'DM Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(212,96,26,0.1); }

        .btn-row { display: flex; gap: 12px; margin-top: 8px; }
        .btn { padding: 13px 24px; border-radius: 8px; font-size: 14px; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: none; transition: all 0.15s; }
        .btn-primary { background: var(--ink); color: var(--white); flex: 1; justify-content: center; }
        .btn-primary:hover { background: #222; }
        .btn-secondary { background: var(--cream); color: var(--ink); border: 1.5px solid var(--border); }
        .btn-secondary:hover { background: var(--border); }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .content { padding: 20px 16px; }
            .topbar { padding: 16px; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo"><span class="logo-dot"></span> InvenTrack</div>
    <span class="nav-label">Main Menu</span>
    <a href="table.php" class="nav-item"><span class="nav-icon">📦</span> Dashboard</a>
    <a href="additem.php" class="nav-item active"><span class="nav-icon">➕</span> Add Product</a>
    <div class="sidebar-bottom">
        <a href="index.php">⏻ &nbsp;Logout</a>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>Add Product</h1>
            <p>Fill in the details to add a new item to inventory</p>
        </div>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-icon">➕</div>
            <div class="card-title">New Product</div>
            <div class="card-sub">Enter the product details below to add it to your inventory.</div>

            <?php if ($error): ?>
                <div class="alert alert-error">⚠ Something went wrong. Please try again.</div>
            <?php endif; ?>

            <form method="POST" action="additem.php">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" placeholder="e.g., Laptop" value="<?= htmlspecialchars($item_name) ?>" required>
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="text" name="price" placeholder="e.g., 1500.00" value="<?= htmlspecialchars($item_price) ?>" required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quant" placeholder="e.g., 10" min="1" required>
                </div>

                <div class="btn-row">
                    <a href="table.php" class="btn btn-secondary">← Cancel</a>
                    <button type="submit" name="add" class="btn btn-primary">➕ Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
