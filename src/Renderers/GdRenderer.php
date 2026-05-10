<?php

namespace DevWizardHQ\Captcha\Renderers;

use DevWizardHQ\Captcha\CaptchaChallenge;
use DevWizardHQ\Captcha\Contracts\ImageRenderer;
use RuntimeException;

class GdRenderer implements ImageRenderer
{
    public function render(CaptchaChallenge $challenge, array $config): string
    {
        if (! extension_loaded('gd')) {
            throw new RuntimeException('The GD extension is required to generate CAPTCHA images.');
        }

        $width = (int) ($config['width'] ?? 180);
        $height = (int) ($config['height'] ?? 60);

        if ($width < 1 || $height < 1) {
            throw new RuntimeException('CAPTCHA image dimensions must be greater than zero.');
        }

        $image = imagecreatetruecolor($width, $height);

        if (! $image) {
            throw new RuntimeException('Could not create CAPTCHA image.');
        }

        [$r, $g, $b] = $config['background'] ?? [245, 245, 245];

        $background = imagecolorallocate($image, $r, $g, $b);

        imagefill($image, 0, 0, $background);

        $this->drawNoise($image, $width, $height, (int) ($config['noise'] ?? 80));
        $this->drawLines($image, $width, $height, (int) ($config['lines'] ?? 5));
        $this->drawText($image, $challenge->question, $width, $height, $config);

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();

        if (! is_string($contents)) {
            throw new RuntimeException('Could not render CAPTCHA image.');
        }

        return $contents;
    }

    private function drawNoise(\GdImage $image, int $width, int $height, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $color = imagecolorallocate(
                $image,
                random_int(120, 220),
                random_int(120, 220),
                random_int(120, 220),
            );

            imagesetpixel(
                $image,
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                $color,
            );
        }
    }

    private function drawLines(\GdImage $image, int $width, int $height, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $color = imagecolorallocate(
                $image,
                random_int(80, 180),
                random_int(80, 180),
                random_int(80, 180),
            );

            imageline(
                $image,
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                $color,
            );
        }
    }

    private function drawText(\GdImage $image, string $text, int $width, int $height, array $config): void
    {
        $fontSize = max(1, min(5, (int) ($config['font_size'] ?? 5)));
        $characterWidth = imagefontwidth($fontSize);
        $characterHeight = imagefontheight($fontSize);
        $length = strlen($text);

        if ($length === 0) {
            return;
        }

        $availableWidth = max($characterWidth, $width - 20);
        $spacing = $length > 1
            ? max($characterWidth, min(18, (int) floor(($availableWidth - $characterWidth) / ($length - 1))))
            : 0;
        $textWidth = $characterWidth + (($length - 1) * $spacing);
        $x = max(0, (int) floor(($width - $textWidth) / 2));
        $y = max(0, min($height - $characterHeight, (int) floor(($height - $characterHeight) / 2)));
        $jitterX = min(2, max(0, (int) floor(($spacing - $characterWidth) / 2)));

        for ($i = 0; $i < $length; $i++) {
            $color = imagecolorallocate(
                $image,
                random_int(0, 90),
                random_int(0, 90),
                random_int(0, 90),
            );

            imagestring(
                $image,
                $fontSize,
                min(
                    max(0, $x + ($i * $spacing) + random_int(-$jitterX, $jitterX)),
                    max(0, $width - $characterWidth),
                ),
                min(
                    max(0, $y + random_int(-5, 5)),
                    max(0, $height - $characterHeight),
                ),
                $text[$i],
                $color,
            );
        }
    }
}
