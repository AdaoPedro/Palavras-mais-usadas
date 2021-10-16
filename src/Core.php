<?php
    namespace APDevs\MyApp {

        use React\Promise\Promise;

        function lerDiretorios(null|string $caminho = null): mixed {
            $caminho = $caminho ?: dirname(__DIR__) . '/legendas';
            return new Promise( function($resolve, $reject) use ($caminho) {
                $arquivos = scandir($caminho);
                if(!$arquivos) {
                    $reject(new \Exception('Oops! Nao conseguimos ler o directorio.'));
                }
                $arquivos = array_map(array: $arquivos, callback: fn($item) => ($caminho . '/' . $item));
                $resolve($arquivos);
            }, function(){
                throw new \Exception('Oops! Houve um erro.');
            } );
        }

        function elementosQueTerminamCom(string $padrao): \Closure {
            return function(array $array) use ($padrao): array {
                return array_filter($array, function(string $item) use ($padrao){
                    return str_ends_with($item, $padrao);
                });
            };
        }

        function lerConteudoDe(string $caminho) {
            return new Promise(function($resolve, $reject) use ($caminho){
                try {
                    $conteudo = file_get_contents($caminho);
                    $resolve($conteudo);
                } catch(\Exception $ex) {
                    $reject($ex);
                }
            }, fn() => throw new \Exception('Oops! Houve um erro.'));
        }

        function lerTodosArquivos(array $array): \React\Promise\Promise {
            $promises = [];
            $promises = array_map(function(string $caminho){
                return lerConteudoDe($caminho);
            }, $array);
            return \React\Promise\all($promises);
        }

        function unirElementos(array $array): string {
            return implode(PHP_EOL, $array);
        }

        function separarStringPor(string $delimitador): \Closure {
            return function(string $entry) use ($delimitador): array {
                return explode($delimitador, $entry);
            };
        }

        function filtrarElementosNaoNumericos(array $array) {
            return array_filter(callback: fn($el) => !is_numeric($el), array: $array);
        }

        function filtrarElementosSem(string $padrao): \Closure {
            return function(array $array) use ($padrao): array {
                return array_filter(callback: fn($el) => !str_contains($el, $padrao), array: $array);
            };
        }

        function filtrarElementosNaoVazios(array $array): array {
            return array_filter(callback: function($el){
                if(!(ctype_space($el) || $el === " " || $el == null || $el === "")){
                    return $el;
                }
            }, array: $array);
        }

        function eliminarSimbolos(array $simbolos) {
            return function(array $array) use ($simbolos) {
                return array_map(array: $array, callback: function($el) use ($simbolos) {
                    $novoElemento = $novoElemento ?? $el;
                    foreach($simbolos as $simbolo){
                        $novoElemento = str_ireplace($simbolo, '', $novoElemento);
                    }
                    return $novoElemento;
                });
            };
        }

        function agruparElementos(array $array){
            return array_reduce($array, function($agrupamento, $item){
                $atributo = trim(strtolower($item));
                if($agrupamento[$atributo]) {
                    $agrupamento[$atributo]['qtde'] += 1;
                } else {
                    $agrupamento = [...$agrupamento, $atributo => ['elemento'=>$atributo, 'qtde' => 1]];
                }
                return $agrupamento;
            }, []);
        }

        function arrayToPrettyJonFile(string $filename): \Closure {
            return function(array $array) use ($filename): bool {
                $file = fopen($filename, 'w');
                if(!$file){
                    throw new \Exception('Erro. Nao foi possivel criar o arquivo');
                    return false;
                }
                fwrite($file, json_encode($array, JSON_PRETTY_PRINT));
                fclose($file);
                return true;
            };
        }
        
    }


    