<?php

/* Copyright 2015 Attibee (http://attibee.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Attibee\Uri;

class Uri {
    private $protocol = null;
    private $username = null;
    private $password = null;
    private $domain = null;
    private $tld = null;
    private $port = null;
    private $path = null;
    private $query = null;
    private $anchor = null;
    
    public function setProtocol( $value ) {
        $this->protocol = $value;
    }
    
    public function setUsername( $value ) {
        $this->username = $value;
    }
    
    public function setPassword( $value ) {
        $this->password = $value;
    }

    public function setDomain( $value ) {
        $this->domain = $value;
    }
    
    public function setTld( $value ) {
        $this->tld = $value;
    }
    
    public function setPort( $value ) {
        $this->port = $value;
    }
    
    public function setPath( $value ) {
        $this->path = $value;
    }
    
    public function setQuery( $value ) {
        $this->query = $value;
    }
    
    public function setAnchor( $value ) {
        $this->anchor = $value;
    }

    public function getProtocol() {
        return $this->protocol;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getDomain() {
        return $this->domain;
    }

    public function getTld() {
        return $this->tld;
    }
    
    public function getPort() {
        return $this->port;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function getQuery() {
        return $this->query;
    }
    
    public function getAnchor() {
        return $this->anchor;
    }
    
    
}