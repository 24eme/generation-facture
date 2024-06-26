<?php

namespace App\Lib;

use Exception;

class FactureLatex {

  private $idFacture = null;
  private $periode = null;
  private $facture = array();
  private $infosClient = array();
  private $infosCompany = array();
  private $infosExtra = array();
  private $twig = null;
  private $output_path = '';

  const OUTPUT_TYPE_PDF = 'pdf';
  const OUTPUT_TYPE_LATEX = 'latex';


  function __construct($idFacture, array $facture, $twig) {
    $this->idFacture = $idFacture;
    $this->facture = $facture;
    $this->twig = $twig;
  }

  public function getLatexFile() {
    $fn = $this->getLatexFileName();
    $leFichier = fopen($fn, "w");
    if (!$leFichier) {
      throw new \Exception("Cannot write on ".$fn);
    }
    fwrite($leFichier, $this->getLatexFileContents());
    fclose($leFichier);
    $retour = chmod($fn,intval('0660',8));
    return $fn;
  }

  private function getLatexDestinationDir() {
    if($path = $this->getOutputPath()){
      return $path;
    }
    return realpath(__DIR__.'/../../../out/pdf');
  }

  protected function getTEXWorkingDir() {
    return '/tmp/';
  }

  public function generatePDF() {
    $cmdCompileLatex = '/usr/bin/pdflatex -output-directory="'.$this->getTEXWorkingDir().'" -synctex=1 -interaction=nonstopmode "'.$this->getLatexFile().'" 2>&1';
    $output = shell_exec($cmdCompileLatex);

    if (!preg_match('/Transcript written/', $output) || preg_match('/Fatal error/', $output)) {
      throw new Exception($output." : ".$cmdCompileLatex);
    }

    $pdfpath = $this->getLatexFileNameWithoutExtention().'.pdf';
    if (!file_exists($pdfpath)) {
      throw new Exception("pdf not created ($pdfpath): ".$output);
    }
    return $pdfpath;
  }

  private function cleanPDF() {
    $file = $this->getLatexFileNameWithoutExtention();
    @unlink($file.'.aux');
    @unlink($file.'.log');
    @unlink($file.'.pdf');
    @unlink($file.'.tex');
    @unlink($file.'.synctex.gz');
  }

  public function getPDFFile() {
    $filename = $this->getLatexDestinationDir()."/".$this->getPublicFileName();
    // if(file_exists($filename))
    //   return $filename;
    $tmpfile = $this->generatePDF();
    if (!file_exists($tmpfile)) {
      throw new \Exception("pdf not created :(");
    }
    if (!rename($tmpfile, $filename)) {
      throw new Exception("not possible to rename $tmpfile to $filename");
    }
    $this->cleanPDF();
    return $filename;
  }

  public function getPDFFileContents() {
    return file_get_contents($this->getPDFFile());
  }

  public function echoPDFWithHTTPHeader() {
    $attachement = 'attachment; filename='.$this->getPublicFileName();
    header("content-type: application/pdf\n");
    header("content-length: ".filesize($this->getPDFFile())."\n");
    header("content-disposition: $attachement\n\n");
    echo $this->getPDFFileContents();
  }

  public function echoLatexWithHTTPHeader() {
    $attachement = 'attachment; filename='.$this->getPublicFileName('.tex');
    header("content-type: application/latex\n");
    header("content-length: ".filesize($this->getLatexFile())."\n");
    header("content-disposition: $attachement\n\n");
    echo $this->getLatexFileContents();
  }

  public function echoWithHTTPHeader($type = 'pdf') {
    if ($type == self::OUTPUT_TYPE_LATEX)
      return $this->echoLatexWithHTTPHeader();
    return $this->echoPDFWithHTTPHeader();
  }

  public function getLatexFileName() {
    return $this->getLatexFileNameWithoutExtention().'.tex';
  }

  public function getFileNameWithoutExtention() {
    return  $this->idFacture.'_Facture'.str_replace("è", "e", $this->infosCompany["name"]).'_'.str_replace(" ","",$this->infosClient["name"]);
  }


  public function getLatexFileNameWithoutExtention() {

    return $this->getTEXWorkingDir().$this->getFileNameWithoutExtention();
  }

  public function getLatexFileContents() {
      return $this->twig->render('invoice_tex.twig', ['idFacture' => $this->idFacture,
                                                      'facture' => $this->facture,
                                                      'infosClient' => $this->infosClient,
                                                      'infosCompany' => $this->infosCompany,
                                                      'infosExtra' => $this->infosExtra]);
  }

  public function getPublicFileName($extention = '.pdf') {

      return $this->getFileNameWithoutExtention().$extention;
  }

  public function setInfosCompany($infosCompany){
    $this->infosCompany = $infosCompany;
  }

  public function getInfosCompany(){

    return $this->infosCompany;
  }

  public function setInfosExtra($infosExtra){
    $this->infosExtra = $infosExtra;
  }

  public function getInfosExtra(){

    return $this->infosExtra;
  }

  public function setInfosClient($infosClient){
    $this->infosClient = $infosClient;
  }

  public function getInfosClient(){

    return $this->infosClient;
  }

  public function setOutputPath($output_path){
    $this->output_path = $output_path;
  }

  public function getOutputPath(){
    return $this->output_path;
  }

  public function toMail() {
    return $this->twig->render('mail.twig', ['idFacture' => $this->idFacture,
                                             'facture' => $this->facture,
                                             'infosClient' => $this->infosClient,
                                             'infosCompany' => $this->infosCompany,
                                             'infosExtra' => $this->infosExtra,
                                             'fileName' => $this->getPublicFileName(),
                                             'base64Attachment' =>  base64_encode(file_get_contents($this->getLatexDestinationDir()."/".$this->getPublicFileName()))]);
  }

}
