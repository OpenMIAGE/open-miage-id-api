<?php

Import::php("util.http.OpenM_Server");
Import::php("util.wrapper.RegExp");
Import::php("util.OpenM_Log");

/**
 * OpenM_ID_Tool is a tool class to share operation process.
 * for example getClientIp that required for OpenM_SSO provider.
 * @package OpenM 
 * @subpackage OpenM\OpenM-ID\api 
 * @copyright (c) 2013, www.open-miage.org
 * @license http://www.apache.org/licenses/LICENSE-2.0 Licensed under the Apache License, Version 2.0 (the "License");
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
 * @link http://www.open-miage.org
 * @author Gaël Saunier
 */
class OpenM_ID_Tool {
    
    const OpenM_API_TOKEN = "API";
    
    /**
     * provide a specific hashed signature of caller's IP.
     * @param String $algo is an encryption algorithm (ex: sha256)
     * @see OpenM_Crypto::isAlgoValid
     * @return String the hashed signature
     */
    public static function getClientIp($algo){
        return OpenM_Server::getClientIpCrypted($algo, $_SERVER['HTTP_USER_AGENT']);
    }
    
    /**
     * check if the given token is valid
     * @param String $token is normaly a temporary token provided by OpenM_ID
     * by using OpenID protocol
     * @return boolean true if valid, else false
     */
    public static function isTokenValid($token) {
        OpenM_Log::debug("$token", __CLASS__, __METHOD__, __LINE__);
        return RegExp::ereg("^(".self::OpenM_API_TOKEN."\.)?[a-f0-9]+(_[0-9]+(\.[0-9]+)?)?$", $token);
    }
    
    /**
     * check if the given token is provided for an API
     * @param String $token is normaly a temporary token provided by OpenM_ID
     * @return boolean true if a token provide for an API, else false
     */
    public static function isTokenApi($token) {
        OpenM_Log::debug("$token", __CLASS__, __METHOD__, __LINE__);
        return RegExp::ereg("^".self::OpenM_API_TOKEN."\.[a-f0-9]+(_[0-9]+(\.[0-9]+)?)?$", $token);
    }
    
    /**
     * 
     * @param String $token
     * @return String
     */
    public static function getTokenApi($token) {
        OpenM_Log::debug("$token", __CLASS__, __METHOD__, __LINE__);
        return self::OpenM_API_TOKEN.".$token";
    }
    
    /**
     * Get simple ID from an OID (an OID is an URL that contain the ID)
     * @param String $oid is the user openId provided by OpenM_ID
     * by using OpenM-ID_GET API
     * @return String ID
     */
    public static function getId($oid){
        $id = substr(strrchr ($oid, "?".OpenM_ID::URI_API."="),  strlen("?".OpenM_ID::URI_API."="))."";
        OpenM_Log::debug("$oid => $id", __CLASS__, __METHOD__, __LINE__);
        return $id;
    }
}
?>