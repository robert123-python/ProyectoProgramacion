    <?php
    use Parle\{Lexer, Parser, Token};

    class Libreria {
        private $lex;
        private $par;
        private $cont;
        private $exis;
        private $palabraBuscada;
        private $textoCompleto;

        public function __construct() {
            $this->par = new Parser();
            $this->par->token("PALABRA");
            $this->par->push("START", "COMANDO");
            $this->cont = $this->par->push("COMANDO", "PALABRA 'contar'");
            $this->exis = $this->par->push("COMANDO", "PALABRA 'existe'");
            $this->par->build();

            $this->lex = new Lexer();
            $this->lex->push("contar", $this->par->tokenId("'contar'"));
            $this->lex->push("existe", $this->par->tokenId("'existe'"));
            $this->lex->push("[a-zA-ZáéíóúÁÉÍÓÚñÑ]+", $this->par->tokenId("PALABRA"));
            $this->lex->push("[\s\.,\:\;\?]+", Token::SKIP);
            $this->lex->build();
        }

        public function crearGramatica($archivo) {
            if (!file_exists($archivo)) {
                throw new Exception("No se encontró el archivo $archivo");
            }
            $arch = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($arch as $linea) {
                $tokens = explode(' ', $linea);
                foreach ($tokens as $token) {
                    $token = trim($token);
                    if (!empty($token)) {
                        // Aquí puedes procesar cada token si lo deseas
                    }
                }
            }
        }

        public function leerArchivoEntrada($archivo) {
            if (!file_exists($archivo)) {
                throw new Exception("No se encontró el archivo $archivo");
            }
            $this->textoCompleto = file_get_contents($archivo);
            $lineas = explode("\n", $this->textoCompleto);

            if (count($lineas) < 2) {
                throw new Exception("El archivo debe tener al menos dos líneas");
            }
            $this->palabraBuscada = trim($lineas[1]);
        }

        public function contarPalabraBuscada() {
            $contador = 0;
            $palabra = strtolower($this->palabraBuscada);
            $lineas = explode("\n", $this->textoCompleto);
            $textoAnalizar = $lineas[0];

            $palabras = preg_split('/[\s\.,\:\;\?]+/', $textoAnalizar);
            foreach ($palabras as $p) {
                $p = strtolower(trim($p));
                if (!empty($p) && $p === $palabra) {
                    $contador++;
                }
            }

            echo "La palabra '{$this->palabraBuscada}' aparece $contador veces en el texto.\n";

            // Extra con lexer
            $contadorLexer = 0;
            $this->lex->consume($textoAnalizar);

            while ($this->lex->advance()) {
                $tokenId = $this->lex->getToken();
                $tokenText = $this->lex->getTokenText();

                if ($tokenId == $this->par->tokenId("PALABRA") &&
                    strtolower($tokenText) === $palabra) {
                    $contadorLexer++;
                }
            }
        }

        public function esperarComando() {
            echo "Presiona Ctrl+H para buscar palabra";

            $entrada = trim(fgets(STDIN));

            if ($entrada === "") {
                $this->contarPalabraBuscada();
            } else {
                echo "Comando no reconocido.\n";
            }
        }

        // ✅ MÉTODO PARA PROCESAR COMANDOS DE VOZ
        public function procesarComandoVoz($comando, $texto) {
            $this->textoCompleto = $texto;
            $this->palabraBuscada = $this->extraerPalabra($comando);

            if (str_contains($comando, "contar")) {
                $this->contarPalabraBuscada();
            } elseif (str_contains($comando, "existe")) {
                $this->verificarExistencia();
            } else {
                echo "Comando no reconocido desde la librería.\n";
            }
        }

        private function extraerPalabra($comando) {
            $partes = explode(" ", $comando);
            return trim(end($partes));
        }

        private function verificarExistencia() {
            $palabra = strtolower($this->palabraBuscada);
            $existe = stripos(strtolower($this->textoCompleto), $palabra) !== false;
            if ($existe) {
                echo "La palabra '{$this->palabraBuscada}' sí existe en el texto.\n";
            } else {
                echo "La palabra '{$this->palabraBuscada}' no se enc    ontró.\n";
            }
        }
    }
    ?>
