<? $baseUrl = Yii::app()->request->baseUrl; ?>
<style type="text/css">
#talentcalc-1 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/1-greyscale.jpg); }
#talentcalc-2 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/2-greyscale.jpg); }
#talentcalc-3 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/3-greyscale.jpg); }
#talentcalc-4 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/4-greyscale.jpg); }
#talentcalc-5 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/5-greyscale.jpg); }
#talentcalc-6 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/6-greyscale.jpg); }
#talentcalc-7 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/7-greyscale.jpg); }
#talentcalc-8 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/8-greyscale.jpg); }
#talentcalc-9 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/9-greyscale.jpg); }
#talentcalc-11 .talentcalc-cell .icon .texture { background-image: url(<?= $baseUrl; ?>/images/wow/talents/icons/11-greyscale.jpg); }
</style>
<?php
$this->breadcrumbs = array(
    'Talent Calculator' => array('/tool/talentCalculator'),
); ?>
<div class="profile-sidebar-anchor">
    <div class="profile-sidebar-outer">
        <div class="profile-sidebar-inner">
            <div class="profile-sidebar-contents">
<?php
$classes = Yii::app()->db->createCommand("
        SELECT id, name_ru AS name FROM wow_classes WHERE id <> 6")
    ->queryAll();
$links = array();
foreach($classes as $class)
{
    $links[] = array(
        'label'=> $class['name'],
        'url' => array('/tool/talentCalculator', 'class'=>$class['id'])
    );
}

$this->widget('WProfileSidebarMenu', array('items' => $links));?>
            </div>
        </div>
    </div>
</div>
<div class="profile-contents">
    <div class="profile-section-header">
        <h3 class="category ">Таланты</h3>
    </div>

        <div class="profile-section">

            <div class="character-talents-wrapper">

    <div id="talentcalc-character" class="talentcalc talentcalc-locked">
<?php for($i = 0; $i < 3; $i++) { ?>
            <div class="talentcalc-tree-wrapper<?=($i==2)?" tree-last":""?>">

    <div class="talentcalc-tree-header" style="visibility: visible; ">
        <span class="icon">
        <span class="icon-frame-treeheader ">
            <img src="http://eu.media.blizzard.com/wow/icons/36/<?=$data[$i]['icon']?>.jpg" alt="" width="36" height="36" />
            <span class="frame"></span>
        </span>
        </span>
        <span class="points">
            <span class="value">0</span>
        </span>
        <span class="name"><?=$data[$i]['name']?></span>
        <span class="clear"><!-- --></span>
    </div>

    <div class="talentcalc-tree" style="width: 228px; height: 387px; background-image: url(/images/wow/talents/backgrounds/<?=$classId?>.jpg); background-position: -<?=(228 * $i)?>px 0">
        <div id="talentcalc-<?=$classId?>" class="talentcalc-cells-wrapper">

<?php
$j = 0;
foreach($data[$i]['talents'] as $tal):
    $class = '';
    if(isset($tal['req']))
        $class .= ' talent-arrow';
?>
    <div class="talentcalc-cell <?=$class?>" style="left: <?=($tal['x'] * 53)?>px; top: <?=($tal['y'] * 53)?>px;" data-id="<?=$tal['id']?>">
        <span class="icon">
            <span class="texture" style="background-position: -<?=($j * 36)?>px -<?=($i * 36)?>px;"></span>
<?php if($tal['keyAbility']): ?>
            <span class="ability"></span>
<?php endif; ?>
            <span class="frame"></span>
        </span>
        <a href="javascript:;" class="interact"><span class="hover"></span></a>
        <span class="points"><span class="frame"></span><span class="value">0</span></span>
<?php if(isset($tal['req'])):

    foreach($data[$i]['talents'] as $prev):
        if($prev['id'] == $tal['req'])
            break;
    endforeach;

    if($tal['x'] == $prev['x'])
    {
        $type = 'down';
        $w = 40;
        $l = 7;
        $h = 14 + ($tal['y'] - $prev['y'] - 1) * 53;
        $t = -6 - ($tal['y'] - $prev['y'] - 1) * 53;
    }
    elseif($tal['x'] > $prev['x'] && $tal['y'] == $prev['y'])
    {
        $type = 'right';
        $h = 40;
        $t = 7;
        $l = -6;
        $w = 13;
    }
    else
    {
        $type = 'right-down';
        $w = 53;
        $l = -6;
        $t = -24;
        $h = 31;
    }


?>
        <span class="arrow arrow-<?=$type?>" style="width: <?=$w?>px; height: <?=$h?>px; left: <?=$l?>px; top: <?=$t?>px;">
        <?php if($type == 'right-down'): ?>
            <ins></ins><em></em>
        <?php endif; ?>
        </span>
<?php endif; ?>
    </div>
<?php
    $j++;
endforeach;
?>


        </div>
    </div>

             </div>
<?php } ?>
<div class="talentcalc-bottom">
    <div class="talentcalc-info">
        <div class="export"><a href="#">Экспортировать</a></div>
        <div class="reset"><a href="javascript:;">Сбросить</a></div>
        <div class="pointsspent"><span class="name">Очков потрачено:</span><span class="value"><span>0</span><ins>/</ins><span>0</span><ins>/</ins><span>0</span></span></div>
        <div class="pointsleft"><span class="name">Очков осталось:</span><span class="value">51</span></div>
        <div class="requiredlevel"><span class="name">Требуемый уровень:</span><span class="value">-</span></div>

    </div>

    <span class="clear"><!-- --></span>
</div>

    <script type="text/javascript">
    //<![CDATA[
        $(document).ready(function() {
            new TalentCalculator({ id: "character", classId: <?=$classId?>, calculatorMode: true, build: "<?=$build?>", callback: "", nTrees: 3 });
        });
        var MsgTalentCalculator = {
            talents: {
                tooltip: {
                    rank: "Уровень {0} / {1}",
                    primaryTree: "Сначала потратьте {0} очков талантов основной специализации.",
                    reqTree: "Требуется {0} очков в специализации «{1}».",
                    reqTalent: "Требуется {0} очк. в «{1}».",
                    nextRank: "Следующий уровень:",
                    click: "Щелкните, чтобы изучить",
                    rightClick: "Щелкните правой кнопкой мыши, чтобы забыть"
                }
            },
            buttons: {
                overviewPane: {
                    show: "Просмотреть сводку",
                    hide: "Просмотреть таланты"
                }
            },
            info: {
                calcMode: {
                    tooltip: {
                        title: "Режим «Калькулятор»",
                        description: "В этом режиме вы можете редактировать таланты. Это временные правки. Они не отображаются в игре."
                    }
                }
            }
        };
    //]]>
    </script>


            </div>

        </div>

        </div>
