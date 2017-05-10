<?php

namespace Jimmyjs\ReportGenerator\ReportMedia;

use Jimmyjs\ReportGenerator\ReportGenerator;

class PdfReport extends ReportGenerator
{
	public function make()
	{
		$headers = $this->headers;
		$query = $this->query;
		$columns = $this->columns;
		$limit = $this->limit;
		$groupByArr = $this->groupByArr;
		$orientation = $this->orientation;
		$editColumns = $this->editColumns;
		$showTotalColumns = $this->showTotalColumns;
		$styles = $this->styles;

		$html = \View::make('report-generator-view::general-pdf-template', compact('headers', 'columns', 'editColumns', 'showTotalColumns', 'styles', 'query', 'limit', 'groupByArr', 'orientation'))->render();

		try {
			$pdf = \App::make('snappy.pdf.wrapper');
			$pdf->setOption('footer-font-size', 10);
			$pdf->setOption('footer-left', 'Page [page] of [topage]');
			$pdf->setOption('footer-right', 'Date Printed: ' . date('d M Y H:i:s'));
		} catch (\ReflectionException $e) {
			$pdf = \App::make('dompdf.wrapper');
		}

		return $pdf->loadHTML($html)->setPaper($this->paper, $orientation);
	}

	public function stream()
	{
		return $this->make()->stream();
	}

	public function download($filename)
	{
		return $this->make()->download($filename . '.pdf');
	}
}