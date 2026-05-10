<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('it will not use deprecated gd cleanup functions')
    ->expect(['imagedestroy'])
    ->each->not->toBeUsed();
