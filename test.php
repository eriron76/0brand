<?php

$node_id = 1;
$language = 'italian';
//$language = 'english';
$search_keyword = '';
$page_num = 0;
$page_size = 100;

?>
<html>
    <head></head>
    <body>
        <div>
            <form method="GET" action="api.php">
                
                <?php if ($node_id > 0) { ?>          
                Node ID: <input id="node_id" name="node_id" value="<?php echo $node_id; ?>" /><br><hr>
                <?php } ?>
                    
                <?php if ($language !== '') { ?>  
                    Language: <select id="language" name="language">
                        <option value="english" <?php if ("english" === $language) echo 'selected' ?> >english</option>
                        <option value="italian" <?php if ("italian" === $language) echo 'selected' ?> >italian</option>
                    </select><br><hr>
                <?php } ?>                
                
                <?php if ($search_keyword !== '') { ?>          
                    KW: <input id="search_keyword" name="search_keyword" value="<?php echo $search_keyword; ?>" /><br><hr>
                <?php } ?>                

                <?php if ($page_num > 0) { ?>          
                    Page Num: <input id="page_num" name="page_num" value="<?php echo $page_num; ?>" /><br><hr>
                <?php } ?>
                
                    <?php if ($page_size > 0) { ?>          
                    Page Size: <input id="page_size" name="page_size" value="<?php echo $page_size; ?>" /><br><hr>
                <?php } ?>
                
                    <input type="submit" value="invia" />
            </form>
        </div>
        
    </body>
</html>