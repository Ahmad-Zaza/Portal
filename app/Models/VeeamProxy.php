<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VeeamProxy extends Model
{
    protected $table = 'veeam_proxies';
    public $timestamps  = true;
    protected $fillable = [
        "server_id", "guid", "name", "cache_path"
    ];

    public function server(){
        return $this->belongsTo(VeeamServer::class,'server_id');
    }

    public static function getLessUsedProxy($serverId)
    {
        $proxies = DB::select('select veeam_proxies.*
            from veeam_proxies
            where veeam_proxies.server_id=' . $serverId . ' and veeam_proxies.id not in (select veeam_backup_repositories.proxy_id from veeam_backup_repositories where 1)
            ');
        if (!empty($proxies)) {
            return $proxies[0];
        } else {
            $res = DB::select('select x.proxy_id,x.cnt from
                    (SELECT proxy_id,count(proxy_id) cnt FROM veeam_backup_repositories
                    join veeam_proxies on veeam_proxies.id= veeam_backup_repositories.proxy_id
                    where veeam_proxies.server_id=' . $serverId . '
                    GROUP BY proxy_id)x
                    where x.cnt=
                    (
                        select min(z.cnt) from (
                            SELECT count(proxy_id) cnt FROM veeam_backup_repositories
                            join veeam_proxies on veeam_proxies.id= veeam_backup_repositories.proxy_id
                            where veeam_proxies.server_id=' . $serverId . '
                            GROUP BY proxy_id)z
                    )');
            $proxy = -1;
            if ($res) {
                $proxy = VeeamProxy::find($res[0]->proxy_id);
            }
            return $proxy;
        }
    }
}
