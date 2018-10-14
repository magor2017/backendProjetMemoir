<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Entity\Token;
use App\Entity\Formulaire;

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
		 $reponses[]=array("titre"=>$tontou->getTitre(),"questions"=>strval($tontou->getQuestions()));  
		}
	   
	 //  $reponse='{"titre":"'.$em->getTitre().'","questions":"fkfkk"}';
	    
	   return new Response(json_encode($reponses));
	  
	}
	
  	
}
?>


