 <?php
                $badges = [
                    ['icon' => '🚀', 'name' => 'First Step',    'desc' => 'Started Ch. 1',      'color' => '#D1FAE5', 'earned' => true],
                    ['icon' => '🔍', 'name' => 'Token Hunter',  'desc' => 'Finished Lexical',   'color' => '#CFFAFE', 'earned' => true],
                    ['icon' => '🌳', 'name' => 'Tree Builder',  'desc' => 'Finished Syntax',    'color' => '#FEF3C7', 'earned' => true],
                    ['icon' => '🧠', 'name' => 'Semantics Pro', 'desc' => 'Finish Ch. 3',       'color' => '#FEE2E2', 'earned' => false],
                    ['icon' => '⚙️', 'name' => 'Code Forge',    'desc' => 'Finish Ch. 4',       'color' => '#EEF2FF', 'earned' => false],
                    ['icon' => '🎯', 'name' => 'Quiz Ace',      'desc' => 'Score 100% on quiz', 'color' => '#F0FDF4', 'earned' => false],
                    ['icon' => '⚡', 'name' => 'Speed Runner',  'desc' => 'Finish in 7 days',   'color' => '#FFF7ED', 'earned' => false],
                    ['icon' => '🏆', 'name' => 'Top Scholar',   'desc' => 'Reach rank #1',      'color' => '#FEFCE8', 'earned' => false],
                    ['icon' => '🌟', 'name' => 'Compiler God',  'desc' => 'Complete all',       'color' => '#F5F3FF', 'earned' => false],
                ];
                $earnedCount = count(array_filter($badges, fn($b) => $b['earned']));
                ?>


<div class="section-heading" style="margin-top: 28px;">Badges earned</div>
                <div class="badges-card">
                    <div class="badges-card-header">
                        <span class="badges-card-title">Your badges</span>
                        <span class="badges-count-pill"><?= $earnedCount ?> / <?= count($badges) ?> earned</span>
                    </div>
                    <div class="badges-grid">
                        <?php foreach ($badges as $badge): ?>
                            <div class="badge-slot <?= $badge['earned'] ? 'badge-earned' : 'badge-locked' ?>">
                                <?php if ($badge['earned']): ?>
                                    <div class="badge-earned-tick">
                                        <svg viewBox="0 0 10 8" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="1,4 3.5,6.5 9,1" />
                                        </svg>
                                    </div>
                                    <div class="badge-icon" style="background:<?= $badge['color'] ?>;"><?= $badge['icon'] ?></div>
                                <?php else: ?>
                                    <div class="badge-icon badge-icon-locked">🔒</div>
                                <?php endif; ?>
                                <span class="badge-name"><?= htmlspecialchars($badge['name']) ?></span>
                                <span class="badge-desc"><?= htmlspecialchars($badge['desc']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>



                <style>
                     /* Badges style */
        .badges-card {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 16px;
            overflow: hidden;
        }

        .badges-card-header {
            padding: 14px 20px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .badges-card-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .badges-count-pill {
            font-size: 11px;
            color: var(--color-text-secondary);
            background: var(--color-background-secondary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 20px;
            padding: 2px 10px;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--color-border-tertiary);
        }

        .badge-slot {
            background: var(--color-background-primary);
            padding: 16px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            position: relative;
            transition: background 0.18s;
        }

        .badge-earned:hover {
            background: var(--color-background-secondary);
        }

        .badge-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .badge-icon-locked {
            background: var(--color-background-secondary);
            border: 1.5px dashed var(--color-border-secondary);
            font-size: 18px;
        }

        .badge-earned-tick {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #10B981;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-earned-tick svg {
            width: 9px;
            height: 9px;
        }

        .badge-name {
            font-size: 11px;
            font-weight: 600;
            text-align: center;
            color: var(--color-text-primary);
            line-height: 1.3;
        }

        .badge-desc {
            font-size: 10px;
            text-align: center;
            color: var(--color-text-secondary);
            line-height: 1.3;
        }

        .badge-locked .badge-name,
        .badge-locked .badge-desc {
            opacity: 0.5;
        }

        /* Badges style */
                </style>