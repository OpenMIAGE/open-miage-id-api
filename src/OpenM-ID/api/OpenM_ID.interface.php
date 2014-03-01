<?php

Import::php("OpenM-Services.api.OpenM_Service");

/**
 * OpenM_ID is central authentification provider.
 * It permit remote authentification.
 * It provide a token (with short timeout), by using OpenID protocol.
 * Those tokens are used to open OpenM-SSO sessions.
 * OpenM_ID validate or not the token by using checkUserRights from OpenM_SSO api.
 * OpenM_ID is the only authentification control point for all the OpenM_SSO
 * connections.
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
interface OpenM_ID extends OpenM_Service {

    const OID_PARAMETER = "OpenM_OID";
    const NO_REDIRECT_TO_LOGIN_PARAMETER = "noRedirectToLogin";
    const TOKEN_PARAMETER = "nickname";
    const EMAIL_PARAMETER = "email";
    const GetOpenID_API = "OpenM-ID_GET";
    const URI_API = "OpenM-ID_URI";
    const LOGIN_API = "OpenM-ID_Login";
    const LOGOUT_API = "OpenM-ID_Logout";
    const MY_ACCOUNT_API = "OpenM-ID_My_Account";
    const RETURN_TOKEN_PARAMETER = "API_SSO_TOKEN";
    const RETURN_SERVICE_ID_PARAMETER = "SERVICE_ID";
    const VERSION = "1.0.3";

    /**
     * Install Service is required for all OpenM_SSO provider.
     * After service registry and administrator validation, OpenM_SSO provider
     * could check if user is correcly authentifiate.
     * OpenM_ID ensure to the OpenM_SSO provider that remote user is the good one.  
     * @param String $serviceName is a specific name (not unique required),
     * that permit to identify easyer a registered service by administrator
     * @param String $oid is the user openId provided by OpenM_ID
     * by using OpenM-ID_GET API
     * @param String $token is a temporary token provided by OpenM_ID
     * by using OpenID protocol
     * @return HashtableString contains a SERVICE_ID of installed Service
     */
    public function installService($serviceName, $oid, $token);

    /**
     * An API could provide service for other API.
     * To ensure that remote user is already correctly authentificate,
     * OpenM_SSO provider need to register it's client to delegate clients rights
     * to OpenM_ID by using checkUserRights (by an API as a rooter).
     * @param String $serviceId is the SERVICE_ID provided by installService
     * @param String $oid is the user openId provided by OpenM_ID
     * by using OpenM-ID_GET API
     * @param String $token is a temporary token provided by OpenM_ID
     * by using OpenID protocol
     * @param String $clientIp is hash signature of client ip.
     * client IP hashed calculation is specified in OpenM_ID_Tool::getClientIp
     * @see OpenM_ID_Tool::getClientIp, OpenM_ID::installService
     * @return HashtableString contains calling status OK if calling 
     * successfully finished, else error
     */
    public function addServiceClient($serviceId, $oid, $token, $clientIp);

    /**
     * CheckUserRights is THE most important method (after registerd Service,
     * and clients). This method permit to check if user associate to $oid,
     * is correclty authentifiate and anwser status to caller.
     * This method permit to all OpenM_SSO provider to check if caller is the
     * good one.
     * @param String $serviceId is the SERVICE_ID provided by installService
     * @param String $oid is the user openId provided by OpenM_ID
     * by using OpenM-ID_GET API
     * @param String $token is a temporary token provided by OpenM_ID
     * by using OpenID protocol
     * @param String $clientIp is hash signature of client ip.
     * client IP hashed calculation is specified in OpenM_ID_Tool::getClientIp
     * @see OpenM_ID_Tool::getClientIp, OpenM_ID::installService
     * @return HashtableString contains calling status OK if calling 
     * successfully finished, else error
     */
    public function checkUserRights($serviceId, $oid, $token, $clientIp = null);

    /**
     * Remove Service is the oposit of addServiceClient.
     * This method remove access rights of a client to OpenM_SSO provider caller.
     * @param String $serviceId is the SERVICE_ID provided by installService
     * @param String $clientIp is hash signature of client ip.
     * client IP hashed calculation is specified in OpenM_ID_Tool::getClientIp
     * @see OpenM_ID_Tool::getClientIp, OpenM_ID::installService
     * @return HashtableString contains calling status OK if calling 
     * successfully finished, else error
     */
    public function removeServiceClient($serviceId, $clientIp);
}

?>