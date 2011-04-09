<?php
$this->breadcrumbs=array(
    'Statistic'=>array('index'),
    'PvP',
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'characters-grid',
    'filter' => $model,
    'dataProvider'=>$mapper->search(40),
    'enableSorting'=>true,
    'columns'=>array(
        'name',
        'level',
        array(
            'type'=>'raw',
            'value'=>'CHtml::image("/images/wow/class/$data->class.gif","Test")',
            'name'=>'class',
            'sortable'=>true,
            'filter' => array(
                0 => 'All',
                1 => 'Warrior',
                2 => 'Paladin', 
                3 => 'Hunter',
                4 => 'Rogue',
                5 => 'Priest',
                7 => 'Shaman',
                8 => 'Mage',
                9 => 'Warlock',
                11 => 'Druid', 
                ),
        ),
        array(
            'type'=>'raw',
            'value'=>'CHtml::image("/images/wow/race/$data->race-$data->gender.gif","Test")',
            'name'=>'race',
            'sortable'=>true,
            'filter' => array(
                0 => 'All',
                1 => 'Human',
                2 => 'Orc', 
                3 => 'Dwarf',
                4 => 'Night Elf',
                5 => 'Undead',
                6 => 'Tauren',
                7 => 'Gnome',
                8 => 'Troll',
                ),
        ),
        array(
            'type'=>'raw',
            'value'=>'CHtml::image("/images/wow/faction/$data->faction.png","Test")',
            'name'=>'faction',
            'sortable'=>false,
            'filter' => array(
                0 => 'Alliance',
                1 => 'Horde',
                2 => 'Both',
                ),
        ),
        'hk',
        'honor_standing',
        array(
            'type'=>'raw',
            'value'=>'CHtml::image("/images/wow/rank/PvPRank0$data->honor_highest_rank.png")',
            'name'=>'honor_highest_rank',
            'sortable'=>true,
            'filter'=>false,
        ),
        array(
            'type'=>'raw',
            'value'=>'CHtml::image("/images/wow/rank/PvPRank0$data->honorRank.png")',
            'name'=>'honor_rank_points',
            'sortable'=>true,
            'filter'=>false,
        ),
        'thisWeek_cp',
        'thisWeek_kills',
    ),
)); ?>