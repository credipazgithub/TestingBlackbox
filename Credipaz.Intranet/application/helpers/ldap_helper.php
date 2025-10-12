<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

function LDAPSyncGroups($username,$password,$groups,$obj){
    $USERS=$obj->createModel(MOD_BACKEND,"Users","Users");
    $user=$USERS->get(array("where"=>"username='".$username."'"));
    if(!isset($user["data"][0])){
        $params=array(
            "id"=>0,
            "code"=>$username,
            "description"=>$username,
            "username"=>$username,
            "password"=>$password,
            "id_type_user"=>78,
            "id_application"=>7
        );
        $user=$USERS->save($params,null);
        $id_user=$user["data"]["id"];
    } else{
        $id_user=$user["data"][0]["id"];
    }
    if (is_array($groups)) {
        $filter = implode("','", $groups);
        $filter = ("'" . $filter . "'");
        $filter = str_replace("'", "", $filter);
        $sql = "exec dbIntranet.dbo.NS_LDAPSyncGroups @id_user=" . $id_user . ", @filter='" . $filter . "'";
        $obj->execAdHoc($sql);
    }
    /*
    $REL_USERS_GROUPS=$obj->createModel(MOD_BACKEND,"Rel_users_groups","Rel_users_groups");
    $REL_USERS_GROUPS->deleteByWhere(array("id_user"=>$id_user));
    if (is_array($groups)) {
        $filter=implode("','",$groups);
        $filter=("'".$filter."'");
        $GROUPS=$obj->createModel(MOD_BACKEND,"Groups","Groups");
        $adds=$GROUPS->get(array("pagesize"=>-1,"where"=>"code in (".$filter.")"));
        foreach($adds["data"] as $item) {
            $fields=array("id_group"=>$item["id"],"id_user"=>$id_user);
            $REL_USERS_GROUPS->save(array("id"=>0),$fields);
        }
    }
    */
}

function LDAPInit($values){
    try {
        $ldap_con=ldap_connect(LDAP_SERVER);
        ldap_get_option($ldap_con, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
        if ($ldap_con!==false) {
            ldap_set_option($ldap_con,LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
            if(@ldap_bind($ldap_con, ($values["username"]."@".LDAP_SERVER), $values["password"])) {
               return $ldap_con;
            } else {
               ldap_get_option($ldap_con, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
               throw new Exception("Fail LDAP Bind: ".$err);
            }
        } else {
           throw new Exception("Fail LDAP Connect: ".$err);
        }
    } catch(Exception $e){
      logError($e,__METHOD__ );
      return null;
    }
}
function LDAPInitForced(){
    try {
        $ldap_con=ldap_connect(LDAP_SERVER);
        ldap_get_option($ldap_con, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
        if ($ldap_con!==false) {
            ldap_set_option($ldap_con,LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
            if(@ldap_bind($ldap_con, ("neodata@".LDAP_SERVER), "wQ5GEeN5Fz%hSB\$sFeUi")) {
               return $ldap_con;
            } else {
               ldap_get_option($ldap_con, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
               throw new Exception("Fail LDAP Bind: ".$err);
            }
        } else {
           throw new Exception("Fail LDAP Connect: ".$err);
        }
    } catch(Exception $e){
      logError($e,__METHOD__ );
      return null;
    }
}
function LDAPSearch($ldap_con,$filter,$attributes){
    try {
        $result=@ldap_search($ldap_con, LDAP_TREE, $filter, $attributes);
        $data=@ldap_get_entries($ldap_con,$result);
        return $data;
    } catch(Exception $e) {
        logError($e,__METHOD__ );
        return null;
    }
}
function LDAPCheck($obj,$values) {
    $return=array("status"=>null,"raw"=>null,"mode"=>"LOCAL","username"=>null,"groups"=>null);
    $groups=array();
    try {
        if(!isset($values["password"])){
            $ldap_con=LDAPInitForced();
        } else {
            $ldap_con=LDAPInit($values);
        }
        if ($ldap_con!=null){
            $data=LDAPSearch($ldap_con,"(|(sAMAccountName=".$values["username"]."))",array("memberof"));
            /*---------------------------------------------------------------*/
            //Login consolidation for user and groups!
            /*---------------------------------------------------------------*/
            for ($i=0; $i<$data[0]["memberof"]["count"]; $i++) {
                $memberof=$data[0]["memberof"][$i];
                if(strpos($memberof,"OU=Intranet")!==False){
                    $memberof=explode(",",$memberof)[0];
                    $memberof=explode("=",$memberof)[1];
                    array_push($groups,$memberof);
                }
            }
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "mode"=>($values["username"]!="" ? "LDAP" : "LOCAL"),
                "username"=>$values["username"],
                "groups"=>$groups,
                "message"=>"LDAPChecked",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
            );
            /*---------------------------------------------------------------*/
            
            /*---------------------------------------------------------------*/
            //Login consolidation for user and groups!
            /*---------------------------------------------------------------*/
            LDAPSyncGroups($values["username"],$values["password"],$groups,$obj);
            /*---------------------------------------------------------------*/
            ldap_close($ldap_con);
        }
    	return $return;
    } catch(Exception $e) {
        return $return;
    } 
}
