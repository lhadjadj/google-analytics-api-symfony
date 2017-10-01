<?php
namespace LHadjadj\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

//Gestion des indicateurs GoogleAnalytics

/**
 * @MongoDB\Document(collection="GoogleAnalytics") 
 */
class GoogleAnalytics
{
    
   /** @MongoDB\Id(strategy="AUTO") */
   protected $id;
   
   /**  @MongoDB\Field(type="string") */
   protected $dateMesure;

   /**  @MongoDB\Field(type="string") */
   protected $ip; 

   /**  @MongoDB\Field(type="string") */
   protected $type; 
   
   /**  @MongoDB\Field(type="integer") */
   protected $visiteur; 

   /**  @MongoDB\Field(type="integer") */
   protected $session; 
   
   /**  @MongoDB\Field(type="float") */
   protected $sessionDuration; 

   /**  @MongoDB\Field(type="float") */
   protected $sessionsPerUser;
   
   /**  @MongoDB\Field(type="integer") */
   protected $bounces;

   /**  @MongoDB\Field(type="float") */
   protected $bounceRate;
   
   /**  @MongoDB\Field(type="float") */
   protected $avgTimeOnPage;

   /**  @MongoDB\Field(type="float") */
   protected $pageViewsPerSession;

   /**  @MongoDB\Field(type="float") */
   protected $percentNewVisits;
   
   /**  @MongoDB\Field(type="integer") */
   protected $pageViews;

   /**  @MongoDB\Field(type="integer") */
   protected $uniquePageviews;

   /**  @MongoDB\Field(type="float") */
   protected $avgPageLoadTime;

   /**  @MongoDB\Field(type="hash") */
   protected $browser;   

   /**  @MongoDB\Field(type="hash") */
   protected $operatingSystemDateRange;   

   /**  @MongoDB\Field(type="hash") */
   protected $deviceCategory;   

   /**  @MongoDB\Field(type="hash") */
   protected $country;   

   /**  @MongoDB\Field(type="hash") */
   protected $city;   
   
   /**  @MongoDB\Field(type="hash") */
   protected $month;   

   /**  @MongoDB\Field(type="hash") */
   protected $hour;   
   
   

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateMesure
     *
     * @param date $dateMesure
     * @return $this
     */
    public function setDateMesure($dateMesure)
    {
        $this->dateMesure = $dateMesure;
        return $this;
    }

    /**
     * Get dateMesure
     *
     * @return date $dateMesure
     */
    public function getDateMesure()
    {
        return $this->dateMesure;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set visiteur
     *
     * @param integer $visiteur
     * @return $this
     */
    public function setVisiteur($visiteur)
    {
        $this->visiteur = $visiteur;
        return $this;
    }

    /**
     * Get visiteur
     *
     * @return integer $visiteur
     */
    public function getVisiteur()
    {
        return $this->visiteur;
    }

    /**
     * Set session
     *
     * @param integer $session
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Get session
     *
     * @return integer $session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set sessionDuration
     *
     * @param float $sessionDuration
     * @return $this
     */
    public function setSessionDuration($sessionDuration)
    {
        $this->sessionDuration = $sessionDuration;
        return $this;
    }

    /**
     * Get sessionDuration
     *
     * @return float $sessionDuration
     */
    public function getSessionDuration()
    {
        return $this->sessionDuration;
    }

    /**
     * Set sessionsPerUser
     *
     * @param float $sessionsPerUser
     * @return $this
     */
    public function setSessionsPerUser($sessionsPerUser)
    {
        $this->sessionsPerUser = $sessionsPerUser;
        return $this;
    }

    /**
     * Get sessionsPerUser
     *
     * @return float $sessionsPerUser
     */
    public function getSessionsPerUser()
    {
        return $this->sessionsPerUser;
    }

    /**
     * Set bounces
     *
     * @param integer $bounces
     * @return $this
     */
    public function setBounces($bounces)
    {
        $this->bounces = $bounces;
        return $this;
    }

    /**
     * Get bounces
     *
     * @return integer $bounces
     */
    public function getBounces()
    {
        return $this->bounces;
    }

    /**
     * Set bounceRate
     *
     * @param float $bounceRate
     * @return $this
     */
    public function setBounceRate($bounceRate)
    {
        $this->bounceRate = $bounceRate;
        return $this;
    }

    /**
     * Get bounceRate
     *
     * @return float $bounceRate
     */
    public function getBounceRate()
    {
        return $this->bounceRate;
    }

    /**
     * Set avgTimeOnPage
     *
     * @param float $avgTimeOnPage
     * @return $this
     */
    public function setAvgTimeOnPage($avgTimeOnPage)
    {
        $this->avgTimeOnPage = $avgTimeOnPage;
        return $this;
    }

    /**
     * Get avgTimeOnPage
     *
     * @return float $avgTimeOnPage
     */
    public function getAvgTimeOnPage()
    {
        return $this->avgTimeOnPage;
    }

    /**
     * Set pageViewsPerSession
     *
     * @param float $pageViewsPerSession
     * @return $this
     */
    public function setPageViewsPerSession($pageViewsPerSession)
    {
        $this->pageViewsPerSession = $pageViewsPerSession;
        return $this;
    }

    /**
     * Get pageViewsPerSession
     *
     * @return float $pageViewsPerSession
     */
    public function getPageViewsPerSession()
    {
        return $this->pageViewsPerSession;
    }

    /**
     * Set percentNewVisits
     *
     * @param float $percentNewVisits
     * @return $this
     */
    public function setPercentNewVisits($percentNewVisits)
    {
        $this->percentNewVisits = $percentNewVisits;
        return $this;
    }

    /**
     * Get percentNewVisits
     *
     * @return float $percentNewVisits
     */
    public function getPercentNewVisits()
    {
        return $this->percentNewVisits;
    }

    /**
     * Set pageViews
     *
     * @param integer $pageViews
     * @return $this
     */
    public function setPageViews($pageViews)
    {
        $this->pageViews = $pageViews;
        return $this;
    }

    /**
     * Get pageViews
     *
     * @return integer $pageViews
     */
    public function getPageViews()
    {
        return $this->pageViews;
    }

    /**
     * Set uniquePageviews
     *
     * @param integer $uniquePageviews
     * @return $this
     */
    public function setUniquePageviews($uniquePageviews)
    {
        $this->uniquePageviews = $uniquePageviews;
        return $this;
    }

    /**
     * Get uniquePageviews
     *
     * @return integer $uniquePageviews
     */
    public function getUniquePageviews()
    {
        return $this->uniquePageviews;
    }

    /**
     * Set avgPageLoadTime
     *
     * @param float $avgPageLoadTime
     * @return $this
     */
    public function setAvgPageLoadTime($avgPageLoadTime)
    {
        $this->avgPageLoadTime = $avgPageLoadTime;
        return $this;
    }

    /**
     * Get avgPageLoadTime
     *
     * @return float $avgPageLoadTime
     */
    public function getAvgPageLoadTime()
    {
        return $this->avgPageLoadTime;
    }

    /**
     * Set browser
     *
     * @param hash $browser
     * @return $this
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }

    /**
     * Get browser
     *
     * @return hash $browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set operatingSystemDateRange
     *
     * @param hash $operatingSystemDateRange
     * @return $this
     */
    public function setOperatingSystemDateRange($operatingSystemDateRange)
    {
        $this->operatingSystemDateRange = $operatingSystemDateRange;
        return $this;
    }

    /**
     * Get operatingSystemDateRange
     *
     * @return hash $operatingSystemDateRange
     */
    public function getOperatingSystemDateRange()
    {
        return $this->operatingSystemDateRange;
    }

    /**
     * Set deviceCategory
     *
     * @param hash $deviceCategory
     * @return $this
     */
    public function setDeviceCategory($deviceCategory)
    {
        $this->deviceCategory = $deviceCategory;
        return $this;
    }

    /**
     * Get deviceCategory
     *
     * @return hash $deviceCategory
     */
    public function getDeviceCategory()
    {
        return $this->deviceCategory;
    }

    /**
     * Set country
     *
     * @param hash $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return hash $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param hash $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return hash $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set month
     *
     * @param hash $month
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Get month
     *
     * @return hash $month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set hour
     *
     * @param hash $hour
     * @return $this
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }

    /**
     * Get hour
     *
     * @return hash $hour
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }
}
