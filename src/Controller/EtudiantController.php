<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Entity\Token;
use App\Entity\Formulaire;
use App\Entity\Reponse;
use App\Entity\Historique;

class EtudiantController extends Controller{
  
  /**
   * @Route("/getForm")
   */
   public function getForm(){
	   header("Access-Control-allow-Origin: *");
	  /* $datas=json_decode($_POST['param']);
	   $em=$this->getDoctrine()->getManager();
	   $formulaire=new Formulaire();
	   $formulaire->setQuestions($datas->question);
	   $formulaire->setTitre($datas->titre);
	   $em->persist($formulaire);
	   $em->flush();*/
	  // $em=$this->getDoctrine()->getManager()->getRepository(Formulaire::class)->find(1);
	  // $reponse='{"titre":"'.$em->getTitre().'","questions":'.strval($em->getQuestions()).'}';
	   $em=$this->getDoctrine()->getManager()->getRepository(Formulaire::class)->findAll();
	   $reponses=[];
	   foreach($em as $tontou){
		 //$reponses[]='{"titre":"'.$tontou->getTitre().'","questions":'.strval($tontou->getQuestions()).'}';  
		 $reponses[]=array("titre"=>$tontou->getTitre(),"questions"=>strval($tontou->getQuestions()),"idForm"=>$tontou->getId());  
		}
	   
	 //  $reponse='{"titre":"'.$em->getTitre().'","questions":"fkfkk"}';
	    
	   return new Response(json_encode($reponses));
	  
	}
	/**
	 * @Route("/validerReponse")
	 */
	public function validerReponse(){
		header("Access-Control-allow-Origin: *");
		$datas=json_decode($_POST['param']);
		$em=$this->getDoctrine()->getManager();
		$hist=$this->getDoctrine()->getRepository(Historique::class)->findOneBy(['idUser'=>intval($datas->id),'idFormulaire'=>intval($datas->idForm)]);
		if(!$hist){
			$reponse=new Reponse();
			$reponse->setReponse($datas->reponse);
			$reponse->setFormulaire($datas->idForm);
			$em->persist($reponse);
			$histo=new Historique();
			$histo->setIdUser(intval($datas->id));
			$histo->setIdFormulaire(intval($datas->idForm));
			\date_default_timezone_set('UTC');
			$date=new \DateTime();
			$histo->setDate($date);
			$em->persist($histo);
			$em->flush();
			return new Response($_POST['param']);
		}else{
			return new Response("0");
		}
	}	
}
?>


