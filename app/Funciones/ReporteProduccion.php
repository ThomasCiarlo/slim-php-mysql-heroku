<?php

require "PDF.php";

class ReporteProduccion
{

    public function GenerarPdf($request, $response, $args)
    {

        $resultado = Produccion::obtenerTodos();

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();


        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(70, 6, 'empleadoNombre', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'producto', 1, 0, 'C', 1);
        $pdf->Cell(70, 6, 'id Pedido', 1, 1, 'C', 1);

        $pdf->SetFont('Arial', '', 10);

        foreach ($resultado as $row) {
            $pdf->Cell(70, 6, utf8_decode($row->empleadoNombre), 1, 0, 'C');
            $pdf->Cell(20, 6, $row->producto, 1, 0, 'C');
            $pdf->Cell(70, 6, utf8_decode($row->idPedido), 1, 1, 'C');
        }
        $pdf->Output();
    }
}
