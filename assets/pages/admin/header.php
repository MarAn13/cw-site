<?php
$link = $_SERVER['REQUEST_URI'];
$link = explode('/', $link);
$link = end($link);
$link = explode('?', $link)[0];
if ($link == 'account.php') {
    $link_add = "admin/";
} else {
    $link_add = false;
}
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="
            <?php
            echo $link_add;
            ?>reportHistory.php">StreamMAP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a href="<?php
                            if ($link === 'account.php') {
                                echo $link;
                            } else {
                                echo "../account.php";
                            }
                            ?>" class="ms-auto">
                    <button type="button" class="btn btn-floating text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                    </button>
                </a>
                <a href="<?php
                            if ($link === 'account.php') {
                                echo 'php/signOut.inc.php';
                            } else {
                                echo "../php/signOut.inc.php";
                            }
                            ?>">
                    <button type="button" class="btn btn-floating text-white" id="signOutButton">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-door-closed-fill" viewBox="0 0 16 16" id="signOutClose">
                            <path d="M12 1a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2a1 1 0 0 1 1-1h8zm-2 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-door-open-fill d-none" viewBox="0 0 16 16" id="signOutOpen">
                            <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2h.5a.5.5 0 0 1 .5.5V15h-1V2zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z" />
                        </svg>
                    </button>
                </a>
            </div>
        </div>
    </nav>
</header>