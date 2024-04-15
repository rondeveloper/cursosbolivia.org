<?php

define ('HMAC_SHA256', 'sha256');
define ('SECRET_KEY', '55e92f41829640b4b4ed7a87b0bf37ad17cb4ed62ae942f78357f8452c995da0f9ca1e1cbde944c099162ee294102e52887aabd0ff244c1a84798dc09bac587e338215c820bd4b79be1d2119393762c052435b87ba5e4229b4f5df84d48ec2beea7ca33c09ad409c9ce9f784d979922c93a60b7bfb9b4bacae84af9eae3135e9');

function sign ($params) {
  return signData(buildDataToSign($params), SECRET_KEY);
}

function signData($data, $secretKey) {
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return commaSeparate($dataToSign);
}

function commaSeparate ($dataToSign) {
    return implode(",",$dataToSign);
}
