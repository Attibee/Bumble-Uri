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

/**
 * Parses a URI into its components. Additionally, this will validate a URI. If the
 * URI is invalid, then the UriParser::parse method will return null, else it returns
 * an {@link Uri} instance.
 */
class UriParser {
    public function parse( $str ) {
        $uri = new Uri();

        $matched = preg_match(
            '/^(?:([a-z\.-]+):\/\/)?' . //protocol, http, ftp, etc
            '(?:([^:]+)(?::(\S+))?@)?' . //username:password
            '(' .
                '(?:[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})'. //ip
                '|(?:(?:(?:[^\.:\/\s-]+)(?:-[^\.:\/\s-]+)*\.)+)(?:[^?\/:\.]+)' . //domain
            ')' .
            '(?::([0-9]+))?' . //port
            '(' .
                '\/(?:[^#?\/\s.]+\/?)*' . //path
                '(?:[^\.\s]*\.[^#?\s]+)?' . //filename.ext
            ')?' .
            '(\?(?:[a-z0-9%-]+(?:=[a-z0-9%-]+)?)(?:&(?:[a-z0-9%-]+(?:=[a-z0-9%-]+)?))*)?' . //query
            '(?:\#([a-z0-9&=-]+))?' . //anchor
            '$/ixSs',
            $str,
            $data
        );

        if( !$matched )
            return null;

        $protocol     = isset($data[1]) && !empty($data[1]) ? $data[1] : null;
        $username     = isset($data[2]) && !empty($data[2]) ? $data[2] : null;
        $password     = isset($data[3]) && !empty($data[3]) ? $data[3] : null;
        $domain     = trim( $data[4], '.' ); //domain always exists
        $port         = isset($data[6]) && !empty($data[6]) ? $data[6] : null;
        $path         = isset($data[7]) && !empty($data[7]) ? $data[7] : null;
        $query         = isset($data[8]) && !empty($data[8]) ? ltrim( $data[8], '?' ) : null;
        $anchor     = isset($data[9]) && !empty($data[9]) ? $data[9] : null;
        
        //the entire ip/domain is caught as one piece.. was easier than regexing it
        //so we tld is null for ip
        //and we separate tld/domain for the domain version
        if( is_numeric( str_replace( '.', '', $domain ) ) ) {
            //ip is not the right length
            if( count( explode( '.', $domain ) ) != 4 )
                return null;
            
            $tld = null;
        } else {
            $tld = end( explode( '.', $domain ) );
            $domain = implode( '.', explode( '.', $domain, -1 ) );
        }
        
        $uri->setProtocol( $protocol );
        $uri->setUsername( $username );
        $uri->setPassword( $password );
        $uri->setDomain( $domain );
        $uri->setTld( $tld );
        $uri->setPort( $port );
        $uri->setPath( $path );
        $uri->setQuery( $query );
        $uri->setAnchor( $anchor );
        
        return $uri;
    }
    
    public function chompProtocol( &$str ) {
        $pieces = explode( '://', $str, 2 );
        
        //protocol exists
        if( count( $pieces ) == 2 ) {
            $str = $pieces[1];
            return $pieces[0];
        }
        
        return null;
    }
    
    public function chompUsernamePassword( &$str ) {
        $pieces = explode( '@', $str, 2 );
        
        //username:password exists
        if( count( $pieces ) == 2 ) {
            $str = $pieces[1];
            $pieces = explode( ':', $pieces[0], 2 );
            
            //pass exists, get username
            if( count( $pieces ) == 2 )
                return array( $pieces[0], $pieces[1] );
            else
                return array( $pieces[0], null );
        }
        
        return null;
    }
    
    public function chompDomain( &$str ) {
        $pieces = preg_split( '/[\/:?#]/', $str, 2 );

        //rest is the domain
        if( count( $pieces ) == 2 ) {
            $str = $str{strlen($pieces[0])} . $pieces[1]; //add back the :/?# character for further exploding
            return $pieces[0];
        }
        
        return $str;
    }
    
    public function chompPort( &$str ) {
        //no port
        if( !$str || $str[0] !== ':' ) return null;
        
        $pieces = preg_split( '/[\/?#]/', $str, 2 );
        
        //port exists
        if( count( $pieces ) == 2 ) {
            $str = $str{strlen($pieces[0])} . $pieces[1]; //add back the /?# character for further exploding
            return ltrim( $pieces[0], ':' );
        } else {
            $return = ltrim( $str, ':' );
            $str = '';
            return $return;
        }
        
        return null;
    }
    
    public function chompPath( &$str ) {
        //no port
        if( !$str || $str[0] !== '/' ) return null;
        
        $pieces = preg_split( '/[?#]/', $str, 2 );
        
        //port exists
        if( count( $pieces ) == 2 ) {
            $str = $str{strlen($pieces[0])} . $pieces[1]; //add back the /?# character for further exploding
            return ltrim( $pieces[0], '/' );
        } else {
            $return = ltrim( $str, ':' );
            $str = '';
            return $return;
        }
        
        return null;
    }
    
    public function chompQuery( &$str ) {
        //no port
        if( !$str || $str[0] !== '?' ) return null;
        
        $pieces = preg_split( '/[#]/', $str, 2 );
        
        
        //port exists
        if( count( $pieces ) == 2 ) {
            $str = $str{strlen($pieces[0])} . $pieces[1]; //add back the /?# character for further exploding
            return ltrim( $pieces[0], '?' );
        } else {
            $return = ltrim( $str, ':' );
            $str = '';
            return $return;
        }
        
        return null;
    }
    
    public function chompAnchor( &$str ) {
        //no port
        if( !$str || $str[0] !== '#' ) return null;

        $anchor = ltrim( $str, '#' );
        
        if( $anchor )
            return $anchor;
        else
            return null;
    }
}