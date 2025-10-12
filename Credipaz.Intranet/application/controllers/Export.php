<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
class Export extends MY_Controller {
    function __contruct(){
        parent::__construct();
    }
	
	function resumenLegal($id_operator_task){
        try {
			$OPERATORS_TASKS=$this->createModel(MOD_LEGAL,"operators_tasks","operators_tasks");
            $html=$OPERATORS_TASKS->buildResumenLegal($id_operator_task);
			$this->load->library("m_pdf");
			$this->m_pdf->pdf->useSubstitutions=false;
			$this->m_pdf->pdf->simpleTables=true;

			$this->m_pdf->pdf->WriteHTML($html, 2);
			//ob_end_clean();
			$this->m_pdf->pdf->Output("resumenLegal-".$id_operator_task.".pdf", "I");
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}

    function altamedicaArt($id_sinister,$mode,$id_user=null){
        try {
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
            $html=$SINISTERS->buildAltaMedica($id_sinister,$mode,$id_user);
			$this->load->library("m_pdf");
			$this->m_pdf->pdf->useSubstitutions=false;
			$this->m_pdf->pdf->simpleTables=true;

			$this->m_pdf->pdf->WriteHTML($html, 2);
			//ob_end_clean();
			$this->m_pdf->pdf->Output("altamedicaArt-".$id_sinister."-form0772.pdf", "I");
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}

    function altamedicalaboralArt($id_sinister,$mode,$id_user=null){
        try {
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
            $html=$SINISTERS->buildAltaMedicaLaboral($id_sinister,$mode,$id_user);
			$this->load->library("m_pdf");
			$this->m_pdf->pdf->useSubstitutions=false;
			$this->m_pdf->pdf->simpleTables=true;

			$this->m_pdf->pdf->WriteHTML($html, 2);
			//ob_end_clean();
			$this->m_pdf->pdf->Output("altamedicaArt-".$id_sinister."-form0772.pdf", "I");
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}

    function revisionArt($id_sinister,$nRevision,$mode,$id_user=null){
        try {
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
            $html=$SINISTERS->buildRevision($id_sinister,$nRevision,$mode,$id_user);
			$this->load->library("m_pdf");
			$this->m_pdf->pdf->WriteHTML($html, 2);
			//ob_end_clean();
			$this->m_pdf->pdf->Output("revisionArt-".$id_sinister."-".$nRevision."-form0104.pdf", "I");
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    function siniestroArt($id_sinister,$mode,$id_user=null){
        try {
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
            $html=$SINISTERS->buildSiniestro($id_sinister,$mode,$id_user);
			$this->load->library("m_pdf");
			$this->m_pdf->pdf->WriteHTML($html, 2);
			//ob_end_clean();
			$this->m_pdf->pdf->Output("siniestroArt-".$id_sinister."-form0069.pdf", "I");
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    function siniestrocasoleveArt($id_sinister,$mode,$id_user=null){
        try {
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
            $html=$SINISTERS->buildSiniestroCasoLeve($id_sinister,$mode,$id_user);
			$this->load->library("m_pdf");
			$this->m_pdf->pdf->WriteHTML($html, 2);
			//ob_end_clean();
			$this->m_pdf->pdf->Output("siniestroArt-".$id_sinister."-form0069.pdf", "I");
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
}
