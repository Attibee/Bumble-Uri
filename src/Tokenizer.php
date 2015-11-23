<?php

/*
 * Copyright 2015 Attibee (http://attibee.com)

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

namespace Bumble\Uri;

/**
 * Parses a URI into its components.
 * 
 * The Tokenizer parses a Uri into components, or tokens. Note that this does not validate
 * a Uri, but may return null of the Uri is very malformed. To validate a Uri, use the
 * Bumble\Validation package.
 */
class Tokenizer {
    /**
     * Parses the string and returns the {@link Uri}.
     * 
     * Parses the string and returns the {@link Uri}. If parsing fails, null is returned.
     * 
     * @param String $string The url.
     * 
     * @return \Bumble\Uri\Uri The Uri object.
     */
    public function parse( $string ) {
        $data = parse_url( $string );
        
        //helper function gets $a[$k], checks if exists
        function get( $a, $k ) {
            if( array_key_exists( $k, $a ) ) {
                return empty( $a[$k] ) ? null : $a[$k];
            }
            
            return null;
        }
        
        if( $data === null ) {
            return null;
        }
        
        $uri = new Uri;
        
        $uri->setProtocol( get( $data, 'scheme' ) );
        $uri->setUsername( get( $data, 'user' ) );
        $uri->setPassword( get( $data, 'pass' ) );
        $uri->setHost( get( $data, 'host' ) );
        $uri->setPort( get( $data, 'port' ) );
        $uri->setPath( get( $data, 'path' ) );
        $uri->setQuery( get( $data, 'query' ) );
        $uri->setAnchor( get( $data, 'anchor' ) );
        
        return $uri;
    }
}
