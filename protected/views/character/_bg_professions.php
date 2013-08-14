<div class="summary-stats-bottom">
    <div class="summary-battlegrounds">
        <ul>
            <li class="rating">
                <span class="name">PvP Rank</span>
                <span class="value"><?= $model->honorRank ?></span>
                <span class="clear"><!-- --></span>
            </li>
            <?php if ($model->honor): ?>
                <li class="kills">
                    <span class="name">Honorable Kills</span>
                    <span class="value"><?= $model->honor->hk ?></span>
                    <span class="clear"><!-- --></span>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="summary-professions">
        <ul>
            <?php
            // Professions
            $professions = $model->professions;
            if (is_array($professions))
                for ($i = 0; $i < 2; $i++)
                    if (!isset($professions[$i])) {
                        ?>
                        <li class="empty">
	<span class="profession-details">
		<span class="icon">
			<span class="icon-frame frame-12">
				<img src="http://media.blizzard.com/wow/icons/18/inv_misc_questionmark.jpg" alt=""
                     width="12" height="12"/>
			</span>
		</span>
		<span class="name">No profession</span>
	</span>
                        </li>
                    <?php } else { ?>
                        <li>
                            <div
                                class="profile-progress border-3 <?= (($professions[$i]['value'] == 300) ? 'completed' : '') ?>">
                                <div class="bar border-3" style="width: <?= ($professions[$i]['value'] / 3) ?>%"></div>
                                <div class="bar-contents">
	<span class="profession-details">
		<span class="icon">
			<span class="icon-frame frame-12">
				<img src="http://media.blizzard.com/wow/icons/18/<?= $professions[$i]['icon'] ?>.jpg"
                     alt="" width="12" height="12"/>
			</span>
		</span>
		<span class="name"><?= $professions[$i]['name'] ?></span>
		<span class="value"><?= $professions[$i]['value'] ?></span>
	</span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
        </ul>
    </div>
    <span class="clear"><!-- --></span>
</div>
