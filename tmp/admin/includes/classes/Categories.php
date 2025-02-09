<?php
class Categories {
    function getCategoriesTree(&$tree,&$numnodes,$parent_id,&$level,&$export_categories)
    {
        if (!is_array($tree))   {
            $tree=array(array(
                'id' => 0,
                'name'=>'Main category',
                'nodename' => "export_categories[0]",
                'parent' => -1,
                'status_pc' => 0,
                'level' => $level,
                'subfolders'=>0,
                'selected'=>(in_array($catid, $export_categories)),
                'object'=>new Category(array('id'=>0,'name'=>'Main category'))
            ));
            $level++;
            $this->getCategoriesTree($tree,$tree[sizeof($tree)-1]['subfolders'],0,$level,$export_categories);
            --$level;
            return;
        }
        $categories_query = tep_db_query("select t1.*, categories_name, parent_id from " . TABLE_CATEGORIES . " t1 left join " . TABLE_CATEGORIES_DESCRIPTION . " t2 on (t1.categories_id=t2.categories_id) where t1.parent_id=$parent_id and t1.categories_status=1 and t2.language_id=".$GLOBALS['languages_id']." order by sort_order, categories_name");
        while ($categories = tep_db_fetch_array($categories_query))  {
            $catid = $categories['categories_id'];
            $numnodes++;
            $thisnode = array(
                'id' => $catid,
                'name' => $categories['categories_name'],
                'nodename' => "export_categories[$catid]",
                'parent' => $categories['parent_id'],
                'status_pc' => $categories['categories_status_pc'],
                'level' => $level,
                'subfolders'=>0,
                'selected'=>(in_array($catid, $export_categories)),
                'object'=>new Category($categories)
            );
            array_push($tree, $thisnode);
            $level++;
            $this->getCategoriesTree($tree,$tree[sizeof($tree)-1]['subfolders'],$catid,$level,$export_categories);
            --$level;
        }
    }
}
?>