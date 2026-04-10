<?php
include('config.php');

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($db, $_POST['product_name']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $quant = mysqli_real_escape_string($db, $_POST['quantity']);
    mysqli_query($db, "UPDATE product SET product_name='$name', price='$price', quantity='$quant' WHERE product_id='$id'");
    header("Location: table.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];
    $result = mysqli_query($db, "SELECT * FROM product WHERE product_id=" . $_GET['id']);
    $row = mysqli_fetch_array($result);
    if ($row) {
        $id    = $row['product_id'];
        $name  = $row['product_name'];
        $price = $row['price'];
        $quant = $row['quantity'];
    } else {
        echo "No results!"; exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvenTrack — Edit Product</title>
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

        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
        input[type="text"] { width: 100%; padding: 13px 16px; border: 1.5px solid var(--border); background: var(--cream); border-radius: 8px; font-size: 15px; font-family: 'DM Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
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
    <a href="edit.php" class="nav-item active"><span class="nav-icon">✏️</span> Edit Product</a>
    <div class="sidebar-bottom">
        <a href="index.php">⏻ &nbsp;Logout</a>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>Edit Product</h1>
            <p>Update the product details below</p>
        </div>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-icon">✏️</div>
            <div class="card-title">Edit Product</div>
            <div class="card-sub">Modify the product information and save your changes.</div>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" value="<?= htmlspecialchars($name) ?>" required>
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="text" name="price" value="<?= htmlspecialchars($price) ?>" required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="text" name="quantity" value="<?= htmlspecialchars($quant) ?>" required>
                </div>

                <div class="btn-row">
                    <a href="table.php" class="btn btn-secondary">← Cancel</a>
                    <button type="submit" name="submit" class="btn btn-primary">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
