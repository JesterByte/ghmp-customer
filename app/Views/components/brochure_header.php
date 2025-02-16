<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <script src="<?= base_url("js/color-modes.js") ?>"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title><?= $pageTitle . " - Green Haven Memorial Park" ?></title>
    <link rel="shortcut icon" href="<?= base_url("favicon.ico") ?>" type="image/x-icon">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/carousel/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="<?= base_url("css/bootstrap.min.css") ?>" rel="stylesheet">
    <link href="<?= base_url("css/brochure_head.css") ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= base_url("css/carousel.css") ?>" rel="stylesheet">
</head>
<body>
  <?php 
    // include_once COMPONENTS_PATH . "theme.php";
    // include_once COMPONENTS_PATH . "navbar.php";
    echo $this->include("components/theme");
    echo $this->include("components/navbar");
  ?>