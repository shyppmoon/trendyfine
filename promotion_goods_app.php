<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
assign_template();

//设定产品列表的ids
$goods_id_1 = "(2727,2653,2673,2681,2680,2763,2649,2430,2421,2809,2647)";
$goods_id_2 = "(2505,2476,2556,2809,2647,2770,1511,1785,2082,978,2055)";
$goods_id_3 = "(2595,2721,2638,2585,1274)";

$sql = "select * from " .$ecs->table('goods'). " where goods_id IN " . $goods_id_1;
$goods_list = $db->getAll($sql);
foreach($goods_list as $key=>$val)
{
	$goods_list[$key]['market_price']     = price_format($val['market_price']);
	$goods_list[$key]['shop_price']       = price_format($val['shop_price']);
	$goods_list[$key]['goods_thumb']      = get_image_path($val['goods_id'], $val['goods_thumb'], true);
	$goods_list[$key]['url']              = build_uri('goods', array('gid'=>$val['goods_id']), $val['goods_name']);
}
$goods_list_1 = $goods_list;

$sql = "select * from " .$ecs->table('goods'). " where goods_id IN " . $goods_id_2;
$goods_list = $db->getAll($sql);
foreach($goods_list as $key=>$val)
{
	$goods_list[$key]['market_price']     = price_format($val['market_price']);
	$goods_list[$key]['shop_price']       = price_format($val['shop_price']);
	$goods_list[$key]['goods_thumb']      = get_image_path($val['goods_id'], $val['goods_thumb'], true);
	$goods_list[$key]['url']              = build_uri('goods', array('gid'=>$val['goods_id']), $val['goods_name']);
}
$goods_list_2 = $goods_list;

$sql = "select * from " .$ecs->table('goods'). " where goods_id IN " . $goods_id_3;
$goods_list = $db->getAll($sql);
foreach($goods_list as $key=>$val)
{
	$goods_list[$key]['market_price']     = price_format($val['market_price']);
	$goods_list[$key]['shop_price']       = price_format($val['shop_price']);
	$goods_list[$key]['goods_thumb']      = get_image_path($val['goods_id'], $val['goods_thumb'], true);
	$goods_list[$key]['url']              = build_uri('goods', array('gid'=>$val['goods_id']), $val['goods_name']);
}
$goods_list_3 = $goods_list;




$smarty->assign('goods_list_1', $goods_list_1); // 产品1
$smarty->assign('goods_list_2', $goods_list_2); // 产品2
$smarty->assign('goods_list_3', $goods_list_3); // 产品3


$smarty->assign('categories', get_categories_tree()); // 分类树
$smarty->assign('top_goods',  get_top10());           // 销售排行

$position = assign_ur_here(0, "");
$smarty->assign('page_title', $position['title']);   // 页面标题
$smarty->assign('ur_here',    $position['ur_here']); // 当前位置


$smarty->assign('helps', get_shop_help()); // 网店帮助
$smarty->assign('shop_url',    $ecs->url());

$smarty->display('promotion_goods_app.dwt');

?>