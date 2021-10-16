<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_WARNING);

    require_once(__DIR__ . '/vendor/autoload.php');

    const SIMBOLOS = [
        '.', '?', '-', ',', '"', '♪', '_', '<i>', '</i>', '\r', '[', ']', '(', ')', '%', '!', ':',
    ];

    APDevs\MyApp\lerDiretorios()
        ->then(APDevs\MyApp\elementosQueTerminamCom('.srt')(...))
        ->then(APDevs\MyApp\lerTodosArquivos(...))
        ->then(APDevs\MyApp\unirElementos(...))
        ->then(APDevs\MyApp\separarStringPor(PHP_EOL)(...))
        ->then(APDevs\MyApp\filtrarElementosNaoNumericos(...))
        ->then(APDevs\MyApp\filtrarElementosSem('-->')(...))
        ->then(APDevs\MyApp\filtrarElementosNaoVazios(...))
        ->then(APDevs\MyApp\eliminarSimbolos(SIMBOLOS)(...))
        ->then(APDevs\MyApp\filtrarElementosSem('</font>')(...))
        ->then(APDevs\MyApp\filtrarElementosNaoNumericos(...))
        ->then(APDevs\MyApp\unirElementos(...))
        ->then(APDevs\MyApp\separarStringPor(' ')(...))
        ->then(APDevs\MyApp\filtrarElementosNaoVazios(...))
        ->then(APDevs\MyApp\unirElementos(...))
        ->then(APDevs\MyApp\separarStringPor(PHP_EOL)(...))
        ->then(APDevs\MyApp\filtrarElementosNaoVazios(...))
        ->then(APDevs\MyApp\agruparElementos(...))
        ->then(APDevs\MyApp\arrayToPrettyJonFile(__DIR__ . '/saida.json')(...), print_r(...))
        ->then(print_r(...), print_r(...));

        