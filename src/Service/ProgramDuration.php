<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $seasons = $program->getSeasons();
        $duration = 0;
        foreach ($seasons as $season) {
            $episodes = $season->getEpisodes();
            foreach ($episodes as $episode) {
                $duration += $episode->getDuration();
            }
        }
        $d = floor($duration / 1440);
        $h = floor(($duration - $d * 1440) / 60);
        $m = $duration - ($d * 1440) - ($h * 60);
        return "=> Il vaut faudra " . $d . " jour(s), " . $h . " heure(s) et " . $m . " minute(s) pour regarder toute la série.";
    }
}
