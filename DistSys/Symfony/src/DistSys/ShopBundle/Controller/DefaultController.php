<?php

namespace DistSys\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

  public function indexAction($url) {
    if (strcmp($url, 'frontpage') !== 0) {
      $simpleSite = $this->getDoctrine()->getRepository('DistSysShopBundle:SimpleSite')->findByTitle($url);
      if (is_a($simpleSite, 'SimpleSite')) {
        return $this->render('DistSysShopBundle:Default:index.html.twig', array('url' => $url, 'site' => $simpleSite));
      } else {
        return $this->render('DistSysShopBundle:Default:index.html.twig', array('url' => $url));
      }
    } else {
      return $this->render('DistSysShopBundle:Default:index.html.twig', array('url' => $url));
    }
  }

}
