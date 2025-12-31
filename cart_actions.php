<?php
// Student Name: Mikey
// File: cart_actions.php
session_start();

// 初始化购物车
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- 功能 A: 移除商品 (Remove) ---
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id_to_remove = $_GET['id'];
    
    // 从 Session 数组中销毁该 ID
    if (isset($_SESSION['cart'][$id_to_remove])) {
        unset($_SESSION['cart'][$id_to_remove]);
    }
    
    // 完事后跳回购物车
    header("Location: cart.php");
    exit();
}

// --- 功能 B: 更新数量 (Update Quantities) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    // $_POST['qty'] 是一个数组，格式为: [商品ID => 数量]
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $product_id => $new_quantity) {
            $new_quantity = intval($new_quantity);
            
            // 数量必须大于0，否则视为删除
            if ($new_quantity > 0) {
                $_SESSION['cart'][$product_id] = $new_quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    
    // 更新完跳回购物车
    header("Location: cart.php?msg=updated");
    exit();
}

// 如果没有动作，直接回购物车
header("Location: cart.php");
exit();
?>