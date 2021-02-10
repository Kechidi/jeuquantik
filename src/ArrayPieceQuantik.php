<?php

    namespace quantik\src;
    
    require_once("../src/PieceQuantik.php");
    
    use quantik\src\PieceQuantik;
    
    class ArrayPieceQuantik {
        protected $pieceQuantiks;
        protected $taille;
        
        private function __construct() {
            $this->taille = 0;
            $this->pieceQuantiks = array();
        }
        
        public function __toString(): string {
          $str = "";
          foreach($this->pieceQuantiks as $p) {
            $str = $p->__toString();
          }
          return $str;
        }
        
        public function getPieceQuantik(int $pos): PieceQuantik {
            $this->checkPos($pos);
            return $this->pieceQuantiks[$pos];
        }
        
        public function setPieceQuantik(int $pos, PieceQuantik $pieceQuantik) {
            $this->pieceQuantiks[$pos] = $pieceQuantik;
            $this->taille++;
        }
        
        public function addPieceQuantik(PieceQuantik $pieceQuantik) {
            array_push($this->pieceQuantiks, $pieceQuantik);
            $this->taille++;
        }
        
        public function removePieceQuantik(int $pos) {
            $this->checkPos($pos);
            array_splice($this->pieceQuantiks, $pos, 1);
            $this->taille--;
        }
        
        public function getTaille():int {
            return $this->taille;
        }
        
        public static function initPiecesNoires(): self {
            $arrayPieceQuantik = new ArrayPieceQuantik();
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initBlackSphere());
            return $arrayPieceQuantik;
        }
        
        public static function initPiecesBlanches(): self {
            $arrayPieceQuantik = new ArrayPieceQuantik();
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCube());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCone());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());
            $arrayPieceQuantik->addPieceQuantik(PieceQuantik::initWhiteSphere());
            return $arrayPieceQuantik;
        }
        
        private function checkPos(int $pos) {
            if ( $pos < 0 || $pos >= $this->taille ) throw new \Exception("Les positions des pieces ne sont pas valides");
        }
        
    }
?>
