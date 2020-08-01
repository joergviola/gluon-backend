<?php

namespace Gluon\Backend\Api;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function updatePlanned($project_id) {
        $percent = API::provider('task')
            ->where('project_id', $project_id)
            ->where('percent', 1)
            ->where('purchased', 0)
            ->sum('planned') / 100
            + 1.0;

        API::provider('project')
            ->where('id', $project_id)
            ->update([
                'planned' => DB::raw("(SELECT ROUND(SUM(planned)*$percent, 1) FROM task WHERE project_id=$project_id AND purchased=0 AND percent=0)")
            ]);

        API::provider('position')
            ->where('accounting.project_id', $project_id)
            ->where('accounting.type', 'QUOTE')
            ->where('accounting.state', 'NEW')
            ->join('accounting', 'accounting.id', '=', 'position.accounting_id')
            ->update([
                'position.planned' => DB::raw("(SELECT ROUND(SUM(planned)*$percent, 1) FROM task WHERE (task.position_id IS NULL OR task.position_id=position.id) AND task.position=position.name AND task.project_id=accounting.project_id AND task.purchased=0 AND task.percent=0)"),
                'position.price' => DB::raw("
                    (SELECT ROUND(SUM(
                        CASE
                            WHEN task.purchased=0 THEN task.planned*$percent*(1.0+accounting.percentBuffer/100.0)*accounting.pricePerUnit
                            WHEN task.purchased=1 THEN task.price
                        END)
                        , -accounting.rounding)
                    FROM task
                    WHERE (task.position_id IS NULL OR task.position_id=position.id)
                    AND task.position=position.name
                    AND task.project_id=accounting.project_id
                    AND task.percent=0)
                    "),
            ]);

        API::provider('accounting')
            ->where('accounting.project_id', $project_id)
            ->where('accounting.type', 'QUOTE')
            ->update([
                    'accounting.price' => DB::raw('(SELECT SUM(position.price) FROM position WHERE position.accounting_id=accounting.id AND position.accepted)'),
                ]);

    }
}
