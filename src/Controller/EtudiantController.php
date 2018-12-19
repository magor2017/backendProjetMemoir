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
use App\Entity\Linkcible;
use App\Entity\Campagne;

class EtudiantController extends Controller{
  
  /**
   * @Route("/getForm")
   */
   public function getForm(){
	   header("Access-Control-allow-Origin: *");
	   $datas=json_decode($_POST['param']);
	  /* $em=$this->getDoctrine()->getManager();
	   $formulaire=new Formulaire();
	   $formulaire->setQuestions($datas->question);
	   $formulaire->setTitre($datas->titre);
	   $em->persist($formulaire);
	   $em->flush();*/
	  // $em=$this->getDoctrine()->getManager()->getRepository(Formulaire::class)->find(1);
	   //$reponse='{"titre":"'.$em->getTitre().'","questions":'.strval($em->getQuestions()).'}';
	   $etudiant=$this->getDoctrine()->getManager()->getRepository(Users::class)->findOneBy(["id"=>intval($datas->id)]);
	   if(!$etudiant){
		   return new Response(json_encode([]));
	   }
	   $link=$this->testpdo($etudiant->getNiveau(),$etudiant->getClasse());
//$em=$this->getDoctrine()->getManager()->getRepository(Formulaire::class)->findAll();
       $campagne=[];
       foreach($link as $l){
		$cam=$this->getDoctrine()->getManager()->getRepository(Campagne::class)->findBy(["id" =>$l['campagne']]);
		foreach($cam as $k){
		  $campagne[]=array("campagne" =>array("forms" =>$k->getFormulaire(),"nom"=>$k->getNom(),"idCam"=>$k->getId()),"idform"=>$l["formulaire"]);
		}
	   }
	   $reponses=[];
	  // foreach($em as $tontou){
		 //$reponses[]='{"titre":"'.$tontou->getTitre().'","questions":'.strval($tontou->getQuestions()).'}';  
	//	 $reponses[]=array("titre"=>$tontou->getTitre(),"questions"=>strval($tontou->getQuestions()),"idForm"=>$tontou->getId());  
	//	}
	  // return new Response(json_encode($campagne));
	   return new Response(json_encode($campagne));
	}
	public function inTab($table,$el){
		for($i=0;$i<count($table);$i++){
			if(strcmp(strtolower($table[$i]),strtolower($el))==0){
				return true;
			}
		}
		return false;
	}
	/**
	 * @Route("/testpdo")
	 */
	public function testpdo($niv,$cla){
	//	public function testpdo(){
		//$niv="l1";
		//$cla="ir";
		$bdd = new \PDO('mysql:host=localhost;dbname=projetmemoir', 'root', '');
		$req=$bdd->prepare("SELECT * FROM linkcible WHERE niveau LIKE '%".$niv."%' AND classe LIKE '%".$cla."%'");
		$req->execute();
		$rep=$req->fetchAll();

		return $rep;
		//return new Response(json_encode($rep[0]));
    }
	
	/**
	 * @Route("/validerReponse")
	 */
	public function validerReponse(){
		header("Access-Control-allow-Origin: *");
		$datas=json_decode($_POST['param']);
		$em=$this->getDoctrine()->getManager();
		$hist=$this->getDoctrine()->getRepository(Historique::class)->findOneBy(['idUser'=>intval($datas->id),'idFormulaire'=>intval($datas->idForm),"campagne"=>intval($datas->idCamp)]);
		if(!$hist){
			$reponse=new Reponse();
			$reponse->setReponse($datas->reponse);
			$reponse->setFormulaire(intval($datas->idForm));
			$reponse->setCampagne(intval($datas->idCamp));
			$em->persist($reponse);
			$histo=new Historique();
			$histo->setIdUser(intval($datas->id));
			$histo->setIdFormulaire(intval($datas->idForm));
			$histo->setCampagne(2);
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


