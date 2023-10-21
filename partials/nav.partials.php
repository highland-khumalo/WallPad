<?php
if (!isset($title)) {
    $title = $config['name'];
}
?>

<div class="navbar bg-base-100">

    <div class="navbar-start">
        <a href="/index.php" class="btn btn-ghost normal-case text-xl">
            <img src="logo.svg" class="h-12" alt="<?= $config['name'] ?> Logo">
            <span class="-ml-3"><?= $config['name'] ?></span>
        </a>
    </div>

    <div class="navbar-end">
        <div class="dropdown">
            <label tabindex="0" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content -ml-[100px] mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="/index.php">Homepage</a></li>
                <li><a href="/desktop.php">Desktop Wallpapers</a></li>
                <li><a href="/mobile.php">Mobile Wallpapers</a></li>
            </ul>
        </div>
    </div>

</div>