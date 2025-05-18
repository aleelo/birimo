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
            $company_info = isset($this->invoice_data['company_info']) ? $this->invoice_data['company_info'] : null;


        $company_id = isset($client_info->company_id) ? $client_info->company_id : null;

        // Use company-specific settings
        $color = isset($company_info->invoice_color) ? $company_info->invoice_color : "#2AA384";
        $img_file = isset($company_info->invoice_pdf_background_image) ? WRITEPATH . $company_info->invoice_pdf_background_image : null;

         
            
        
            $break_margin = $this->getBreakMargin();
            $auto_page_break = $this->AutoPageBreak;
            $this->SetAutoPageBreak(false, 0);

            $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 500, '', false, false, 0);

            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $break_margin);
            
        } else {
            // call the original Header method from the parent class
            parent::Header();
        }
    }


    public function Footer() {
        if ($this->pdf_type == 'invoice') {
            $client_info = isset($this->invoice_data['client_info']) ? $this->invoice_data['client_info'] : null;
            $company_info = isset($this->invoice_data['company_info']) ? $this->invoice_data['company_info'] : null;

    
                 $color = isset($company_info->invoice_color) ? $company_info->invoice_color : "#2AA384";

    
            $data = '
            <div style="background-color: '.$color.';
                        height: 10px;
                        width: 100%;">
            </div>';
            
            $data2 = '
            
            <div style="background-color: '.$color.';
                        color: white;
                        font-size: 24px;
                        font-weight: bold;
                        width: 100%;
                        padding: 6px 12px;
                        line-height: 51;
                        height: 2px;
                        
                        
           ">
            </div>
            
            <br><br><br> 
            ';
    
            $this->SetY(0); 
            $this->setX(-0);
            
            $this->writeHTMLCell(0, 0, '', '', $data, 0, 1, 0, true, '', true);
            $this->SetY(-5); 

            $this->writeHTMLCell(0, 0, '', '', $data2, 0, 1, 0, true, '', true);

        } else {
            parent::Footer();
        }
    }
}
