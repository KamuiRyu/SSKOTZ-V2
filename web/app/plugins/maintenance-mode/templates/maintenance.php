<?php
/**
 * Maintenance mode template that's shown to logged out users.
 *
 * @package   maintenance-mode
 * @copyright Copyright (c) 2015, Ashley Evans
 * @license   GPL2+
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo plugins_url(
            'assets/css/style.css',
            dirname(__FILE__)
        ); ?>">
        <link rel="stylesheet" href="<?php echo plugins_url(
            'assets/lib/bootstrap/css/bootstrap.min.css',
            dirname(__FILE__)
        ); ?>" type="text/css" media="screen">
        <script type="application/javascript" src="<?php echo plugins_url(
            'assets/lib/jquery/jquery.min.js',
            dirname(__FILE__)
        ); ?>"></script>
        <script type="application/javascript" src="<?php echo plugins_url(
            'assets/lib/bootstrap/js/bootstrap.min.js',
            dirname(__FILE__)
        ); ?>"></script>
        <script type="application/javascript" src="<?php echo plugins_url(
            'assets/lib/popperjs/popper.min.js',
            dirname(__FILE__)
        ); ?>"></script>
        <script  src="<?php echo plugins_url(
            'assets/lib/jquery-countdown/jquery.plugin.js',
            dirname(__FILE__)
        ); ?>"></script>
        <script src="<?php echo plugins_url(
            'assets/lib/jquery-countdown/jquery.countdown.js',
            dirname(__FILE__)
        ); ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url(
            'assets/lib/jquery-countdown/jquery.countdown-pt-BR.js',
            dirname(__FILE__)
        ); ?>"></script>
        <script>
            $(function () {
                $.countdown.setDefaults($.countdown.regionalOptions['pt-BR']);
	            $('#defaultCountdown').countdown({until: new Date(2020, 4-1, 05)}); 
            });
        </script>
        <title>Estamos em manutenção!! < Saint Seiya Awakening</title>
    </head>

    <body>
        <header></header>
        <main class="h-100"> 
           <!--DARK OVERLAY-->
            <div class="overlay"></div>
            <!--/DARK OVERLAY-->

            <!--WRAP-->
            <div id="wrap">
                <!--CONTAINER-->
                <div class="container h-100">
                    <div class="row h-100">
                        <div class="col-12 m-auto">
                            <h1>
                                <span class="text-white">Nós estamos </span><br/>
                                <span class="text-white">elevando </span><span class="yellow">Nosso cosmo</span>
                            </h1>
                            <p class="text-white">Nosso site está <strong>em construção</strong>, mas estamos trabalhando duro<br/> para criar um design novo e atualizado.</p>
                            <div id="defaultCountdown"></div>
                            
                        </div>
                    </div>
                </div>
                <!--/CONTAINER-->
            </div>
            <!--/WRAP-->

        </main>

    </body>
</html>