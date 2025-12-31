<?php
// Student Name: Mikey
session_start();

// 清除所有 session
session_unset();
session_destroy();

// 跳转回首页
header("Location: index.php");
exit;
?>