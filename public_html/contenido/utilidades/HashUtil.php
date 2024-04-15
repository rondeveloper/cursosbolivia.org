<?php

class HashUtil
{

    public static function hashIdRegistroTienda($id_tienda_registro)
    {
        return md5('t10--' . $id_tienda_registro);
    }
}
