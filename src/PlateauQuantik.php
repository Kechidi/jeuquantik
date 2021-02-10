<?php 

    namespace quantik\src;
    
    require_once("../src/PieceQuantik.php");
    
    use quantik\src\PieceQuantik;
    use Exception;
    

    class PlateauQuantik {
    
        public const NB_ROWS = 4;
        public const NB_COLS = 4;
        public const NW = 0;
        public const NE = 1;
        public const SW = 2;
        public const SE = 3;
        
        protected $cases;

        public function __construct() 
        {
            $this->cases = array(array());
            for ( $i = 0; $i < self::NB_ROWS; $i++ ) 
                for ( $y = 0; $y < self::NB_COLS; $y++ )
                    $this->cases[$i][$y] = PieceQuantik::initVoid();
        }
       

        public function getPiece(int $rowNum, int $colNum): PieceQuantik 
        {
            self::checkBounds($rowNum, $colNum);
            return $this->cases[$rowNum][$colNum];
        }
        

        public function setPiece(int $rowNum, int $colNum, PieceQuantik $p) 
        {
            self::checkBounds($rowNum, $colNum);
            $this->cases[$rowNum][$colNum] = $p;
        }

        public function getRow(int $rowNum): array
        {
            self::checkBounds($rowNum, 0);
            return $this->cases[$rowNum];
        }
        

        public function getCol(int $colNum): array
        {
             self::checkBounds(0, $colNum);
             $col = array();
             for($i = 0; $i < self::NB_COLS; $i++)
                $col[$i] = $this->cases[$i][$colNum];
             return $col;
        }
        

        public function getCorner(int $dir): array 
        {
            self::checkDir($dir);
            switch($dir) 
            {
                case self::NW:
                    return [ $this->cases[0][0], $this->cases[0][1], $this->cases[1][0], $this->cases[1][1] ]; 
                    
                case self::NE: 
                    return [ $this->cases[0][2], $this->cases[0][3], $this->cases[1][2], $this->cases[1][3] ];
                    
                case self::SW:
                    return [ $this->cases[2][0], $this->cases[2][1], $this->cases[3][0], $this->cases[3][1] ];
                    
                case self::SE: 
                    return [ $this->cases[2][2], $this->cases[2][3], $this->cases[3][2], $this->cases[3][3] ];
            }

        }
        

        public function __toString(): string 
        {
            $res = "";
            for ( $i = 0; $i < self::NB_ROWS; $i++ )
                $res .= "|-----------------|";
                
            $res .= "\n";
            
            for ( $i = 0; $i < self::NB_ROWS; $i++ ) {
                for ( $y = 0; $y < self::NB_COLS; $y++ ) {
                    $piece = $this->cases[$i][$y];
                    $res .= "|" . sprintf(" %-16s",$piece) . "|";
                }
                $res .= "\n";
            }
            
            for ( $i = 0; $i < self::NB_ROWS; $i++ )
                $res .= "|-----------------|";
                
            return $res;
        }
        

        public static function getCornerFromCoord(int $rowNum, int $colNum): int {
            self::checkBounds($rowNum, $colNum);
            
            if ( $rowNum <  self::NB_ROWS/2 && $colNum <  self::NB_COLS/2 ) return self::NW;
            if ( $rowNum <  self::NB_ROWS/2 && $colNum >= self::NB_COLS/2 ) return self::NE;
            if ( $rowNum >= self::NB_ROWS/2 && $colNum <  self::NB_COLS/2 ) return self::SW;
            if ( $rowNum >= self::NB_ROWS/2 && $colNum >= self::NB_COLS/2 ) return self::SE;
        }

        private static function checkBounds(int $rowNum, int $colNum): void {
            if ( $rowNum < 0 || $rowNum >= self::NB_ROWS ||
                 $colNum < 0 || $colNum >= self::NB_COLS   ) throw new \Exception("les coordonées ne sont pas dans  plateau\n");
        }

        private static function checkDir(int $dir) {
            if ( $dir < 0 && $dir > self::SE ) throw new \Exception("Direction incorect\n");
        }
    }
?>
