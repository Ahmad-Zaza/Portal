<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VeeamServer extends Model
{
    protected $table = 'veeam_servers';
    public $timestamps = true;
    protected $fillable = [
        "username", "password", "url", "access_token", "is_multi_use", "region",
    ];

    public function organizations()
    {
        return $this->hasMany(BacOrganization::class, "server_id");
    }

    public static function getLessUsedServer($microsoftTenantGuid = "")
    {
        //------------------
        if ($microsoftTenantGuid) {
            $server = VeeamServer::doesntHave('organizations')->where("microsoft365_tenant_guid", $microsoftTenantGuid)->first();
            if ($server) {
                return $server;
            }
        }
        //------------------
        $server = VeeamServer::doesntHave('organizations')->where("is_multi_use", 1)->first();
        if ($server) {
            return $server;
        }
        //------------------
        $singleUserServersIds = VeeamServer::where("is_multi_use", 0)->pluck("id")->toArray();
        if ($singleUserServersIds) {
            $singleUserServersIds = implode(",", $singleUserServersIds);
        }
        //------------------
        if ($singleUserServersIds) {
            $res = DB::select('select x.server_id,x.cnt from
                (SELECT server_id, count(server_id) cnt FROM bac_organizations
                where server_id > 0
                and server_id not in (' . $singleUserServersIds . ')
                GROUP BY server_id) x
                where x.cnt=
                (
                    select min(z.cnt) from (
                        SELECT count(server_id) cnt FROM bac_organizations
                        where server_id > 0
                        and server_id not in (' . $singleUserServersIds . ')
                        GROUP BY server_id) z
                )')[0]->server_id;
        } else {
            $res = DB::select('
                select x.server_id,x.cnt from
                (SELECT server_id, count(server_id) cnt FROM bac_organizations
                where server_id > 0
                GROUP BY server_id) x
                where x.cnt=
                (
                    select min(z.cnt) from (
                        SELECT count(server_id) cnt FROM bac_organizations
                        where server_id > 0
                        GROUP BY server_id) z
                )')[0]->server_id;
        }
        $veeamServer = -1;
        if ($res) {
            $veeamServer = VeeamServer::find($res);
        }
        return $veeamServer;
    }
}
