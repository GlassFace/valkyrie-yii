<?php
$equipList = '';
$count = 0;
foreach($model->items as $item)
{
    if(isset($item['entry']) && $item['can_displayed'])
    {
        if($count)
            $equipList .= ',';
    
        $equipList .= $item['slot'].','.$item['display_id'];
        $count++;
    }
} ?>
<object type="application/x-shockwave-flash" data="http://static.wowhead.com/modelviewer/ModelView.swf" width="100%" height="100%">
    <param name="wmode" value="transparent">
    <param name="quality" value="high">
    <param name="menu" value="true">
    <param name="flashvars" value="model=<?=$model::itemAlias('races', $model->race).$model::itemAlias('genders', $model->gender)?>&modelType=16&blur=1&equipList=<?=$equipList?>&contentPath=http://static.wowhead.com/modelviewer/">
</object>
