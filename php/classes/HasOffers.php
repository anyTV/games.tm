<?php 
    
    class HasOffers {
        /* Protected Properties
            -------------------------------*/
        protected $_base;
        protected $_default_params; 

        public function __construct(){
            $configuration =  json_decode(file_get_contents('hasoffers.conf'),true);
            $this->_base = 'https://api.hasoffers.com/Api?';
            $this->_default_params = array(
                'Format' => 'json'
                ,'Service' => 'HasOffers'
                ,'Version' => 3
                ,'NetworkId' => $configuration['NetworkId']
                ,'NetworkToken' => $configuration['NetworkToken']
            );
            // var_dump($this->_default_params);
        }

        function request($added_parameters, $view, $sleep = 0){
            $this->_default_params = array_merge($this->_default_params, $added_parameters);
            $url = $this->_base . http_build_query( $this->_default_params );
            $result = json_decode(file_get_contents($url));
            sleep($sleep);
            if($view){
                echo "<pre>" . print_r($result,true) . "</pre>";
                return $result->response->data;
            }
            else
                return $result->response->data;
        }

    }

?>