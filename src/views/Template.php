<?php

declare(strict_types=1);
/**
 * Variables below are defined in controllers/Controller.php.
 * @var string $template_tags Additional tags for template
 * @var string $template_title Title of web page
 * @var string $template_content HTML content of web page
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description"
          content="Welcome to Steamy Sips Café, where every sip is an experience. Step into our cozy world of aromatic delights, where the perfect brew meets community and conversation."/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <link rel="icon"
          href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🐞</text></svg>"/>

    <!-- start of styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
          integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/styles/global.css"/>
    <!-- end of styles -->

    <!-- start of scripts -->

    <!-- theme switcher-->
    <script src="<?= ROOT ?>/js/minimal-theme-switcher.js" defer></script>

    <!-- shopping cart modal-->
<!--    <script src="--><?php //= ROOT ?><!--/js/modal.js" defer></script>-->

    <!--    scroll animations-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
            integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- end of scripts -->

    <?= $template_tags ?>
    <title><?= $template_title ?></title>
</head>
<body>

<nav class="container-fluid">
    <ul>
        <li>
            <a href="<?= ROOT ?>/" class="contrast">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M3 14c.83 .642 2.077 1.017 3.5 1c1.423 .017 2.67 -.358 3.5 -1c.83 -.642 2.077 -1.017 3.5 -1c1.423 -.017 2.67 .358 3.5 1"/>
                    <path d="M8 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2"/>
                    <path d="M12 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2"/>
                    <path d="M3 10h14v5a6 6 0 0 1 -6 6h-2a6 6 0 0 1 -6 -6v-5z"/>
                    <path d="M16.746 16.726a3 3 0 1 0 .252 -5.555"/>
                </svg>
                <h4>steamy sips</h4>
            </a>
        </li>
    </ul>
    <ul>
        <li>
            <a href="<?= ROOT ?>/shop" class="contrast" data-tooltip="Shop" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/cart" class="contrast" data-tooltip="Shopping cart" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                    <path d="M17 17h-11v-14h-2"/>
                    <path d="M6 5l14 1l-1 7h-13"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="<?= ROOT ?>/profile" class="contrast" data-tooltip="Profile" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                </svg>
            </a>
        </li>
    </ul>
</nav>

<?= $template_content ?>

<footer id="page-footer" class="container-fluid">
    <small class="secondary">
        © <?= date("Y") ?> Steamy Sips Café
    </small>
</footer>
</body>
</html>