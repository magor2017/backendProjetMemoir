<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Entity\Token;
use App\Entity\Formulaire;

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
  	
}
?>

