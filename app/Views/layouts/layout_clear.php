<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$pageTitle</title>
    <?= $this->renderSection("custom_css") ?>
</head>
<body>
    <main>
      <?= $this->renderSection("content") ?>
    </main>
    <?= $this->getCSRFDefaultScript() ?>
    <script src="/js/common.js"></script>
    <?= $this->renderSection("custom_scripts") ?>
</body>

</html>