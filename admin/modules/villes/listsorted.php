<?php
if(isset($_POST['save']) && stripslashes($_POST['save'])==getLang('SAVE_LIST_ORDER')){
	$sortedlist=explode(',',$_POST['sortedlist']);
	sortdata("$module","id",$sortedlist);
	$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('REORDER_SUCCESSFUL').'</p></div>';
	header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
}
else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
	header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
}
$connector = new DbConnector();
$result = $connector->query("SELECT * FROM $module ORDER BY order_position");
?>

<div class="box">
	<div class="header">
    	<h3><?php showLang('SORT_CITIES') ?></h3>
    </div>
    <div class="container">
    	<form action="moduleinterface.php" method="post" onsubmit="return setSortedList2()">	
    		<div class="hidden">
                <input type="hidden" name="module" value="<?php echo $module; ?>" />
                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                <input type="hidden" id="sortedlist" name="sortedlist" value="" />
            </div>
            <div class="buttons">
                <p>
                  <input name="save" type="submit" value="<?php showLang('SAVE_LIST_ORDER') ?>" class="button">
                  <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                </p>
            </div>
            <ul id="list2"><?php
				while ($row = $connector->fetchArray($result)){ ?>
					 <li id="<?php echo $row['id']; ?>"><p><?php echo osql($row['name']); ?></p></li><?php
				 } ?>
			</ul>
            <div class="buttons">
                <p>
                  <input name="save" type="submit" value="<?php showLang('SAVE_LIST_ORDER') ?>" class="button">
                  <input name="cancel" type="submit" value="<?php showLang('CANCEL') ?>" class="button">
                </p>
            </div>
            <div>&nbsp;</div>
        </form>
    </div>
</div>