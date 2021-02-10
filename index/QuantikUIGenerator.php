<?php

require_once "../src/PlateauQuantik.php";
require_once "../src/ArrayPieceQuantik.php";

use quantik\src\PieceQuantik;
use quantik\src\PlateauQuantik;
use quantik\src\ActionQuantik;
use quantik\src\ArrayPieceQuantik;


class QuantikUIGenerator
{


    public static function getDebutHTML(string $title = "Jeu Quantik"): string{
        return "<!doctype html>
<html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>$title</title>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"quantik.css\" />
        
    </head>
    <body>
        
        <h1 class=\"quantik-title\">$title</h1>
  
        <div class='quantik'>\n";
    }


    public static function getFinHTML(): string
    {
        return "</div></body>\n</html>";
    }

    public static function getPageErreur(string $message): string
    {
        header("HTTP/1.1 400 Bad Request");
        $resultat = self::getDebutHTML("400 Bad Request");
        $resultat .= "<h2>$message</h2>";
        $resultat .= "<p><br /><br /><br /><a href='quantik.php?reset'>acceuil</a></p>";
        $resultat .= self::getFinHTML();
        return $resultat;
    }


    public static function getButtonClass(PieceQuantik $pq) {
        if ($pq->getForme()==PieceQuantik::VOID)
            return "vide";
        $ch = $pq->__toString();
        return substr($ch,1,2).substr($ch,4,1);
    }

    public static function getDivPlateauQuantik(PlateauQuantik $p): string
    {
        $resultat ="";
        $resultat = "<table>\n";
        for ( $i = 0; $i < PlateauQuantik::NB_ROWS; $i++ ) {
            $resultat .= "<tr>\n";
            for ($y = 0; $y < PlateauQuantik::NB_COLS; $y++ ) {
                $resultat .= "<td><button class=\"quantik-btn\" type=\"submit\" name=\"active\" disabled>".$p->getPiece($i, $y)."</button></td>\n";
            }
            $resultat .= "</tr>\n";
        }
        $resultat .= "</table>\n";
        return $resultat;
    }


    public static function getDivPiecesDisponibles(ArrayPieceQuantik $apq, int $pos = -1): string {
        $resultat = "";
        $resultat = "<div class=\"quantikDispo\">\n";
        for($i = 0; $i < $apq->getTaille(); $i++) {
            $class = "quantik-btn";
            if ( $pos != -1 && $pos == $i ) $class .= " quantik-btn-selected";
            $resultat .= "<button class=\"$class\" type=\"submit\" name=\"active\" disabled>". $apq->getPieceQuantik($i) ."</button>\n";
        }
        $resultat .= "</div>\n";
        return $resultat;
    }

    public static function getFormSelectionPiece(ArrayPieceQuantik $apq): string {
        $resultat = "<form><div>";
        for($i = 0; $i < $apq->getTaille(); $i++)
            $resultat .= "<button class=\"quantik-btn\" type=\"submit\" value=\"$i\" name=\"active\">". $apq->getPieceQuantik($i) ."</button>";
        $resultat .= "<input type=\"hidden\" name=\"action\" value=\"choisirPiece\" />";
        $resultat .= "</div></form>";
        return $resultat;
    }


    public static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece, int $position): string {
        $resultat ="";
        $action = new ActionQuantik($plateau);
        $resultat = "<form>";
        $resultat .= "<table>";
        for ( $i = 0; $i < PlateauQuantik::NB_ROWS; $i++ ) {
            $resultat .= "<tr>";
            for ($y = 0; $y < PlateauQuantik::NB_COLS; $y++ ) {
                if ( $action->isValidePose($i, $y, $piece) )
                    $resultat .= "<td><button class=\"quantik-btn\" type=\"submit\" name=\"active\" value=\"$i-$y\">".$plateau->getPiece($i, $y)."</button></td>";
                else
                    $resultat .= "<td><button class=\"quantik-btn\" type=\"submit\" name=\"active\" disabled>".$plateau->getPiece($i, $y)."</button></td>";
            }
            $resultat .= "</tr>";
        }
        unset($action);
        $resultat .= "</table>";
        $resultat .= "<input type=\"hidden\" name=\"action\" value=\"poserPiece\" />";
        $resultat .= "<input type=\"hidden\" name=\"positionPiece\" value=\"$position\" />";
        $resultat .="</form>";
        $resultat .= self::getFormBoutonAnnuler();
        return $resultat;
    }

    public static function getFormBoutonAnnuler() : string {
        return "<form style=\"text-align: center;\"><button class=\"quantik-btn quantik-btn-undo\" type=\"submit\" name=\"action\" value=\"annulerChoix\">Annuler choix</button></form>";
    }

    public static function getDivMessageVictoire(int $couleur): string {   
        $couleurGagnant = ($couleur == PieceQuantik::WHITE)?'BLANC':'NOIR';
        $resultat = "<h1 class=\"victoire\">le gagnant est de la couleur : " . $couleurGagnant . "</h1>";
        return $resultat;
    }

    public static function getLienRecommencer(): string {
        return "<p class=\"recommencer\"><a href='?reset'> voulez vous recommencer la partie  ?</a></p>";
    }


    public static function getPageSelectionPiece(array $lesPiecesDispos, int $couleurActive, PlateauQuantik $plateau): string {
        $pageHTML = self::getDebutHTML();
        
        $piecesActives = $lesPiecesDispos[$couleurActive];
        unset($lesPiecesDispos[$couleurActive]);
        $pieceNonActive = array_shift($lesPiecesDispos);
        $pageHTML .="<div id=\"pieceDispo\">".self::getDivPiecesDisponibles($pieceNonActive)."</div>";
        $pageHTML .= "<div id=\"plateau\">".self::getDivPlateauQuantik($plateau)."</div>";
        $pageHTML .= "<div id=\"pieceNonDispo\">".self::getFormSelectionPiece($piecesActives)."</div>";

        return $pageHTML. self::getFinHTML();
    }


    public static function getPagePosePiece(array $lesPiecesDispos, int $couleurActive, int $posSelection, PlateauQuantik $plateau): string {
        $pageHTML = self::getDebutHTML();

        $piecesActives = $lesPiecesDispos[$couleurActive];
        array_splice($lesPiecesDispos, $couleurActive, 1);
        $pieceNonActive = array_shift($lesPiecesDispos);

        
        $pieceAPoser = $piecesActives->getPieceQuantik($posSelection);
        
        $pageHTML .="<div id=\"pieceDispo\">". self::getDivPiecesDisponibles($pieceNonActive)."</div>";
        $pageHTML .= "<div id=\"plateau\">".self::getFormPlateauQuantik($plateau, $pieceAPoser, $posSelection)."</div>";
        $pageHTML .= "<div id=\"pieceNonDispo\">".self::getDivPiecesDisponibles($piecesActives, $posSelection)."</div>";
        
        return $pageHTML . self::getFinHTML();
    }


    public static function getPageVictoire(array $lesPiecesDispos, int $couleurActive, PlateauQuantik $plateau): string {
        $pageHTML = self::getDebutHTML();

        $pageHTML .= self::getDivMessageVictoire($couleurActive);
        $pageHTML .= self::getLienRecommencer();

        $piecesActives = $lesPiecesDispos[$couleurActive];
        unset($lesPiecesDispos[$couleurActive]);
        $pieceNonActive = array_shift($lesPiecesDispos);
        
        $pageHTML .= self::getDivPiecesDisponibles($pieceNonActive);
        $pageHTML .= self::getDivPlateauQuantik($plateau);
        $pageHTML .= self::getDivPiecesDisponibles($piecesActives);
        
        return $pageHTML . self::getFinHTML();

    }

}
