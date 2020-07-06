<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
$flash = $this->Flash->render();
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= /** @var string $title */ $title ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <?= $this->Html->css('app.css') ?>

    <?= $this->Html->script('jquery.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarEexample">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">改定ちゃん</a>
            </div>
            <div class="collapse navbar-collapse" id="navbarEexample">
                <?php if (isset($pageList) and count($pageList)){ ?>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">メニュー <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php /** @var array $pageList */ foreach ($pageList as $page){ ?>
                                    <li>
                                        <a href="<?= $this->Url->build(['controller' => $page['controller'], 'action' => $page['method']]) ?>">
                                            <?= $page['name'] ?>
                                            <?php if (isset($page['notice'])){ ?>
                                                <span class="badge"><?= $page['notice'] ?></span>
                                            <?php } ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><?= $this->Html->link('ログアウト', 'users/logout', ['target' => '_self']) ?></li>
                </ul>
                <p class="navbar-text navbar-right">
                    ログイン中のユーザ: <?= /** @var string $loginUserName */ $loginUserName ?>
                </p>
            </div>
        </div>
    </nav>
</header>
<div id="container">
    <div class="page-header">
        <h1><?= $title ?></h1>
    </div>

    <?php if ($flash){ ?>
        <?= $flash ?>
    <?php } ?>

    <div id="content">
        <?= $this->fetch('content') ?>
    </div>
</div>
<footer>
</footer>
</body>
</html>
