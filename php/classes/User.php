<?php
session_start();
    class User{
        /* Protected Properties
            -------------------------------*/
        protected $_ho;
        protected $_mongoConnector;
        protected $_user; 

        public function __construct(){
            $this->_ho = new HasOffers();
            $this->_mongoConnector = new MongoConnector();
        }

        function signIn($user, $redirect = false){
            $response = $this->_ho->request(array('Target' => 'Authentication'
                                    ,'Method' => 'findUserByCredentials'
                                    ,'email' => $user['email']
                                    ,'password' => $user['password'] ), false);
            if($response===''){
                return false;
            }
            else{
                $user['user_id'] = $response->user_id;
                $response = $this->_ho->request(array(
                                'Target' => 'AffiliateUser'
                                ,'Method' => 'findById'
                                ,'fields' => array('affiliate_id')
                                ,'id' => $user['user_id']),false);
                $user['affiliate_id'] = $response->AffiliateUser->affiliate_id;

                $user['admin'] = $this->_mongoConnector->isIdAdmin($user['affiliate_id']);

                $_SESSION['user'] = $user;
                $this->_user = $user;
                if($redirect)
                    header("Location: http://www.games.tm/#/game/".$redirect);
                return $user;
            }   
        }

        function signOut(){
            unset($_SESSION['user']);
        }

        function getSavedUser(){
            if(isset($_SESSION['user'])==false){
                return false;
            }
            else{
                $this->_user = $_SESSION['user'];
                return  $_SESSION['user'];
            }
        }

        function getReferrals(){
            $this->getSavedUser();
            $completeReferrals = array();
            $referrals = $this->_ho->request(array('Target' => 'Affiliate'
                                    ,'Method' => 'getReferralAffiliateIds'
                                    ,'id' => $this->_user['affiliate_id'] ), false);
            
            $limit = 50;
            $loops = ceil(sizeof($referrals)/$limit);
            $start = 0;

            for($i=0;$i<$loops;$i++){
                $st = $i * 50;
                $trimmed = array_slice($referrals, $st,$limit);

                $response = $this->_ho->request(array('Target' => 'Affiliate'
                                        ,'Method' => 'findAllByIds'
                                        ,'fields' => array('company')
                                        ,'ids' => $trimmed), false);
                foreach ($response as $key => $value) {
                    $completeReferrals[$key]['company']=$value->Affiliate->company;
                }
            }
            return $completeReferrals;
        }

        function getPlayNowLink($offer_id){
            $this->getSavedUser();
            $playnow = $this->_ho->request(array('Target' => 'Offer'
                                    ,'Method' => 'generateTrackingLink'
                                    ,'offer_id' => $offer_id, 'affiliate_id' => $this->_user['affiliate_id'] ), false);
            return $playnow->click_url;
        }

    }

?>