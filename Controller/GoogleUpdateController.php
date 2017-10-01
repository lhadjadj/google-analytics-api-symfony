<?php
namespace LHadjadj\GoogleAnalyticsApi\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use PortailBundle\Document\GoogleAnalytics;

/**
 * Class GoogleUpdateController
 * @package LHadjadj\GoogleAnalyticsApi\Controller
 */
class GoogleUpdateController extends Controller
{
/**
 *@Route("/google/update/{token}", name="googleAnalyticUpdate", options = { "utf8": true })
*/
 public function googleAnalyticUpdateAction($token)
 {   
  if ($token=="normal" or $token=="batch") 
    {
      $tempStart = \explode(" ", \microtime());
      $timeStart = \bcadd($tempStart[0], $tempStart[1], 6);
    } 
  else {exit;}

     //YYYY-MM-DD - [0-9]{4}-[0-9]{2}-[0-9]{2}|today|yesterday|[0-9]+(daysAgo)
  if ($token == "normal") {
      $dateDuJour= \date('Y\-m\-d');
      $newDate = strtotime ( '-1 day' , strtotime ( $dateDuJour ) ) ;
      $date = \date ( 'Y\-m\-d' , $newDate );
      print("Procédure : ".$token)."<br />"."----------"."<br />";
      $tempo=self::googleAPI($date, "QUOTIDIEN");
      print("Date : ".$date)." -----> ".$tempo."<br />";
    }
     
  if ($token == "batch") {
      $dateDuJour= \date('2017-01-01');  // <-- à modifier
      print("Procédure : ".$token)."<br />"."----------"."<br />";
      for ( $i=0; $i< \date('z'); $i++)
         { 
            $newDate = strtotime ( '+'.$i.' day' , strtotime ( $dateDuJour ) ) ; 
            $date = \date ( 'Y\-m\-d' , $newDate );
            $tempo=self::googleAPI($date, "QUOTIDIEN");
            print("Date : ".$date)." -----> ".$tempo."<br />";
        }
       }

     $tempStop = \explode(" ", \microtime());
     $timeStop = \bcadd($tempStop[0], $tempStop[1], 6);
     $time = \bcsub($timeStop, $timeStart, 6);  
     return new Response('<br />Temps d\'excution : '.round($time,2). 'secondes');  
    }     
    
    private function googleAPI($date, $type)
     {
     $cnxGoogleAnalytics = $this->get('doctrine_mongodb')->getRepository('PortailBundle:GoogleAnalytics');
     $googleAnalytics=$cnxGoogleAnalytics->findBy(array('dateMesure'=>$date));
     if ($googleAnalytics) {return "OK";}
         
     $analyticsService = $this->get('google_analytics_api.api');
     $viewId = $this->container->getParameter('google_analytics_view_id');

     // Metrics
     $users = $analyticsService->getUsersDateRange($viewId,$date,$date);
     $session = $analyticsService->getSessionsDateRange($viewId,$date,$date);
     $sessionDuration= $analyticsService->getSessionDurationDateRange($viewId,$date,$date);
     $sessionsPerUser = $analyticsService->getSessionsPerUserDateRange($viewId,$date,$date);
     $bounces = $analyticsService->getBouncesDateRange($viewId,$date,$date);
     $bounceRate = $analyticsService->getBounceRateDateRange($viewId,$date,$date);
     $avgTimeOnPage = $analyticsService->getAvgTimeOnPageDateRange($viewId,$date,$date);
     $pageViewsPerSession = $analyticsService->getPageviewsPerSessionDateRange($viewId,$date,$date);
     $percentNewVisits = $analyticsService->getPercentNewVisitsDateRange($viewId,$date,$date);
     $pageViews = $analyticsService->getPageViewsDateRange($viewId,$date,$date);
     $uniquePageviews = $analyticsService->getUniquePageviewsDateRange($viewId,$date,$date);
     $avgPageLoadTime = $analyticsService->getAvgPageLoadTimeDateRange($viewId,$date,$date);
   
     //Dimentions 
     $browser = $analyticsService->getBrowserDateRange($viewId,$date,$date);
     $operatingSystemDateRange = $analyticsService->getOperatingSystemDateRange($viewId,$date,$date);
     $deviceCategory = $analyticsService->getDeviceCategoryDateRange($viewId,$date,$date);
     $country = $analyticsService->getCountryDateRange($viewId,$date,$date);
     $city = $analyticsService->getCityDateRange($viewId,$date,$date);
     $month = $analyticsService->getMonthDateRange($viewId,$date,$date);
     $hour = $analyticsService->getHourDateRange($viewId,$date,$date);
   
    /*** Sauvegarde en base de données des indicateurs                        ***/
     $dm=$this->get('doctrine_mongodb')->getManager();
     $ip = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
     
     $googleUpdate = new GoogleAnalytics();
     $googleUpdate->setDateMesure($date);
     $googleUpdate->setIp($ip);
     $googleUpdate->setType($type);
     
     $googleUpdate->setVisiteur($users);

     $googleUpdate->setSession($session);
     $googleUpdate->setSessionDuration($sessionDuration);
     $googleUpdate->setSessionsPerUser($sessionsPerUser);
     $googleUpdate->setPercentNewVisits($percentNewVisits);

     $googleUpdate->setBounces($bounces);
     $googleUpdate->setBounceRate($bounceRate);

     $googleUpdate->setPageViews($pageViews);
     $googleUpdate->setPageViewsPerSession($pageViewsPerSession);
     $googleUpdate->setUniquePageviews($uniquePageviews);

     $googleUpdate->setAvgPageLoadTime($avgPageLoadTime);
     $googleUpdate->setAvgTimeOnPage($avgTimeOnPage);

     $googleUpdate->setBrowser($browser);
     $googleUpdate->setOperatingSystemDateRange($operatingSystemDateRange);
     $googleUpdate->setDeviceCategory($deviceCategory);

     $googleUpdate->setCountry($country);
     $googleUpdate->setCity($city);
     $googleUpdate->setMonth($month);
     $googleUpdate->setHour($hour);

     $dm->persist($googleUpdate);
     $dm->flush($googleUpdate);

     $tempo=$cnxGoogleAnalytics->findBy(array('dateMesure'=>$date));
     if ($tempo) {$response=" Enregistré.";} else {$response=" Erreur."; }
     
     return $response;
    }
 }     
    