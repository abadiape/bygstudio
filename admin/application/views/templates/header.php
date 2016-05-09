<!DOCTYPE HTML>
<html http-equiv="Content-Type" content="text/html; charset=utf-8" lang="es">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
        <code>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
            <script src="<?=base_url('trackpad-scroll/jquery.trackpad-scroll-emulator.min.js') ?>"></script>
            <script type="text/javascript" src=<?=base_url("fancybox/lib/jquery.mousewheel-3.0.6.pack.js"); ?>></script>
            <script type="text/javascript" src=<?=base_url("fancybox/source/jquery.fancybox.pack.js?v=2.1.5"); ?>></script>
            <script src=<?=base_url("ckeditor/ckeditor.js"); ?>></script>
            <script src=<?=base_url("bootstrap-3.3.5/js/bootstrap.min.js");?>></script>
            <link rel="stylesheet" href=<?=base_url("bootstrap-3.3.5/css/bootstrap.min.css");?>>
            <link rel="stylesheet" type="text/css" href=<?=base_url("css/byg_admin.css");?> />
            <link rel="stylesheet" type="text/css" href=<?=base_url("css/menu_admin.css");?> />
            <link rel="stylesheet" href="<?=base_url('trackpad-scroll/css/trackpad-scroll-emulator.css') ?>" />
            <link rel="stylesheet" href="<?=base_url('css/scrolls.css') ?>" />
            <link rel="stylesheet" href=<?=base_url("fancybox/source/jquery.fancybox.css?v=2.1.5"); ?> type="text/css" media="screen" />
            <link rel="stylesheet" href="<?=base_url('css/clientslist.css');?>">
        </code>
        <title>
            <?php echo $title ?>
        </title>
        <noscript>
            <style>
              /**
               * Reinstate scrolling for non-JS clients
               * 
               * You coud do this in a regular stylesheet, but be aware that
               * even in JS-enabled clients the browser scrollbars may be visible
               * briefly until JS kicks in. This is especially noticeable in IE.
               * Wrapping these rules in a noscript tag ensures that never happens.
               */
              .tse-scrollable {
                overflow-y: scroll;
              }
            </style>
        </noscript>
    </head>
    <body>