var Summary = {

    init: function() {
        setTimeout(Summary.initEvents, 1);
    },

    initEvents: function() {

        // Talent specializations
        $('#profile-info-spec').mouseover(function() {

            var $tooltip = $('<ul/>');

            function appendTreeLine(title, $source) {

                var isActiveSpec = $source.is('.active');

                var $line = $('<li/>').addClass(isActiveSpec ? 'color-tooltip-yellow' : 'color-q0');

                $('<strong>').text($('span.name', $source).text()).appendTo($line);
                $line.append(' (' + $('span.build', $source).text().replace(/\//g, ' / ') + ')');

                $tooltip.append($line);
            }

            $('<li/>').append($('<h3/>').text(MsgSummary.talents.specTooltip.title)).appendTo($tooltip);

            var $talents = $('#summary-talents');
            appendTreeLine(MsgSummary.talents.specTooltip.secondary, $('a:nth(0)', $talents));

            Tooltip.show(this, $tooltip);
        });

        // View options
        $('#profile-view-options-summary').delegate('a', 'click', function() {
            var viewName = ($(this).attr('class'));
            if(viewName.indexOf('disabled') != -1) {
                return;
            }
            Cookie.create('wow.character.summary.view', viewName, { path: Core.baseUrl + '/character/', expires: 720 }); // 30 days
        })
            .find('a.threed').mouseover(function() {
                Tooltip.show(this, Wow.createSimpleTooltip(MsgSummary.viewOptions.threed.title, MsgProfile.tooltip.feature.notYetAvailable));
            });

        // Audit "What is this"
        $('#summary-audit-whatisthis').mouseover(function() {
            Tooltip.show(this, Wow.createSimpleTooltip($('#summary-audit h3').text(), MsgSummary.audit.whatIsThis));
        });

        // Statistics
        $('#summary-stats-simple-arrow')
            .click(function() {
                var $this = $(this);
                var $current = $this.siblings('div:visible');
                var $next = $current.next();
                if($next.length == 0) {
                    $next = $this.siblings('div:first');
                }

                $current.hide();
                $next.show();
            });

        $('#summary-stats-toggler').click(function() {

            var $simple = $('#summary-stats-simple');
            var $advanced = $('#summary-stats-advanced');
            var $toggler = $('span.arrow', this);

            if($advanced.is(':visible')) {
                $advanced.hide();
                $simple.show();
                $toggler.text(MsgSummary.stats.toggle.core);
                $toggler.parent().addClass('expanded');
            } else {
                $advanced.show();
                $simple.hide();
                $toggler.text(MsgSummary.stats.toggle.all);
                $toggler.parent().removeClass('expanded');
            }
        });

        // Recent activity
        $('#profile-recentactivity-rss').mouseover(function() {
            Tooltip.show(this, MsgSummary.recentActivity.subscribe);
        });
    },

    createItemListTooltip: function(items) {

        items.sort(function(a, b) {
            // Quality DESC, Name ASC
            var cmp = b.quality - a.quality;
            if(cmp != 0) {
                return cmp;
            }
            return a.name.localeCompare(b.name);
        });

        var $tooltip = $('<ul />').addClass('summary-item-list');

        $.each(items, function() {
            $('<li/>').append(
                    $('<span/>').addClass('icon').append(Wow.Icon.framedIcon(this.icon, 12))
                )
                .append(
                    $('<span/>').addClass('description color-q' + this.quality).text(this.name)
                )
                .appendTo($tooltip);
        });
        return $tooltip;
    }
};

$(document).ready(Summary.init);

Summary.Inventory = function(options, data) {

    // Variables
    var self = this;
    Summary.Inventory.instance = self;

    // State
    var highlightedSlotIds;

    // DOM nodes
    var $inventory;
    var $slotsById = {};

    // Constructor
    init();

    // Public functions
    self.highlightSlots = highlightSlots;

    self.removeHighlighting = removeHighlighting;

    self.getItemsBySlotIds = function(slotIds) {
        var result = [];
        $.each(slotIds, function() {
            result.push(data[this]);
        });
        return result;
    }

    // Private functions
    function init() {

        $inventory = $('#summary-inventory');

        setTimeout(initHtml, 1);
        setTimeout(initEvents, 1);
    }

    function initHtml() {
        $inventory.children('div.slot').each(function() {
            $slotsById[getSlotIdFromSlot(this)] = $(this);
        });
    }

    function initEvents() {

        $inventory.delegate('a.empty', 'mouseover', emptySlotMouseOver);
        if(options.view == 'advanced') {
            $inventory.delegate('a.audit-warning', 'mouseover', slotAuditMouseOver);
            $inventory.delegate('a.audit-warning', 'mouseout',  removeHighlighting);
        }
    }

    function highlightSlots(slotIds) {

        self.removeHighlighting();

        $.each(slotIds, function() {
            $slotsById[this].addClass('slot-highlight');
        });
        highlightedSlotIds = slotIds;
    }

    function removeHighlighting() {

        if(highlightedSlotIds == null) {
            return;
        }

        $.each(highlightedSlotIds, function() {
            $slotsById[this].removeClass('slot-highlight');
        });

        highlightedSlotIds = null;
    }

    // Event handlers
    function emptySlotMouseOver() {

        var slotName = MsgSummary.inventory.slots[getSlotTypeFromSlot(this.parentNode.parentNode.parentNode)];

        Tooltip.show(this, Wow.createSimpleTooltip(slotName, MsgSummary.inventory.slots.empty));
    }

    function slotAuditMouseOver() {

        var slotId = getSlotIdFromSlot(this.parentNode.parentNode.parentNode.parentNode.parentNode);

        //highlightSlots([slotId]);

        var $tooltip = $('<ul/>').addClass('color-tooltip-yellow');

        var audit = Summary.Audit.instance.getData();
        var enchanterEnchantAvailable = !!(audit.missingEnchanterEnchants && audit.missingEnchanterEnchants[slotId]);

        var $ul = $('<ul/>').addClass('summary-audit-list');

        // Enchant
        if(audit.unenchantedItems && audit.unenchantedItems[slotId] && !enchanterEnchantAvailable) {
            $('<li/>').text(MsgSummary.audit.enchants.tooltip).appendTo($ul);
        }

        // Empty sockets
        if(audit.itemsWithEmptySockets) {
            var nEmptySockets = audit.itemsWithEmptySockets[slotId];
            if(nEmptySockets > 0) {
                $('<li/>').text(nEmptySockets + ' ' + (nEmptySockets == 1 ? MsgSummary.audit.sockets.singular : MsgSummary.audit.sockets.plural)).appendTo($ul);
            }
        }

        // Non-optimal armor
        if(audit.inappropriateArmorItems && audit.inappropriateArmorItems[slotId]) {
            $('<li/>').text(Core.msg(MsgSummary.audit.armor.tooltip, MsgSummary.audit.armor[audit.appropriateArmorType])).appendTo($ul);
        }

        // Low level items
        if(audit.lowLevelItems && audit.lowLevelItems[slotId]) {
            $('<li/>').text(MsgSummary.audit.lowLevel.tooltip).appendTo($ul);
        }

        // Missing belt buckle
        if(audit.missingExtraSockets && audit.missingExtraSockets[slotId] && audit.recommendedBeltBuckleName) {
            $('<li/>').append(Core.msg(MsgSummary.audit.missing, '<span class="color-q' + audit.recommendedBeltBuckleQualityId + '">' + audit.recommendedBeltBuckleName + '</span>')).appendTo($ul);
        }

        // Blacksmithing
        if(audit.missingBlacksmithSockets && audit.missingBlacksmithSockets[slotId]) {
            $('<li/>').append($('<span/>').addClass('icon').append(Wow.Icon.framedIcon('trade_blacksmithing', 12))).append($('<span/>').text(Core.msg(Msg.grammar.colon, MsgSummary.audit.blacksmithing.name) + ' ' + MsgSummary.audit.blacksmithing.tooltip)).appendTo($ul);
        }

        // Enchanting
        if(enchanterEnchantAvailable) {
            $('<li/>').append($('<span/>').addClass('icon').append(Wow.Icon.framedIcon('trade_engraving', 12))).append($('<span/>').text(Core.msg(Msg.grammar.colon, MsgSummary.audit.enchanting.name) + ' ' + MsgSummary.audit.enchanting.tooltip)).appendTo($ul);
        }

        // Engineering
        if(audit.missingEngineerEnchants && audit.missingEngineerEnchants[slotId]) {
            $('<li/>').append($('<span/>').addClass('icon').append(Wow.Icon.framedIcon('trade_engineering', 12))).append($('<span/>').text(Core.msg(Msg.grammar.colon, MsgSummary.audit.engineering.name) + ' ' + MsgSummary.audit.engineering.tooltip)).appendTo($ul);
        }

        // Inscription
        if(audit.missingScribeEnchants && audit.missingScribeEnchants[slotId]) {
            $('<li/>').append($('<span/>').addClass('icon').append(Wow.Icon.framedIcon('inv_inscription_tradeskill01', 12))).append($('<span/>').text(Core.msg(Msg.grammar.colon, MsgSummary.audit.inscription.name) + ' ' + MsgSummary.audit.inscription.tooltip)).appendTo($ul);
        }

        // Leatherworking
        if(audit.missingLeatherworkerEnchants && audit.missingLeatherworkerEnchants[slotId]) {
            $('<li/>').append($('<span/>').addClass('icon').append(Wow.Icon.framedIcon('inv_misc_armorkit_17', 12))).append($('<span/>').text(Core.msg(Msg.grammar.colon, MsgSummary.audit.leatherworking.name) + ' ' + MsgSummary.audit.leatherworking.tooltip)).appendTo($ul);
        }

        $ul.appendTo($tooltip);

        Tooltip.show(this, $tooltip);
    }

    // Utilities
    function getSlotIdFromSlot(slot) {
        return $(slot).attr('data-id');
    }

    function getSlotTypeFromSlot(slot) {
        return $(slot).attr('data-type');
    }
};

Summary.Audit = function(data) {

    // Variables
    var self = this;
    Summary.Audit.instance = self;

    // Constructor
    init();

    // Public functions
    self.getData = function() {
        return data;
    }

    // Private functions
    function init() {
        setTimeout(initEvents, 1);
    }

    function initEvents() {

        var $audit = $('#summary-audit');

        var $auditList = $audit.find('ul');
        $auditList.delegate('span.tip', 'mouseover', lineMouseOver);
        $auditList.delegate('span.tip', 'mouseout',  lineMouseOut);
    }

    // Event handlers
    function lineMouseOver() {

        var slotIds = getSlotIdsFromSpan(this);
        if(slotIds == null) {
            return;
        }

        Summary.Inventory.instance.highlightSlots(slotIds);

        var highlightedItems = Summary.Inventory.instance.getItemsBySlotIds(slotIds);
        var $tooltip = Summary.createItemListTooltip(highlightedItems);
        Tooltip.show(this, $tooltip);
    }

    function lineMouseOut() {

        Summary.Inventory.instance.removeHighlighting();
    }

    // Utilities
    function getSlotIdsFromSpan(span) {

        var slots = $(span).parent().attr('data-slots');
        if(slots) {
            return slots.split(',');
        }
    }
};

Summary.Reforging = function() {

    // Variables
    var self = this;

    // DOM nodes
    var $reforging;
    var $reductionLinesByStatId = {};
    var $increaseLinesByStatId = {};

    // State
    var $highlightedLines;

    // Constructor
    init();

    // Private functions
    function init() {

        $reforging = $('#summary-reforging')

        setTimeout(initHtml, 1);
        setTimeout(initEvents, 1);
    }

    function initHtml() {

        var $line;

        $reforging
            .find('ul.reductions li').each(function() {
                $line = $(this);
                $line.reduction = true;
                $reductionLinesByStatId[getStatIdFromLine(this)] = $line;

            }).end()
            .find('ul.increases li').each(function() {
                $line = $(this);
                $line.reduction = false;
                $increaseLinesByStatId[getStatIdFromLine(this)] = $line;
            });
    }

    function initEvents() {

        $reforging
            .delegate('li', 'mouseover', lineMouseOver)
            .delegate('li', 'mouseout',  lineMouseOut);
    }

    function highlight(reductionLines, increaseLines) {
        $highlightedLines = [];
        highlightLines(reductionLines, $reductionLinesByStatId);
        highlightLines(increaseLines,  $increaseLinesByStatId);
        $reforging.addClass('reforging-highlight');
    }

    function highlightLines(statIds, $linesByStatId) {

        if($.isPlainObject(statIds)) {
            $.each(statIds, function(statId, amount) {
                highlightLine($linesByStatId[statId], amount);
            });
        } else {
            var statId = statIds;
            highlightLine($linesByStatId[statId]);
        }
    }

    function highlightLine($line, amount) {

        if(!$line.arrowed) {
            $('<div/>').addClass('arrow').prependTo($line);
            $line.arrowed = true;
        }

        $line.addClass('highlight');
        $highlightedLines.push($line);

        if(amount > 0) {
            updateLineText($line, amount);
        }
    }

    function updateLineText($line, amount) {
        var $value = $line.find('span.value');
        $line.previousValue = Math.abs(parseInt($value.text()));
        $value.text(($line.reduction ? '-' : '+') + amount);
    }

    function removeHighlighting() {

        if($highlightedLines == null) {
            return;
        }

        var $line;
        $.each($highlightedLines, function() {
            $line = this;
            $line.removeClass('highlight');
            if($line.previousValue > 0) {
                updateLineText($line, $line.previousValue);
                $line.previousValue = null;
            }
        });
        $highlightedLines = null;
        $reforging.removeClass('reforging-highlight');
    }

    // Event handlers
    function lineMouseOver() {
        var $this = $(this);

        var reduction = ($this.parent().hasClass('reductions'));
        if(reduction) {
            highlight(getStatIdFromLine(this), getSourcesFromLine(this));
        } else {
            highlight(getSourcesFromLine(this), getStatIdFromLine(this));
        }
    }

    function lineMouseOut() {
        removeHighlighting();
    }

    // Utilities
    function getStatIdFromLine(li) {
        return $(li).data('stat'); // number
    }

    function getSourcesFromLine(li) {
        return $(li).data('sources'); // json
    }
};

Summary.Stats = function(data) {

    // Variables
    var self = this;

    // Constants
    var TEMPLATE_RANGE = '{0} - {1}';
    var TEMPLATE_MULTIVALUE = '{0} / {1}';
    var PERCENT_DECIMALS = 2;
    var SPEED_DECIMALS = 2;
    var DPS_DECIMALS = 1;
    var DAMAGE_DECIMALS = 0;
    var ATTACK_POWER_MAGIC_NUMBER = 14;

    // Tooltips
    var statTooltips = {

        // Misc.
        averageilvl: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.averageItemLevel.title, data.averageItemLevelBest) + '</h3>',
                MsgSummary.stats.averageItemLevel.description
            ];
        },

        // Health
        health: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.health.title, data.health) + '</h3>',
                MsgSummary.stats.health.description
            ];
        },

        // Power
        'power-0': function() { // Mana
            return [
                '<h3>' + Core.msg(MsgSummary.stats.mana.title, data.power) + '</h3>',
                MsgSummary.stats.mana.description
            ];
        },
        'power-1': function() { // Rage
            return [
                '<h3>' + Core.msg(MsgSummary.stats.rage.title, data.power) + '</h3>',
                MsgSummary.stats.rage.description
            ];
        },
        'power-3': function() { // Energy
            return [
                '<h3>' + Core.msg(MsgSummary.stats.energy.title, data.power) + '</h3>',
                MsgSummary.stats.energy.description
            ];
        },

        // Base
        strength: function() {
            var res = ['<h3>' + Core.msg(MsgSummary.stats.strength.title, data.strTotal) + tooltipBaseStatBonusText(data.strBase, data.strTotal) + '</h3>'];
            if(data.str_ap > 0)
                res.push(Core.msg(MsgSummary.stats.increases.attackPower, data.str_ap));
            return res;
        },
        agility: function() {
            var res = ['<h3>' + Core.msg(MsgSummary.stats.agility.title, data.agiTotal) + tooltipBaseStatBonusText(data.agiBase, data.agiTotal) + '</h3>'];
            if(data.agi_ap > 0)
                res.push(Core.msg(MsgSummary.stats.increases.attackPower, data.agi_ap));
            if(data.agi_crit > 0)
                res.push(Core.msg(MsgSummary.stats.increases.critChance, data.agi_crit.toFixed(PERCENT_DECIMALS)));
            return res;
        },
        stamina: function() {
            var res = ['<h3>' + Core.msg(MsgSummary.stats.stamina.title, data.staTotal) + tooltipBaseStatBonusText(data.staBase, data.staTotal) + '</h3>'];
            if(data.sta_hp > 0) {
                res.push(Core.msg(MsgSummary.stats.increases.health, data.sta_hp));
            }
            return res;
        },
        intellect: function() {
            var res = ['<h3>' + Core.msg(MsgSummary.stats.intellect.title, data.intTotal) + tooltipBaseStatBonusText(data.intBase, data.intTotal) + '</h3>'];
            if(data.int_mp > 0) {
                res.push(Core.msg(MsgSummary.stats.increases.mana, data.int_mp));
                if(data.int_crit > 0) {
                    res.push(Core.msg(MsgSummary.stats.increases.spellCritChance, data.int_crit.toFixed(PERCENT_DECIMALS)));
                }
            } else {
                res.push(Core.msg('<span class="color-q0">' + MsgSummary.stats.noBenefits + '</span>'));
            }
            return res;
        },
        spirit: function() {
            var res = ['<h3>' + Core.msg(MsgSummary.stats.spirit.title, data.sprTotal) + tooltipBaseStatBonusText(data.sprBase, data.sprTotal) + '</h3>'];
            if(data.spr_regen > 0) {
                res.push(Core.msg(MsgSummary.stats.increases.manaRegen, data.spr_regen));
            } else {
                res.push(Core.msg('<span class="color-q0">' + MsgSummary.stats.noBenefits + '</span>'));
            }
            return res;
        },

        // Melee
        meleedamage: function() {
            var res = ['<h3>' + MsgSummary.inventory.slots[21] + '</h3>'];
            tooltipDamageDetails(res, data.dmgMainSpeed, data.dmgMainMin, data.dmgMainMax, data.dmgMainDps);
            if(data.hasOffhandWeapon) {
                res.push('<br /><h3 class="color-q1">' + MsgSummary.inventory.slots[22] + '</h3>');
                tooltipDamageDetails(res, data.dmgOffSpeed, data.dmgOffMin, data.dmgOffMax, data.dmgOffDps);
            }
            return res;
        },
        meleedps: function() {
            return [
                '<h3>' + MsgSummary.stats.meleeDps.title + '</h3>'
            ];
        },
        meleeattackpower: function() {
            var totalAp = data.atkPowerBase + data.atkPowerBonus + data.atkPowerLoss;
            return [
                '<h3>' + Core.msg(MsgSummary.stats.meleeAttackPower.title, totalAp) + tooltipBaseStatBonusText(data.atkPowerBase, totalAp) + '</h3>',
                Core.msg(MsgSummary.stats.increases.meleeDps, (totalAp / ATTACK_POWER_MAGIC_NUMBER).toFixed(DPS_DECIMALS))
            ];
        },
        meleespeed: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.meleeSpeed.title, (
                    data.hasOffhandWeapon ?
                        Core.msg(TEMPLATE_MULTIVALUE, data.dmgMainSpeed.toFixed(SPEED_DECIMALS), data.dmgOffSpeed.toFixed(SPEED_DECIMALS)) :
                        data.dmgMainSpeed.toFixed(SPEED_DECIMALS)
                    )) + '</h3>'
            ];
        },
        meleecrit: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.meleeCrit.title, data.critPercent.toFixed(PERCENT_DECIMALS)) + '</h3>',
                MsgSummary.stats.meleeCrit.description
            ];
        },
        expertise: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.expertise.title, (
                    data.hasOffhandWeapon ?
                        Core.msg(TEMPLATE_MULTIVALUE, data.expertiseMain, data.expertiseOff) :
                        data.expertiseMain
                    )) + '</h3>',
                Core.msg(MsgSummary.stats.decreases.dodgeParry, (
                    data.hasOffhandWeapon ?
                        Core.msg(TEMPLATE_MULTIVALUE, data.expertiseMainPercent.toFixed(PERCENT_DECIMALS) + '%', data.expertiseOffPercent.toFixed(PERCENT_DECIMALS)) :
                        data.expertiseMainPercent.toFixed(PERCENT_DECIMALS)
                    )),
                Core.msg(MsgSummary.stats.expertise.description, data.expertiseRating, data.expertiseRatingPercent.toFixed(PERCENT_DECIMALS))
            ];
        },

        // Ranged
        rangeddamage: function() {
            var res = ['<h3>' + MsgSummary.inventory.slots[15] + '</h3>'];
            tooltipDamageDetails(res, data.dmgRangeSpeed, data.dmgRangeMin, data.dmgRangeMax, data.dmgRangeDps);
            return res;
        },
        rangeddps: function() {
            return [
                '<h3>' + MsgSummary.stats.rangedDps.title + '</h3>'
            ];
        },
        rangedattackpower: function() {
            var totalRap = data.rangeAtkPowerBase + data.rangeAtkPowerBonus + data.rangeAtkPowerLoss;
            var res = [
                '<h3>' + Core.msg(MsgSummary.stats.rangedAttackPower.title, totalRap) + tooltipBaseStatBonusText(data.rangeAtkPowerBase, totalRap) + '</h3>',
                Core.msg(MsgSummary.stats.increases.rangedDps, (totalRap / ATTACK_POWER_MAGIC_NUMBER).toFixed(DPS_DECIMALS))
            ];
            if(data.rap_petAp > 0) {
                res.push(Core.msg(MsgSummary.stats.increases.petAttackPower, data.rap_petAp));
            }
            if(data.rap_petSpellDmg > 0) {
                res.push(Core.msg(MsgSummary.stats.increases.petSpellDamage, data.rap_petSpellDmg));
            }
            return res;
        },
        rangedspeed: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.rangedSpeed.title, data.dmgRangeSpeed.toFixed(SPEED_DECIMALS)) + '</h3>'
            ];
        },
        rangedhaste: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.rangedHaste.title, data.rangeHasteRatingPercent.toFixed(PERCENT_DECIMALS)) + '</h3>',
                MsgSummary.stats.rangedHaste.description2,
                Core.msg(MsgSummary.stats.rangedHaste.description, data.rangeHasteRating, data.rangeHasteRatingPercent.toFixed(PERCENT_DECIMALS))
            ];
        },
        rangedhit: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.rangedHit.title, data.rangeHitRatingPercent.toFixed(PERCENT_DECIMALS)) + '</h3>',
                Core.msg(MsgSummary.stats.rangedHit.description, data.rangeHitRating, data.rangeHitRatingPercent.toFixed(PERCENT_DECIMALS))
            ];
        },
        rangedcrit: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.rangedCrit.title, data.rangeCritPercent.toFixed(PERCENT_DECIMALS)) + '</h3>',
                MsgSummary.stats.rangedCrit.description
            ];
        },

        // Spell
        spellpower: function() {
            var res = [
                '<h3>' + Core.msg(MsgSummary.stats.spellPower.title, data.holyDamage) + '</h3>',
                MsgSummary.stats.spellPower.description
            ];
            if(data.spellDmg_petAp > 0 && data.spellDmg_petSpellDmg > 0)
                res.push(Core.msg(MsgSummary.stats.increases.petAttackPowerSpellDamage, data.spellDmg_petAp, data.spellDmg_petSpellDmg));
            return res;
        },
        spellcrit: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.spellCrit.title, data.spellCritPercent.toFixed(PERCENT_DECIMALS)) + '</h3>',
                MsgSummary.stats.spellCrit.description
            ];
        },
        manaregen: function() {
            return [
                '<h3>' + MsgSummary.stats.manaRegen.title + '</h3>',
                Core.msg(MsgSummary.stats.manaRegen.description , data.manaRegenPerFive)
            ];
        },
        combatregen: function() {
            return [
                '<h3>' + MsgSummary.stats.combatRegen.title + '</h3>',
                Core.msg(MsgSummary.stats.combatRegen.description, data.manaRegenCombat)
            ];
        },

        // Defense
        armor: function() {
            var res = [
                '<h3>' + Core.msg(MsgSummary.stats.armor.title, data.armorTotal) + '</h3>',
                Core.msg(MsgSummary.stats.decreases.damageTaken, data.armorPercent.toFixed(PERCENT_DECIMALS))
            ];
            if(data.armor_petArmor > 0) {
                res.push(Core.msg(MsgSummary.stats.increases.petArmor, data.armor_petArmor));
            }
            return res;
        },
        dodge: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.dodge.title, data.dodge.toFixed(PERCENT_DECIMALS)) + '</h3>',
                '<span class="color-q0">' + MsgSummary.stats.beforeReturns + '</span>'
            ];
        },
        parry: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.parry.title, data.parry.toFixed(PERCENT_DECIMALS)) + '</h3>',
                '<span class="color-q0">' + MsgSummary.stats.beforeReturns + '</span>'
            ];
        },
        block: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.block.title, data.block.toFixed(PERCENT_DECIMALS)) + '</h3>',
                Core.msg(MsgSummary.stats.block.description, data.block_damage.toFixed(PERCENT_DECIMALS))
            ];
        },

        // Resistance
        arcaneres: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.arcaneRes.title, data.arcaneResist) + '</h3>',
                Core.msg(MsgSummary.stats.arcaneRes.description, (0).toFixed(PERCENT_DECIMALS))
            ];
        },
        fireres: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.arcaneRes.title, data.fireResist) + '</h3>',
                Core.msg(MsgSummary.stats.fireRes.description, (0).toFixed(PERCENT_DECIMALS))
            ];
        },
        frostres: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.arcaneRes.title, data.frostResist) + '</h3>',
                Core.msg(MsgSummary.stats.frostRes.description, (0).toFixed(PERCENT_DECIMALS))
            ];
        },
        natureres: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.arcaneRes.title, data.natureResist) + '</h3>',
                Core.msg(MsgSummary.stats.natureRes.description, (0).toFixed(PERCENT_DECIMALS))
            ];
        },
        shadowres: function() {
            return [
                '<h3>' + Core.msg(MsgSummary.stats.arcaneRes.title, data.shadowResist) + '</h3>',
                Core.msg(MsgSummary.stats.shadowRes.description, (0).toFixed(PERCENT_DECIMALS))
            ];
        }
    };

    // Constructor
    init();

    function init() {

        $('#summary-stats').delegate('li', 'mouseover', statMouseOver);
        $('#summary-health, #summary-power, #summary-averageilvl-best').mouseover(statMouseOver);
    }

    function statMouseOver() {

        var $this = $(this);

        var statId = getStatIdFromListItem(this);
        if(!statTooltips[statId] || $this.hasClass('no-tooltip')) {
            return;
        }
        var lines = statTooltips[statId]();
        if(!lines || lines.length <= 0) {
            return;
        }

        var $tooltip = $('<ul/>').addClass('color-tooltip-yellow'); // Yellow overall

        lines[0] = '<div class="color-q1">' + lines[0] + '</div>'; // First line in white
        $tooltip.html('<li>' + lines.join('</li><li>') + '</li>');

        Tooltip.show(this, $tooltip);
    }

    // Tooltip helpers
    function tooltipBaseStatBonusText(base, total) {

        var diff = total - base;
        var diffStr = '';
        if(diff > 0)
            diffStr += '<span class="color-tooltip-green">+' + diff + '</span>';
        else if(diff < 0)
            diffStr += '<span class="color-tooltip-red">+' + diff + '</span>';

        return ' (' + base + diffStr + ')';

    }

    function tooltipDamageDetails(res, speed, damageMin, damageMax, dps) {
        res.push('<span class="float-right">' + speed.toFixed(SPEED_DECIMALS) + '</span>' + MsgSummary.stats.damage.speed);
        res.push('<span class="float-right">' + Core.msg(TEMPLATE_RANGE, damageMin.toFixed(DAMAGE_DECIMALS), damageMax.toFixed(DAMAGE_DECIMALS)) + '</span>' + MsgSummary.stats.damage.damage);
        res.push('<span class="float-right">' + dps.toFixed(DPS_DECIMALS) + '</span>' + MsgSummary.stats.damage.dps);
    }

    // Utility functions
    function getStatIdFromListItem(li) {

        return $(li).attr('data-id');
    }

};

Summary.RaidProgression = function(options, data) {

    // Constants
    var ICON_WIDTH = 61;
    var SPACER_WIDTH = 26;
    var NUM_RAIDS_AT_A_TIME = 9;
    var SCROLL_ANIM_DURATION = 333;
    var SCROLL_MIN_LEFT = 0;
    var SCROLL_MAX_LEFT;
    var SCROLL_DISTANCE = NUM_RAIDS_AT_A_TIME * (ICON_WIDTH + SPACER_WIDTH);

    // DOM nodes
    var $tableWrapper = $('#summary-raid-wrapper-table');
    var $leftArrow    = $('#summary-raid-arrow-left');
    var $rightArrow   = $('#summary-raid-arrow-right');

    // Variables
    var scrollLeft;

    // Constructor
    init();

    // Public functions
    // ...

    // Private functions
    function init() {
        setScroll();
        setTimeout(initEvents, 1);
    }

    function setScroll() {
        $tableWrapper.scrollLeft(99999); // Far right
        scrollLeft = SCROLL_MAX_LEFT = $tableWrapper.scrollLeft();

        // Left-align the "Trivial" label if the trivial group is initially visible
        if(options.nOptimalRaids + options.nChallengingRaids < NUM_RAIDS_AT_A_TIME) {
            $('#summary-raid-head-trivial').css('left', scrollLeft + 'px');
        }
    }

    function initEvents() {
        $tableWrapper.delegate('tr.icons td', 'mouseover', iconMouseOver);
        $tableWrapper.delegate('tr.normal td, tr.heroic td', 'mouseover', statusMouseOver);
        $tableWrapper.delegate('span.square-anchor', 'mouseover', timelineSquareMouseOver);
        $leftArrow.click(leftArrowClick);
        $rightArrow.click(rightArrowClick);
    }

    function showRaidSummaryTooltip($node, raid) {
        var nPlayers = raid.nPlayers;
        if(nPlayers == "-10") {
            nPlayers = "10/25";
        }

        var $tooltip = Wow.createSimpleTooltip(raid.name);
        $tooltip.append($('<li/>').addClass('color-tooltip-yellow').text(raid.location));
        $tooltip.append($('<li/>').addClass('color-tooltip-yellow').text(Core.msg(MsgSummary.raid.tooltip.players, nPlayers)));
        if(raid.expansion > 0) {
            $tooltip.append($('<li/>').addClass('color-ex' + raid.expansion).text(MsgSummary.raid.tooltip.expansions[raid.expansion]));
        }
        Tooltip.show($node, $tooltip);
    }

    function showRaidDetailedTooltip($node, raid) {

        var heroicMode = $node.parent().hasClass('heroic');

        var name = raid.name;
        var $tooltip = $('<ul />').addClass('summary-raid-tooltip');
        if(raid.heroicEncounters != null) {
            name += ' ' + (heroicMode ? MsgSummary.raid.tooltip.heroic : MsgSummary.raid.tooltip.normal);
        }
        $('<li/>').append($('<h3/>').text(name)).appendTo($tooltip);

        var encounters = (heroicMode ? raid.heroicEncounters : raid.normalEncounters);
        var nBosses = encounters.length;
        var nBossedKilled = 0;

        for(var i = 0; i < encounters.length; ++i) {
            var encounter = encounters[i];

            if(encounter.nKills != 0) {
                ++nBossedKilled;
            }
        }

        var percentKilled = Math.round(nBossedKilled / nBosses * 100);

        $tooltip.append($('<li/>').addClass('color-tooltip-yellow').text(Core.msg(MsgSummary.raid.tooltip.complete, percentKilled, nBossedKilled, nBosses)));

        for(var i = 0; i < encounters.length; ++i) {

            var encounter = encounters[i];

            var $line = $('<li/>').addClass(encounter.nKills == 0 ? 'incomplete' : 'completed');

            var $count = $('<span/>').addClass('count');
            if(encounter.nKills == -1) {
                $count.text(String.fromCharCode(10004)); // Tick
            } else {
                $count.text(encounter.nKills);
            }
            $count.appendTo($line);

            $('<span/>').addClass('times').text('x').appendTo($line);
            $('<span/>').addClass('name').text(encounter.name + (encounter.optional ? ' ' + MsgSummary.raid.tooltip.optional : '')).appendTo($line);
            $('<span/>').addClass('clear').appendTo($line);

            $line.appendTo($tooltip);
        }

        Tooltip.show($node, $tooltip);
    }

    function scroll(direction) {

        scrollLeft = Math.min(SCROLL_MAX_LEFT, Math.max(SCROLL_MIN_LEFT, scrollLeft + (SCROLL_DISTANCE * direction)));
        $tableWrapper.stop().animate({scrollLeft: scrollLeft}, SCROLL_ANIM_DURATION);

        showArrow($leftArrow,  scrollLeft > SCROLL_MIN_LEFT);
        showArrow($rightArrow, scrollLeft < SCROLL_MAX_LEFT);
    }

    function showArrow($arrow, visible) {
        if(visible == !$arrow.is(':visible')) {
            setTimeout(function() { $arrow[visible ? 'fadeIn' : 'fadeOut']('fast'); }, 1);
        }
    }

    function hideArrow($arrow) {
        if($arrow.is(':visible')) {
            setTimeout(function() { $arrow.fadeOut(); }, 1);
        }
    }

    // Event handlers
    function iconMouseOver() {
        var $cell = $(this);
        var raid = getRaidFromCell($cell);
        if(!raid) return;

        showRaidSummaryTooltip($cell, raid);
    }

    function statusMouseOver() {
        var $cell = $(this);
        var raid = getRaidFromCell($cell);
        if(!raid) return;

        showRaidDetailedTooltip($cell, raid);
    }

    function timelineSquareMouseOver() {
        var $this = $(this);
        var expansion = $this.data('expansion');

        Tooltip.show($this, MsgSummary.raid.expansions[expansion]);
    }

    function timelineMouseOut() {
        var $this = $(this);
        $this.children('div').removeClass('marker-hover');
    }

    function leftArrowClick() {
        scroll(-1);
    }

    function rightArrowClick() {
        scroll(1);
    }

    // Utilities
    function getRaidFromCell($cell) {
        var raidAbbr = $cell.data('raid');
        if(raidAbbr) {
            return data[raidAbbr];
        }
    }
};
