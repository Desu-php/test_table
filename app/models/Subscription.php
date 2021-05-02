<?php


namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'status',
        'utm_campaign',
        'utm_source',
        'utm_medium',
        'order',
        'ip'
    ];

    public $timestamps = false;

    public function scopeSource($query, $column, $date, $order)
    {
        return $query->select(['utm_source', 'COUNT(id) AS count'])
            ->groupby($column)
            ->where('order', $order)
            ->whereDate('created_at', $date);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
