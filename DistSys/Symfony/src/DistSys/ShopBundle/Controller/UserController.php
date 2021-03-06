<?php

namespace DistSys\ShopBundle\Controller;

use DistSys\ShopBundle\Form\Type\ProfileType;
use DistSys\ShopBundle\Form\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller {
	
	public function indexAction() {

    $user = $this->get('security.context')->getToken()->getUser();
//    $em = $this->getDoctrine()->getManager();
    $profileForm = $this->createForm(new ProfileType(), $user);


    return $this->render('DistSysShopBundle:User:index.html.twig', array('user' => $user,
        'profileform' => $profileForm->createView()
        )
    );
  }

  public function updateAction(Request $request) {
	
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm( new ProfileType(), $user );
		$form->bind($this->getRequest());
	
    // Speichern, wenn das Formular Valid ist
    if ($form->isValid()) {
         $user = $form->getData();


                
         // Adresse in die Datenbank speichern
         $em->persist($user);
         $em->flush();
                        
         // Weiterleitung zur Übersicht 
         $res = true;
         $status = "Benutzerdaten erfolgreich geändert.";
      }else {
      	// Zurück mit Fehlerausgabe
      	$res = false;
        $status = "Bitte füllen Sie alle benötigten Felder aus.";
      }
	
		return new JsonResponse(array('res' => $res, 'status' => $status));
	}
	

	public function passwordAction(Request $request) {
	
		$user = $this->get('security.context')->getToken()->getUser();
		$passwordForm = $this->createForm( new PasswordType() );
		
	
		return $this->render('DistSysShopBundle:User:password.html.twig', 
				       array('user' => $user,
				       		   'postform' => $passwordForm->createView()
				       		  )
				   );
	}
	
	public function passwordUpdateAction(Request $request) {
	
		// User Session 
    $user = $this->get('security.context')->getToken()->getUser();
    $em = $this->getDoctrine()->getManager();
                                
    // Formular initialisieren
    $form = $this->createForm(new PasswordType());
    $form->bind($this->getRequest());
                
    // Speichern, wenn das Formular Valid ist
    if ($form->isValid()) {
         $pass = $form->getData();

                        
         $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
         //Neues Passwort für den Benutzer erzuegen
         $password = $encoder->encodePassword($pass['password'], $user->getSalt());
        
         // Passwort verschlüsselt in der Datenbank speichern
         $user->setPassword($password);
                
         // Adresse in die Datenbank speichern
         $em->persist($user);
         $em->flush();
                        
         // Weiterleitung zur Übersicht 
         $res = true;
         $status = "Passwort erfolgreich geändert.";
      }else {
      	// Zurück mit Fehlerausgabe
      	$res = false;
        $status = "Bitte geben sie 2 gleiche Passwörter ein";
      }
	
		return new JsonResponse(array('res' => $res, 'status' => $status));
	}
	
	
//  public function showAction($userId) {
//    // basic function to show user data
//    $repository = $this->getDoctrine()->getRepository('DistSysShopBundle:User');
//    $user = $repository->find($userId);
//
//    if (!$user) {
//      throw $this->createNotFoundException('No user with Id "' . $userId . '" found');
//    } else {
//      return $this->render(
//          'DistSysShopBundle:User:userShow.html.twig', array('user' => $user)
//      );
//    }
//  }

  public function myAccountAction() {
    // greeting screen for User
    $username = $this->get('security.context')->getToken()->getUser();
    $user = $this->getDoctrine()->getRepository('DistSysShopBundle:User')->findOneByEmail($username);

    return $this->render(
        'DistSysShopBundle:User:myAccount.html.twig', array(
        'userdata' => $user
        )
    );
  }


  public function myDataAction() {
    // form to change Userdata
    $em = $this->getDoctrine()->getManager();
    $request = $this->getRequest();

    $username = $this->get('security.context')->getToken()->getUser();
    $user = $em->getRepository('DistSysShopBundle:User')->findOneByEmail($username);
    $lastUrl = $request->headers->get('referer');

    # get inserted data from Form          
    # check weather there are changes
    # alter the user entry in the database
    if ($request->get('_gender') && $request->get('_gender') !== $user->getGender())
      $user->setGender($request->get('_gender'));
    if ($request->get('_firstname') && $request->get('_firstname') !== $user->getFirstname())
      $user->setFirstname($request->get('_firstname'));
    if ($request->get('_lastname') && $request->get('_lastname') !== $user->getLastname())
      $user->setLastname($request->get('_lastname'));
    if ($request->get('_street') && $request->get('_street') !== $user->getStreet())
      $user->setStreet($request->get('_street'));
    if ($request->get('_postal') && $request->get('_postal') !== $user->getPostal())
      $user->setPostal($request->get('_postal'));
    if ($request->get('_city') && $request->get('_city') !== $user->getCity())
      $user->setCity($request->get('_city'));
    $em->flush();

    return $this->render(
        'DistSysShopBundle:User:myData.html.twig', array(
        'user' => $user,
        'back' => $lastUrl,
        )
    );
  }


  public function myOrdersAction() {
  // show Orders
    $username = $this->get('security.context')->getToken()->getUser();
    $user = $this->getDoctrine()->getRepository('DistSysShopBundle:User')->findOneByEmail($username);
    $orders = $this->getDoctrine()->getRepository('DistSysShopBundle:Booking')->findByUser($user->getUserId());
    $orderArray = array();
    #write orders in an array, necessary because of the DateTimeObject
    for ($i = 0; $i < count($orders); $i++) {
      $orderdate = $orders[$i]->getDateOfOrder();
      $orderdate->format('Y-m-d H:i:s');
      $orderArray[$i]['orderdate'] = $orderdate;
      $orderArray[$i]['id'] = $orders[$i]->getId();
      $priceComplete = 0;

      switch ($orders[$i]->getStatus()) {
        case 1:
          $orderArray[$i]['status'] = 'eingegangen';
          break;
        case 2:
          $orderArray[$i]['status'] = 'in Bearbeitung';
          break;
        case 3:
          $orderArray[$i]['status'] = 'versendet';
          break;
        case 4:
          $orderArray[$i]['status'] = 'abgeschlossen';
          break;
        case 5:
          $orderArray[$i]['status'] = 'storniert';
          break;
        default:
          $orderArray[$i]['status'] = 'undefiniert';
      }
      #get OrderItems
      $orderArray[$i]['items'] = $this->getDoctrine()->getRepository('DistSysShopBundle:BookingItem')->findById($orders[$i]->getId());
      $k = 0;
      foreach ($orderArray[$i]['items'] as $item) {
        $bookedProduct = $this->getDoctrine()->getRepository('DistSysShopBundle:Product')->find($item->getProduct());
        $orderArray[$i]['booked'][$k] = $bookedProduct;
        $k++;
        $priceComplete+=$item->getQuantity() * $bookedProduct->getPrice();
      }
      if ($priceComplete < 50) {
        $priceComplete+=1.45;
      }
      $orderArray[$i]['priceComplete'] = $priceComplete;
    }
    return $this->render(
        'DistSysShopBundle:User:myOrders.html.twig', array(
        'orderArray' => $orderArray,
        )
    );
  }

  #function to delete Useraccount
  public function deleteAccountAction($userId) {
    $em = $this->getDoctrine()->getManager();
    $request = $this->getRequest();
    $username = $this->get('security.context')->getToken()->getUser();
    $user = $this->getDoctrine()->getRepository('DistSysShopBundle:User')->findOneByEmail($username);
    $error = false;
    #check password
    if ($request->get('password') != '') {
      if ($user->getPassword() === md5($request->get('password'))) {  #delete user object
        $em->remove($user);
        $em->flush();
        $user = 0;
        #redirect to homepage
        return $this->redirect($this->generateUrl('DistSysLogout'));
      } else {
        $error = true;
      }
    }
    #password does not match: redirect
    return $this->render(
        'DistSysShopBundle:User:deleteAccount.html.twig', array(
        'user' => $user,
        'error' => $error,
        )
    );
  }

}
