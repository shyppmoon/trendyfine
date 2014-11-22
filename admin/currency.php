<?php

/**
 * ECSHOP 多国货币管理程序
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: currency.php 17063 2010-03-25 06:35:46Z liuhui $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$exc = new exchange($ecs->table("currency"), $db, 'currency_id', 'currency_to');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

/* act操作项的初始化 */
$_REQUEST['act'] = trim($_REQUEST['act']);
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}

/*  */
function get_currency_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'currency_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        /* 获得总记录数据 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('currency');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取数据 */
        $sql  = 'SELECT * '.
               ' FROM ' .$GLOBALS['ecs']->table('currency').
                " ORDER by $filter[sort_by] $filter[sort_order]";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
	
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $list = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
         $rows['currency_icon'] = empty($rows['currency_icon']) ? '' :
            '<img src="../data/currency_icon/'.$rows['currency_icon'].'" border="0" />';
		$list[] = $rows;
    }
	
    return array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}


/*------------------------------------------------------ */
//-- 
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    
	admin_priv('currency_manage');
	
	$currency_list = get_currency_list();
   
    $smarty->assign('ur_here',     $_LANG['022_currency_list']);
    $smarty->assign('action_link', array('text' => $_LANG['023_currency_add'], 'href' => 'currency.php?act=add'));
	
	$smarty->assign('currency_list',   $currency_list['list']);
    $smarty->assign('filter',          $currency_list['filter']);
    $smarty->assign('record_count',    $currency_list['record_count']);
    $smarty->assign('page_count',      $currency_list['page_count']);

    $smarty->assign('full_page',   1);

    assign_query_info();
    $smarty->display('currency_list.htm');
}

elseif($_REQUEST['act'] == 'generate')
{
	
	admin_priv('currency_manage');
	
	clear_cache_files();

	$sql  = 'SELECT * '.
               ' FROM ' .$GLOBALS['ecs']->table('currency').
                " where is_show=1 order by sort_order asc";
	$directory_currency = $db->getAll($sql);
	
	$max_key = count($directory_currency);
	$directory_currency[$max_key]['currency_name'] = $_CFG['currency_name'];
	$directory_currency[$max_key]['currency_icon'] = $_CFG['currency_to'].'.jpg';
	$directory_currency[$max_key]['currency_to'] = $_CFG['currency_to'];
	$directory_currency[$max_key]['currency_format'] = $_CFG['currency_format'];
	$directory_currency[$max_key]['rate'] = 1.00;
	
	$content = "<?php\r\n";
	$content .= "\$currency_list= " . var_export($directory_currency, true) . ";\r\n";
	$content .= "?>";
	file_put_contents(ROOT_PATH.'data/currency_list.php', $content, LOCK_EX);
	$link[0]['text'] = $_LANG['back_list'];
    $link[0]['href'] = 'currency.php?act=list';
    sys_msg($_LANG['currency_generate_succed'],0, $link);
	
	
}
/*------------------------------------------------------ */
//-- 查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    admin_priv('currency_manage');
	
	$currency_list = get_currency_list();

    $smarty->assign('currency_list',   $currency_list['list']);

    make_json_result($smarty->fetch('currency_list.htm'));
}

/*------------------------------------------------------ */
//-- 
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('currency_manage');

    $smarty->assign('ur_here',     $_LANG['023_currency_add']);
    $smarty->assign('action_link', array('text' => $_LANG['022_currency_list'], 'href' => '?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('currency_info.htm');
}
elseif ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('currency_manage');

    /*检查是否重复*/
    $is_only = $exc->is_only('currency_to', $_POST['currency_to']);

    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['currency_to_exist'], stripslashes($_POST['currency_to'])), 1);
    }
    
	$currency_icon = basename($image->upload_image($_FILES['currency_icon'],'currency_icon'));

    $sql = "INSERT INTO ".$ecs->table('currency')."(currency_to,currency_name,currency_format,rate,sort_order,is_show,currency_icon )
           VALUES ('$_POST[currency_to]','$_POST[currency_name]','$_POST[currency_format]',  '$_POST[rate]','$_POST[sort_order]', '$_POST[is_show]', '$currency_icon')";
    $db->query($sql);

    admin_log($_POST['currency_to'],'add','currency');

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'currency.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'currency.php?act=list';
    clear_cache_files();
    sys_msg($_POST['currency_to'].$_LANG['currency_succed'],0, $link);
}

/*------------------------------------------------------ */
//-- 
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('currency_manage');

    $sql = "SELECT * FROM ".
           $ecs->table('currency'). " WHERE currency_id='$_REQUEST[id]'";
    $currency = $db->GetRow($sql);

    
    $smarty->assign('currency',         $currency);
    $smarty->assign('ur_here',     $_LANG['023_currency_add']);
    $smarty->assign('action_link', array('text' => $_LANG['022_currency_list'], 'href' => 'currency.php?act=list'));
    $smarty->assign('form_action', 'update');

    assign_query_info();
    $smarty->display('currency_info.htm');
}
elseif ($_REQUEST['act'] == 'update')
{
    /* 权限判断 */
    admin_priv('currency_manage');
	

    /*检查重名*/
    if ($_POST['currency_to'] != $_POST['old_currency_to'])
    {
        $is_only = $exc->is_only('currency_to', $_POST['currency_to'], $_POST['id']);

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['currency_to_exist'], stripslashes($_POST['currency_to'])), 1);
        }
    }

    $currency_icon = basename($image->upload_image($_FILES['currency_icon'],'currency_icon'));
	
    if ($exc->edit("currency_to = '$_POST[currency_to]',currency_name = '$_POST[currency_name]', currency_format ='$_POST[currency_format]', rate='$_POST[rate]',sort_order = '$_POST[sort_order]',is_show='$_POST[is_show]'",$_POST['id']))
    {
        
		
		if(!empty($currency_icon))
		{
			$exc->edit("currency_icon = '$currency_icon'",$_POST['id']);
		}
		
		
		$link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'currency.php?act=list';
        $note = sprintf($_LANG['currencyedit_succed'], $_POST['currency_to']);
        
		admin_log($_POST['currency_to'], 'edit', 'currency');
        clear_cache_files();
        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}



/*------------------------------------------------------ */
//-- 
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_sort_order')
{
    
	check_authz_json('currency_manage');
	
    $id    = intval($_POST['id']);
    $order = json_str_iconv(trim($_POST['val']));

    /* 检查输入的值是否合法 */
    if (!preg_match("/^[0-9]+$/", $order))
    {
        make_json_error(sprintf($_LANG['enter_int'], $order));
    }
    else
    {
        if ($exc->edit("sort_order = '$order'", $id))
        {
            clear_cache_files();
            make_json_result(stripslashes($order));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'toggle_is_show')
{
    check_authz_json('currency_manage');

    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

    if ($exc->edit("is_show = '$val'", $id))
    {
        clear_cache_files();
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}



/*------------------------------------------------------ */
//-- 
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
	check_authz_json('currency_manage');
    $id = intval($_GET['id']);
    $exc->drop($id);
    clear_cache_files();
    admin_log('', 'remove', 'currency');
    $url = 'currency.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");
    exit;
}

?>
