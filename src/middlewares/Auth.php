<?php

class Auth {

  function execute($req, $res) {
    $req = $req ? $req : Request::getInstance();
    $res = $res ? $res : Response::getInstance();
    $authHeader = $req->headers('authorization');

    if (!$authHeader) {
      $res->status(401)->json([ 'message'=> 'Token not provided' ]);
    }

    $authElements = is_string($authHeader) ? explode(" ", $authHeader) : [];
    $token = isset($authElements[1]) ? $authElements[1] : "";

    if (!$token) {
      $res->status(401)->json(['message' => 'Token not found']);
    }
    
    $jwt = MyJWT::getInstance();
    if (!$jwt->validate($token)) {
      $res->status(401)->json(['message' => $jwt->getMessage()]);
    }
  
    $result = $jwt->getResult();
    $payload = $result['payload'];
    //$res->json($payload);
    $id = isset($payload['id']) ? $payload['id'] : (isset($payload['user_id']) ? $payload['user_id'] : false);
    $req->userId = $id;
  }
}