<?php if (!defined ('SYSTEM_ROOT')) exit (); ?>
<?php
	function cron_add ($url) // 添加一个计划任务
    {
        $data = array (
        	'url' => $url,
        	'lasttime' => 0,
        	'protect' => 0
        );
        $ret = $GLOBALS['db']->insert ('cron', $data);
        
        return $ret;
    }
    
    function cron_delete ($cid) // 删除一个计划任务
    {
        $where = array (
			'cid' => $cid
		);
		$GLOBALS['db']->delete ('cron', $where);
    }

    function cron_getinfo ($cid) // 获取某任务信息
    {
        // 初始化变量
        $cid = $cid == 0 ? '%' : $cid;
        
        // 查询
        $where = array (
        	'cid[~]' => $cid
        );
		$ret = $GLOBALS['db']->select ('cron', '*', $where);

        return (count ($ret) == 0 ? '' : $ret);
    }

    function cron_run ($cid) // 执行某任务
    {
    	// 初始化变量
    	$croninfo = cron_getinfo ($cid);

    	// 执行
    	if (is_array ($croninfo)) {
    		if (is_file (SYSTEM_ROOT . $croninfo[0]['url']))
    		{
    			require SYSTEM_ROOT . '/' . $croninfo[0]['url'];
    		}
    	}

    	// 更新执行时间
    	$data = array (
        	'lasttime' => time ()
        );
        $where = array (
        	'cid' => $cid
        );
        $GLOBALS['db']->update ('cron', $data, $where);
    }
?>