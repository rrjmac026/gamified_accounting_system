<?php

namespace App\PDF;

use FPDF;

class LeaderboardPDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 10, 'Leaderboard Report', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    public function generateLeaderboard($ranked, $periodType, $generatedAt)
    {
        $this->AliasNbPages();
        $this->AddPage();
        
        // Period and Generation Time
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, "Period: $periodType", 0, 1);
        $this->Cell(0, 10, "Generated at: $generatedAt", 0, 1);
        $this->Ln(5);

        // Table Header
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(10, 10, '#', 1);
        $this->Cell(60, 10, 'Student Name', 1);
        $this->Cell(40, 10, 'Course', 1);
        $this->Cell(40, 10, 'XP Earned', 1);
        $this->Cell(40, 10, 'Tasks Done', 1);
        $this->Ln();

        // Table Content
        $this->SetFont('Arial', '', 11);
        $rank = 1;
        foreach ($ranked as $entry) {
            $this->Cell(10, 10, $rank++, 1);
            $this->Cell(60, 10, $entry['Student Name'], 1);
            $this->Cell(40, 10, $entry['Course'], 1);
            $this->Cell(40, 10, $entry['Total XP'], 1);
            $this->Cell(40, 10, $entry['Tasks Completed'], 1);
            $this->Ln();
        }
    }
}
