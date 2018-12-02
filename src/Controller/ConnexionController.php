<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Entity\Token;

class ConnexionController extends Controller{
  
  /**
   * @Route("/connexion")
   */
   public function connexion(){
	   header("Access-Control-allow-Origin: *");
	   $datas=json_decode($_POST['param']);
	   //$user=new Users();
	    $users=$this->getDoctrine()->getRepository(Users::class)->findOneBy(['identifiant'=>$datas->identifiant,'password'=>$datas->password]);
	   // $users=$this->getDoctrine()->getRepository(Users::class)->findOneBy(['identifiant'=>'admin','password'=>'admin']);
	  
	   $reponse="";
	   $rep="";
	   if($users){
		    $repository=$this->getDoctrine()->getRepository(Token::class);
		    $token=$repository->findOneBy(['idUser'=>$users->getId()]);
		    $em=$this->getDoctrine()->getManager();
		    if($token){
		        $em->remove($token);
		        $em->flush();
		     }
		      \date_default_timezone_set('UTC');
		      $date=new \DateTime();
              
		      $newToken=sha1(\time().$users->getId());
		      $token=new Token();
		      $token->setToken($newToken);
		      $token->setIdUser($users->getId());
		      $token->setDate($date);
		      $em->persist($token);
		      $em->flush();
		    //  $data=strval('{"id":'..'}');
		      $reponse='{"status":1,"id":"'.$users->getId().'","token":"'.$newToken.'","level":"'.$users->getAccessLevel().'"}';
		  
	    }else{
			$rep="no";
			$reponse='{"status":0,"data":0}';
	   }
	   return new Response(strval($reponse));
	 //  $reponse='{"status":0,"data":0}';
	  // return new Response(strval($reponse));
	   return new Response($token->getToken());
	   
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
