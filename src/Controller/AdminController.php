<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Entity\Token;
use App\Entity\Formulaire;
use App\Entity\Campagne;
use App\Entity\Cible;
use App\Entity\Linkcible;
use App\Entity\Historique;
use App\Entity\Reponse;
class AdminController extends Controller{
  
  /**
   * @Route("/newFormulaire")
   */
   public function newFormulaire(){
	   header("Access-Control-allow-Origin: *");
	   $datas=json_decode($_POST['param']);
	   $em=$this->getDoctrine()->getManager();
	   $formulaire=new Formulaire();
	   $formulaire->setQuestions($datas->question);
	   $formulaire->setTitre($datas->titre);
	   $formulaire->setCampagne(0);
	   \date_default_timezone_set('UTC');
	   $date=new \DateTime();
	   $formulaire->setDateCreation($date);
	   $em->persist($formulaire);
	   $em->flush();
	    
	   return new Response(strval("ok"));
	  
	}
	/**
	 * @Route("/auth")
	 */
	 public function authentification(){
		 header("Access-Control-allow-Origin: *");
		 return new Response("yes");
	    	 
	}
	/**
	 * @Route("/newCampagne")
	 */
  	public function newCampagne(){
		header("Access-Control-allow-Origin: *");
		$datas=json_decode($_POST['param']);
		$em=$this->getDoctrine()->getManager();
		$campagne=new Campagne();
		$campagne->setNom($datas->nom);
		$campagne->setObjectif($datas->objectif);
		$campagne->setEtat(0);
		$campagne->setFormulaire("[]");
		\date_default_timezone_set('UTC');
		$date=new \DateTime();
		$campagne->setDateCreation($date);
		$em->persist($campagne);
		$em->flush();
		return new Response("new campagne");
	  }
	/**
	 * @Route("/listeCampagne")
	 */
	public function listeCamgne(){
		header("Access-Control-allow-Origin: *");
		$campagne=$this->getDoctrine()->getRepository(Campagne::class)->findAll();
		$reponse=[];
		if(count($campagne)!=0){
			foreach($campagne as $camp){
				$reponse[]=array("id"=>$camp->getId(),"nom"=>$camp->getNom(),"etat" =>$camp->getEtat(),"dateCreation"=>$camp->getDateCreation(),"objectif"=>$camp->getObjectif(),"formulaire"=>$camp->getFormulaire());
			}
			return new Response(json_encode($reponse));
		}else{
			return new Response("0");
		}

	}
	/**
	 * @Route("/affecterForm")
	 */
	public function affecterForm(){
		header("Access-Control-allow-Origin: *");
		$liste=$this->getDoctrine()->getRepository(Formulaire::class)->findBy(array("campagne"=>0));
		$reponse=[];
		foreach($liste as $l){
			$reponse[]=array("titre"=>$l->getTitre(),"date"=>$l->getDateCreation(),"id"=>$l->getId());
		}
        return new Response(json_encode($reponse));
	}
	/**
	 * @Route("/getFormee")
	 */
	public function getForm(){
		header("Access-Control-allow-Origin: *");
		$form=$this->getDoctrine()->getRepository(Formulaire::class)->findAll();
		$reponse=[];
		foreach($form as $l){
			$reponse[]=array("titre"=>$l->getTitre(),"date"=>$l->getDateCreation(),"id"=>$l->getId(),"questions"=>$l->getQuestions());
		}
        return new Response(json_encode($reponse));
	}
	/**
	 * @Route("/validerAjoutForm")
	 */
	public function validerAjoutForm(){
		header("Access-Control-allow-Origin: *");
		$em=$this->getDoctrine()->getManager();
		$datas=json_decode($_POST["param"]);
		$campagne=$this->getDoctrine()->getRepository(Campagne::class)->findOneBy(["id" =>$datas->id]);
		$campagne->setFormulaire($datas->form);
		//$em->persist($campagne);
		$em->flush();
		return new Response("ok");
	}
	/**
	 * @Route("/supprimerFormulaire")
	 */
	public function supprimerFormulaire(){
		header("Access-Control-allow-Origin: *");
		$em=$this->getDoctrine()->getManager();
		$datas=json_decode($_POST["param"]);
		$campagne=$this->getDoctrine()->getRepository(Campagne::class)->findOneBy(["id" =>$datas->id]);
		$campagne->setFormulaire($datas->form);
		//$em->persist($campagne);
		$em->flush();
		return new Response(json_encode("ok"));

	}
	/**
	 * @Route("/getCampagneById")
	 */
	public function getCampagneById(){
		header("Access-Control-allow-Origin: *");
		$em=$this->getDoctrine()->getManager();
		$datas=json_decode($_POST["param"]);
		$campagne=$this->getDoctrine()->getRepository(Campagne::class)->findOneBy(["id" =>intval($datas->id)]);
		$forms=json_decode($campagne->getFormulaire());
		$nbEtudiants=0;
		$nbcibleRestant=0;
		for($i=0;$i<count($forms);$i++){
			$nbEtudiants+=$this->getNbCible($datas->id,$forms[$i]->id);
			$nbcibleRestant+=$this->getNbCibleRestant($datas->id,$forms[$i]->id);
		}
		$reponse=$this->getDoctrine()->getRepository(Reponse::class)->findBy(["campagne"=>intval($datas->id)]);
		$rep=[];
		foreach($reponse as $r){
			$rep[]=array("form"=>$r->getFormulaire(),"reponse"=>$r->getReponse(),"cible"=>$this->getNbCible(intval($datas->id),intval($r->getFormulaire())));

		}
		return new Response(json_encode(array("id"=>$campagne->getId(),"nom"=>$campagne->getNom(),"objectif"=>$campagne->getObjectif(),"observation"=>$campagne->getObservation(),"etat"=>$campagne->getEtat(),"date_debut"=>$campagne->getDateDebut(),"date_fin"=>$campagne->getDateFin(),"date_creation"=>$campagne->getDateCreation(),"formulaire"=>$campagne->getFormulaire(),"nbcible"=>$nbEtudiants,"cibleRestant"=>$nbcibleRestant,"reponse"=>$rep)));

	}
	/**
	 * @Route("/getcible")
	 */
	public function getNbCible($camp,$form){
		//$camp="2";
		//$form="10";
		$etudiants=$this->getDoctrine()->getRepository(Users::class)->findAll();
		$bdd=new \pdo("mysql:host=localhost;dbname=projetmemoir","root","");
		$nbEtudiant=0;
		for($i=0;$i<count($etudiants);$i++){
			$req=$bdd->prepare("SELECT * FROM linkcible WHERE campagne=:c AND formulaire=:f AND niveau LIKE '%".$etudiants[$i]->getNiveau()."%' AND classe LIKE '%".$etudiants[$i]->getClasse()."%'");
			$req->execute(array("c"=>intval($camp),"f"=>intval($form)));
			if($req->fetch()){
				$nbEtudiant++;
			}
	   }
	   return $nbEtudiant;
	}
	/**
	 * @Route("/ciblerestant")
	 */
	public function getNbCibleRestant($camp,$form){
		//$camp="2";
		//$form="9";
		$etudiants=$this->getDoctrine()->getRepository(Users::class)->findAll();
		$bdd=new \pdo("mysql:host=localhost;dbname=projetmemoir","root","");
		$nbEtudiant=0;
		for($i=0;$i<count($etudiants);$i++){
			$req=$bdd->prepare("SELECT * FROM linkcible WHERE campagne=:c AND formulaire=:f AND niveau LIKE '%".$etudiants[$i]->getNiveau()."%' AND classe LIKE '%".$etudiants[$i]->getClasse()."%'");
			$req->execute(array("c"=>intval($camp),"f"=>intval($form)));
			if($req->fetch()){
				$historique=$this->getDoctrine()->getRepository(Historique::class)->findOneBy(["idUser"=>$etudiants[$i]->getId(),"campagne"=>intval($camp),"idFormulaire"=>intval($form)]);
				if(!$historique){
					$nbEtudiant++;
				}
			}
	   }
	  // return new Response($nbEtudiant);
	  return $nbEtudiant;
	}
	/**
	 * @Route("/getAllCible")
	 */
	public function getAllCible(){
		header("Access-Control-allow-Origin: *");
		$cibles=$this->getDoctrine()->getRepository(Cible::class)->findAll();
		$cib=[];
		foreach($cibles as $cible){
			$cib[]=array("niveau"=>$cible->getNiveau(),"class" =>$cible->getClasse());
		}
		return new Response(json_encode($cib));
	}
	/**
	 * @Route("/validerAjoutCible")
	 */
	public function validerAjoutCible(){
		header("Access-Control-allow-Origin: *");
		$datas=json_decode($_POST['param']);
		$linkcible=new Linkcible();
		$em=$this->getDoctrine()->getManager();
		$l=$this->getDoctrine()->getRepository(Linkcible::class)->findOneBy(["campagne"=>intval($datas->campagne),"formulaire"=>intval($datas->form)]);
		if(!$l){
			$linkcible->setCampagne(intval($datas->campagne));
			$linkcible->setFormulaire(intval($datas->form));
			$linkcible->setNiveau($datas->niveau);
			$linkcible->setClasse($datas->classe);
			$em->persist($linkcible);
			$em->flush();
		}else{
			$l->setCampagne(intval($datas->campagne));
			$l->setFormulaire(intval($datas->form));
			$l->setNiveau($datas->niveau);
			$l->setClasse($datas->classe);
			$em->flush();
		}
		return new Response("ok");

	}
	/**
	 * @Route("/voirCible")
	 */
	public function voirCible(){
		header("Access-Control-allow-Origin: *");
		$datas=json_decode($_POST['param']);
		$link=$this->getDoctrine()->getRepository(Linkcible::class)->findOneBy(["campagne" =>intval($datas->campagne),"formulaire"=>intval($datas->form)]);
		$rep=[];
		if($link){
			$rep[]=array("campagne"=>$link->getCampagne(),"form"=>$link->getFormulaire(),"niveau"=>$link->getNiveau(),"classe"=>$link->getClasse());
		}
		return new Response(json_encode($rep));
	}
}
?>

