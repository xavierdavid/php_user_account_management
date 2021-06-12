<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $page_description; ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title; ?></title>
    
    <!-- CSS link -->
    <link rel="stylesheet" href="<?= URL ?>public/css/style.css">
    <?php if(!empty($page_css)) {
        // Si d'autres fichiers css existent, on effectue le lien pour chacun d'entre-eux
        foreach($page_css as $file_css) { ?>
            <link rel="stylesheet" href="<?= URL ?>public/css/<?= $file_css ?>">
        <?php }
    } ?>

    <!-- Fontawesome link -->
        <link rel="stylesheet" href="<?= URL ?>public/fontawesome/css/all.css">
</head>

<body>
    <!-- Header -->
    <?php require_once("views/common/_partials/header.php"); ?>

    <!-- Main container -->
    <main class="content">
    <?php 
        // Affichage des alertes stockées dans la session si elles existent
        if(!empty($_SESSION['alert'])) {
            // On parcourt le tableau de la session alertes 
            foreach($_SESSION['alert'] as $alert) { ?>
                <div class="<?= $alert['type']; ?>" role="alert">
                    <p><?= $alert['message']; ?></p>
                </div>
            <?php  
            } 
            // On détruit la variable 'alert' de la session
            unset($_SESSION['alert']);  
        }
            // Affichage de la vue spécifique de la page
            echo $page_content;
        ?>
    </main>

    <!-- Footer -->
    <?php require_once("views/common/_partials/footer.php"); ?>

    <!-- Javascript link -->
    <script src="<?= URL ?>public/js/dropdown.js"></script>
    <script src="<?= URL ?>public/js/menu.js"></script>
    <?php if(!empty($page_js)) {
        // Si d'autres fichiers css existent, on effectue le lien pour chacun d'entre-eux
        foreach($page_js as $file_js) { ?>
            <script src="<?= URL ?>public/js/<?= $file_js ?>"></script>
        <?php }
    } ?>
    
</body>
</html>