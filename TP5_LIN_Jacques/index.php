<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    echo "Ah bien, PHP fonctionne !\n";

    date_default_timezone_set('Europe/Paris');

    echo date('d/m/Y H:i');
    ?>
</body>

</html>