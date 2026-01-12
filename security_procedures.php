<?php
/**
 * Student Name: Mikey
 * Security Procedures Implementation
 */

// 1. Session Security: 防止会话劫持
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // 重新生成 Session ID 以防止固定攻击
    session_regenerate_id(true); 
}

// 2. SQL Injection Prevention: 使用预处理语句
function safe_login($conn, $email, $phone) {
    // 永远不要直接在 SQL 中拼接变量
    $stmt = $conn->prepare("SELECT * FROM wpid_fc_subscribers WHERE email = ? AND phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    return $stmt->get_result();
}

// 3. XSS Prevention: 输出转义
function clean_output($data) {
    // 防止恶意脚本在浏览器执行
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// 4. Database Credentials Protection: 变量解耦
// 数据库敏感信息定义在受保护的配置文件中，不随 HTML 输出
?>