<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
assign_template();


$position = assign_ur_here(0, "");
$smarty->assign('page_title', $position['title']);   // 页面标题
$smarty->assign('ur_here',    $position['ur_here']); // 当前位置


$smarty->assign('helps', get_shop_help()); // 网店帮助
$smarty->display('read_policy.dwt');

?>