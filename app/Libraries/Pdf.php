<?php

namespace App\Libraries;

require_once APPPATH . "ThirdParty/tcpdf/tcpdf.php";

class Pdf extends \TCPDF {

    private $pdf_type;

    public function __construct($pdf_type = '') {
        parent::__construct();

        $this->pdf_type = $pdf_type;
        $this->SetFontSize(10);
    }
    protected $invoice_data;

    public function setInvoiceData($data) {
        $this->invoice_data = $data;
    }
    public function Header() {
        if ($this->pdf_type == 'invoice') {
            $client_info = isset($this->invoice_data['client_info']) ? $this->invoice_data['client_info'] : null;

            if (isset($client_info->company_id)) {
                if ($client_info->company_id == 1) {
                    $color = get_setting("estimate_color_pixel");
                } elseif ($client_info->company_id == 2) {
                    $color = get_setting("estimate_color_solution");
                } else {
                    $color = get_setting("estimate_color");
                }
            } else {
                $color = get_setting("estimate_color");
            }
            
            if (!$color) {
                $color = get_setting("invoice_color") ? get_setting("invoice_color") : "#2AA384";
            }
       
            $break_margin = $this->getBreakMargin();
            $auto_page_break = $this->AutoPageBreak;
            $this->SetAutoPageBreak(false, 0);

            $img_file = get_file_from_setting("invoice_pdf_background_image", false, get_setting("timeline_file_path"));
            $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 500, '', false, false, 0);

            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $break_margin);
            $data = '
            <div style="background-color: '.$color.';
                        color: white;
                        font-size: 24px;
                        font-weight: bold;
                        width: 100%;
                        padding: 1px 2px;
                        line-height: 81px;
                        height: 42px;
                        
            padding: 100px;">
            </div>
            
            <br><br><br> 
';
            
            $this->writeHTMLCell(0, 0, '', '', $data, 0, 1, 0, true, '', true);
        
        } else {
            // call the original Header method from the parent class
            parent::Header();
        }
    }


    public function Footer() {
        if ($this->pdf_type == 'invoice') {
            $client_info = isset($this->invoice_data['client_info']) ? $this->invoice_data['client_info'] : null;
    
            if (isset($client_info->company_id)) {
                if ($client_info->company_id == 1) {
                    $color = get_setting("estimate_color_pixel");
                } elseif ($client_info->company_id == 2) {
                    $color = get_setting("estimate_color_solution");
                } else {
                    $color = get_setting("estimate_color");
                }
            } else {
                $color = get_setting("estimate_color");
            }
    
            if (!$color) {
                $color = get_setting("invoice_color") ? get_setting("invoice_color") : "#2AA384";
            }
    
            $data = '
            <div style="background-color: '.$color.';
                        color: white;
                        font-size: 24px;
                        font-weight: bold;
                        width: 100%;
                        padding: 1px 2px;
                        line-height: 51;
                        height: 2px;
                        
            padding: 100px;">
            </div>
            
            <br><br><br> 
            ';
    
            $this->SetY(-5); 
            
            $this->writeHTMLCell(0, 0, '', '', $data, 0, 1, 0, true, '', true);
            
        } else {
            parent::Footer();
        }
    }
}
