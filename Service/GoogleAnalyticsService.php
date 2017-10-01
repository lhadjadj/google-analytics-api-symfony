<?php

namespace LHadjadj\GoogleAnalyticsApi\Service;

use Google_Client;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_ReportRequest;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class GoogleAnalyticsService
 * @package LHadjadj\GoogleAnalyticsApi\Service
 */
class GoogleAnalyticsService {

    /**
     * @var Google_Client
     */
    private $client;
    /**
     * @var Google_Service_AnalyticsReporting
     */
    private $analytics;

    /**
     * construct
     */
    public function __construct($keyFileLocation) {

        if (!file_exists($keyFileLocation)) {
            throw new Exception("can't find file key location defined by google_analytics_api.google_analytics_json_key parameter, ex : ../data/analytics/analytics-key.json");
        }

        $this->client = new Google_Client();
        $this->client->setApplicationName("GoogleAnalytics");
        $this->client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->client->setAuthConfig($keyFileLocation);

        $this->analytics = new Google_Service_AnalyticsReporting($this->client);

    }

    /**
     * @return Google_Service_AnalyticsReporting
     */
    public function getAnalytics() {

        return $this->analytics;

    }

    /**
     * @return Google_Client
     */
    public function getClient() {

        return $this->client;

    }

        /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @param $metric
     * @param $dimension
     * @return mixed
     */
    private function getDimensionDataDateRange($viewId,$dateStart,$dateEnd,$metric,$dimension) {
        // Create the DateRange object
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($dateStart);
        $dateRange->setEndDate($dateEnd);

        //Create the dimension object
        $dimention = new Google_Service_AnalyticsReporting_Dimension();
        $dimention->setName("ga:$dimension");

        // Create the Metrics object
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:$metric");
        $sessions->setAlias("sessions");

        // Create the ReportRequest object
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($viewId);
        $request->setDateRanges($dateRange);
        $request->setDimensions([$dimention]);
        $request->setMetrics([$sessions]);
        

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);
                
        $reports = $this->analytics->reports->batchGet($body);

        if (array_key_exists("rows", $reports[0])) 
            {
                for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
                    $report = $reports[ $reportIndex ];
                    $header = $report->getColumnHeader();
                    $dimensionHeaders = $header->getDimensions();
                    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
                    $rows = $report->getData()->getRows();
            
                for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                    $row = $rows[ $rowIndex ];
                    $dimensions = $row->getDimensions();
                    $metrics = $row->getMetrics();
                    for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                        $arrayDimensions[$rowIndex]=[$dimensions[$i]];
                        $tempoMetrics[$rowIndex]=[$metrics[$i]->getValues()];
                        $arrayMetrics[$rowIndex]=$tempoMetrics[$rowIndex][0][0];
                        }
                }   
            $labelDimensions=mb_strcut($dimensionHeaders[0], 3);
            $labelMetrics=$metricHeaders[0]->getName();
            }
            return [$labelDimensions, $labelMetrics, $arrayDimensions, $arrayMetrics];
        }    
        else
        {
            $tempoDimensions=$reports[0]->getColumnHeader()->getDimensions();
            $tempoMetrics=$reports[0]->getColumnHeader()->getMetricHeader()->getMetricHeaderEntries();
            
            $labelDimensions = mb_strcut($tempoDimensions[0], 3);
            $labelMetrics = $tempoMetrics[0]->getname();
            
            return [$labelDimensions, $labelMetrics, [0], [0]];
        }    
    }
    
    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @param $expression
     * @return mixed
     */
    private function getDataDateRange($viewId,$dateStart,$dateEnd,$expression) {

        // Create the DateRange object
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($dateStart);
        $dateRange->setEndDate($dateEnd);

        // Create the Metrics object
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:$expression");
        $sessions->setAlias("sessions");

        // Create the ReportRequest object
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($viewId);
        $request->setDateRanges($dateRange);
        $request->setMetrics([$sessions]);

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);
        
        $report = $this->analytics->reports->batchGet($body);

        $result = $report->getReports()[0]
            ->getData()
            ->getTotals()[0]
            ->getValues()[0]
        ;

        return $result;
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/27
     */
    public function getUsersDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'users');
    }

    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getSessionsDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'sessions');
    }

    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017-09-27
     */
    public function getSessionDurationDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'sessionDuration');
    }
    
    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/27
    */
    public function getSessionsPerUserDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'sessionsPerUser');
    }
    
    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/27
     */
    public function getBouncesDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'bounces');
    }
    
    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getBounceRateDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'bounceRate');
    }

    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getAvgTimeOnPageDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'avgTimeOnPage');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/27
     */
    public function getBrowserDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','browser');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/28
     */
    public function getOperatingSystemDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','operatingSystem');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/28
     */
    public function getDeviceCategoryDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','deviceCategory');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/28
     */
    public function getCountryDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','country');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/28
     */
    public function getCityDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','city');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/28
     */
    public function getHourDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','hour');
    }

     /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     * LHD - 2017/09/28
     */
    public function getMonthDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDimensionDataDateRange($viewId,$dateStart,$dateEnd,'users','month');
    }
    
    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getPageviewsPerSessionDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'pageviewsPerSession');
    }

    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getPercentNewVisitsDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'percentNewVisits');
    }

    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getPageViewsDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'pageviews');
    }

    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     *  LHD - 2017-09-28
     */
    public function getUniquePageviewsDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'uniquePageviews');
    }
    
    /**
     * @param $viewId
     * @param $dateStart
     * @param $dateEnd
     * @return mixed
     */
    public function getAvgPageLoadTimeDateRange($viewId,$dateStart,$dateEnd) {
        return $this->getDataDateRange($viewId,$dateStart,$dateEnd,'avgPageLoadTime');
    }

}
