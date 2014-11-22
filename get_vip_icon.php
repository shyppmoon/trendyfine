<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
assign_template();


$smarty->assign('categories', get_categories_tree()); // 分类树
$smarty->assign('top_goods',  get_top10());           // 销售排行

$position = assign_ur_here(0, "");
$smarty->assign('page_title', $position['title']);   // 页面标题
$smarty->assign('ur_here',    $position['ur_here']); // 当前位置

$smarty->assign('shop_url',    $ecs->url());

$smarty->assign('helps', get_shop_help()); // 网店帮助
$smarty->display('get_vip_icon.dwt');

?>